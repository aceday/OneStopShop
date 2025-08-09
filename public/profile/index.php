<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!isset($_COOKIE["auth_token"])) {
    header("Location: /public/login");
    exit;
}


try {
    $this_user = JWT::decode($_COOKIE["auth_token"], new Key($jwt_secret_key, 'HS256'));
} catch (Exception $e) {
    header("Location: /public/login");
    exit;
}

$username = $this_user->username;
$user_id = $this_user->user_id;
$role_type = $this_user->role_type;
$page_name = $username;
$user_image = $this_user->user_image;
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
        <!-- Header-->
        <header class="d-block d-md-none bg-dark py-3 px-4" style="max-height:50px">
                <span class="text-white">
                    <?php echo $page_name?>
                </span>
        </header>
        <header class="d-none d-md-block bg-dark py-5 px-4">
            <div class="d-flex align-items-center" style="height: 100%;">
                <span class="text-white fw-bold fs-3">
                    <?php echo $page_name ?>
                </span>
            </div>
        </header>

        <section class="py-2">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="d-flex flex-col flex-md-row">
                    <div class="col-12 card">
                        <div class="card-header">
                            <span id="this_user_username">My Profile</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 div col-md-6">
                                    <div class="w-100">
                                    <div style="display: flex; justify-content: center; align-items: center; width: 100%;">
                                        <img 
                                            src="" 
                                            alt="<?php echo $username ?>" 
                                            id="this_user_image"
                                            style="
                                                width: 300px;
                                                height: 300px;
                                                object-fit: cover;
                                                border-radius: 50%;
                                                display: block;
                                                border: 5px solid #ccc;
                                            "
                                        >
                                    </div>


                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <span class="fw-bold fs-1 "><?php echo $username?></span>
                                        </div>
                                        <div class="col-12">
                                            <span class="fw-bold">User ID: </span>
                                            <span id="this_user_id"><?php echo $user_id?></span>
                                        </div>
                                        <div class="col-12">
                                            <span class="fw-bold">Role: </span>
                                            <span id="this_user_role"><?php echo $role_type?></span>
                                        </div>
                                    </div>
                                    <form class="card mt-5 needs-validation" action="POST" id="frmUpdateProfile" novalidate>
                                        <input type="hidden" name="action" value="user_update">
                                        <div class="card-header">
                                            Account Settings
                                        </div>
                                        <div class="card-body">
                                            <!-- <div class="alert alert-info">
                                                <i class="bi bi-info-circle-fill"></i>
                                                After some changes, you need to login again
                                            </div> -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="alert alert-info d-none" id="updateProfileAlert">
                                                        <span id="updateProfileAlertMsg"></span>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label for="update_profile_image">Profile Image</label>
                                                    <input type="file" class="form-control" id="update_profile_image" name="update_profile_image" accept="image/*" required>
                                                    <div class="invalid-feedback">
                                                        Please select a profile image.
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label for="update_username" class="form-label">Username</label>
                                                    <input type="text" class="form-control" id="update_username" name="update_username" aria-describedby="inputGroupPrepend" value="<?php echo $username?>" required>
                                                    <div class="invalid-feedback">
                                                        Please input a username.
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <label for="update_email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="update_email" name="update_email" value="" aria-describedby="inputGroupPrepend" required>
                                                    <div class="invalid-feedback">
                                                        Please input a valid email.
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <label for="update_password1" class="form-label">Password</label>
                                                    <input type="password" class="form-control" id="update_password" name="update_password" aria-describedby="inputGroupPrepend" required>
                                                    <div class="invalid-feedback">
                                                        Please input a password.
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-2">   
                                                    <label for="update_password2" class="form-label">Confirm Password</label>
                                                    <input type="password" class="form-control" id="update_password2" name="update_password2" aria-describedby="inputGroupPrepend" required>
                                                    <div class="invalid-feedback">
                                                        Please confirm your password.
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <button type="submit" class="btn btn-secondary"><i class="bi bi-pen"></i>  Update Profile</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php after_js()?>
        <?php include_once __DIR__ . "/../footer.php";?>
    </body>

    <script>
        let this_user_image = document.getElementById("this_user_image");
        function fetchUser() {
            let param = new URLSearchParams({
                user: true,
                idUser: this_user_id
            });
            $.ajax({
                url: '/api/v1.php?' + param,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    this_user_image.src = response.users[0].user_image;
                }
            })
        }

        fetchUser();
        (() => {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
                }, false)
            })
        })()
        let frmUpdateProfile = document.getElementById("frmUpdateProfile");
        frmUpdateProfile.addEventListener("submit", function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let updateProfileAlert = document.getElementById('updateProfileAlert');
            let updateProfileAlertMsg = document.getElementById('updateProfileAlertMsg');
            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(response) {
                    
                },
                success: function(response) {
                    console.log(response);
                    updateProfileAlertMsg.textContent = response.message;
                    updateProfileAlert.classList.remove("alert-danger", "alert-info");
                    updateProfileAlert.classList.add("alert-success");
                    updateProfileAlert.classList.remove("d-none");
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);

                }
            });
        });
    </script>
</html>
