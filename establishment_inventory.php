<?php
require_once __DIR__ . '/php/require_login.php';
require_role('Establishment');
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
    <link rel="stylesheet" href="css/alert.css">
     <link rel="stylesheet" href="css/messages_notification.css">
    <link rel="icon" type="image/png" href="fav.png" />
   
</head>
<body>


    <!-- Navigation Bar -->
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
            <!-- Sidebar -->
           <div class="col-lg-2 col-md-3 p-0 sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link" href="establishment_dashboard.php" data-section="dashboard">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a class="nav-link " href="establishment_customers.php" data-section="customers">
                        <i class="fas fa-users"></i> Customers
                    </a>
                    <a class="nav-link " href="establishment_purchased.php" data-section="purchased">
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

                   <a class="nav-link active" href="establishment_inventory.php" data-section="inventory">
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
            <div class="col-lg-10 col-md-9 p-4">
                <!-- Dashboard Section -->
                <div  class="dashboard-section active">
                    <h2 class="mb-4">Inventory Management</h2>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                           
                     <button class="btn btn-primary mb-3" id="addItemBtn">
                            ➕ Add Item
                        </button>


                        <input type="text" id="searchInventory" class="form-control mb-3" placeholder="🔍 Search item...">

                        <table class="table table-bordered" id="inventoryTable">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Yesterday Stocks</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th width="220">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                 

                        </div>
                    </div>
                </div>
                
                
             
          


<!-- INVENTORY MODAL -->
<div class="modal fade" id="inventoryModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="modalTitle">Add Inventory Item</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="inv_id">

        <label>Item Name</label>
        <input type="text" class="form-control mb-2" id="item_name">

        <label>Price</label>
        <input type="number" class="form-control mb-2" id="price">

        <label>Quantity</label>
        <input type="number" class="form-control mb-2" id="quantity">

        <label>Restock (+)</label>
        <input type="number" class="form-control mb-2" id="restock">
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="saveItem">Save</button>
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

    <style>
      .table-danger > td, 
        .table-danger > th {
            background-color: #B83C08 !important;
            color: #ffffff !important;
        }



    </style>

    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/establishment_inventory.js"></script>
     <script src="js/messages_notification.js"></script>
     <script src="js/tutorial_video.js"></script>
</body>
</html>