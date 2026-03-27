<?php 
require_once __DIR__ . '/php/require_login.php';
require_role('Establishment');
require_once __DIR__ . '/php/dbconnect.php';

date_default_timezone_set('Asia/Manila');

$establishment = $_SESSION['business_name'] ?? null;
if (!$establishment) {
    die("No establishment in session");
}

// Get all purchases for this establishment
$stmt = $conn->prepare("
    SELECT 
        p.customer_sid,
        p.full_name,
        pi.item_name AS item_purchase,
        p.date_purchase
    FROM purchased p
    INNER JOIN purchase_items pi 
        ON pi.purchase_id = p.purchased_sid
    WHERE p.establishment = ?
    ORDER BY p.date_purchase DESC
");
$stmt->bind_param("s", $establishment);
$stmt->execute();
$result = $stmt->get_result();
$stmt->bind_param("s", $establishment);
$stmt->execute();
$result = $stmt->get_result();

$customers = [];
$now = new DateTime('now', new DateTimeZone('Asia/Manila'));

while ($row = $result->fetch_assoc()) {

    $cid   = $row['customer_sid'];
    $name  = $row['full_name'];
    $drink = $row['item_purchase'];
    $dtime = $row['date_purchase'];

    if (!isset($customers[$cid])) {
        $customers[$cid] = [
            'full_name'             => $name,
            'last_purchase_overall' => $dtime,
            'drinks'                => []
        ];
    }

    // update overall last purchase
    if (strtotime($dtime) > strtotime($customers[$cid]['last_purchase_overall'])) {
        $customers[$cid]['last_purchase_overall'] = $dtime;
    }

    // track drinks
    if (!isset($customers[$cid]['drinks'][$drink])) {
        $customers[$cid]['drinks'][$drink] = [
            'count'         => 0,
            'last_purchase' => $dtime
        ];
    }

    $customers[$cid]['drinks'][$drink]['count']++;

    if (strtotime($dtime) > strtotime($customers[$cid]['drinks'][$drink]['last_purchase'])) {
        $customers[$cid]['drinks'][$drink]['last_purchase'] = $dtime;
    }
}

$stmt->close();

// ---------------------------------------------------------
// HIGH-RISK ALGORITHM
// ---------------------------------------------------------
$highRiskCustomers = [];

foreach ($customers as $cid => $cdata) {


        

    $fullName = $cdata['full_name'];

    // days since last purchase
    $overallLast = new DateTime($cdata['last_purchase_overall']);
    $daysSinceLast = (int)$overallLast->diff($now)->days;

    // -----------------------------------------------------
    // DETERMINE FAVORITE DRINK
    // -----------------------------------------------------
    $favoriteDrink = null;
    $favoriteCount = -1;
    $favoriteLast  = null;

    foreach ($cdata['drinks'] as $drinkName => $info) {

        $itemCount = $info['count'];
        $itemLast  = strtotime($info['last_purchase']);

        if (
            $itemCount > $favoriteCount ||
            ($itemCount == $favoriteCount &&
             $itemLast > strtotime($favoriteLast ?? '1970-01-01'))
        ) {
            $favoriteDrink = $drinkName;
            $favoriteCount = $itemCount;
            $favoriteLast  = $info['last_purchase'];
        }
    }

    // days since favorite item was ordered
    $favDays = $favoriteLast ? (new DateTime($favoriteLast))->diff($now)->days : null;

    // -----------------------------------------------------
    // SCORING SYSTEM (FINAL FIXED VERSION)
    // -----------------------------------------------------
    $riskReasons = [];
    $riskScore   = 0;
    $riskLevel   = "Low";

    // Rule 1 — inactivity
    if ($daysSinceLast >= 30) {
        $riskScore += 60;
        $riskLevel = "Severe";
        $riskReasons[] = "Inactive for 30+ days";
    }
    elseif ($daysSinceLast >= 14) {
        $riskScore += 40;
        $riskLevel = "High";
        $riskReasons[] = "Inactive for 14+ days";
    }

    // Rule 2 — Favorite drink not ordered for 10 days
    // FIXED: no more comparing favDays < daysSinceLast
    if ($favDays !== null && $favDays >= 10) {
        $riskScore += 30;
        $riskReasons[] = "Favorite not ordered for 10+ days";

        if ($riskLevel === "Low") {
            $riskLevel = "Product Risk";
        }
    }

// ✅ AUTO-GENERATED MARKETING MESSAGE SYSTEM (WITH 3 RANDOM VARIATIONS)
$fname = explode(" ", $fullName)[0]; // First name
$favItem = $favoriteDrink ?: "our products";

$severeMessages = [
    "Hi $fname! We miss you at $establishment 😢 It's been a while since your last visit. Enjoy a special DISCOUNT on your favorite item ($favItem) today only! 💖",
    "Hello $fname! Big promo just for you! 🎉 Come back today and enjoy an exclusive DISCOUNT on your favorite $favItem at $establishment!",
    "Hey $fname! We haven’t seen you in a while 😔 Get a surprise DISCOUNT on your favorite $favItem when you visit $establishment today!"
];

$highMessages = [
    "Hi $fname! We noticed you haven't visited us recently. Enjoy a LIMITED-TIME deal on your favorite $favItem today at $establishment ☕✨",
    "Hello $fname! Your favorite $favItem is waiting for you 😍 Drop by $establishment today and enjoy a special reactivation offer!",
    "Hey $fname! Come back today and enjoy a special treat on your favorite $favItem only at $establishment 🎁"
];

$productRiskMessages = [
    "Hi $fname! We haven’t seen you order your favorite $favItem lately 😮 Visit $establishment today and enjoy a surprise just for you!",
    "Hello $fname! Your favorite $favItem is still our bestseller! 😍 Come back to $establishment today and enjoy it again!",
    "Hey $fname! Don’t forget your favorite $favItem 😁 Visit $establishment today—we saved something special for you!"
];

$lowMessages = [
    "Hi $fname! Thank you for being one of our valued customers at $establishment. See you again soon! 😊",
    "Hello $fname! We truly appreciate your support at $establishment. Hope to see you again today! ☕",
    "Hey $fname! Thanks for choosing $establishment. Your favorite $favItem will always be waiting for you ❤️"
];

// ✅ RANDOM PICK BASED ON RISK LEVEL
if ($riskLevel === "Severe") {
    $suggestedIdea = $severeMessages[array_rand($severeMessages)];
} 
elseif ($riskLevel === "High") {
    $suggestedIdea = $highMessages[array_rand($highMessages)];
} 
elseif ($riskLevel === "Product Risk") {
    $suggestedIdea = $productRiskMessages[array_rand($productRiskMessages)];
} 
else {
    $suggestedIdea = $lowMessages[array_rand($lowMessages)];
}




    // Only add if truly high-risk
    if (!empty($riskReasons)) {

        if ($riskScore > 100) $riskScore = 100;

        $highRiskCustomers[] = [
            'customer_sid'    => $cid,
            'full_name'       => $fullName,
            'last_purchase'   => $cdata['last_purchase_overall'],
            'days_since_last' => $daysSinceLast,
            'favorite_drink'  => $favoriteDrink,
            'favorite_last'   => $favoriteLast,
            'favorite_days'   => $favDays,
            'risk_level'      => $riskLevel,
            'risk_score'      => $riskScore,
            'risk_reasons'    => implode("; ", $riskReasons),
            'suggested_idea'  => $suggestedIdea // ✅ NEW COLUMN
        ];
    }


 

}

// Summary cards
$totalHighRisk = count($highRiskCustomers);
$inactive14    = 0;
$severe30      = 0;
$productRisk   = 0;

foreach ($highRiskCustomers as $c) {
    if ($c['days_since_last'] >= 14) $inactive14++;
    if ($c['days_since_last'] >= 30) $severe30++;
    if ($c['favorite_days'] !== null && $c['favorite_days'] >= 10) $productRisk++;
}

function formatDateTimeNice($dt) {
    if (!$dt) return '-';
    return date('M d, Y h:i A', strtotime($dt));
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borongan City Customer Segmentation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/establishment.css">
    <link rel="stylesheet" href="css/messages_notification.css">
    <link rel="icon" type="image/png" href="fav.png" />
</head>
<body>



<style>

    /* ✅ FORCE 1-LINE ONLY FOR THESE COLUMNS */
.table th,
.table td {
    white-space: nowrap;
    vertical-align: middle;
}

/* ✅ ALLOW WRAPPING ONLY FOR SUGGESTED IDEA COLUMN */
.table td.suggested-col,
.table th.suggested-col {
    white-space: normal !important;
    max-width: 420px;
    line-height: 1.4;
}

/* ✅ Fix badge alignment */
.table .badge {
    white-space: nowrap;
}

</style>
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

    <!-- Modal for View All Notifications -->
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
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 p-0 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link" href="establishment_dashboard.php" data-section="dashboard">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a class="nav-link" href="establishment_customers.php" data-section="customers">
                        <i class="fas fa-users"></i> Customers
                    </a>
                    <a class="nav-link" href="establishment_purchased.php" data-section="purchased">
                        <i class="fas fa-shopping-cart"></i> Purchased
                    </a>
                    
                     <a class="nav-link active" href="establishment_high_risk.php">
                        <i class="fas fa-user-slash me-2"></i>High-Risk Customers
                    </a>

                    <a class="nav-link" href="establishment_product_analytics.php">
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

                     <a class="nav-link  " href="establishment_reminders.php"><i class="fas fa-calendar-alt"></i> Reminders</a>

                     <a class="nav-link " href="establishment_logs.php"><i class="fas fa-database"></i> System Logs</a>
                     
                    <a class="nav-link" href="establishment_settings.php" data-section="settings">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 col-md-9 p-4 main-content">
                <h2 class="mb-4">High-Risk Customers</h2>

                <!-- High-Risk Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">High-Risk Customers List</h5>
                        <div>
                            <button class="btn btn-primary btn-sm me-2" id="openBulkEmail">
                                <i class="fas fa-envelope"></i> Send Email (Bulk)
                            </button>

                            <button class="btn btn-success btn-sm" id="openBulkSMS">
                                <i class="fas fa-sms"></i> Send SMS (Bulk)
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($highRiskCustomers)): ?>
                            <p class="text-muted mb-0">No high-risk customers at the moment.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>
                                                        <input type="checkbox" id="selectAllCustomers">
                                                    </th>

                                            <th>Customer</th>
                                            <th>Last Purchase</th>
                                            <th>Days Inactive</th>
                                            <th>Favorite Item</th>
                                            <th>Fav. Last Ordered</th>
                                            <th>Risk Level</th>
                                            <th>Risk Score</th>
                                            <th class="suggested-col">Reasons</th>
                                            <th class="suggested-col">Suggested Marketing Idea</th>


                                        </tr>
                                    </thead>

                                    <!-- ADD ID HERE FOR JS PAGINATION -->
                                    <tbody id="highRiskTableBody">
                                        <?php foreach ($highRiskCustomers as $c): ?>
                                            <tr>
                                                 <td>
                                                   <input 
                                                        type="checkbox" 
                                                        class="selectCustomer"
                                                        value="<?= $c['customer_sid'] ?>"
                                                        data-message="<?= htmlspecialchars($c['suggested_idea'], ENT_QUOTES) ?>"
                                                        >

                                                </td>
                                                <td><?= htmlspecialchars($c['full_name']) ?></td>
                                                <td><?= formatDateTimeNice($c['last_purchase']) ?></td>
                                                <td><?= $c['days_since_last'] ?></td>
                                                <td><?= htmlspecialchars($c['favorite_drink'] ?? '-') ?></td>
                                                <td><?= formatDateTimeNice($c['favorite_last']) ?></td>
                                                <td>
                                                    <?php
                                                        $badgeClass = 'bg-secondary';
                                                        if ($c['risk_level'] === 'Severe') $badgeClass = 'bg-danger';
                                                        elseif ($c['risk_level'] === 'High') $badgeClass = 'bg-warning text-dark';
                                                        elseif ($c['risk_level'] === 'Product Risk') $badgeClass = 'bg-info text-dark';
                                                    ?>
                                                    <span class="badge <?= $badgeClass ?>">
                                                        <?= htmlspecialchars($c['risk_level']) ?>
                                                    </span>
                                                </td>
                                                <td><?= $c['risk_score'] ?></td>
                                                <td class="suggested-col" style="font-size: 14px;"><?= htmlspecialchars($c['risk_reasons']) ?></td>
                                                <td class="suggested-col" style="font-size: 13px;">
                                                            <?= htmlspecialchars($c['suggested_idea']) ?>
                                                        </td>


                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- RECORD COUNT + PAGINATION (JS ONLY) -->
                            <div class="d-flex justify-content-between mt-2">
                                <div id="riskRecordCount" class="text-muted"></div>
                            </div>

                            <nav aria-label="High Risk pagination">
                                <ul class="pagination justify-content-center" id="riskPagination"></ul>
                            </nav>

                        <?php endif; ?>
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




            </div><!-- /main-content -->
        </div><!-- /row -->
    </div><!-- /container-fluid -->

    <!-- Bulk Email Modal -->
    <div class="modal fade" id="bulkEmailModal" tabindex="-1" aria-labelledby="bulkEmailLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="bulkEmailLabel">Send Bulk Email</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <p class="text-muted mb-2">
              Email will be sent to <strong>selected customers only</strong>.

            </p>

            <label class="form-label">Email Subject</label>
            <input type="text" id="bulk_email_subject" class="form-control mb-3" placeholder="Enter subject">

            <label class="form-label">Message</label>
            <textarea id="bulk_email_message" class="form-control" rows="6"
                placeholder="Write your email message here..."></textarea>

          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-primary" id="sendBulkEmail">
                <i class="fas fa-paper-plane"></i> Send Email
            </button>
          </div>

        </div>
      </div>
    </div>

    <!-- Bulk SMS Modal -->
    <div class="modal fade" id="bulkSMSModal" tabindex="-1" aria-labelledby="bulkSMSLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="bulkSMSLabel">Send Bulk SMS</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">

            <p class="text-muted mb-2">
              SMS will be sent to <strong>selected customers only</strong>.

            </p>

            <textarea id="bulk_sms_message" class="form-control" rows="5"
              placeholder="Type your SMS message here..."></textarea>

          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success" id="sendBulkSMS">
                <i class="fas fa-sms"></i> Send SMS
            </button>
          </div>

        </div>
      </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/messages_notification.js"></script>
    <script src="js/tutorial_video.js"></script>
    
    <script>
$(function () {

    /* ============================================================
       1. AUTO-FILL MESSAGE WHEN CUSTOMER IS CHECKED
       ============================================================ */
    $(document).on("change", ".selectCustomer", function () {

        let autoMessage = $(this).data("message");

        // If checked → auto-fill message (only if empty)
        if ($(this).is(":checked")) {
            if ($("#bulk_email_message").val().trim() === "") {
                $("#bulk_email_message").val(autoMessage);
            }
        } 
        // If unchecked → clear textarea if no selections left
        else {
            if ($(".selectCustomer:checked").length === 0) {
                $("#bulk_email_message").val("");
            }
        }
    });


    /* ============================================================
       2. SELECT ALL CHECKBOX
       ============================================================ */
    $("#selectAllCustomers").on("change", function () {
        $(".selectCustomer").prop("checked", $(this).prop("checked")).trigger("change");
    });


    /* ============================================================
       3. SEND BULK EMAIL
       ============================================================ */
    $("#sendBulkEmail").on("click", function () {

        let subject = $("#bulk_email_subject").val().trim();
        let msg      = $("#bulk_email_message").val().trim();

        const selected = $(".selectCustomer:checked").map(function () {
            return {
                id: $(this).val(),
                message: $(this).data("message")
            };
        }).get();

        // No customers selected
        if (selected.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "No customer selected",
                text: "Please select at least one customer."
            });
            return;
        }

        // Auto-fill message from first customer if blank
        if (msg === "") {
            $("#bulk_email_message").val(selected[0].message);
            msg = selected[0].message;
        }

        // Missing subject
        if (subject === "") {
            Swal.fire({
                icon: "warning",
                title: "Missing subject",
                text: "Please enter an email subject."
            });
            return;
        }

        // AJAX SEND
        $.ajax({
            type: "POST",
            url: "php/create/send_bulk_highrisk_email.php",
            dataType: "json",
            data: {
                subject:   subject,
                message:   msg,
                customers: selected.map(item => item.id)
            },
            success: function (result) {

                if (result.status == 1) {

                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: "Email sent successfully to " + result.sent + " customer(s).",
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $("#bulkEmailModal").modal("hide");
                        $("#bulk_email_subject").val("");
                        $("#bulk_email_message").val("");
                        $(".selectCustomer").prop("checked", false);
                        $("#selectAllCustomers").prop("checked", false);
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Failed",
                        text: result.message
                    });
                }
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: "Something went wrong. Check console for details."
                });
            }
        });

    });


    /* ============================================================
       4. SEND BULK SMS
       ============================================================ */
    $("#sendBulkSMS").on("click", function () {

        const selected = $(".selectCustomer:checked").map(function () {
            return $(this).val();
        }).get();

        if (selected.length === 0) {
            alert("Please select at least one customer.");
            return;
        }

        console.log("Selected for SMS:", selected);

        // When you finish send_bulk_highrisk_sms.php,  
        // we will wire it exactly like the email sender.
    });


    /* ============================================================
       5. OPEN MODALS
       ============================================================ */
    $("#openBulkEmail").on("click", function () {
        $("#bulkEmailModal").modal("show");
    });

    $("#openBulkSMS").on("click", function () {
        $("#bulkSMSModal").modal("show");
    });


    /* ============================================================
       6. CLIENT-SIDE PAGINATION (10 rows per page)
       ============================================================ */

    const rows = $("#highRiskTableBody tr");
    const totalRows = rows.length;
    const perPage = 10;
    const $recordCount = $("#riskRecordCount");
    const $pagination  = $("#riskPagination");

    if (totalRows > 0) {

        const totalPages = Math.ceil(totalRows / perPage);
        let currentPage = 1;

        function renderPage(page) {

            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;
            currentPage = page;

            rows.hide();

            const start = (page - 1) * perPage;
            const end   = start + perPage;

            rows.slice(start, end).show();

            const startEntry = start + 1;
            const endEntry   = Math.min(end, totalRows);

            $recordCount.text(`Showing ${startEntry} - ${endEntry} of ${totalRows} entries`);

            $pagination.empty();

            // Previous
            $pagination.append(`
                <li class="page-item ${page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${page - 1}">Previous</a>
                </li>
            `);

            // Page numbers
            let maxPages = 5;
            let startPage = Math.max(1, page - Math.floor(maxPages / 2));
            let endPage = Math.min(totalPages, startPage + maxPages - 1);

            if (endPage - startPage + 1 < maxPages) {
                startPage = Math.max(1, endPage - maxPages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                $pagination.append(`
                    <li class="page-item ${i === page ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
            }

            // Next
            $pagination.append(`
                <li class="page-item ${page === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${page + 1}">Next</a>
                </li>
            `);
        }

        // Pagination click event
        $pagination.on("click", "a.page-link", function (e) {
            e.preventDefault();
            const targetPage = parseInt($(this).data("page"));
            if (!isNaN(targetPage)) renderPage(targetPage);
        });

        renderPage(1);
    } else {
        $recordCount.text("No entries");
        $pagination.empty();
    }

});
</script>


</body>
</html>
