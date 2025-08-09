<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "Product Name";

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
                    <?php echo "Product"?>
                </span>
        </header>
        <header class="d-none d-md-block bg-dark py-5 px-4">
            <div class="d-flex align-items-center" style="height: 100%;">
                <span class="text-white fw-bold fs-3">
                    <?php echo "Product" ?>
                </span>
            </div>
        </header>

        <section class="py-2">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="w-100">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a class="text-decoration-none text-black" href="/public/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><span class="this_product_name"></span></li>
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
                        <h2 class="fw-bolder mb-4 this_product_name" id="">Product Listing</h2>
                    </div>
                    <div class="d-flex flex-column flex-md-row">
                        <div class="w-100 w-md-50">
                            <div class="card shadow">
                                <div style="min-width:100%;min-height:100%">
                                    <div class="h-100 w-100">
                                        <img src="" alt="" id="product_image" style="max-width: 800px; max-height: 500px; object-fit: cover;" class="img-fluid rounded-start">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="w-100">
                                <div class="row p-2">
                                    <div class="col p-0 mx-2">
                                        <div class="m-2 bg-primary h-100 w-100">
                                            1
                                        </div>
                                    </div>
                                    <div class="col p-0 mx-2">
                                        <div class="m-2 bg-primary h-100 w-100">
                                            1
                                        </div>
                                    </div>
                                    <div class="col p-0 mx-2">
                                        <div class="m-2 bg-primary h-100 w-100">
                                            1
                                        </div>
                                    </div>
                                    <div class="col p-0 mx-2">
                                        <div class="m-2 bg-primary h-100 w-100">
                                            1
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                        <div class="w-100 w-md-50">
                            <div class="p-4">
                                <div class="w-100">
                                    <div class="row">
                                        <div class="col">
                                            Type
                                        </div>
                                        <div class="col">
                                            <span id="this_product_type"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Description
                                        </div>
                                        <div class="col">
                                            <span id="this_product_description"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Product Code
                                        </div>
                                        <div class="col">
                                            <span id="this_product_code"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            Stock
                                        </div>
                                        <div class="col">
                                            <span id="this_product_stock"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="p-2">
                                                <span class="fs-1" id="this_product_price_now">P500.00</span>
                                                <span class="fs-4 text-underline text-decoration-line-through text-muted d-none" id="this_product_price_original">P1000.00</span>
                                                <span class="bg-gray text-danger d-none" id="this_product_price_discount_percent">-15%</span>
                                            </div>
                                        </div>
                                        <?php if ($role_type == "standard"):?>
                                        <div class="col-12">
                                            <div class="w-100 d-none" id="buyToggle">
                                                <div class="w-100 mb-4">
                                                    <div class="input-group mb-4" style="max-width:200px">
                                                        <div class="input-group-text p-0">
                                                            <a class="text-decoration-none fs-3 text-black" id="product_quantity_set_value_dec">
                                                                <span class="px-3">-</span>
                                                            </a>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="text" id="product_quantity_set_value" class="form-control" placeholder="Qty." value="1">
                                                            <label for="">Qty.</label>
                                                        </div>
                                                        <div class="input-group-text p-0">
                                                            <a class="text-decoration-none fs-3 text-black" id="product_quantity_set_value_inc">
                                                                <span class="px-3">+</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-danger d-none" id="product_qunatity_limit_alert">
                                                        Maximum quantity is <span id="product_quantity_set_value_max"></span>
                                                    </div>
                                                </div>
                                                <a class="btn btn-secondary" id="btnAddCart">Add to Cart</a>
                                                <a class="btn btn-secondary" id="btnBuyNow" data-bs-toggle="modal" data-bs-target="#buyModal">Buy Now</a>
                                            </div>
                                        </div>
                                        <?php else:?>
                                        <div class="col-12">
                                            <div class="w-100">
                                                <a class="btn btn-secondary" href="/public/product_management">View On Product Management</a>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                    </div>
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

    <div class="modal fade" id="buyModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmBuyNow">
            <input type="hidden" name="action" id="action" value="checkout-make" />
            <input type="hidden" name="checkout_mode" id="checkout_mode" value="buy" />
            <input type="hidden" name="product_id" id="product_id" value="<?php echo $_GET['id']?>" />
            <input type="hidden" name="product_quantity" id="product_quantity" value="1" />
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
                                <textarea name="customer_phone" id="customer_phone" class="form-control" placeholder="Customer Phone No."></textarea>
                                <label for="customer_phone">Phone Number</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <select name="delivery_type" id="delivery_type" class="form-control">
                                    <option value="none">--</option>
                                    <option value="pickup">Pick-up</option>
                                    <option value="deliver">Deliver</option>
                                </select>
                                <label for="delivery_type">Deliver Type</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <select name="payment_type" id="payment_type" class="form-control">
                                    <option value="none">--</option>
                                    <option value="cod">Cash On Delivery (COD)</option>
                                    <option value="card">Card</option>
                                    <option value="gcash">GCash</option>
                                </select>
                                <label for="payment_type">Payment Type</label>
                            </div>
                        </div>
                        <input type="hidden" name="product_quantity" id="in_product_quantity_set" value="1" />
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
        var this_product_id = "<?php echo $_GET['id']?>";
        var this_product_quantity_max = 0;
        var this_product_quantity_set = 0;
        
        let product_qunatity_limit_alert = document.getElementById("product_qunatity_limit_alert");
        function fetchProduct() {
            let params = new URLSearchParams({
                product: true,
                id: this_product_id
            })
            $.ajax({
                url: '/api/v1.php?' + params,
                type: 'GET',
                success: function(response) {
                    product = response.products[0];
                    this_product_id = product.idProduct;
                    let this_product_name = document.getElementsByClassName("this_product_name");
                    for (let i = 0; i < this_product_name.length; i++) {
                        this_product_name[i].textContent = product.product_name;
                    }
                    this_product_name.textContent = product.product_name;
                    let this_product_type = document.getElementById("this_product_type");
                    let this_product_description = document.getElementById("this_product_description");
                    let this_product_code = document.getElementById("this_product_code");
                    let this_product_stock = document.getElementById("this_product_stock");
                    let this_product_image = document.getElementById("product_image");

                    this_product_type.textContent = product.product_category;
                    this_product_description.textContent = product.product_description;
                    this_product_code.textContent = product.product_code;
                    this_product_image.src = product.product_image;
                    if (product.product_quantity > 0) {
                        buyToggle.classList.remove('d-none');
                        this_product_quantity_set = 1;
                        this_product_stock.textContent = product.product_quantity;
                        this_product_stock_max = product.product_quantity;
                    } else {
                        // product_quantity_limit_alert.classList.remove('d-none');
                        this_product_stock.textContent = "Out of Stock";
                        this_product_stock.classList.add('text-danger', 'fw-bold');
                    }

                    let this_product_price_now = document.getElementById("this_product_price_now");
                    let this_product_price_original = document.getElementById("this_product_price_original");
                    console.log(product.product_price_now, product.product_price_original);
                    let this_product_discount_set = Math.round((product.product_price_now / product.product_price_original) * 100) + "%";
                    this_product_price_now.textContent = "₱" + product.product_price_now;
                    this_product_price_original.textContent = "₱" + product.product_price_original;
                    let this_product_price_discount_percent = document.getElementById("this_product_price_discount_percent");
                    if (product.product_price_now > product.product_price_original) {
                        this_product_price_original.classList.remove('d-none');
                        this_product_price_discount_percent.classList.remove('d-none');
                        this_product_price_discount_percent.textContent = this_product_discount_set;
                    }
                },
                
            })
        }

        fetchProduct();

        let buyToggle = document.getElementById("buyToggle");
        let product_quantity_set_value = document.getElementById("product_quantity_set_value");
        let product_quantity_set_value_dec = document.getElementById("product_quantity_set_value_dec");
        product_quantity_set_value_dec.addEventListener("click", function(e) {
            e.preventDefault();
            let value = parseInt(product_quantity_set_value.value);
            if (value > 1) {
                product_quantity_set_value.value -= 1;
                in_product_quantity_set = parseInt(product_quantity_set_value.value);
            }
            console.log(this_product_quantity_set); 
        });
        product_quantity_set_value_inc.addEventListener("click", function(e) {
            e.preventDefault();
            let value = parseInt(product_quantity_set_value.value);
            if (value < this_product_stock_max) {
                this_product_quantity_set += 1;
                product_quantity_set_value.value = this_product_quantity_set;
                in_product_quantity_set = parseInt(product_quantity_set_value.value);
            }
            console.log(this_product_quantity_set);
        });

        let btnAddCart = document.getElementById("btnAddCart");
        let btnBuyNow = document.getElementById("btnBuyNow");

        let cart_info = document.getElementById("cart_info");
        let cart_info_msg = document.getElementById("cart_info_msg");

        btnAddCart.addEventListener("click", function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: JSON.stringify({
                    action : "cart_add",
                    idUser : this_user_id,
                    idProduct : this_product_id,
                    quantity : this_product_quantity_set
                }),
                success: function(response) {
                    console.log(response);
                    cart_info.classList.remove('alert-info', 'alert-danger', 'alert-warning');
                    cart_info.classList.add('alert-success');
                    cart_info.classList.remove('d-none');
                    cart_info_msg.textContent = response.message;
                    setInterval(() => {
                        cart_info.classList.add('d-none');
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            })
        })

        let frmBuyNow = document.getElementById('frmBuyNow');
        btnBuyNow.addEventListener('click', function(e) {
            e.preventDefault();
            frmBuyNow.reset();
            let formData = new FormData(frmBuyNow);
            formData.set("product_id", this_product_id);
            formData.set("product_quantity", product_quantity_set_value.value);
            let in_product_quantity_set = document.getElementById("in_product_quantity_set");
            in_product_quantity_set.value = product_quantity_set_value.value;

        });
        frmBuyNow.addEventListener('submit', function (e) {
            e.preventDefault();
        
            let formData = new FormData(this);
            let customer_name = formData.get("customer_name");
            let customer_address = formData.get("customer_address");
            let customer_phone_no = formData.get("customer_phone");
            let customer_deliver_type = formData.get("deliver_type");
            let customer_payment_type = formData.get("payment_type");
        
            let buyNowAlert = document.getElementById("buyNowAlert");
            let buyNowAlertMsg = document.getElementById("buyNowAlertMsg");
            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    buyNowAlert.classList.remove('alert-success', 'alert-danger');
                    buyNowAlert.classList.add('alert-info');
                    buyNowAlert.classList.remove('d-none');
                    buyNowAlertMsg.textContent = "Processing your order...";
                },
                success: function(response) {
                    console.log(response);
                    buyNowAlertMsg.textContent = response.message;
                    buyNowAlert.classList.remove('alert-info', 'alert-danger');
                    buyNowAlert.classList.add('alert-success');
                    buyNowAlert.classList.remove('d-none');
                    setTimeout(function() {
                        window.location.href = "/public/orders";
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    console.error("Error XHR:", xhr);
                    console.error("Error Status:", status);
                    console.error("Error:", error);
                }
            });
        });

    </script>
</html>
