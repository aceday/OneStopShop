<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "About";


try {
    if (isset($_COOKIE["auth_token"])) {
        $this_user = JWT::decode($_COOKIE["auth_token"], new Key($jwt_secret_key, 'HS256'));
    }
} catch (Exception $e) {
    header("Location: /public/login");
    exit;
}

$username = $this_user->username ?? '';
$user_id = $this_user->user_id ?? '';
$role_type = $this_user->role_type ?? '';

if (isset($_GET['id'])) {
    // Pass
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?php echo $page_name?> | <?php echo $project_name?></title>
        <?php 
        head_css();
        head_js();
        ?>
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
                <div class="w-100">
                    <h3>About this Project <span>"<?php echo $project_name?>"</span></h3>

                    <!-- Go make it design it -->
                    <div class="row">
                        <div class="col-4">
                            <div class="card">
                                <div class="m-4">
                                    <img class="w-100 h-100"
                                        src="/public/assets/devs/1.png"
                                        alt="marc"
                                        style="border-radius: 50%; object-fit: cover; max-height: 300px;"
                                >
                                </div>
                                <div class="m-4 text-center">
                                    <span class="fw-bold">Mark Cedie Buday</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card">
                                <div class="m-4">
                                    <img class="w-100 h-100"
                                        src="/public/assets/devs/3.png"
                                        alt="mic"
                                        style="border-radius: 50%; object-fit: cover; max-height: 300px;"
                                >
                                </div>
                                <div class="m-4 text-center">
                                    <span class="fw-bold">Michael Austria</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card">
                                <div class="m-4">
                                    <img class="w-100 h-100"
                                        src="/public/assets/devs/2.png"
                                        alt="lester"
                                        style="border-radius: 50%; object-fit: cover; max-height: 300px;"
                                >
                                </div>
                                <div class="m-4 text-center">
                                    <span class="fw-bold">John Lester Balmaceda</span>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </section>
        <?php after_js()?>
        <?php include_once __DIR__ . "/../footer.php";?>
    </body>

    <div class="modal fade" id="buyModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmBuyNow">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="buyNowTitle">Buy Now > Customer Details</h5>
                    </div>
                    <div class="modal-body py-4 px-6" id="buyNowBody">
                        <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="buyNowAlert">
                                <span id="buyNowAlertMsg"></span>
                            </div>
                        </div>
                        <div class="alert alert-info w-100" id="add_cat_name_alert">
                            <span id="add_cat_name_alert_msg">
                                <span>
                                    <i class="bi bi-info-circle-fill"></i>
                                </span>
                                Fill up for the following
                            </span>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Customer Name" required>
                                <label for="customer_name">Name</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <textarea name="customer_address" id="customer_address" class="form-control" placeholder="Customer Address"></textarea>
                                <label for="customer_address">Address</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <textarea name="customer_phone_no" id="customer_phone_no" class="form-control" placeholder="Customer Phone No."></textarea>
                                <label for="customer_phone_no">Phone Number</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <select name="customer_deliver_type" id="customer_deliver_type" class="form-control">
                                    <option value="none">--</option>
                                    <option value="pickup">Pick-up</option>
                                    <option value="deliver">Deliver</option>
                                </select>
                                <label for="customer_deliver_type">Deliver Type</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <select name="customer_payment_type" id="customer_payment_type" class="form-control">
                                    <option value="none">--</option>
                                    <option value="cod">Cash On Delivery (COD)</option>
                                    <option value="card">Card</option>
                                    <option value="gcash">GCash</option>
                                </select>
                                <label for="customer_payment_type">Payment Type</label>
                            </div>
                        </div>
                        <div class="modal-footer" id="buyNowFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="btnBuyNowSubmit">
                                <i class="bi bi-floppy-fill"></i>
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let nav_about = document.getElementById("nav-about");
        nav_about.classList.add("active");
    </script>
</html>
