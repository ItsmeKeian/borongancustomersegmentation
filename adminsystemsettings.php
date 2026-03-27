
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Borongan City Customer Segmentation</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/admindashboard.css">
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
                    <a class="nav-link" href="adminestablishment.php">
                        <i class="fas fa-store"></i> Establishments
                    </a>
                    
                    <a class="nav-link active" href="adminsystemsettings.php">
                        <i class="fas fa-cogs"></i> System Settings
                    </a>
                   
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 col-md-9 p-4">
                <!-- System Settings Section -->
                <div id="adminSystem" class="dashboard-section active">
                    <h2 class="mb-4">System Settings</h2>
                    
                         <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">System Logs</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Timestamp</th>
                                            <th>Establishment</th>
                                            <th>Action</th>
                                            <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody id="logsTable">
                                        <!-- Logs will load here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Failed Login</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Establishment</th>
                                            <th>Attemps</th>
                                            <th>Last Attemp</th>
                                        </tr>
                                    </thead>
                                    <tbody id="login_attemp">
                                        <!-- Logs will load here -->
                                    </tbody>
                                </table>
                                <div id="login_pagination"></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">System Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">System Version</th>
                                            <td>v2.3.1</td>
                                        </tr>
                                        <tr>
                                            <th>Last Backup</th>
                                            <td>2023-10-15 23:45:12</td>
                                        </tr>
                                        <tr>
                                            <th>PHP Version</th>
                                            <td>8.1.10</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Database Size</th>
                                            <td>245.8 MB</td>
                                        </tr>
                                        <tr>
                                            <th>Server Uptime</th>
                                            <td>15 days, 4 hours</td>
                                        </tr>
                                        <tr>
                                            <th>System Status</th>
                                            <td><span class="badge bg-success">Operational</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
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
                                <input type="text" class="form-control" placeholder="Enter business name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Business Type</label>
                                <select class="form-select">
                                    <option selected>Select type</option>
                                    <option>Food & Beverage</option>
                                    <option>Retail</option>
                                    <option>Services</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Owner Name</label>
                                <input type="text" class="form-control" placeholder="Enter owner's name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter email address">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" placeholder="Enter contact number">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" placeholder="Enter business address"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Initial Password</label>
                        <input type="password" class="form-control" value="defaultPassword123">
                        <div class="form-text">This will be the temporary password for the establishment</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create Establishment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-toggle="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" placeholder="Enter full name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" placeholder="Enter email address">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select">
                            <option selected>Select role</option>
                            <option>Administrator</option>
                            <option>Business Owner</option>
                            <option>Manager</option>
                            <option>Staff</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Establishment</label>
                        <select class="form-select">
                            <option selected>Select establishment</option>
                            <option>Maria's Milk Tea Shop</option>
                            <option>Borongan Clothing Store</option>
                            <option>Seaside Restaurant</option>
                            <option>City Hardware</option>
                            <option>Baybay Coffee Shop</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" value="tempPassword123">
                        <div class="form-text">This will be the temporary password for the user</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Create User</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="js/adminsystemsetting.js"></script>



</body>
</html>