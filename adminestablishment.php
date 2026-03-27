<?php
require_once __DIR__ . '/php/require_login.php';
require_role('Admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Establishment Management - Borongan City Customer Segmentation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admindashboard.css">
    <link rel="stylesheet" href="css/alert.css">
    <link rel="icon" type="image/png" href="fav.png" />
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-chart-pie me-2"></i>
                Borongan Customer Segmentation - Admin Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
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
                    <a class="nav-link" href="admindashboard.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a class="nav-link active" href="adminestablishment.php">
                        <i class="fas fa-store"></i> Establishments
                    </a>
                   
                    <a class="nav-link" href="adminsystemsettings.php">
                        <i class="fas fa-cogs"></i> System Settings
                    </a>
                  
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 col-md-9 p-4">
                <!-- Establishments Management Section -->
                <div id="adminEstablishments" class="dashboard-section active">
                    <h2 class="mb-4">Establishment Management</h2>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">All Establishments</h5>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEstablishmentModal">
                                    <i class="fas fa-plus me-1"></i> Add Establishment
                                </button>
                            </div>
                            
                            <div class="table-responsive">
                                <table id="allrecords" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th hidden>ID</th>
                                            <th scope="col">Business Name</th>
                                            <th scope="col">Business Type</th>
                                            <th scope="col">Owner</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Contact</th>
                                            <th scope="col">Date Created</th>
                                            <th scope="col">Address</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                        <!-- More establishment rows would go here -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <nav aria-label="Establishment pagination">
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
            </div>
        </div>
    </div>

    <!-- Add Establishment Modal -->
    <div class="modal fade" id="addEstablishmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Establishment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Business Name</label>
                                <input type="text" id="bname" class="form-control" placeholder="Enter business name" >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Business Type</label>
                                <input type="text" id="btype" class="form-control" placeholder="Enter business type" >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Owner Name</label>
                                <input type="text" class="form-control" placeholder="Enter owner's name" id="ownersname">
                            </div>

                            
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter email address" id="email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" placeholder="Enter contact number" id="contact">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" placeholder="Enter business address" id="address"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="datetime-local" class="form-control"  id="date_time">
                       
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" value="Enter Password" id="password">
                       
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" value="Enter Password" id="confirmpassword">
                       
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save">Create Establishment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->

    <!--view modal --> 
   <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Establishment Details</h5>
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


    <!-- edit motal -->

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Establishment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                             <input type="hidden" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label">Business Name</label>
                                <input type="text" id="edit_bname" class="form-control" placeholder="Enter business name" >
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Business Type</label>
                                <input type="text" class="form-control" placeholder="Enter owner's name" id="edit_btype">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Owner Name</label>
                                <input type="text" class="form-control" placeholder="Enter owner's name" id="edit_owner">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter email address" id="edit_email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" placeholder="Enter contact number" id="edit_contact">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" placeholder="Enter business address" id="edit_address"></textarea>
                            </div>
                        </div>
                    </div>
                    

                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="datetime-local" class="form-control" value="Enter Password" id="edit_date">
                       
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="text" class="form-control" value="Enter Password" id="edit_password">
                       
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="text" class="form-control" value="Enter Password" id="edit_confirmpassword">
                       
                    </div>
                </div>
                <div class="modal-footer">
     
                    <button type="button" class="btn btn-primary" id="edit_save">Save</button>
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
    <script src="js/admindashboard.js"></script>

    <script src="js/adminestablishment.js"> </script>

</body>
</html>