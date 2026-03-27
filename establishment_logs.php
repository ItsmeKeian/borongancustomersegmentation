<?php
require_once __DIR__ . '/php/require_login.php';
require_role('Establishment');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs - Borongan Customer Segmentation</title>

    <!-- Bootstrap / Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/establishment.css">
    <link rel="stylesheet" href="css/alert.css">
    <link rel="stylesheet" href="css/messages_notification.css">

    <link rel="icon" type="image/png" href="fav.png" />
</head>


<body>

<!-- NAVBAR -->
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


<!-- MAIN LAYOUT -->
<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-lg-2 col-md-3 p-0 sidebar">
            <nav class="nav flex-column">
                <a class="nav-link" href="establishment_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                <a class="nav-link" href="establishment_customers.php"><i class="fas fa-users"></i> Customers</a>
                <a class="nav-link" href="establishment_purchased.php"><i class="fas fa-shopping-cart"></i> Purchased</a>
                <a class="nav-link" href="establishment_high_risk.php"><i class="fas fa-user-slash me-2"></i>High-Risk Customers</a>
                <a class="nav-link" href="establishment_product_analytics.php"><i class="fas fa-chart-line"></i> Product Analytics</a>
                <a class="nav-link" href="establishment_campaigns.php"><i class="fas fa-bullhorn"></i> Campaigns</a>
                <a class="nav-link" href="establishment_segmentation.php"><i class="fas fa-object-group"></i> Segmentation</a>
                <a class="nav-link" href="establishment_filtersegments.php"><i class="fas fa-filter"></i> Filter Segment</a>
                <a class="nav-link" href="establishment_analytics.php"><i class="fas fa-chart-line"></i> Reports</a>
                <a class="nav-link" href="establishment_inventory.php"><i class="fas fa-boxes"></i> Inventory</a>
                <a class="nav-link" href="establishment_reminders.php"><i class="fas fa-calendar-alt"></i> Reminders</a>
                <a class="nav-link active" href="establishment_logs.php"><i class="fas fa-database"></i> System Logs</a>
                <a class="nav-link" href="establishment_settings.php"><i class="fas fa-cog"></i> Settings</a>
            </nav>
        </div>


        <!-- CONTENT -->
        <div class="col-lg-10 col-md-9 p-4">

            <h3 class="mb-3">System Logs</h3>

                <div class="card">
                    <div class="card-body">

                        <!-- Search -->
                        <div class="mb-3">
                            <input type="text" id="searchLogs" class="form-control" placeholder="Search logs...">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>IP</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="logsTable"></tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Log pagination">
                            <div class="d-flex justify-content-between mt-2">
                                <div id="logCount" class="text-muted">Showing 0 logs</div>
                            </div>
                            <ul class="pagination justify-content-center"></ul>
                        </nav>

                    </div>
                </div>


        </div>

    </div>
</div>

  <!-- All Notifications Modal -->
                <div class="modal fade" id="allNotificationsModal" tabindex="-1" aria-labelledby="allNotificationsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="allNotificationsModalLabel">All Notifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Notifications will be injected here dynamically -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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


<!-- LOG DETAILS MODAL -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Log Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody id="logDetailsBody"></tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>




<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="js/messages_notification.js"></script>
<script src="js/tutorial_video.js"></script>

<script>

// VIEW DETAILS MODAL (Improved)
function openLogDetails(detailsString) {
    try {
        const obj = JSON.parse(detailsString);
        let html = "";

        /* ================================
           UPDATE LOGS (before / after)
        ================================= */
        if (obj.before && obj.after && obj.changes) {

            html += `<tr><th colspan="2" class="table-secondary">Updated Fields</th></tr>`;

            for (let field in obj.changes) {
                html += `
                    <tr>
                        <th style="width:30%">${field.replace(/_/g," ")}</th>
                        <td>
                            <span class="text-danger fw-bold">${obj.changes[field].old}</span>
                            →
                            <span class="text-success fw-bold">${obj.changes[field].new}</span>
                        </td>
                    </tr>
                `;
            }

            html += `<tr><th colspan="2" class="table-secondary">After Update</th></tr>`;

            for (let key in obj.after) {
                html += `
                    <tr>
                        <th>${key.replace(/_/g," ")}</th>
                        <td>${obj.after[key]}</td>
                    </tr>
                `;
            }
        }

        /* ================================
           BULK EMAIL LOG (NEW FIX)
        ================================= */
        else if (obj.subject && obj.sent_count !== undefined && Array.isArray(obj.customers)) {

    html += `
        <tr><th>Subject</th><td>${obj.subject}</td></tr>
        <tr>
            <th>Message</th>
            <td style="white-space:pre-wrap">${obj.message}</td>
        </tr>
        <tr><th>Sent Count</th><td>${obj.sent_count}</td></tr>

        <tr><th colspan="2" class="table-secondary">Recipients</th></tr>
        <tr>
            <td colspan="2">
                <ul class="mb-0">
                    ${
                        obj.customers.map(c => `
                            <li>${c.full_name}</li>
                        `).join("")
                    }
                </ul>
            </td>
        </tr>
    `;
}


        /* ================================
           ITEMS TABLE (PURCHASE LOGS)
        ================================= */
        else {

            for (let key in obj) {

                let label = key.replace(/_/g, " ");

                if (key === "items" && Array.isArray(obj[key])) {

                    html += `
                        <tr>
                            <th>${label}</th>
                            <td>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${obj[key].map(item => `
                                            <tr>
                                                <td>${item.item_name}</td>
                                                <td>${item.price}</td>
                                                <td>${item.quantity}</td>
                                                <td>${item.subtotal}</td>
                                            </tr>
                                        `).join("")}
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    `;
                } else {
                    html += `
                        <tr>
                            <th>${label}</th>
                            <td>${obj[key]}</td>
                        </tr>
                    `;
                }
            }
        }

        document.getElementById("logDetailsBody").innerHTML = html;

    } catch (err) {
        document.getElementById("logDetailsBody").innerHTML =
            `<tr><td colspan="2">${detailsString}</td></tr>`;
    }

    new bootstrap.Modal(document.getElementById("logDetailsModal")).show();
}






// DELETE LOG
function deleteLog(id) {

    Swal.fire({
        title: "Are you sure?",
        text: "This log will be permanently removed.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        position: "center",
        customClass: {
            popup: 'small-alert',
            title: 'small-title',
            content: 'small-content',
            actions: 'small-actions'
        }
    }).then((result) => {

        if (result.isConfirmed) {

            fetch("php/delete/delete_log.php?id=" + id)
                .then(r => r.json())
                .then(res => {

                    if (res.status === 1) {

                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: "Log deleted successfully",
                            showConfirmButton: false,
                            timer: 1500,
                            position: "center",
                            customClass: {
                                popup: 'small-alert',
                                title: 'small-title',
                                content: 'small-content',
                                actions: 'small-actions'
                            }
                        }).then(() => {
                            loadLogs(); // Refresh log list only
                        });

                    } else {

                        Swal.fire({
                            icon: "error",
                            title: "Failed!",
                            text: res.message ?? "Failed to delete log.",
                            position: "center",
                            customClass: {
                                popup: 'small-alert',
                                title: 'small-title',
                                content: 'small-content',
                                actions: 'small-actions'
                            }
                        });

                    }

                });

        }

    });

}




// PAGINATION + SEARCH + LOAD LOGS
$(function () {

    let currentPage = 1;
    const limit = 10;
    let searchTimer = null;
    let activeRequest = null;
    let lastRequestId = 0;

    function loadLogs(page = 1, search = "") {

        if (activeRequest) activeRequest.abort();

        const requestId = ++lastRequestId;

        $("#logsTable").html("<tr><td colspan='5' class='text-center'>Loading...</td></tr>");

        activeRequest = $.ajax({
            type: "POST",
            url: "php/get/get_establishment_logs.php",
            data: { page, limit, search },
            dataType: "json",

            success: function (result) {

                if (requestId !== lastRequestId) return;

                $("#logsTable").empty();

                if (!result.logs || result.logs.length === 0) {
                    $("#logsTable").html("<tr><td colspan='5' class='text-center'>No logs found</td></tr>");
                    $(".pagination").empty();
                    $("#logCount").text("Showing 0 logs");
                    return;
                }

                result.logs.forEach(log => {

                    let summary = log.details;

                    try {
                        const obj = JSON.parse(log.details);

                        if (log.action === "Customer Created") 
                            summary = `Created: <strong>${obj.full_name}</strong>`;

                        else if (log.action === "Delete Customer") 
                            summary = `Deleted: <strong>${obj.full_name ?? "Unknown"}</strong>`;

                       else if (log.action === "Update Customer") {
                        const obj = JSON.parse(log.details);

                        // Display the customer's name only
                        let displayName = "";

                        // 1. If full name changed → use old name (for summary)
                        if (obj.before && obj.before.full_name) {
                            displayName = obj.before.full_name;
                        }
                        // 2. Or if not changed → use after name
                        else if (obj.after && obj.after.full_name) {
                            displayName = obj.after.full_name;
                        }

                        summary = `Updated: <strong>${displayName}</strong>`;
                    }


                      
                        else if (log.action === "Checkout" || log.action === "POS Checkout") {
                            summary = `Checkout: <strong>${obj.full_name}</strong> (₱${obj.grand_total})`;
                        }


                        else if (log.action === "Delete Purchase" || log.action === "Delete Purchased") {
                            try {
                                const obj = JSON.parse(log.details);

                                const name = obj.full_name ?? "Unknown";
                                const total = obj.total ?? 0;

                                summary = `Deleted purchase: <strong>${name}</strong> (₱${total})`;
                            } catch (e) {
                                summary = log.details; // fallback
                            }
                        }

                        else if (log.action === "Sent Campaign") {
                        const obj = JSON.parse(log.details);

                        summary = `
                            Sent Campaign: <strong>${obj.campaign_name}</strong> 
                            → ${obj.target_segment} (${obj.channel})
                        `;
                    }

                            // DELETE CAMPAIGN — readable summary
                        else if (log.action === "Delete Campaign") {
                            summary = `Deleted Campaign: <strong>${obj.campaign_name}</strong> → ${obj.target_segment} (${obj.channel})`;
                        }


                        else if (log.action === "Send Bulk Email") {
                            const obj = JSON.parse(log.details);

                            summary = `
                                Bulk Email: <strong>${obj.subject}</strong>
                                → Sent to ${obj.sent_count} customer(s)
                            `;
                        }

                        


                    } catch (e) {}

                    // Safely escape JSON string inside backticks
                    const safeDetails = btoa(unescape(encodeURIComponent(log.details)));


                    $("#logsTable").append(`
                        <tr>
                            <td>${log.created_at}</td>
                            <td>${log.action}</td>
                            <td>${summary}</td>
                            <td>${log.ip_address}</td>
                            <td>
                                <button class="btn btn-sm btn-primary"
                                    onclick="openLogDetails(atob(\`${safeDetails}\`))">
                                View
                            </button>


                                <button class="btn btn-sm btn-danger" 
                                        onclick="deleteLog(${log.systemlog_sid})">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `);
                });

                const start = (page - 1) * limit + 1;
                const end = start + result.logs.length - 1;
                $("#logCount").text(`Showing ${start} - ${end} of ${result.total} logs`);

                const totalPages = Math.ceil(result.total / limit);
                const pagination = $(".pagination");
                pagination.empty();

                // Previous
                pagination.append(`
                    <li class="page-item ${page <= 1 ? "disabled" : ""}">
                        <a class="page-link" data-page="${page - 1}" href="#">Previous</a>
                    </li>
                `);

                // Page numbers
                let maxVisible = 5;
                let startPage = Math.max(1, page - Math.floor(maxVisible / 2));
                let endPage = Math.min(totalPages, startPage + maxVisible - 1);

                for (let i = startPage; i <= endPage; i++) {
                    pagination.append(`
                        <li class="page-item ${i === page ? "active" : ""}">
                            <a class="page-link" data-page="${i}" href="#">${i}</a>
                        </li>
                    `);
                }

                // Next
                pagination.append(`
                    <li class="page-item ${page >= totalPages ? "disabled" : ""}">
                        <a class="page-link" data-page="${page + 1}" href="#">Next</a>
                    </li>
                `);
            },

            complete: function () {
                activeRequest = null;
            }
        });
    }

    // Initial
    loadLogs(currentPage);

    // Pagination click
    $(document).on("click", ".pagination a", function (e) {
        e.preventDefault();
        const page = parseInt($(this).data("page"));
        const search = $("#searchLogs").val().trim();
        if (!isNaN(page) && page > 0) {
            currentPage = page;
            loadLogs(currentPage, search);
        }
    });

    // Search (debounced)
    $("#searchLogs").on("input", function () {
        const keyword = $(this).val().trim();
        clearTimeout(searchTimer);

        searchTimer = setTimeout(() => {
            currentPage = 1;
            loadLogs(currentPage, keyword);
        }, 400);
    });

});

</script>


</body>
</html>
