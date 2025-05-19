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
        <?php head_css(); ?>
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
                    <div class="alert alert-info" id="cart_info">
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
                                        1
                                    </div>
                                </div>
                            </div>
                            <div class="w-100">
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
                            </div>
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
                                        <div class="col-12">
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
                                            <div class="w-100">
                                                <a class="btn btn-primary" id="btnAddCart">Add to Cart</a>
                                                <a class="btn btn-primary" id="btnBuyNow">Buy Now</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="product_info">
                    <div class="col-12 card">
                        <div class="m-4">
                            <div class="row">
                                <div class="col-4">
                                    <div class="m-2">
                                        <div class="bg-secondary h-100 w-100">
                                            1
                                        </div>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <span class="fs-5">
                                        Seller Name
                                    </span>
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

    <!-- Add Product -->
    <div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmAddToCart">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="AddProductTitle">Add to Cart: <span id=""></span></h5>
                    </div>
                    <div class="modal-body py-4 px-6" id="AddProductBody">
                        <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="AddProductAlert">
                                <span id="AddProductAlertMsg"></span>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="text" name="add_product_name" id="add_product_name" class="form-control" placeholder="Name" required>
                                <label for="add_product_name">Name</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="text" name="add_product_code" id="add_product_code" class="form-control" placeholder="Product Code" required>
                                <label for="add_product_code">Prodct Code</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <textarea name="add_product_description" id="add_product_description" class="form-control" placeholder="Description"></textarea>
                                <label for="add_product_description">Description</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <select name="add_product_category" id="add_product_category" class="form-control">
                                </select>
                                <label for="add_product_description">Category</label>
                            </div>
                        </div>
                        <!-- Add file image -->
                        <div class="mb-2">
                            <label for="add_product_image">Product Images</label>
                            <input type="file" name="add_product_image" id="add_product_image" class="form-control" placeholder="Image" required multiple>
                        </div>
                        <!-- Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_price" id="add_product_price" class="form-control" placeholder="Price" required>
                                <label for="add_product_price">Price</label>
                            </div>
                        </div>
                        <!-- Discount Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_original_price" id="add_product_original_price" class="form-control" placeholder="Discount Price">
                                <label for="add_product_original_price">Original Price</label>
                            </div>
                        </div>
                        <!-- Stock -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_stock" id="add_product_stock" class="form-control" placeholder="Stock" required>
                                <label for="add_product_stock">Stock</label>
                            </div>
                        </div>
                        <div class="modal-footer" id="AddProductFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="btnAddProduct">
                                <i class="bi bi-floppy-fill"></i>
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Product -->
    <div class="modal fade" id="updateProductModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmUpdateProduct">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="UpdateProductTitle">Update Product</h5>
                    </div>
                    <div class="modal-body py-4 px-6" id="UpdateProductBody">
                        <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="UpdateProductAlert">
                                <span id="UpdateProductAlertMsg"></span>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="text" name="add_product_name" id="add_product_name" class="form-control" placeholder="Name" required>
                                <label for="add_product_name">Name</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <textarea name="add_product_description" id="add_product_description" class="form-control" placeholder="Description"></textarea>
                                <label for="add_product_description">Description</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <select name="add_product_category" id="add_product_category" class="form-control">
                                </select>
                                <label for="add_product_description">Category</label>
                            </div>
                        </div>
                        <!-- Add file image -->
                        <div class="mb-2">
                            <label for="add_product_image">Product Images</label>
                            <input type="file" name="add_product_image" id="add_product_image" class="form-control" placeholder="Image" required multiple>
                        </div>
                        <!-- Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_price" id="add_product_price" class="form-control" placeholder="Price" required>
                                <label for="add_product_price">Price</label>
                            </div>
                        </div>
                        <!-- Discount Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_discount_price" id="add_product_discount_price" class="form-control" placeholder="Discount Price">
                                <label for="add_product_discount_price">Discount Price</label>
                            </div>
                        </div>
                        <!-- Stock -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_stock" id="add_product_stock" class="form-control" placeholder="Stock" required>
                                <label for="add_product_stock">Stock</label>
                            </div>
                        </div>
                        <div class="modal-footer" id="AddProductFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="btnAddProduct">
                                <i class="bi bi-floppy-fill"></i>
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
        <!-- Add Product -->
        <div class="modal fade" id="updateProductModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmUpdateProduct">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="AddProductTitle">Update Product</h5>
                    </div>
                    <div class="modal-body py-4 px-6" id="AddProductBody">
                        <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="UpdateProductAlert">
                                <span id="UpdateProductAlertMsg"></span>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="text" name="update_product_name" id="update_product_name" class="form-control" placeholder="Name" required>
                                <label for="update_product_name">Name</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <textarea name="update_product_description" id="update_product_description" class="form-control" placeholder="Description"></textarea>
                                <label for="update_product_description">Description</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <select name="update_product_category" id="update_product_category" class="form-control">
                                </select>
                                <label for="update_product_description">Category</label>
                            </div>
                        </div>
                        <!-- Add file image -->
                        <div class="mb-2">
                            <label for="update_product_image">Product Images</label>
                            <input type="file" name="update_product_image" id="update_product_image" class="form-control" placeholder="Image" required multiple>
                        </div>
                        <!-- Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="update_product_price" id="update_product_price" class="form-control" placeholder="Price" required>
                                <label for="update_product_price">Price</label>
                            </div>
                        </div>
                        <!-- Discount Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="update_product_discount_price" id="update_product_discount_price" class="form-control" placeholder="Discount Price">
                                <label for="update_product_discount_price">Discount Price</label>
                            </div>
                        </div>
                        <!-- Stock -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="update_product_stock" id="update_product_stock" class="form-control" placeholder="Stock" required>
                                <label for="update_product_stock">Stock</label>
                            </div>
                        </div>
                        <div class="modal-footer" id="AddProductFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="btnAddProduct">
                                <i class="bi bi-floppy-fill"></i>
                                Update
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
                    this_product_type.textContent = product.product_category;
                    this_product_description.textContent = product.product_description;
                    this_product_code.textContent = product.product_code;
                    if (product.product_quantity > 0) {
                        this_product_quantity_set = 1;
                        this_product_stock.textContent = product.product_quantity;
                        this_product_stock_max = product.product_quantity;
                    } else {
                        product_quantity_limit_alert.classList.remove('d-none');
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

        let product_quantity_set_value = document.getElementById("product_quantity_set_value");
        let product_quantity_set_value_dec = document.getElementById("product_quantity_set_value_dec");
        let product_quantity_set_value_inc = document.getElementById("product_quantity_set_value_inc");
        product_quantity_set_value_dec.addEventListener("click", function(e) {
            e.preventDefault();
            let value = parseInt(product_quantity_set_value.value);
            if (value > 1) {
                product_quantity_set_value.value = value - 1;
            }
        });
        product_quantity_set_value_inc.addEventListener("click", function(e) {
            e.preventDefault();
            let value = parseInt(product_quantity_set_value.value);
            if (value < this_product_stock_max) {
                this_product_quantity_set =  value + 1;
                product_quantity_set_value.value = this_product_quantity_set;
            }
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
                    idUser : <?php echo $user_id?>,
                    idProduct : this_product_id,
                    quantity : this_product_quantity_set
                }),
                success: function(response) {
                    console.log(response);
                    cart_info.classList.remove('alert-info', 'alert-danger', 'alert-warning');
                    cart_info.classList.add('alert-success');
                    cart_info_msg.textContent = response.message;
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            })
        })
    </script>
</html>
