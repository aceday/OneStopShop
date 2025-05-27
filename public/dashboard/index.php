<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "Dashboard";

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

// echo json_encode($this_user);
// echo $role_type;
// exit;
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
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Shop in style</h1>
                    <p class="lead fw-normal text-white-50 mb-0"><?php echo $project_description?></p>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="w-100 px-4 py-2 mb-4">
                    <div class="input-group">
                        <div class="input-group-text">
                            <span class="px-2">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>
                        <div class="form-floating">
                            <input type="text" id="product_search" class="form-control" placeholder="Search">
                            <label for="product_search">Search</label>
                        </div>
                    </div>
                </div>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="product-list">
                    ...
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
        <?php after_js()?>
        <?php include_once __DIR__ . "/../footer.php";?>
    </body>

    <script>
        let nav_home = document.getElementById("nav-home");
        nav_home.classList.add("active");

        var page = 1;
        var paginate = 20;
        var search_name = "";
        var search_category = "";
        var search_code = "";

        let products_list = document.getElementById("product-list");
        let product_search = document.getElementById("product_search");
        product_search.addEventListener("keyup", function(e) {
            search_name = e.target.value;
            fetchProducts();
        });
        function fetchProducts() {
            let params = new URLSearchParams({
                product: true, 
                page: page,
                paginate: paginate,
                name: search_name      
            })
            console.log(params.toString());
            $.ajax({
                url: "/api/v1.php?" + params,
                type: "GET",
                success: function(response) {
                    console.log(response);
                    products_list.innerHTML = "";
                    let products = response.products;
                    products.forEach(product => {
                        let product_id = product.idProduct;
                        let product_name = product.product_name;
                        let product_price_now = product.product_price_now;
                        let product_price_original = product.product_price_original;

                        console.log(product_price_now, product_price_original, product_price_now > product_price_original);
                        // Product infos
                        let product_view_original = product_price_now > product_price_original
                            ? `<span class=\"text-muted text-decoration-line-through\">₱${product_price_original}</span>`
                            : ``;
                        let product_image = product.product_image
                            ? product.product_image
                            : "https://dummyimage.com/450x300/dee2e6/6c757d.jpg";
                        products_list.innerHTML += `
                            <div class="col mb-5">
                                <div class="card h-100">
                                    <!-- Sale badge-->
                                    <!-- <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div> -->
                                    <!-- Product image-->
                                    <img class="card-img-top" src="${product_image}" alt="..." />
                                    <!-- Product details-->
                                    <div class="card-body p-4">
                                        <div class="text-center">
                                            <!-- Product name-->
                                            <h5 class="fw-bolder">${product_name}</h5>                                            <!-- Product price-->
                                            ${product_view_original}
                                            ₱${product_price_now}
                                        </div>
                                    </div>
                                    <!-- Product actions-->
                                    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                        <div class="row">
                                            <div class="col">
                                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" id="product-update-${product_id}" href="/public/product/?id=${product_id}">View</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                },
                error: function(response) {
                    let products = response.responseJSON;
                    products_list.innerHTML = `
                        <div class="mb-4 w-100 card text-center p-4">
                            <span class="fw-bold">
                                ${products.message}
                            </span>
                        </div>
                    `;
                }
            });
        }
    
        fetchProducts();
    </script>
</html>
