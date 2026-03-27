
document.addEventListener("click", function (e) {

    const searchBar = document.getElementById("chatbot-search-bar");
    const searchInput = document.getElementById("chatbot-search-input");

    // If search bar is hidden → do nothing
    if (searchBar.style.display === "none") return;

    // Do NOT hide when clicking these:
    if (
        e.target === searchInput ||
        searchBar.contains(e.target) ||          // inside search bar
        document.getElementById("chatbot-menu-btn").contains(e.target) || // menu button
        document.getElementById("chatbot-search-btn").contains(e.target)   // menu item
    ) {
        return;
    }

    // Hide search bar for ALL OTHER CLICKS
    searchBar.style.display = "none";
    document.getElementById("chatbot-search-results").style.display = "none";
    searchInput.value = "";
});



/* ======================================================
   GLOBAL VARIABLES
====================================================== */
let lastMessageId = 0;
let initialLoaded = false;
let chatOpen = false;
let unreadCount = 0;

// To avoid duplicates
const loadedMessageIds = new Set();

// Timestamp of the latest USER message (in ms)
let lastUserMessageTime = 0;

/* ======================================================
   Helpers
====================================================== */

// Smooth scroll to bottom
function smoothScroll() {
    const box = document.getElementById("chatbot-messages");
    box.scrollTo({ top: box.scrollHeight, behavior: "smooth" });
}

// Parse message date+time -> timestamp (ms)
function getMessageTimestamp(msg) {
    // PHP sends e.g. date: "01/17/2025", time: "04:12 PM"
    // This format is generally understood by Date in browsers
    return new Date(`${msg.date} ${msg.time}`).getTime();
}

/* ======================================================
   Date Separator
====================================================== */
let lastDate = "";

function addDateSeparator(dateText) {
    if (dateText === lastDate) return;
    lastDate = dateText;

    const div = document.createElement("div");
    div.className = "date-separator";
    div.innerText = dateText;

    document.getElementById("chatbot-messages").appendChild(div);
}

/* ======================================================
   Append Message (Supports Date + Time for Search)
====================================================== */
function appendMessage(text, type, time = null, date = null) {

    if (date) addDateSeparator(date);

    // Auto-generate time if missing
    if (!time) {
        time = new Date().toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit"
        });
    }

    const msgBox = document.getElementById("chatbot-messages");

    const wrap = document.createElement("div");
    wrap.classList.add(type === "user" ? "user-msg" : "bot-msg");

    /* ★★★ Store date + time inside attributes (used for search) ★★★ */
    wrap.setAttribute("data-date", date || "");
    wrap.setAttribute("data-time", time || "");

    wrap.innerHTML = `
        <span>${text.replace(/\n/g, "<br>")}</span>
        <div class="timestamp">${time}</div>
    `;

    msgBox.appendChild(wrap);
    smoothScroll();
}


/* ======================================================
   Typing Indicator
====================================================== */
function showTyping() {
    document.getElementById("typing-indicator").style.display = "block";
    smoothScroll();
}

function hideTyping() {
    document.getElementById("typing-indicator").style.display = "none";
}

/* ======================================================
   Badge Update
====================================================== */
function updateUnreadBadge() {
    const badge = document.getElementById("chatbot-badge");
    if (unreadCount > 0) {
        badge.innerText = unreadCount;
        badge.style.display = "inline-block";
    } else {
        badge.style.display = "none";
    }
}

/* ======================================================
   Open / Close Chat
====================================================== */
document.getElementById("chatbot-button").onclick = () => {
    document.getElementById("chatbot-box").style.display = "flex";
    chatOpen = true;

    // Kapag binuksan ang chat, consider read na lahat
    unreadCount = 0;
    updateUnreadBadge();
};

document.getElementById("chatbot-close").onclick = () => {
    document.getElementById("chatbot-box").style.display = "none";
    chatOpen = false;
};

/* ======================================================
   Send User Message
====================================================== */
async function sendUserMessage(text) {
    if (!text.trim()) return;

    showTyping();

    try {
        const res = await fetch("/borongan/php/chat/send_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: text })
        });

        const data = await res.json();
        hideTyping();

        // Huwag mag-append ng user message dito
        // Polling (pollReplies) will display it from DB

        // If bot auto-reply
        if (data.reply) {
            appendMessage(data.reply, "bot");
        }

        // Since user just replied, logically all previous admin msgs are "read"
        unreadCount = 0;
        updateUnreadBadge();

    } catch (err) {
        hideTyping();
        console.error("Send Error:", err);
    }
}

// Send button click
document.getElementById("chatbot-send").onclick = () => {
    const input = document.getElementById("chatbot-input");
    const msg = input.value;
    input.value = "";
    sendUserMessage(msg);
};

// Enter key
document.getElementById("chatbot-input").addEventListener("keypress", e => {
    if (e.key === "Enter") {
        e.preventDefault();
        document.getElementById("chatbot-send").click();
    }
});

/* ======================================================
   Load FULL HISTORY
   - Shows all messages
   - Finds latest USER message time
   - Counts only ADMIN messages after that as unread
====================================================== */
async function loadHistory() {
    try {
        const res = await fetch("/borongan/php/chat/fetch_messages.php?since=0");
        const messages = await res.json();

        if (!Array.isArray(messages)) return;

        let highestId = 0;
        lastUserMessageTime = 0;

        // 1️⃣ Find latest USER message time
        for (const m of messages) {
            if (m.sender_type === "user") {
                const t = getMessageTimestamp(m);
                if (t > lastUserMessageTime) {
                    lastUserMessageTime = t;
                }
            }
            if (m.id > highestId) highestId = m.id;
        }

        // 2️⃣ Display all, count ONLY admin messages AFTER last user message
        let unreadOnLogin = 0;

        for (const m of messages) {
            loadedMessageIds.add(m.id);

            appendMessage(
                m.message,
                m.sender_type === "user" ? "user" : "bot",
                m.time,
                m.date
            );

            const msgTime = getMessageTimestamp(m);

            if (!chatOpen &&
                m.sender_type === "admin" &&
                (lastUserMessageTime === 0 || msgTime > lastUserMessageTime)) {
                unreadOnLogin++;
            }
        }

        unreadCount = unreadOnLogin;
        updateUnreadBadge();

        lastMessageId = highestId;
        initialLoaded = true;

    } catch (err) {
        console.error("LOAD HISTORY ERROR:", err);
    }
}

/* ======================================================
   Poll NEW Messages ONLY
   - Uses lastMessageId
   - Updates lastUserMessageTime if user sends new msg
   - Increments badge only for admin msgs after lastUserMessageTime
====================================================== */
async function pollReplies() {
    if (!initialLoaded) return;

    try {
        const res = await fetch("/borongan/php/chat/fetch_messages.php?since=" + lastMessageId);
        const messages = await res.json();

        if (!Array.isArray(messages)) return;

        for (const m of messages) {

            // Skip duplicates, just in case
            if (loadedMessageIds.has(m.id)) continue;
            loadedMessageIds.add(m.id);

            const msgTime = getMessageTimestamp(m);

            if (m.sender_type === "user") {
                // New user message → update last user time, clear unread
                if (msgTime > lastUserMessageTime) {
                    lastUserMessageTime = msgTime;
                }
                unreadCount = 0;
                updateUnreadBadge();
            } else if (m.sender_type === "admin") {
                // New admin msg; count only if after last user msg
                if (!chatOpen &&
                    (lastUserMessageTime === 0 || msgTime > lastUserMessageTime)) {
                    unreadCount++;
                    updateUnreadBadge();
                }
            }

            appendMessage(
                m.message,
                m.sender_type === "user" ? "user" : "bot",
                m.time,
                m.date
            );

            if (m.id > lastMessageId) {
                lastMessageId = m.id;
            }
        }

    } catch (err) {
        console.error("POLL ERROR:", err);
    }
}



/* ======================================================
   INIT
====================================================== */
loadHistory();
setInterval(pollReplies, 1500);


/* ======================================================
   THREE DOTS MENU
====================================================== */

// Toggle menu
document.getElementById("chatbot-menu-btn").onclick = (e) => {
    e.stopPropagation();
    const menu = document.getElementById("chatbot-menu-dropdown");
    menu.style.display = (menu.style.display === "none" || menu.style.display === "") 
        ? "block" 
        : "none";
};

// Hide when clicking outside
document.addEventListener("click", function (e) {
    if (!e.target.closest("#chatbot-menu-wrap")) {
        document.getElementById("chatbot-menu-dropdown").style.display = "none";
    }
});



// OPEN SEARCH BAR
document.getElementById("chatbot-search-btn").onclick = (e) => {
    e.stopPropagation();

    // Show search bar
    document.getElementById("chatbot-search-bar").style.display = "block";

    // Focus search input
    document.getElementById("chatbot-search-input").focus();

    // Hide the dropdown menu
    document.getElementById("chatbot-menu-dropdown").style.display = "none";
};


/* ======================================================
   ADVANCED SEARCH (Messenger Style)
====================================================== */



document.getElementById("chatbot-search-input").addEventListener("input", function () {

    const term = this.value.toLowerCase().trim();
    const messages = document.querySelectorAll("#chatbot-messages .user-msg, #chatbot-messages .bot-msg");
    const resultsBox = document.getElementById("chatbot-search-results");

    // Clear previous highlights
    messages.forEach(msg => msg.style.backgroundColor = "");

    if (term === "") {
        resultsBox.style.display = "none";
        resultsBox.innerHTML = "";
        return;
    }

    let results = [];

    // Collect matches
   messages.forEach(msg => {

    const messageText = msg.querySelector("span")?.innerText || "";
    const date = msg.getAttribute("data-date") || "";
    const time = msg.getAttribute("data-time") || "";

    if (messageText.toLowerCase().includes(term)) {
        results.push({
            element: msg,
            preview: messageText.substring(0, 60),
            date: date,
            time: time
        });
    }
});


    // Display results
    resultsBox.innerHTML = "";
    resultsBox.style.display = "block";

    if (results.length === 0) {
        resultsBox.innerHTML = `<div style="padding:8px; color:#777;">No results found</div>`;
        return;
    }

    resultsBox.innerHTML =
        `<div style="font-weight:bold; margin-bottom:6px;">${results.length} results</div>`;

    // Build the clickable result list
    results.forEach(r => {
        const item = document.createElement("div");
        item.className = "search-result-item";

        item.innerHTML = `
            <div class="search-result-text">${r.preview}</div>
            <div class="search-result-time">${r.date} • ${r.time}</div>
        `;

        item.onclick = () => {
            r.element.scrollIntoView({ behavior: "smooth", block: "center" });

            r.element.style.backgroundColor = "#fff3cd";
            setTimeout(() => (r.element.style.backgroundColor = ""), 2000);
        };

        resultsBox.appendChild(item);
    });
});



/* ======================================================
   AUTO-HIDE SEARCH BAR WHEN CLICKING ANYWHERE ELSE
====================================================== */
document.addEventListener("click", function (e) {
    const searchBar = document.getElementById("chatbot-search-bar");
    const searchInput = document.getElementById("chatbot-search-input");
    const menuBtn = document.getElementById("chatbot-menu-btn");
    const searchBtn = document.getElementById("chatbot-search-btn");

    // If not visible → do nothing
    if (searchBar.style.display === "none" || searchBar.style.display === "") return;

    // DO NOT hide if clicking:
    // - search input
    // - inside search bar
    // - menu button (3 dots)
    // - search button inside dropdown
    if (
        e.target === searchInput ||
        searchBar.contains(e.target) ||
        menuBtn.contains(e.target) ||
        searchBtn.contains(e.target)
    ) {
        return;
    }

    // Otherwise hide search bar
    searchBar.style.display = "none";
    searchInput.value = ""; // optional: clear search
});




function checkReminderNotifications() {
    $.get("php/get_today_reminders.php", function(data) {

        if (data.length > 0) {
            let notifCount = $("#notificationCount");
            let current = parseInt(notifCount.text());
            notifCount.text(current + data.length);

            data.forEach(r => {
                $("#notificationDropdown")
                    .next("ul")
                    .prepend(`<li class="dropdown-item">📌 ${r.message}</li>`);
            });
        }

    }, "json");
}

// Check reminders every page load
checkReminderNotifications();















function formatDate(datetime) {
    const date = new Date(datetime);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    });
}


$(document).ready(function() {

    // ---------------------------
    // 1️⃣ Update top notification bell & dropdown
    // ---------------------------
    function updateNotifications() {
        $.ajax({
            url: 'php/get/get_notification.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (!data.success) return;

                $('#notificationCount').text(data.unreadCount);

                let dropdown = $('#notificationDropdown + .dropdown-menu');
                dropdown.find('.notification-item').remove();
                const bottomDivider = dropdown.find('hr').last();

                data.notifications.slice(0,5).forEach(function(note) {
                    const item = $('<li class="notification-item px-3 py-2"></li>')
                        .text(note.message)
                        .attr('data-id', note.id)
                        .addClass(note.is_read == 0 ? 'fw-bold unread unread-hover' : '');

                    item.click(function() {
                        $.post('php/update/update_notification.php', { id: note.id }, function(resp) {
                            if (resp.success) updateNotifications();
                        }, 'json');
                    });

                    item.insertBefore(bottomDivider);
                });
            }
        });
    }

    // ---------------------------
    // 2️⃣ Load modal notifications
    // ---------------------------
    function loadModalNotifications() {
        $.ajax({
            url: 'php/get/get_notification.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if(!data.success) return;

                const modalBody = $('#allNotificationsModal .modal-body');
                modalBody.empty();

                if(data.notifications.length === 0){
                    modalBody.append('<p class="text-muted text-center">No notifications</p>');
                    return;
                }

                data.notifications.forEach(function(note){
                    const p = $(`
                        <div class="notification-item mb-2 d-flex align-items-start justify-content-between ${note.is_read == 0 ? 'fw-bold unread' : ''}">
                            <div class="d-flex align-items-start">
                                <input type="checkbox" class="select-notification me-2" data-id="${note.id}">
                                <div>
                                    <div class="message-text" style="cursor:pointer;">${note.message}</div>
                                    <small class="text-muted">${formatDate(note.date_created)}</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown">
                                    •••
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item mark-unread" href="#" data-id="${note.id}">Mark as Unread</a></li>
                                    <li><a class="dropdown-item delete-one" href="#" data-id="${note.id}">Delete</a></li>
                                    <li><a class="dropdown-item reply" href="#" data-id="${note.id}">Reply</a></li>
                                </ul>
                            </div>
                        </div>
                        <hr>
                    `);

                    // Click message to mark as read
                                        
                  p.find('.message-text').click(function() {
                    if (note.is_read == 0) {
                        $.post('php/update/update_notification.php', { id: note.id }, function(resp) {
                            if(resp.success){
                                updateNotifications();       // Update bell
                                loadModalNotifications();    // Reload modal content
                                $('#allNotificationsModal').modal('show'); // Keep modal open
                            }
                        }, 'json');
                    }
                });



                    // Dropdown actions
                    p.find('.mark-unread').click(function(e) {
                        e.preventDefault();
                        $.post('php/update/update_notification.php', { id: note.id, markUnread: true }, function(resp){
                            if(resp.success){
                                updateNotifications();
                                loadModalNotifications();
                            }
                        }, 'json');
                    });

                    p.find('.delete-one').click(function(e) {
                        e.preventDefault();
                        $.post('php/update/update_notification.php', { id: note.id, delete: true }, function(resp){
                            if(resp.success){
                                updateNotifications();
                                loadModalNotifications();
                                $('#allNotificationsModal').modal('show');
                            }
                        }, 'json');
                    });

                    p.find('.reply').click(function(e){
                        e.preventDefault();
                        alert('Reply action for notification: ' + note.id);
                    });

                    modalBody.append(p);
                });

                // hide checkboxes by default
                $('#allNotificationsModal .select-notification').hide();
                $('#confirmDeleteSelected').hide();
            }
        });
    }

    // ---------------------------
    // 3️⃣ Show modal
    // ---------------------------
    $('#viewAllNotifications').click(function(e) {
        e.preventDefault();
        loadModalNotifications();
        $('#allNotificationsModal').modal('show');
    });

    // ---------------------------
    // 4️⃣ Mark All as Read
    // ---------------------------
    $('#markAllReadModal').click(function(e){
        e.preventDefault();
        $.post('php/update/update_notification.php', { markAll: true }, function(resp){
            if(resp.success){
                updateNotifications();
                loadModalNotifications();
            }
        }, 'json');
    });

    // ---------------------------
    // 5️⃣ Mark All as Unread
    // ---------------------------
    $('#markAllUnreadModal').click(function(e){
        e.preventDefault();
        $.post('php/update/update_notification.php', { markAllUnread: true }, function(resp){
            if(resp.success){
                updateNotifications();
                loadModalNotifications();
            }
        }, 'json');
    });

    // ---------------------------
    // 6️⃣ Delete Selected toggle
    // ---------------------------
    $('#deleteSelectedModal').click(function() {
        const btn = $(this);
        if(btn.text() === 'Delete Selected'){
            btn.text('Cancel Delete');
            $('#allNotificationsModal .select-notification').show();
            $('#confirmDeleteSelected').show();
        } else {
            btn.text('Delete Selected');
            $('#allNotificationsModal .select-notification').prop('checked', false).hide();
            $('#confirmDeleteSelected').hide();
        }
    });

    // ---------------------------
    // 7️⃣ Confirm delete selected
    // ---------------------------
    $('#confirmDeleteSelected').click(function() {
        const selectedIds = [];
        $('#allNotificationsModal .select-notification:checked').each(function(){
            selectedIds.push($(this).data('id'));
        });

        if(selectedIds.length === 0) return alert('No notifications selected.');

        $.post('php/update/update_notification.php', { deleteMultiple: selectedIds }, function(resp){
            if(resp.success){
                updateNotifications();
                loadModalNotifications();
                $('#allNotificationsModal').modal('show');
            }
        }, 'json');
    });

    // ---------------------------
    // 8️⃣ Initial load + refresh every 30s
    // ---------------------------
    updateNotifications();
    setInterval(updateNotifications, 30000);

});


// AUTO CHECK TODAY REMINDERS — triggers only once per page load
function checkTodayReminders() {
    $.getJSON('php/reminder/check_today_reminders.php', function(res){
        if (res.success) {
            if (res.notified > 0) {
                // refresh bell notification
                updateNotifications();
            }
        }
    });
}

checkTodayReminders();
