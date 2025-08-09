<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "Contact Us";

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
                    <h1><?php echo $project_name?></h1>

                    <p class="lead">
                        <?php echo $project_name?> is a leading online shopping
                        platform in the Web Systeem Subject. We are dedicated to
                        providing our customers with a seamless and enjoyable
                        shopping experience. Our platform offers a wide range of
                        products, from electronics to fashion, all at competitive
                        prices. We strive to ensure that our customers have access
                        to the best products and services available in the market.

                    </p>
                    <p class="lead">
                        This project created with
                        <ul class="lead">
                            <li>NativePHP</li>
                            <li>MySQL</li>
                            <li>Bootstrap</li>
                            <li>jQuery</li>
                            <li>CSS (but custom)</li>
                        </ul>
                    </p>
                    <!-- Contact -->
                    <p class="lead">We would love to hear from you! If you have any questions, comments, or feedback, please feel free to reach out to us.</p>
                </div>
            </div>
        </section>
        <?php after_js()?>
        <?php include_once __DIR__ . "/../footer.php";?>
    </body>
    <script>
        let nav_contact = document.getElementById("nav-contact");
        nav_contact.classList.add("active");
    </script>
</html>
