
<?php
require_once __DIR__ . '/php/require_login.php';
require_role('Establishment');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminders - Borongan Customer Segmentation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/establishment.css">
     <link rel="stylesheet" href="css/alert.css">
    <link rel="stylesheet" href="css/messages_notification.css">
   <link rel="icon" type="image/png" href="fav.png" />

    <style>
        .calendar-grid { display:grid; grid-template-columns: repeat(7,1fr); gap:18px; }
        .calendar-day { background:#fff; border:1px solid #e6e6e6; padding:12px; min-height:90px; border-radius:8px; cursor:pointer; position:relative; }
        .calendar-day .day-number { font-weight:700; }
        .reminders-list { margin-top:8px; display:flex; flex-direction:column; gap:6px; }
        .reminder-pill { font-size:12px; padding:4px 8px; border-radius:12px; background:#0d6efd; color:white; cursor:pointer; max-width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .calendar-day.holiday { background:#ffe5e5; border-color:#ffb3b3; color:#b30000; }
        .calendar-day.disabled { background:#f7f7f7; color:#b9b9b9; cursor:default; }
        #upcomingEvents { max-height:420px; overflow:auto; }


       /* Holiday container stays normal */
.calendar-day.holiday {
    background: #ffffff !important;
    border: 1px solid #e6e6e6 !important;
    color: inherit !important;
}

/* Holiday label becomes red pill */
.calendar-day.holiday .holiday-label {
    display: inline-block;
    background: #ff4d4d;
    color: white;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    margin-top: 6px;
}



    .reminder-pill {
    padding: 4px 8px;
    border-radius: 8px;
    color: white;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Color presets */
.r-blue  { background:#377dff; }
.r-green { background:#28c76f; }
.r-orange{ background:#ff9f43; }
.r-purple{ background:#7367f0; }


.up-event-card {
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #e6e6e6;
    transition: 0.2s ease;
}

.up-event-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.up-event-card .btn {
    border-radius: 8px;
    padding: 3px 8px;
}



/* CALENDAR HOLIDAY TAG STYLE (same as your reference image) */
.holiday-tag {
    display: inline-block;
    padding: 2px 6px;
    background: #ff4d4d;
    color: white;
    border-radius: 6px;
    font-size: 11px;
    margin-top: 4px;
    font-weight: 500;
}

/* Optional: spacing from other reminders */
.calendar-day .tags-container {
    margin-top: 6px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}





/* --- Fix layout: Calendar 75%, Upcoming 25% --- */
.calendar-wrapper {
    display: flex;
    gap: 20px;
}

.calendar-left {
    width: 75%;
}

.calendar-right {
    width: 25%;
}

/* --- Fix calendar card stretching --- */
.calendar-day {
    height: 110px !important;         /* fixed height so text can't stretch */
    overflow: hidden !important;
    position: relative;
}

/* Truncate reminder text inside calendar */
.reminder-pill {
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    max-width: 100% !important;
}

/* Ensure long "+more" does not push layout */
.calendar-day small {
    white-space: nowrap;
}

/* Prevent upcoming events cards from pushing layout */
#upcomingEvents {
    max-height: 550px;
    overflow-y: auto;
}


/* Prevent title from stretching the card */
.event-title {
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    max-width: 170px; /* limit text so buttons stay visible */
    display: block;
}

/* --- UPCOMING EVENTS PERFECT FIX --- */

/* Main event card container */
.event-card {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #fff;
    padding: 12px 14px;
    border-radius: 14px;
    border: 1px solid #eaeaea;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

/* Left date box */
.event-date-box {
    min-width: 72px;
    text-align: center;
    padding: 8px;
    border-radius: 10px;
    background: var(--event-color, #eef3ff);
}

.event-date-box .month {
    font-size: 12px;
    font-weight: 600;
    color: #555;
}

.event-date-box .day {
    font-size: 20px;
    font-weight: 700;
}

.event-date-box .year {
    font-size: 12px;
    color: #777;
}

/* Center content area (IMPORTANT FIX) */
.event-content {
    flex: 1;
    min-width: 0; /* REQUIRED for ellipsis */
}

/* Title text ellipsis */
.event-title {
    font-size: 14px;
    font-weight: 700;
    color: #222;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

/* Date text */
.event-date-text {
    font-size: 12px;
    color: #888;
    margin-top: 2px;
}

/* Right-side buttons (fixed size, never shrink) */
.event-actions {
    display: flex;
    gap: 6px;
    flex-shrink: 0; /* Prevent buttons from being pushed */
}

.event-actions button {
    padding: 3px 10px;
    font-size: 12px;
    border-radius: 6px;
    white-space: nowrap;
}




    </style>
</head>
<body>

 <nav class="navbar navbar-expand-lg navbar-dark">


    
        <div class="container-fluid">

        
            <a class="navbar-brand" href="#">
                <i class="fas fa-chart-pie me-2"></i>
                Borongan Customer Segmentation
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                
                <ul class="navbar-nav ms-auto">

                <!-- HELP / TUTORIAL BUTTON -->
                       <li class="nav-item me-3">
                        <a href="#" class="nav-link" id="openTutorialBtn">
                            <i class="fas fa-question-circle fa-lg"></i>
                        </a>
                    </li>



                    <li  class="nav-item dropdown me-3">
                            <a  class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fa-lg"></i>
                                <span  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount">0
                                    <span  class="visually-hidden">unread notifications</span>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="max-width: 300px; max-height: 400px; overflow-y: auto; width: 900px;">
                                <li class="dropdown-header">Notifications</li>
                                <li><hr class="dropdown-divider"></li>
                                <!-- notification items injected dynamically here -->
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="#" id="viewAllNotifications">View all</a></li>
                        </ul>

                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"> <span id="name1" style="font-size: 12px;">
                <?php echo isset($_SESSION['business_name']) ? $_SESSION['business_name'] : ''; ?>
            </span>
                        </i> 
                        </a>
                       <ul class="dropdown-menu dropdown-menu-end">

                                <li>
                                    <a class="dropdown-item" href="establishment_settings.php">
                                        <i class="fas fa-cog me-2"></i>Settings
                                    </a>
                                </li>

                                <!-- ✅ NEW QR CODE BUTTON -->
                                <li>
                                    <a class="dropdown-item text-success" href="generate_qr.php" target="_blank">
                                        <i class="fas fa-qrcode me-2"></i>Generate QR Code
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item text-danger" href="logout.php">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>

                            </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    
       <!-- Modal for View All -->
                    <div class="modal fade" id="allNotificationsModal" tabindex="-1" aria-labelledby="allNotificationsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                        <div class="modal-header d-flex justify-content-between align-items-center">
                            <h5 class="modal-title" id="allNotificationsModalLabel">All Notifications</h5>
                            
                            <div class="dropdown">
                            <button class="btn btn-light btn-sm dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown">
                                •••
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 150px;">
                                <li><button class="dropdown-item" id="markAllReadModal">Mark All as Read</button></li>
                                <li><button class="dropdown-item" id="markAllUnreadModal">Mark All as Unread</button></li>
                                <li><button class="dropdown-item" id="deleteSelectedModal">Delete Selected</button></li>
                            </ul>
                            </div>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" id="confirmDeleteSelected" style="display:none;">Confirm Delete Selected</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        </div>
                    </div>
                    </div>



<div class="container-fluid">
  <div class="row">
     <div class="col-lg-2 col-md-3 p-0 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link " href="establishment_dashboard.php" data-section="dashboard">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a class="nav-link" href="establishment_customers.php" data-section="customers">
                        <i class="fas fa-users"></i> Customers
                    </a>
                    <a class="nav-link" href="establishment_purchased.php" data-section="purchased">
                        <i class="fas fa-shopping-cart"></i> Purchased
                    </a>
                    
                    <!-- High-Risk Customers active here -->
                    <a class="nav-link " href="establishment_high_risk.php">
                        <i class="fas fa-user-slash me-2"></i>High-Risk Customers
                    </a>

                    <a class="nav-link " href="establishment_product_analytics.php">
                            <i class="fas fa-chart-line"></i> Product Analytics
                        </a>
                        
                     <a class="nav-link" href="establishment_campaigns.php" data-section="campaigns">
                        <i class="fas fa-bullhorn"></i> Campaigns
                    </a>
                    
                        <a class="nav-link" href="establishment_segmentation.php" data-section="segmentation">
                        <i class="fas fa-object-group"></i> Segmentation
                    </a>

                    <a class="nav-link" href="establishment_filtersegments.php" data-section="segmentation">
                        <i class="fas fa-filter"></i> Filter Segment
                    </a>
                    <a class="nav-link" href="establishment_analytics.php" data-section="analytics">
                        <i class="fas fa-chart-line"></i> Reports
                    </a>

                    <a class="nav-link" href="establishment_inventory.php" data-section="inventory">
                            <i class="fas fa-boxes"></i> Inventory
                        </a>

                    <!-- NEW PAGE ACTIVE -->
                <a class="nav-link active " href="establishment_reminders.php"><i class="fas fa-calendar-alt"></i> Reminders</a>

                <a class="nav-link " href="establishment_logs.php"><i class="fas fa-database"></i> System Logs</a>
                
                    <a class="nav-link" href="establishment_settings.php" data-section="settings">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </nav>
            </div>


    <div class="col-lg-10 col-md-9 p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Reminders</h2>
        <div class="d-flex gap-2 align-items-center">
          <button class="btn btn-outline-primary" id="prevMonthBtn">‹</button>
          <select id="monthSelect" class="form-select w-auto"></select>
          <select id="yearSelect" class="form-select w-auto"></select>
          <button class="btn btn-outline-primary" id="nextMonthBtn">›</button>
        </div>
      </div>

      <div class="calendar-wrapper">
    
    <div class="calendar-left">
        <div class="calendar-grid weekday-row mb-2">
            <div class="weekday">Sun</div>
            <div class="weekday">Mon</div>
            <div class="weekday">Tue</div>
            <div class="weekday">Wed</div>
            <div class="weekday">Thu</div>
            <div class="weekday">Fri</div>
            <div class="weekday">Sat</div>
        </div>

        <div id="calendarGrid" class="calendar-grid"></div>
    </div>

    <div class="calendar-right">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Upcoming Events</h5>
                <div id="upcomingEvents"></div>
            </div>
        </div>
    </div>

</div>


    </div>
  </div>
</div>





  <!-- Chatbot Button -->
                <div id="chatbot-button" title="Help">
                    💬
                    <span id="chatbot-badge" class="badge-count">0</span>
                </div>

                <!-- Chatbot Box -->
                <div id="chatbot-box" style="display:none;">
                    <div id="chatbot-header">
                    <span>Help Assistant</span>

                    <!-- Three Dots Menu -->
                    <div id="chatbot-menu-wrap">
                        <button id="chatbot-menu-btn">⋮</button>

                        <div id="chatbot-menu-dropdown" style="display: none;">
                            <button id="chatbot-search-btn">
                                <i class="fas fa-search"></i>
                                Search conversation
                            </button>

                        </div>

                  
                    </div>

                    <button id="chatbot-close">✕</button>

                    
                </div>

                           <!-- Hidden Search Bar -->
                <div id="chatbot-search-bar" style="display:none;">
                    <input id="chatbot-search-input" type="text" class="form-control" placeholder="Search messages...">
                </div>

                <div id="chatbot-search-results" style="display:none;"></div>

                <div id="chatbot-messages"></div>


                    <div id="typing-indicator" style="display:none;">
                        Admin is typing…
                    </div>

                    <div id="chatbot-input-wrap">
                        <input id="chatbot-input" placeholder="Ask help to the admin..." />
                        <button id="chatbot-send">Send</button>
                    </div>
                </div>

    
              <!-- Tutorial Video Modal -->
<div class="modal fade" id="tutorialModal" tabindex="-1" aria-labelledby="tutorialModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="tutorialModalLabel">Web Application Tutorial</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-0">
        <iframe 
            id="tutorialVideo"
            width="100%"
            height="600px"
            src=""
            title="Tutorial Video"
            frameborder="0"
            allowfullscreen
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
        </iframe>
      </div>

    </div>
  </div>
</div>



<!-- Modal: Add/Edit -->
<div class="modal fade" id="dayModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Set Reminder</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editingId" value="">
        <div class="mb-2"><strong id="modalDateTitle"></strong></div>
        <label class="form-label">Message</label>
        <textarea id="reminderText" class="form-control" rows="4" placeholder="Write your reminder..."></textarea>
        <div class="mt-3" id="existingRemindersContainer"></div>
      </div>
      <div class="modal-footer">
        <button id="saveReminder" class="btn btn-primary">Save</button>
        <button id="updateReminder" class="btn btn-success" style="display:none">Update</button>
        <button id="deleteReminder" class="btn btn-danger" style="display:none">Delete</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="js/messages_notification.js"></script>
    <script src="js/tutorial_video.js"></script>
    <script src="js/global_alert.js"></script>

<script>
$(function() {
    // -----------------------
    // Config
    // -----------------------
    const SWEET_OPTS = {
        position: 'center',
        customClass: {
            popup: 'small-alert',
            title: 'small-title',
            content: 'small-content',
            actions: 'small-actions'
        }
    };

    const holidays = {
    // --- REGULAR HOLIDAYS ---
    "2025-01-01": "New Year's Day",
    "2025-04-17": "Maundy Thursday",
    "2025-04-18": "Good Friday",
    "2025-05-01": "Labor Day",
    "2025-06-12": "Independence Day",
    "2025-08-25": "National Heroes Day",
    "2025-11-30": "Bonifacio Day",
    "2025-12-25": "Christmas Day",
    "2025-12-30": "Rizal Day",

    // --- SPECIAL NON-WORKING HOLIDAYS ---
    "2025-02-01": "Chinese New Year",
    "2025-02-25": "EDSA People Power Anniversary",
    "2025-04-19": "Black Saturday",
    "2025-08-21": "Ninoy Aquino Day",
    "2025-11-01": "All Saints’ Day",
    "2025-11-02": "All Souls’ Day",
    "2025-12-08": "Feast of the Immaculate Conception",

    // --- ADDITIONAL SPECIAL DAYS (Malacañang usually declares these yearly) ---
    "2025-12-24": "Christmas Eve (Special Non-Working)",
    "2025-12-31": "Last Day of the Year (Special Non-Working)"
};


    // DOM refs
    const $calendarGrid = $('#calendarGrid');
    const $monthSelect  = $('#monthSelect');
    const $yearSelect   = $('#yearSelect');
    const $upcoming     = $('#upcomingEvents');
    const $notifCount   = $('#notificationCount');
    const dayModalEl    = document.getElementById('dayModal');
    const dayModal      = new bootstrap.Modal(dayModalEl);

    let currentDate = new Date();

    // -----------------------
    // UI helpers
    // -----------------------
    function swalConfirm(title = "Are you sure?", text = "You won’t be able to revert this!") {
        return Swal.fire(Object.assign({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel"
        }, SWEET_OPTS));
    }

    function swalSuccess(title = "Success", text = "", ms = 1400) {
        return Swal.fire(Object.assign({
            icon: 'success',
            title: title,
            text: text,
            showConfirmButton: false,
            timer: ms
        }, SWEET_OPTS));
    }

    function swalError(title = "Error", text = "") {
        return Swal.fire(Object.assign({
            icon: 'error',
            title: title,
            text: text
        }, SWEET_OPTS));
    }

    function swalWarn(title = "Warning", text = "") {
        return Swal.fire(Object.assign({
            icon: 'warning',
            title: title,
            text: text
        }, SWEET_OPTS));
    }

    // -----------------------
    // Populate selects
    // -----------------------
    const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    monthNames.forEach((m, i) => { $monthSelect.append(new Option(m, i)); });

    const startYear = currentDate.getFullYear() - 5;
    for (let y = startYear; y <= currentDate.getFullYear() + 5; y++) {
        $yearSelect.append(new Option(y, y));
    }
    $monthSelect.val(currentDate.getMonth());
    $yearSelect.val(currentDate.getFullYear());

    // -----------------------
    // Data fetchers
    // -----------------------
    function fetchRemindersForMonth(year, month) {
        // month is 1-12 expected by backend
        return $.getJSON('php/reminder/fetch_reminders.php', { year: year, month: month });
    }

    function fetchRemindersByDate(date) {
        return $.getJSON('php/reminder/fetch_reminders.php', { date: date });
    }

    // -----------------------
    // Calendar renderer
    // -----------------------
    function renderCalendar() {
        $calendarGrid.empty();

        const year = parseInt($yearSelect.val());
        const month = parseInt($monthSelect.val());

        const firstDay = new Date(year, month, 1);
        const startDay = firstDay.getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // blanks
        for (let i = 0; i < startDay; i++) {
            $calendarGrid.append(`<div class="calendar-day disabled"></div>`);
        }

        // fetch reminders keyed by date
        fetchRemindersForMonth(year, month + 1).done(function(remindersData) {
            // expectations: remindersData is object keyed by yyyy-mm-dd => [ {id, message, reminder_date}, ... ]
            for (let d = 1; d <= daysInMonth; d++) {
                const dateKey = `${year}-${String(month + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                const isHoliday = holidays[dateKey] !== undefined;

                let dayHtml = `<div class="calendar-day ${isHoliday ? 'holiday' : ''}" data-date="${dateKey}">`;
                dayHtml += `<div class="day-number">${d}</div>`;

                if (isHoliday) {
                    dayHtml += `<div class="tags-container"><span class="holiday-tag">${holidays[dateKey]}</span></div>`;
                }

                const dayReminders = remindersData[dateKey] || [];
                if (dayReminders.length) {
                    dayHtml += `<div class="reminders-list">`;
                    const colors = ["r-blue","r-green","r-orange","r-purple"];
                    dayReminders.slice(0,3).forEach(r => {
                        const randColor = colors[Math.floor(Math.random() * colors.length)];
                        // truncate message for pill
                        const safeMsg = $('<div/>').text(r.message).html();
                        dayHtml += `<div class="reminder-pill ${randColor}" data-id="${r.id}">${safeMsg}</div>`;
                    });
                    if (dayReminders.length > 3) {
                        dayHtml += `<small class="text-muted">+${dayReminders.length - 3} more</small>`;
                    }
                    dayHtml += `</div>`;
                }

                dayHtml += `</div>`;
                $calendarGrid.append(dayHtml);
            }

            // attach click handlers (use delegation to be safe)
            $calendarGrid.find('.calendar-day').not('.disabled').off('click').on('click', function(e){
                const date = $(this).data('date');
                openDayModal(date);
            });

            $calendarGrid.find('.reminder-pill').off('click').on('click', function(e){
                e.stopPropagation();
                openEditById($(this).data('id'));
            });

        }).fail(function() {
            console.error('Could not fetch reminders for month.');
        });
    }

    // -----------------------
    // Prev / Next / Selects
    // -----------------------
    $('#prevMonthBtn').on('click', function(){
        let m = parseInt($monthSelect.val()), y = parseInt($yearSelect.val());
        if (m === 0) { $monthSelect.val(11); $yearSelect.val(y - 1); } else $monthSelect.val(m - 1);
        renderCalendar();
    });

    $('#nextMonthBtn').on('click', function(){
        let m = parseInt($monthSelect.val()), y = parseInt($yearSelect.val());
        if (m === 11) { $monthSelect.val(0); $yearSelect.val(y + 1); } else $monthSelect.val(m + 1);
        renderCalendar();
    });

    $monthSelect.on('change', renderCalendar);
    $yearSelect.on('change', renderCalendar);

    // initial
    renderCalendar();

    // -----------------------
    // Modal open + load date reminders
    // -----------------------
    function openDayModal(date) {
        $('#editingId').val('');
        $('#modalDateTitle').text(date);
        $('#reminderText').val('');
        $('#updateReminder').hide();
        $('#deleteReminder').hide();
        $('#saveReminder').show();
        $('#existingRemindersContainer').html('<em>Loading...</em>');
        dayModal.show();

        fetchRemindersByDate(date).done(function(data){
            // data should be array of reminders for that date
            if (!data || !data.length) {
                $('#existingRemindersContainer').html('<div class="text-muted">No reminders yet.</div>');
                return;
            }

            let html = '';
            data.forEach(r => {
                const safeMsg = $('<div/>').text(r.message).html();
                html += `
                    <div class="up-event-card mb-2 p-3 shadow-sm">
                        <div class="fw-bold text-dark">${safeMsg}</div>
                        <div class="text-muted small mb-2">${r.reminder_date}</div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-primary btn-edit-reminder" data-id="${r.id}"><i class="fa-solid fa-pen"></i></button>
                            <button type="button" class="btn btn-sm btn-danger btn-del-reminder" data-id="${r.id}"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </div>
                `;
            });

            $('#existingRemindersContainer').html(html);
        }).fail(function(){
            $('#existingRemindersContainer').html('<div class="text-danger">Failed to load reminders.</div>');
        });
    }

    // -----------------------
    // Save (create) reminder
    // -----------------------
    $('#saveReminder').on('click', function(e) {
        e.preventDefault();

        const date = $('#modalDateTitle').text();
        const message = $('#reminderText').val().trim();

        if (!message) {
            swalWarn('Missing message', 'Please enter a message');
            return;
        }

        $.post('php/reminder/save_reminder.php', { date: date, message: message }, function(res) {
            if (res && (res.status === 'success' || res.status === 1 || res.status === '1')) {
                swalSuccess('Saved!', 'Reminder saved successfully').then(() => {
                    dayModal.hide();
                    renderCalendar();
                    loadUpcoming();
                });
            } else {
                const msg = res && res.message ? res.message : 'Error saving reminder';
                swalError('Failed!', msg);
            }
        }, 'json').fail(function() {
            swalError('Server error', 'Could not reach server');
        });
    });

    // -----------------------
    // Edit by ID (open modal filled)
    // -----------------------
    function openEditById(id) {
        if (!id) return;
        $.getJSON('php/reminder/fetch_reminders.php', { id: id }).done(function(data) {
            if (!data || !data.length) return;
            const r = data[0];
            $('#editingId').val(r.id);
            $('#modalDateTitle').text(r.reminder_date);
            $('#reminderText').val(r.message);
            $('#saveReminder').hide();
            $('#updateReminder').show();
            $('#deleteReminder').show();
            dayModal.show();
        }).fail(function(){
            swalError('Error', 'Unable to load reminder for edit');
        });
    }

    // -----------------------
    // Update existing reminder
    // -----------------------
    $('#updateReminder').on('click', function(e) {
        e.preventDefault();

        const id = $('#editingId').val();
        const message = $('#reminderText').val().trim();
        if (!id || !message) {
            swalWarn('Missing data', 'Please fill required fields');
            return;
        }

        $.post('php/reminder/edit_reminder.php', { id: id, message: message }, function(res) {
            if (res && (res.status === 'success' || res.status === 1 || res.status === '1')) {
                swalSuccess('Updated!', 'Reminder updated successfully').then(() => {
                    dayModal.hide();
                    renderCalendar();
                    loadUpcoming();
                });
            } else {
                const msg = res && res.message ? res.message : 'Update failed';
                swalError('Failed!', msg);
            }
        }, 'json').fail(function(){
            swalError('Server error', 'Could not reach server');
        });
    });

    // -----------------------
    // Delete from modal Delete button
    // -----------------------
    $('#deleteReminder').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const id = $('#editingId').val();
        if (!id) return;

        swalConfirm('Delete this reminder?', 'You won’t be able to revert this!').then(result => {
            if (result.isConfirmed) {
                $.post('php/reminder/delete_reminder.php', { id: id }, function(res) {
                    if (res && (res.status === 'success' || res.status === 1 || res.status === '1')) {
                        swalSuccess('Deleted!', 'Reminder deleted successfully').then(() => {
                            dayModal.hide();
                            renderCalendar();
                            loadUpcoming();
                        });
                    } else {
                        const msg = res && res.message ? res.message : 'Delete failed';
                        swalError('Failed!', msg);
                    }
                }, 'json').fail(function(){
                    swalError('Server error', 'Could not reach server');
                });
            }
        });
    });

    // -----------------------
    // Delegated edit/delete for items inside modal / upcoming list / calendar pills
    // -----------------------
    $(document).on('click', '.btn-edit-reminder', function(e) {
        e.preventDefault();
        e.stopPropagation();
        openEditById($(this).data('id'));
    });

    $(document).on('click', '.btn-del-reminder', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const id = $(this).data('id');
        if (!id) return;

        swalConfirm('Delete this reminder?', 'You won’t be able to revert this!').then(result => {
            if (result.isConfirmed) {
                $.post('php/reminder/delete_reminder.php', { id: id }, function(res) {
                    if (res && (res.status === 'success' || res.status === 1 || res.status === '1')) {
                        // show success, update UI shortly after so user sees it
                        swalSuccess('Deleted!', 'Reminder deleted successfully');
                        setTimeout(() => {
                            renderCalendar();
                            loadUpcoming();
                            // if modal open, refresh its content
                            if ($('#dayModal').hasClass('show')) {
                                openDayModal($('#modalDateTitle').text());
                            }
                        }, 500);
                    } else {
                        const msg = res && res.message ? res.message : 'Delete failed';
                        swalError('Failed!', msg);
                    }
                }, 'json').fail(function(){
                    swalError('Server error', 'Could not reach server');
                });
            }
        });
    });

    // -----------------------
    // Upcoming list loader + renderer
    // -----------------------
    function loadUpcoming() {
        $.getJSON('php/reminder/get_upcoming_reminders.php').done(function(data) {
            if (!data || !data.length) {
                $upcoming.html('<div class="text-muted">No upcoming events</div>');
                return;
            }

            let html = '';
            data.forEach(r => {
                // pastel color choices
                const colors = ["#f9f9ff","#fbfff3","#fffaf3","#fff6fb","#f7f4ff"];
                const chosenColor = colors[Math.floor(Math.random() * colors.length)];

                const d = new Date(r.reminder_date);
                const month = d.toLocaleString('default', { month: 'short' });
                const day = d.getDate();
                const year = d.getFullYear();

                const safeTitle = $('<div/>').text(r.message).html();

                html += `
                                    <div class="event-card">
                    <div class="event-date-box" style="background: ${chosenColor};">
                        <div class="month">${month.toUpperCase()}</div>
                        <div class="day">${day}</div>
                        <div class="year">${year}</div>
                    </div>

                    <div class="event-content">
                        <div class="event-title">${safeTitle}</div>
                        <div class="event-date-text">${r.reminder_date}</div>
                    </div>

                    <div class="event-actions">
                        <button class="btn btn-sm btn-outline-primary btn-edit-reminder" data-id="${r.id}">Edit</button>
                        <button class="btn btn-sm btn-outline-danger btn-del-reminder" data-id="${r.id}">Delete</button>
                    </div>
                </div>

                `;
            });

            $upcoming.html(html);
        }).fail(function() {
            $upcoming.html('<div class="text-danger">Failed to load upcoming events</div>');
        });
    }

    loadUpcoming();

    // -----------------------
    // Today's notifications (bell)
    // -----------------------
    function loadTodayNotifications() {
        $.getJSON('php/reminder/get_today_reminder.php').done(function(data) {
            if (!data || !data.length) return;
            const count = data.length;
            const cur = parseInt($notifCount.text()) || 0;
            $notifCount.text(cur + count);

            // prepend items to the notification dropdown if available
            data.forEach(r => {
                if ($('#notificationItems').length) {
                    $('#notificationItems').prepend(`<li class="dropdown-item">📌 ${$('<div/>').text(r.message).html()}</li><li><hr class="dropdown-divider"></li>`);
                }
            });
        }).fail(function() {
            // silently ignore
        });
    }

    loadTodayNotifications();

}); // end $(function)
</script>

</body>
</html>
 

