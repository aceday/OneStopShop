<?php
include __DIR__ . "/../../base.php";
$page_name = "Forgot Password";
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
                    <form class="p-4">
                        <div class="alert alert-info">
                            Wait
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                @
                            </div>
                            <div data-mdb-input-init class="form-floating">
                                <input type="text" id="auth_username" class="form-control" placeholder="Username" />
                                <label class="form-label" for="auth_username">Username</label>
                            </div>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button  type="button" class="btn btn-secondary "
                            style="padding-left: 2.5rem; padding-right: 2.5rem;">Send Password</button>
                            <p class="small fw-bold mt-2 pt-1 mb-0">
                                Try to login?
                                <a href="/public/register" class="link-danger">Login here</a>
                            </p>
                        </div>

                    </form>
                </div>
            </div>
        </section>
        <?php include_once __DIR__ . "/../footer.php";?>
        <?php after_js(); ?>
    </body>
</html>
