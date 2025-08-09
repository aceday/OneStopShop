<?php
include __DIR__ . "/../../base.php";
$page_name = "Login";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $page_name?> | <?php echo $project_name?></title>
        <?php head_css();head_js(); ?>

    </head>
    <body>
        <?php include_once __DIR__ . "/../navbar.php";?>
        <header class="bg-dark py-5" style="max-height:20px;">
            Login
        </header>

        <section class="vh-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <!-- <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                    class="img-fluid" alt="Sample image">
                </div> -->
                <div class="card col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <div class="w-100 text-center p-4">
                        <h2>
                            <?php echo $page_name?>
                        </h2>    
                    </div>
                    <form class="p-4" id="frmLogin">
                        <div class="alert alert-info d-none" id = "frmLogin_alert">
                            <input type="hidden" name="action" id="action" value="login" />
                            <span id="frmLogin_alert_message"></span>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                @
                            </div>
                            <div data-mdb-input-init class="form-floating">
                                <input type="text" id="auth_username" name="auth_username" class="form-control" placeholder="Username" />
                                <label class="form-label" for="auth_username">Username</label>
                            </div>
                        </div>

                        <div class="input-group mb-4">
                            <div class="input-group-text">
                                @
                            </div>
                            <div data-mdb-input-init class="form-floating">
                                <input type="password" id="auth_password" name="auth_password" class="form-control" placeholder="Password" />
                                <label class="form-label" for="auth_password">Password</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check mb-0">
                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                            <label class="form-check-label" for="form2Example3">
                                Remember me
                            </label>
                            </div>
                            <a href="/public/forgot_password" class="text-body">Forgot password?</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-secondary btn-lg"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Login</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">
                                Don't have an account?
                                <a href="/public/register" class="link-danger">Register</a>
                            </p>
                        </div>

                    </form>
                </div>
            </div>
        </section>
        <?php include_once __DIR__ . "/../footer.php";?>
        <?php after_js(); ?>
    </body>
    <script>
        let frmLogin = document.getElementById("frmLogin");
        let frmLogin_alert = document.getElementById("frmLogin_alert");
        let frmLogin_alert_message = document.getElementById("frmLogin_alert_message");

        frmLogin.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            // let action = formData.get("action");
            // let auth_username = formData.get("auth_username");
            // let auth_password = formData.get("auth_password");

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    frmLogin_alert.classList.remove("alert-success", "alert-danger");
                    frmLogin_alert.classList.add("alert-info");
                    frmLogin_alert.classList.remove("d-none");
                    frmLogin_alert_message.innerHTML = "Logging in...";
                },
                success: function(response) {
                    console.log(response);
                    frmLogin_alert.classList.remove("alert-info", "alert-danger");
                    frmLogin_alert.classList.add("alert-success");
                    frmLogin_alert.classList.remove("d-none");
                    frmLogin_alert_message.innerHTML = response.message;
                    setTimeout(function() {
                        window.location.href = "/public/dashboard";
                    }, 2000);
                },
                error: function(response) {
                    console.log(response);
                    frmLogin_alert.classList.remove("alert-info", "alert-success");
                    frmLogin_alert.classList.add("alert-danger");
                    frmLogin_alert.classList.remove("d-none");
                    frmLogin_alert_message.innerHTML = response.responseJSON.message;
                }
            })
            console.log(formData);
        });

    </script>
</html>
