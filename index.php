
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borongan City Customer Segmentation - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" type="image/png" href="fav.png" />
</head>
<body>
    <!-- Login Page -->
    <div class="login-container">
        <div class="login-card card">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    
                    <i class="fas fa-chart-pie" style="font-size: 50px; color: lightblue;"></i>
                    <h3>Borongan Customer Segmentation</h3>
                    <p class="text-muted">Sign in to your account</p>
                </div>
                
                <div class="role-selector">
                    <button type="button" class="role-btn active" id="establishmentBtn">Establishment</button>
                    <button type="button" class="role-btn" id="adminBtn">System Admin <span class="admin-badge">Team</span></button>
                </div>
                
                <!-- Establishment Login Form -->
                <form id="establishmentLogin" class="login-form">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Enter your email">
        </div>
        <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                    >
                    <span class="input-group-text toggle-password"
                        data-target="#password"
                        style="cursor:pointer;">
                        <i class="fa fa-eye"></i>
                    </span>
                </div>
            </div>

        
        <button type="button" class="btn btn-primary w-100 mb-3" onclick="login('establishment')">Sign in</button>
        <div class="text-center small mt-2">
                <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">
                    Forgot Password?
                </a>
                <br>
                <a href="#" class="text-muted"
                data-bs-toggle="modal"
                data-bs-target="#contactAdminModal">
                    Need help? Contact <strong style='color: #0d6efd; font-weight:450;'>system</strong> administrator
                </a>
            </div>

    </form>


                
                <!-- Admin Login Form -->
                         <form id="adminLogin" class="login-form" style="display: none;">
    <div class="mb-3">
        <label class="form-label">Admin Username</label>
        <input type="text" id="admin_username" name="username" class="form-control" placeholder="Enter admin username">
    </div>
    <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input
                    type="password"
                    id="admin_password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                >
                <span class="input-group-text toggle-password"
                    data-target="#admin_password"
                    style="cursor:pointer;">
                    <i class="fa fa-eye"></i>
                </span>
            </div>
        </div>

    
    <button type="button" class="btn btn-primary w-100 mb-3" onclick="login('admin')">Sign in as Admin</button>
    <div class="text-center">
                      
                    </div>
</form>


            </div>
        </div>
    </div>


        <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="forgotPasswordLabel">Forgot Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="forgotPasswordForm">
          <div class="mb-3">
            <label for="resetEmail" class="form-label">Enter your email address</label>
            <input type="email" class="form-control" id="resetEmail" name="resetEmail" placeholder="you@example.com" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>
        <div id="forgotPasswordMessage" class="mt-3 text-center"></div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="contactAdminModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Contact System Administrator</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="contactAdminForm">

          <div class="mb-3">
            <label class="form-label">Your Email</label>
            <input type="email" class="form-control" name="email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Phone Number (optional)</label>
            <input type="text" class="form-control" name="phone">
          </div>

          <div class="mb-3">
            <label class="form-label">Subject</label>
            <input
                type="text"
                class="form-control"
                name="subject"
                placeholder="Enter subject"
                maxlength="100"
                required
                >

          </div>

          <div class="mb-3">
            <label class="form-label">Message</label>
            <textarea class="form-control" name="message" rows="4" required></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100" id="contactSubmitBtn">
            <span class="btn-text">Send Message</span>
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
        </button>


        </form>

        <div id="contactAdminMsg" class="mt-3 text-center"></div>
      </div>

    </div>
  </div>
</div>




    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/index.js"></script>
    
    <script>
$(document).ready(function () {

    /* =====================================
     * FORGOT PASSWORD
     * ===================================== */
    $("#forgotPasswordForm").on("submit", function (e) {
        e.preventDefault();

        const email = $("#resetEmail").val().trim();
        const $msg  = $("#forgotPasswordMessage");

        if (!email) {
            $msg.html('<div class="alert alert-danger">Email is required.</div>');
            return;
        }

        $.ajax({
            url: "php/send_reset_link.php",
            type: "POST",
            data: { email },
            dataType: "json",

            success(res) {
                const cls = res.status === 1 ? "success" : "danger";
                $msg.html(`<div class="alert alert-${cls}">${res.message}</div>`);

                if (res.status === 1) {
                    $("#forgotPasswordForm")[0].reset();
                }
            },

            error() {
                $msg.html(
                    '<div class="alert alert-danger">An error occurred. Please try again later.</div>'
                );
            }
        });
    });


    /* =====================================
     * CONTACT ADMIN
     * ===================================== */
    $("#contactAdminForm").on("submit", function (e) {
        e.preventDefault();

        const $form    = $(this);
        const $btn     = $("#contactSubmitBtn");
        const $btnText = $btn.find(".btn-text");
        const $spinner = $btn.find(".spinner-border");
        const $msg     = $("#contactAdminMsg");

        const modalEl  = document.getElementById("contactAdminModal");
        const modal    = bootstrap.Modal.getInstance(modalEl);

        if ($btn.prop("disabled")) return;

        // Trim subject
        const $subject = $form.find('input[name="subject"]');
        $subject.val($subject.val().trim());

        // Lock UI
        $btn.prop("disabled", true);
        $btnText.text("Sending...");
        $spinner.removeClass("d-none");
        $msg.html("");

        $.ajax({
            url: "php/contact_admin.php",
            type: "POST",
            data: $form.serialize(),
            dataType: "json",

            success(res) {
                if (res.status === 1) {

                    $msg.html(
                        `<div class="alert alert-success">${res.message}</div>`
                    );

                    $form[0].reset();

                    // Auto-close after 5s
                    setTimeout(() => {
                        modal.hide();
                        resetContactButton();
                        $msg.html("");
                    }, 5000);

                } else {
                    $msg.html(
                        `<div class="alert alert-danger">${res.message}</div>`
                    );
                    resetContactButton();
                }
            },

            error() {
                $msg.html(
                    '<div class="alert alert-danger">Unable to send message.</div>'
                );
                resetContactButton();
            }
        });

        function resetContactButton() {
            $btn.prop("disabled", false);
            $btnText.text("Send Message");
            $spinner.addClass("d-none");
        }
    });

});


$(document).on("click", ".toggle-password", function () {

    const targetInput = $($(this).data("target"));
    const icon = $(this).find("i");

    if (targetInput.attr("type") === "password") {
        targetInput.attr("type", "text");
        icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
        targetInput.attr("type", "password");
        icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
});



/* =====================================
 * LOGIN
 * ===================================== */
function login(role) {

    let username = "";
    let password = "";

    if (role === "establishment") {
        username = $("#username").val().trim();
        password = $("#password").val();
    } else {
        username = $("#admin_username").val().trim();
        password = $("#admin_password").val();
    }

    if (!username || !password) {
        Swal.fire({
            icon: "warning",
            title: "Missing Fields",
            text: "Please fill in all fields.",
            toast: true,
            position: "top-center",
            showConfirmButton: false,
            timer: 2000
        });
        return;
    }

    $.ajax({
        url: "php/checkuser.php",
        type: "POST",
        data: {
            un: username,
            pw: password,
            loginType: role
        },
        dataType: "json",

        success(res) {
            if (res.status === 1) {

                Swal.fire({
                    toast: true,
                    position: "center",
                    icon: "success",
                    title: "Login successful!",
                    showConfirmButton: false,
                    timer: 1000
                }).then(() => {

                    if (res.role === "Admin") {
                        window.location = "admindashboard.php";
                    } else if (res.role === "Establishment") {
                        window.location = "establishment_dashboard.php";
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Unknown Role",
                            text: "Please contact support."
                        });
                    }
                });

            } else {
                Swal.fire({
                    toast: true,
                    position: "center",
                    icon: "error",
                    title: res.msg || "Login failed.",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        },

        error(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                toast: true,
                position: "center",
                icon: "error",
                title: "An error occurred.",
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
}
</script>

</body>
</html>