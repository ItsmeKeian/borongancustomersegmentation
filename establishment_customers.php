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
                    <a class="nav-link active" href="establishment_customers.php" data-section="customers">
                        <i class="fas fa-users"></i> Customers
                    </a>
                    <a class="nav-link" href="establishment_purchased.php" data-section="purchased">
                        <i class="fas fa-shopping-cart"></i> Purchased
                    </a>
                    
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
                    <h2 class="mb-4">Customer Management</h2>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">All Customers</h5>
                        <div>
                            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                                <i class="fas fa-plus me-1"></i> Add Customer
                            </button>

                            <!-- CSV Upload -->
                            <form id="importForm" enctype="multipart/form-data" style="display:inline;">
                                <input type="file" name="file" id="importFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" style="display:none;">

                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('importFile').click();">
                                    <i class="fas fa-upload me-1"></i> Import CSV
                                </button>
                            </form>
                        </div>
    </div>

    <!-- 🔍 Search Input -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search customers...">
    </div>

    <div class="table-responsive">
        <table id="allrecords" class="table table-hover">
            <thead>
                <tr>
                    <th>Full name</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Location</th>
                    <th>Email</th>
                    <th>Phone No.</th>
                    <th>Segment</th>
                    
                    <th>Occupation</th>
                    <th>Estimated Income</th>
                    <th>Education</th>

                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- rows will go here -->
            </tbody>
        </table>
    </div>

    <nav aria-label="Customer pagination">
        <div class="d-flex justify-content-between mt-2">
            <div id="recordCount" class="text-muted">COUNTED</div>
        </div>
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#">Previous</a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Next</a>
            </li>
        </ul>
    </nav>
</div>

                    </div>
                </div>
                
                <!-- Customers Section -->
                
                
             
             
                 <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div class="row">

        <!-- ✅ LEFT COLUMN -->
        <div class="col-md-6">

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" id="fname" class="form-control" placeholder="Enter full name">
            </div>

            <div class="mb-3">
                <label class="form-label">Age</label>
                <input type="text" id="age" class="form-control" placeholder="Enter age">
            </div>

            <div class="mb-3">
                <label class="form-label">Sex</label>
                <select class="form-select" id="gender">
                    <option value="">Select</option>
                    <option>Male</option>
                    <option>Female</option>
                    
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <select class="form-select" id="location">
                    <option value="">Select</option>
                    <option>Alang-alang</option>
                    <option>Amantacop</option>
                    <option>Ando</option>
                    <option>Balacdas</option>
                    <option>Balud</option>
                    <option>Banuyo</option>
                    <option>Baras</option>
                    <option>Bato</option>
                    <option>Bayobay</option>
                    <option>Benowangan</option>
                    <option>Bugas</option>
                    <option>Cabalagnan</option>
                    <option>Cabong</option>
                    <option>Cagbonga</option>
                    <option>Calico-an</option>
                    <option>Calingatnan</option>
                    <option>Camada</option>
                    <option>Campesao</option>
                    <option>Can-abong</option>
                    <option>Can-aga</option>
                    <option>Canjaway</option>
                    <option>Canlaray</option>
                    <option>Canyopay</option>
                    <option>Divinubo</option>
                    <option>Hebacong</option>
                    <option>Hindang</option>
                    <option>Lalawigan</option>
                    <option>Libuton</option>
                    <option>Locso-on</option>
                    <option>Maybacong</option>
                    <option>Maypangdan</option>
                    <option>Pepelitan</option>
                    <option>Pinanag-an</option>
                    <option>Punta Maria</option>
                    <option>Purok A (Pob.)</option>
                    <option>Purok B (Pob.)</option>
                    <option>Purok C (Pob.)</option>
                    <option>Purok D1 (Pob.)</option>
                    <option>Purok D2 (Pob.)</option>
                    <option>Purok E (Pob.)</option>
                    <option>Purok F (Pob.)</option>
                    <option>Purok G (Pob.)</option>
                    <option>Purok H (Pob.)</option>
                    <option>Sabang North</option>
                    <option>Sabang South</option>
                    <option>San Andres</option>
                    <option>San Gabriel</option>
                    <option>San Gregorio</option>
                    <option>San Jose</option>
                    <option>San Mateo</option>
                    <option>San Pablo</option>
                    <option>San Saturnino</option>
                    <option>Santa Fe</option>
                    <option>Siha</option>
                    <option>Sohutan</option>
                    <option>Songco</option>
                    <option>Suribao</option>
                    <option>Surok</option>
                    <option>Taboc</option>
                    <option>Tabunan</option>
                    <option>Tamoso</option>
                </select>
            </div>

              <div class="mb-3">
                <label class="form-label">Estimated Income</label>
                <input type="number" class="form-control" id="income" placeholder="Enter estimated income">
            </div>

             <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="datetime-local" class="form-control" id="date_created">
            </div>

        </div>

        <!-- ✅ RIGHT COLUMN -->
        <div class="col-md-6">

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email address">
            </div>

            <div class="mb-3">
                <label class="form-label">Phone No.</label>
                <input type="text" class="form-control" id="phone" placeholder="Enter contact number">
            </div>

            <div class="mb-3">
                <label class="form-label">Segment</label>
                <select class="form-select" id="segment">
                    <option value="">Select</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Occupation</label>
                <input type="text" class="form-control" id="occupation" placeholder="Enter occupation">
            </div>

          

            <div class="mb-3">
                <label class="form-label">Education</label>
                <select class="form-select" id="education">
                   <option value="">Select</option>
                                    <option>None</option>
                                    <option>Elementary</option>
                                    <option>High School</option>
                                    <option>College</option>
                                    <option>Vocational</option>
                                    <option>Post Graduate</option>
                                    <option>High School Graduate</option>
                                    <option>College Graduate</option>
                </select>
            </div>

           

        </div>

    </div>
</div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save">Create Customer</button>
                </div>
            </div>
        </div>
    </div>



    <!-- view customer modal -->

     <!--view modal --> 
   <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Customer Details Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="viewDetails"><!-- Table will be injected here --></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


    <!-- Edit Modal -->
  <div class="modal fade" id="EditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" id="edit_fname" class="form-control" placeholder="Enter full name" >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Age</label>
                                <input type="text" id="edit_age" class="form-control" placeholder="Enter age" >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sex</label>
                                <select class="form-select" id="edit_gender">
                                    <option selected>Select type</option>

                                    <option>Male</option>
                                    <option>Female</option>
                                    
                                  
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <select class="form-select" id="edit_location">
                                    <option selected>Select</option>
                                    <option>Alang-alang</option>
                                    <option>Amantacop</option>
                                    <option>Ando</option>
                                    <option>Balacdas</option>
                                    <option>Balud</option>
                                    <option>Banuyo</option>
                                    <option>Baras</option>
                                    <option>Bato</option>
                                    <option>Bayobay</option>
                                    <option>Benowangan</option>
                                    <option>Bugas</option>
                                    <option>Cabalagnan</option>
                                    <option>Cabong</option>
                                    <option>Cagbonga</option>
                                    <option>Calico-an</option>
                                    <option>Calingatnan</option>
                                    <option>Camada</option>
                                    <option>Campesao</option>
                                    <option>Can-abong</option>
                                    <option>Can-aga</option>
                                    <option>Canjaway</option>
                                    <option>Canlaray</option>
                                    <option>Canyopay</option>
                                    <option>Divinubo</option>
                                    <option>Hebacong</option>
                                    <option>Hindang</option>
                                    <option>Lalawigan</option>
                                    <option>Libuton</option>
                                    <option>Locso-on</option>
                                    <option>Maybacong</option>
                                    <option>Maypangdan</option>
                                    <option>Pepelitan</option>
                                    <option>Pinanag-an</option>
                                    <option>Punta Maria</option>
                                    <option>Purok A (Pob.)</option>
                                    <option>Purok B (Pob.)</option>
                                    <option>Purok C (Pob.)</option>
                                    <option>Purok D1 (Pob.)</option>
                                    <option>Purok D2 (Pob.)</option>
                                    <option>Purok E (Pob.)</option>
                                    <option>Purok F (Pob.)</option>
                                    <option>Purok G (Pob.)</option>
                                    <option>Purok H (Pob.)</option>
                                    <option>Sabang North</option>
                                    <option>Sabang South</option>
                                    <option>San Andres</option>
                                    <option>San Gabriel</option>
                                    <option>San Gregorio</option>
                                    <option>San Jose</option>
                                    <option>San Mateo</option>
                                    <option>San Pablo</option>
                                    <option>San Saturnino</option>
                                    <option>Santa Fe</option>
                                    <option>Siha</option>
                                    <option>Sohutan</option>
                                    <option>Songco</option>
                                    <option>Suribao</option>
                                    <option>Surok</option>
                                    <option>Taboc</option>
                                    <option>Tabunan</option>
                                    <option>Tamoso</option>
                                  
                                </select>
                            </div>

                            <div class="mb-3">
                            <label class="form-label">Estimated Income</label>
                            <input type="number" class="form-control" id="edit_income">
                        </div>

                          <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="datetime-local" id="edit_date_created" name="created_at" placeholder="Enter date" class="form-control">
                            </div>
                            
                        </div>
                        <div class="col-md-6">



                       

                       


                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter email address" id="edit_email">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone No.</label>
                                <input type="text" class="form-control" placeholder="Enter contact number" id="edit_phone">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Segment</label>
                                <select class="form-select" id="edit_segment">
                                    <option value="Select">Select type</option>
                                </select>
                                  
                                </select>
                            </div>

                            
                                <div class="mb-3">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" class="form-control" id="edit_occupation">
                                </div>

                                <div class="mb-3">
                                <label class="form-label">Education</label>
                                <select class="form-select" id="edit_education">
                                    <option value="">Select</option>
                                    <option>None</option>
                                    <option>Elementary</option>
                                    <option>High School</option>
                                    <option>College</option>
                                    <option>Vocational</option>
                                    <option>Post Graduate</option>
                                    <option>High School Graduate</option>
                                    <option>College Graduate</option>
                                </select>
                            </div>

                           
                            
                        </div>
                    </div>
                    
                   
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="edit_save">Save</button>
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



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/establishment_customer.js"></script>
    <script src="js/messages_notification.js"></script>
    <script src="js/tutorial_video.js"></script>
    
    
</body>
</html>