<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "Product Management";

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
                    <?php echo  $page_name?>
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
                <div class="row">
                    <div class="col-6 mb-4">
                        <h2 class="fw-bolder mb-4">Product Listing</h2>
                    </div>
                    <div class="col-6 mb-4 d-flex justify-content-end">
                        <div>
                            <a href="" 
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#addProductModal"
                            >
                                <i class="bi bi-plus"></i>
                                Add Product
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Fancy Product</h5>
                                    <!-- Product price-->
                                    $40.00 - $80.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">View options</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Sale badge-->
                            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Special Item</h5>
                                    <!-- Product reviews-->
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                    </div>
                                    <!-- Product price-->
                                    <span class="text-muted text-decoration-line-through">$20.00</span>
                                    $18.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Sale badge-->
                            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Sale Item</h5>
                                    <!-- Product price-->
                                    <span class="text-muted text-decoration-line-through">$50.00</span>
                                    $25.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Popular Item</h5>
                                    <!-- Product reviews-->
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                    </div>
                                    <!-- Product price-->
                                    $40.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Sale badge-->
                            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Sale Item</h5>
                                    <!-- Product price-->
                                    <span class="text-muted text-decoration-line-through">$50.00</span>
                                    $25.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Fancy Product</h5>
                                    <!-- Product price-->
                                    $120.00 - $280.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">View options</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Sale badge-->
                            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Special Item</h5>
                                    <!-- Product reviews-->
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                    </div>
                                    <!-- Product price-->
                                    <span class="text-muted text-decoration-line-through">$20.00</span>
                                    $18.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">Popular Item</h5>
                                    <!-- Product reviews-->
                                    <div class="d-flex justify-content-center small text-warning mb-2">
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                        <div class="bi-star-fill"></div>
                                    </div>
                                    <!-- Product price-->
                                    $40.00
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Add to cart</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php after_js()?>
        <?php include_once __DIR__ . "/../footer.php";?>
    </body>

        <!-- Add Category -->
        <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmAddProduct">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="AddProductTitle">Add Product</h5>
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

    <script>
        function fetchCategories() {
            let params = new URLSearchParams({
                category: true
            })
            $.ajax({
                url: "/api/v1.php?" + params,
                type: "GET",
                success: function(response) {
                    let categories = response.categories;
                    categories.forEach(function(category) {
                        let add_product_category = document.getElementById("add_product_category");
                        let option = document.createElement("option");
                        option.value = category.idCategory;
                        option.text = category.category_name;
                        add_product_category.appendChild(option);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching categories:", error);
                }
            });
        }
        fetchCategories();

        let frmAddProduct = document.getElementById("frmAddProduct");
        frmAddProduct.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let add_product_name = formData.get("add_product_name");
            let add_product_description = formData.get("add_product_description");
            let add_product_category = formData.get("add_product_category");
            let add_product_image = formData.get("add_product_image");
            let add_product_price = formData.get("add_product_price");
            let add_product_discount = formData.get("add_product_discount");
            let add_product_quantity = formData.get("add_product_quantity");

            // read the add_product_image
            let reader = new FileReader();
            reader.onload = function(e) {
                console.log(e.target.result);
            }
            reader.readAsDataURL(add_product_image);
            // console.log(add_product_name);
            // console.log(add_product_image);
            // return 0;
            // $.ajax({
            //     url: '/api/v1.php',
            //     type: 'POST',
            //     data: JSON.stringify({
            //         action: "product_create",
            //         product_name: add_product_name,
            //         product_description: add_product_description,
            //         product_price: add_product_price,
            //         product_category: add_product_category
            //     }),
            //     success: function(response) {
            //         console.log(response);
            //     },
            //     error: function(response) {
            //         console.log(response);
            //     }
            // });
        });
    </script>
</html>
