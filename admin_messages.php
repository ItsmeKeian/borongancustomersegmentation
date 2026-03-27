<?php
session_start();
require_once __DIR__ . '/php/require_login.php';
require_role('Admin');
require_once __DIR__ . '/php/dbconnect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messaging - Borongan City Customer Segmentation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admindashboard.css">
    <link rel="icon" type="image/png" href="fav.png" />

    <style>
      /* Main container for chat UI */
      .msg-container {
          background: #fff;
          border-radius: 12px;
          padding: 15px;
          box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      }

      /* Establishment list */
      .est-item {
          padding: 12px;
          border-bottom: 1px solid #eee;
          cursor: pointer;
          font-size: 15px;
          transition: background 0.15s;
      }
      .est-item:hover { background: #f1f3f5; }
      .est-active { background: #e9ecef; }

      /* Chat message scroll container */
      .msg-box {
          height: 430px;
          overflow-y: auto;
          padding: 15px;
          background: #f8f9fa;
          border-radius: 10px;
      }

      /* Fade-in animation */
      @keyframes fadeIn {
          from { opacity: 0; transform: translateY(4px); }
          to   { opacity: 1; transform: translateY(0);   }
      }

      .message {
          margin: 12px 0;
          animation: fadeIn 0.25s ease;
      }

      /* USER → establishment messages (green, left) */
      .user-msg {
          text-align: left;
      }
      .user-msg span {
          background: #d1ffd9;
          padding: 10px 14px;
          border-radius: 14px;
          display: inline-block;
          max-width: 70%;
          line-height: 1.4;
          box-shadow: 0 2px 4px rgba(0,0,0,0.08);
      }

      /* ADMIN → your messages (blue, right) */
      .admin-msg {
          text-align: right;
      }
      .admin-msg span {
          background: #3498db;
          color: white;
          padding: 10px 14px;
          border-radius: 14px;
          display: inline-block;
          max-width: 70%;
          line-height: 1.4;
          box-shadow: 0 2px 4px rgba(0,0,0,0.08);
      }

      /* BOT/system announcements */
      .system-msg {
          text-align: center;
          margin: 14px 0;
      }
      .system-msg span {
          background: #e2e3e5;
          padding: 6px 12px;
          border-radius: 10px;
          font-size: 12px;
          color: #555;
          display: inline-block;
      }

      /* Timestamp under each bubble */
      .timestamp {
          font-size: 11px;
          color: #888;
          margin-top: 4px;
      }

      /* Date separator */
      .date-separator {
          text-align: center;
          margin: 10px 0;
          font-size: 12px;
          color: #777;
      }

      /* Typing indicator text */
      #admin-typing {
          display: none;
          font-size: 13px;
          color: #666;
          margin-top: 4px;
      }

      /* Reply input area */
      .reply-box {
          margin-top: 12px;
          display: flex;
          gap: 10px;
      }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fas fa-comments me-2"></i> Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-user-shield me-1"></i> Admin User
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="adminsystemsettings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 col-md-3 p-0 sidebar">
            <nav class="nav flex-column">
                <a class="nav-link" href="admindashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a class="nav-link" href="adminestablishment.php"><i class="fas fa-store"></i> Establishments</a>
                <a class="nav-link active" href="admin_messages.php"><i class="fas fa-comments"></i> Messages</a>
                <a class="nav-link" href="adminsystemsettings.php"><i class="fas fa-cogs"></i> System Settings</a>
            </nav>
        </div>

        <!-- Main content -->
        <div class="col-lg-10 col-md-9 p-4">
            <h2 class="mb-4">Admin Messaging</h2>

            <div class="row">
                <!-- Establishment list -->
                <div class="col-md-4 mb-3">
                    <div class="msg-container">
                        <h5><i class="fas fa-store"></i> Establishments</h5>
                        <small class="text-muted">Click an establishment to view the conversation.</small>
                        <hr>
                        <div id="est-list">Loading...</div>
                    </div>
                </div>

                <!-- Conversation -->
                <div class="col-md-8 mb-3">
                    <div class="msg-container">
                        <h5 id="chat-title"><i class="fas fa-comments"></i> Conversation</h5>
                        <hr>
                        <div id="chat-box" class="msg-box">
                            <p class="text-muted">Please select an establishment to start messaging.</p>
                        </div>

                        <!-- typing indicator -->
                        <div id="admin-typing">Establishment is typing…</div>

                        <div class="reply-box">
                            <input type="text" id="admin-reply" class="form-control" placeholder="Type a reply…" disabled>
                            <button class="btn btn-primary" id="send-reply" disabled>Send</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const CHAT_BASE = "http://localhost/borongan/php/chat/";

let currentEstId   = null;
let currentEstName = null;
let lastConvMaxId  = 0;   // track last message id in current conversation
let lastDateLabel  = "";  // for date separators

// --------------- Helpers -----------------

function formatMessage(text) {
    // basic escape + line breaks
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/\n/g, "<br>");
}

function formatDateLabel(dateStr) {
    if (!dateStr) return "";
    const d = new Date(dateStr);
    const today = new Date();
    const yesterday = new Date();
    yesterday.setDate(today.getDate() - 1);

    const sameDay = (a, b) =>
        a.getFullYear() === b.getFullYear() &&
        a.getMonth() === b.getMonth() &&
        a.getDate() === b.getDate();

    if (sameDay(d, today)) return "Today";
    if (sameDay(d, yesterday)) return "Yesterday";

    return d.toLocaleDateString(undefined, { month: "short", day: "numeric", year: "numeric" });
}

function smoothScrollChat() {
    const box = document.getElementById("chat-box");
    box.scrollTo({ top: box.scrollHeight, behavior: "smooth" });
}

function showTypingIndicator() {
    const el = document.getElementById("admin-typing");
    if (!el) return;
    el.style.display = "block";
    setTimeout(() => { el.style.display = "none"; }, 1500);
}

function renderConversation(data) {
    const box = document.getElementById("chat-box");
    box.innerHTML = "";
    lastDateLabel = "";
    let maxId = 0;

    data.forEach(m => {
        maxId = Math.max(maxId, parseInt(m.id, 10) || 0);

        const created = m.created_at || null;
        const dateLabel = created ? formatDateLabel(created) : null;

        if (dateLabel && dateLabel !== lastDateLabel) {
            const sep = document.createElement("div");
            sep.className = "date-separator";
            sep.textContent = dateLabel;
            box.appendChild(sep);
            lastDateLabel = dateLabel;
        }

        const wrapper = document.createElement("div");
        wrapper.classList.add("message");

        if (m.sender_type === "admin") {
            wrapper.classList.add("admin-msg");
        } else if (m.sender_type === "bot") {
            wrapper.classList.add("system-msg");
        } else {
            wrapper.classList.add("user-msg");
        }

        const timeStr = created
            ? new Date(created).toLocaleTimeString([], { hour: "2-digit", minute: "2-digit" })
            : "";

        let inner = `<span>${formatMessage(m.message)}</span>`;
        if (m.sender_type !== "bot") {
            inner += `<div class="timestamp">${timeStr}</div>`;
        }

        wrapper.innerHTML = inner;
        box.appendChild(wrapper);
    });

    lastConvMaxId = maxId;
    smoothScrollChat();
}

// ---------------------------
// Load Establishment List
// ---------------------------
async function loadEstablishments() {
    try {
        const res  = await fetch(CHAT_BASE + "fetch_unread_count.php", { credentials: "include" });
        const data = await res.json();
        console.log("UNREAD LIST:", data);

        let html = "";
        if (!Array.isArray(data) || data.length === 0) {
            html = "<p class='text-muted mb-0'>No conversations yet.</p>";
        } else {
            data.forEach(row => {
                const unread = parseInt(row.cnt, 10) || 0;
                html += `
                    <div class="est-item" data-id="${row.establishment_id}" data-name="${row.business_name}">
                        ${row.business_name}
                        ${unread > 0
                            ? `<span class="badge bg-danger float-end">${unread}</span>`
                            : `<span class="badge bg-secondary float-end">0</span>`}
                    </div>
                `;
            });
        }

        document.getElementById("est-list").innerHTML = html;

        document.querySelectorAll(".est-item").forEach(el => {
            el.onclick = () => openConversation(el.dataset.id, el.dataset.name);
        });

    } catch (err) {
        console.error("loadEstablishments error", err);
        document.getElementById("est-list").innerHTML =
            "<p class='text-danger mb-0'>Failed to load establishments.</p>";
    }
}

// ---------------------------
// Load Messages / Conversation
// ---------------------------
async function openConversation(estId, estName) {
    currentEstId   = estId;
    currentEstName = estName;
    lastConvMaxId  = 0;    // reset when switching conversations

    document.querySelectorAll(".est-item").forEach(x => x.classList.remove("est-active"));
    const activeEl = document.querySelector(`.est-item[data-id="${estId}"]`);
    if (activeEl) activeEl.classList.add("est-active");

    document.getElementById("chat-title").innerHTML =
        `<i class="fas fa-comments"></i> Conversation with <strong>${estName}</strong>`;

    try {
        const res  = await fetch(CHAT_BASE + "admin_fetch_conversation.php?est_id=" + estId, {
            credentials: "include"
        });
        const data = await res.json();
        console.log("CONVO:", data);

        if (!Array.isArray(data) || data.length === 0) {
            document.getElementById("chat-box").innerHTML =
                `<p class="text-muted mb-0">No messages yet for ${estName}.</p>`;
        } else {
            renderConversation(data);
        }

        document.getElementById("admin-reply").disabled = false;
        document.getElementById("send-reply").disabled  = false;

        // mark as read
        await fetch(CHAT_BASE + "mark_read.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "est_id=" + encodeURIComponent(estId),
            credentials: "include"
        });

        loadEstablishments(); // refresh badges

    } catch (err) {
        console.error("openConversation error", err);
        document.getElementById("chat-box").innerHTML =
            "<p class='text-danger mb-0'>Failed to load conversation.</p>";
    }
}

// ---------------------------
// Poll updates for current convo
// ---------------------------
async function pollAdminMessages() {
    if (!currentEstId) return;

    try {
        const res  = await fetch(CHAT_BASE + "admin_fetch_conversation.php?est_id=" + currentEstId, {
            credentials: "include"
        });
        const data = await res.json();
        if (!Array.isArray(data) || data.length === 0) return;

        let maxId = 0;
        let hasNewUserMsg = false;

        data.forEach(m => {
            const mid = parseInt(m.id, 10) || 0;
            maxId = Math.max(maxId, mid);
            if (mid > lastConvMaxId && m.sender_type === "user") {
                hasNewUserMsg = true;
            }
        });

        if (maxId > lastConvMaxId) {
            // new messages → re-render
            renderConversation(data);

            if (hasNewUserMsg) {
                showTypingIndicator();
            }
        }
    } catch (err) {
        console.error("pollAdminMessages error:", err);
    }
}

// ---------------------------
// Admin Send Message
// ---------------------------
document.getElementById("send-reply").onclick = async () => {
    const msgInput = document.getElementById("admin-reply");
    const msg      = msgInput.value.trim();

    if (!msg || !currentEstId) return;

    try {
        const res = await fetch(CHAT_BASE + "admin_send.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ est_id: currentEstId, message: msg }),
            credentials: "include"
        });

        const data = await res.json();
        console.log("SEND REPLY:", data);

        msgInput.value = "";
        // reload conversation; polling will also catch it but we want it instant
        openConversation(currentEstId, currentEstName);

    } catch (err) {
        console.error("send-reply error", err);
    }
};

document.getElementById("admin-reply").addEventListener("keypress", e => {
    if (e.key === "Enter") {
        e.preventDefault();
        document.getElementById("send-reply").click();
    }
});

// Initial load + polling
loadEstablishments();
setInterval(loadEstablishments, 5000);
setInterval(pollAdminMessages, 2000);
</script>

</body>
</html>
