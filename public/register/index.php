<?php
include __DIR__ . "/../../base.php";
$page_name = "Register";
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
                    <form class="p-4" id="frmRegister">
                        <div class="alert d-none alert-info" id="frmRegister_alert">
                            <span id="frmRegister_alert_message"></span>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                @
                            </div>
                            <div data-mdb-input-init class="form-floating">
                                <input type="text" id="auth_username" name="auth_username"class="form-control" placeholder="Username" />
                                <label class="form-label" for="auth_username">Username</label>
                            </div>
                        </div>

                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                @
                            </div>
                            <div data-mdb-input-init class="form-floating">
                                <input type="email" id="auth_email" name="auth_email" class="form-control" placeholder="Email" />
                                <label class="form-label" for="auth_email">Email</label>
                            </div>
                        </div>

                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                @
                            </div>
                            <div data-mdb-input-init class="form-floating">
                                <input type="password" id="auth_password" name="auth_password" class="form-control" placeholder="Password" />
                                <label class="form-label" for="auth_password">Password</label>
                            </div>
                        </div>

                        <div class="input-group mb-4">
                            <div class="input-group-text">
                                @
                            </div>
                            <div data-mdb-input-init class="form-floating">
                                <input type="password" id="auth_confirm_password" name="auth_confirm_password" class="form-control" placeholder="Confirm Password" />
                                <label class="form-label" for="auth_confirm_password">Confirm Password</label>
                            </div>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button  type="submit" class="btn btn-success"
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Register</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">
                                Already have an account?
                                <a href="/public/login"class="link-danger">Login</a>
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
        let frmRegister = document.getElementById("frmRegister");
        let frmRegister_alert = document.getElementById("frmRegister_alert");
        let frmRegister_alert_message = document.getElementById("frmRegister_alert_message");
        frmRegister.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            let auth_username = formData.get("auth_username");
            let auth_email = formData.get("auth_email");
            let auth_password = formData.get("auth_password");
            let auth_confirm_password = formData.get("auth_confirm_password");

            if (auth_password !== auth_confirm_password) {
                frmRegister_alert.classList.remove("alert-info", "alert-success");
                frmRegister_alert.classList.add("alert-danger");
                frmRegister_alert.classList.remove("d-none");
                frmRegister_alert_message.innerHTML = "Passwords do not match";
                return;
            }
    
            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: JSON.stringify({
                    action: "register",
                    username: auth_username,
                    email: auth_email,
                    password: auth_password
                }),
                success: function(response) {
                    console.log(response);
                    frmRegister_alert_message.innerHTML = response.message;
                    frmRegister_alert.classList.remove("alert-info", "alert-danger");
                    frmRegister_alert.classList.add("alert-success");
                    frmRegister_alert.classList.remove("d-none");
                    setTimeout(function() {
                        window.location.href = "/public/login";
                    }, 2000);
                },
                error: function(response) {
                    console.log(response);
                    frmRegister_alert_message.innerHTML = response.responseJSON.message;
                    frmRegister_alert.classList.remove("alert-info", "alert-success");
                    frmRegister_alert.classList.add("alert-danger");
                    frmRegister_alert.classList.remove("d-none");
                }
            })
            console.log(formData);
        });
    </script>
</html>
