<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "Carts";

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

if (isset($_GET['id'])) {
    // Pass
}

$page_name = "Carts";
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-decoration-none text-black" href="/public/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><span id="this_page_name"><?php echo $page_name?></span></li>
                    </ol>
                </nav>
                </div>
                <div class="w-100">
                    <div class="alert alert-info d-none" id="cart_info">
                        <span id="cart_info_msg">
                            Added cart successfully
                        </span>
                    </div>
                </div>
                <div class="w-100">
                    <div class="col mb-4">
                        <h2 class="fw-bolder mb-4 this_product_name" id=""><?php echo $page_name?></h2>
                    </div>
                    <div class="d-flex flex-column flex-md-ro w-100">
                        <div class="row mb-4">
                            <div class="col-12">
                                <form action="">
                                    <div class="row">
                                        <div class="col-2">

                                        </div>
                                        <div class="col-5">
                                            Product
                                        </div>
                                        <div class="col-2">
                                            Unit Price
                                        </div>
                                        <div class="col-3">
                                            Quantity
                                        </div>
                                    </div>
                                    <div class="row" id="cart-list">
                                        <div class="col-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <span class="fs-3">Redmi Note 13</span>
                                            <span class="fs-5"></span>
                                        </div>
                                        <div class="col-2">
                                            4
                                        </div>
                                        <div class="col-3">
                                            5
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mb-4 d-flex justify-content-end">
                            <button class="w-sm-100 w-auto btn btn-outline-secondary">Checkout</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php after_js()?>
        <?php include_once __DIR__ . "/../footer.php";?>
    </body>

    <script>
        let cart_list = document.getElementById("cart-list");
        var page = 1;
        var paginate = 5;
        let this_user_id = "<?php echo $user_id?>";
        function fetchCart() {
            let param = new URLSearchParams({
                cart: true,
                user_id: this_user_id,
                page: page,
                paginate: paginate
            });
            $.ajax({
                url: '/api/v1.php?' + param,
                type: 'GET',
                success: function(response) {

                    console.log(response);
                    let cart = response.cart;
                    let cart_count = response.cart[0].total_carts;
                    let cart_list_html = "";
                    for (let i = 0; i < cart.length; i++) {
                        let product_name = cart[i].product_name;
                        let product_price = cart[i].product_price;
                        let product_quantity = cart[i].product_quantity;
                        let product_id = cart[i].product_id;
                        let product_image = cart[i].product_image;

                        cart_list_html += `
                            <div class="row">
                                <div class="col-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <span class="fs-3">${product_name}</span>
                                    <span class="fs-5"></span>
                                </div>
                                <div class="col-2">
                                    ${product_price}
                                </div>
                                <div class="col-3">
                                    <input type="number" class="form-control" id="product_quantity_${product_id}" value="${product_quantity}" min="1" max="10">
                                </div>
                            </div>
                        `;

                    }
                }
        });
        }
        
    </script>
</html>
