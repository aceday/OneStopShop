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
    // header("Location: /public/login");
    // exit;
}

$username = $this_user->username;
$user_id = $this_user->user_id;
$role_type = $this_user->role_type;

// else {
//     header('Location /public/');
// }
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
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="products_list">
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
                            <!-- <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div> -->
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
                </nav>''
            </div>
        </section>
        <?php after_js()?>
        <?php include_once __DIR__ . "/../footer.php";?>
    </body>

    <!-- Add Product -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmAddProduct">
                <input type="hidden" name="action" id="action" value="product_create">
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
                                <label for="add_product_category">Category</label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="add_product_image">Product Images</label>
                            <input type="file" name="add_product_image" id="add_product_image" class="form-control" placeholder="Image" required multiple>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_price_now" id="add_product_price_now" class="form-control" placeholder="Price" required>
                                <label for="add_product_price_now">Price</label>
                            </div>
                        </div>
                        <!-- Discount Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_price_original" id="add_product_price_original" class="form-control" placeholder="Discount Price">
                                <label for="add_product_price_original">Original Price</label>
                            </div>
                        </div>
                        <!-- Stock -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="add_product_quantity" id="add_product_quantity" class="form-control" placeholder="Stock" required>
                                <label for="add_product_quantity">Stock</label>
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
                <input type="hidden" name="action" id="action" value="product_update">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="UpdateProductTitle">Update Product: <span id="update_product_name_view"></span></h5>
                        <input type="hidden" name="update_product_id" id="update_product_id">
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
                                <input type="text" name="update_product_name" id="update_product_name" class="form-control" placeholder="Name" required>
                                <label for="update_product_name">Name</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <textarea name="update_product_code" id="update_product_code" class="form-control" placeholder="Description"></textarea>
                                <label for="update_product_code">Product Code</label>
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
                                <label for="update_product_category">Category</label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="update_product_image">Product Image</label>
                            <input type="file" name="update_product_image" id="update_product_image" class="form-control" placeholder="Image">
                        </div>
                        <!-- Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="update_product_price_now" id="update_product_price_now" class="form-control" placeholder="Price" required>
                                <label for="update_product_price_now">Price</label>
                            </div>
                        </div>
                        <!-- Discount Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="update_product_price_original" id="update_product_price_original" class="form-control" placeholder="Discount Price">
                                <label for="update_product_price_original">Original Price</label>
                            </div>
                        </div>
                        <!-- Stock -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="update_product_quantity" id="update_product_quantity" class="form-control" placeholder="Stock" required>
                                <label for="update_product_quantity">Stock</label>
                            </div>
                        </div>
                        <div class="modal-footer" id="AddProductFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-dark" id="btnAddProduct">
                                <i class="bi bi-floppy-fill"></i>
                                Update
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
                <input type="hidden" name="update_product_action" id="update_product_action" value="product_update">
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
                                <input type="number" name="update_product_price_now" id="update_product_price_now" class="form-control" placeholder="Price" required>
                                <label for="update_product_price_now">Price</label>
                            </div>
                        </div>
                        <!-- Discount Price -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="update_product_price_original" id="update_product_price_original" class="form-control" placeholder="Discount Price">
                                <label for="update_product_price_original">Discount Price</label>
                            </div>
                        </div>
                        <!-- Stock -->
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="number" name="update_product_quantity" id="update_product_quantity" class="form-control" placeholder="Stock" required>
                                <label for="update_product_quantity">Stock</label>
                            </div>
                        </div>
                        <div class="modal-footer" id="AddProductFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-secondary" id="btnAddProduct">
                                <i class="bi bi-floppy-fill"></i>
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Category -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmdeleteProduct">
                <input type="hidden" name="action" id="action" value="product_delete">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="deleteCategoryTitle">
                            Delete Category: <span id="delete_product_name_view"></span>
                        </h5>
                    </div>
                    <div class="modal-body p-4 px-6" id="deleteCategoryBody">
                        <div class="mb-4">
                            <div class="mb-2">
                                <div class="alert alert-danger w-100 d-none" id="deleteProductAlert">
                                    <span id="deleteProductAlertMsg"></span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16" style="color:red">
                                    <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z"/>
                                    <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                                </svg>
                            </div>
                            <input type="hidden" name="delete_product_id" id="delete_product_id">
                            <label class="form-label">
                                Do you want to delete this product <strong><span id="delete_product_name"></span></strong>?
                            </label>
                        </div>
                        <div class="modal-footer" id="deleteCategoryFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" id="btndeleteCategory">Delete</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        var page = 1;
        var paginate = 100;
        var search_name = "";
        var search_category = "";
        var search_code = "";

        let products_list = document.getElementById("products_list");

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
                        // Update Product
                        let add_product_category = document.getElementById("add_product_category");
                        let option = document.createElement("option");
                        option.value = category.idCategory;
                        option.text = category.category_name;
                        add_product_category.appendChild(option);

                        // Update Product
                        let update_product_category = document.getElementById("update_product_category");
                        let option2 = document.createElement("option");
                        option2.value = category.idCategory;
                        option2.text = category.category_name;
                        update_product_category.appendChild(option2);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching categories:", error);
                }
            });
        }
        fetchCategories();

        let product_search = document.getElementById("product_search");
        product_search.addEventListener("keyup", function (e) {
            e.preventDefault();
            console.log(this.value);
            search_name = this.value;
            fetchProducts();
        });

        function fetchProducts() {
            let params = new URLSearchParams({
                product: true, 
                name: search_name,     
                page: page,
                paginate: paginate
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
                        let product_image = product.product_image;
                        console.log(product_price_now, product_price_original, product_price_now > product_price_original);
                        // Product infos
                        let product_view_original = product_price_now > product_price_original
                            ? `<span class=\"text-muted text-decoration-line-through\">₱${product_price_original}</span>`
                            : ``;
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
                                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" id="product-update-${product_id}" data-bs-toggle="modal" data-bs-target="#updateProductModal">Update</a></div>
                                            </div>
                                            <div class="col">
                                                <div class="text-center"><a class="btn btn-outline-danger mt-auto" id="product-delete-${product_id}" data-bs-toggle="modal" data-bs-target="#deleteProductModal">Delete</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                },
                error: function(response) {
                    
                    products_list.innerHTML = "";
                    products_list.innerHTML += `
                    <div class="w-100 mb-4 p-4 card text-center fw-bold">
                        ${response.responseJSON.message}
                    </div>
                    `;
                    console.log(response);
                }
            });
        }
    
        fetchProducts();

        let frmAddProduct = document.getElementById("frmAddProduct");
        frmAddProduct.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let add_product_action = formData.get("action");
            let add_product_name = formData.get("add_product_name");
            let add_product_code = formData.get("add_product_code");
            let add_product_description = formData.get("add_product_description");
            let add_product_category = formData.get("add_product_category");
            let add_product_image = formData.get("add_product_image");
            let add_product_price = formData.get("add_product_price");
            let add_product_original_price = formData.get("add_product_original_price");
            let add_product_quantity = formData.get("add_product_stock");

            // read the add_product_image
            // let reader = new FileReader();
            // reader.onload = function(e) {
            //     console.log(e.target.result);
            // }
            // reader.readAsDataURL(add_product_image);
            // console.log(add_product_name);
            // console.log(add_product_image);
            // return 0;
            console.log("Action: ", add_product_action);
            console.log('Code: ', add_product_code);
            console.log('Name: ', add_product_name);
            console.log('Description: ', add_product_description);
            console.log('Category: ', add_product_category);
            // console.log('Image: ', add_product_image);
            console.log('Price: ', add_product_price);
            console.log('Original Price: ', add_product_original_price);
            console.log('Quantity: ', add_product_quantity);

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let AddProductAlertMsg = document.getElementById("AddProductAlertMsg");
                    let AddProductAlert = document.getElementById("AddProductAlert");
                    AddProductAlertMsg.textContent = response.message;
                    AddProductAlert.classList.remove("alert-danger", "alert-info");
                    AddProductAlert.classList.add("alert-success");
                    AddProductAlert.classList.remove("d-none");
                    setTimeout(()=>{
                        location.reload();
                    }, 2000);
                },  
                error: function(response) {
                    console.log(response.responseJSON);
                    let alert = document.getElementById("AddProductAlert");
                    let alertMsg = document.getElementById("AddProductAlertMsg");
                    alertMsg.innerHTML = response.responseJSON.message;
                    alert.classList.remove("alert-success", "alert-info");
                    alert.classList.add("alert-danger");
                    alert.classList.remove("d-none");
                }
            });
        });

        let frmUpdateProduct = document.getElementById("frmUpdateProduct");
        frmUpdateProduct.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let update_product_action = formData.get("action");
            let update_product_id = formData.get("update_product_id");
            let update_product_name = formData.get("update_product_name");
            let update_product_code = formData.get("update_product_code");
            let update_product_description = formData.get("update_product_description");
            let update_product_category = formData.get("update_product_category");
            let update_product_image = formData.get("update_product_image");
            let update_product_price = formData.get("update_product_price_now");
            let update_product_original_price = formData.get("update_product_price_original");
            let update_product_quantity = formData.get("update_product_quantity");

            console.log("Action: ", update_product_action);
            console.log("ID: ", update_product_id);
            console.log('Code: ', update_product_code);
            console.log('Name: ', update_product_name);
            console.log('Description: ', update_product_description);
            console.log('Category: ', update_product_category);
            console.log('Image: ', update_product_image);
            console.log('Price: ', update_product_price);
            console.log('Original Price: ', update_product_original_price);
            console.log('Quantity: ', update_product_quantity);

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                proccessData: false,
                contentType: false,
                beforeSend: function() {
                    let UpdateProductAlert = document.getElementById("UpdateProductAlert");
                    let UpdateProductAlertMsg = document.getElementById("UpdateProductAlertMsg");
                    UpdateProductAlert.classList.remove("alert-success", "alert-danger");
                    UpdateProductAlert.classList.add("alert-info");
                    UpdateProductAlert.classList.remove("d-none");
                    UpdateProductAlertMsg.textContent = "Updating product...";
                },
                success: function(response) {
                    let UpdateProductAlertMsg = document.getElementById("UpdateProductAlertMsg");
                    let UpdateProductAlert = document.getElementById("UpdateProductAlert");
                    UpdateProductAlertMsg.textContent = response.message;
                    UpdateProductAlert.classList.remove("alert-danger", "alert-info");
                    UpdateProductAlert.classList.add("alert-success");
                    UpdateProductAlert.classList.remove("d-none");
                    setTimeout(()=>{
                        location.reload();
                    }, 2000);
                },  
                error: function(response, status, error) {
                    console.log("XHR: ", response);
                    console.log("Error: ", error);
                    console.log("Status: ", status);
                    console.log(response.responseJSON);
                    let alert = document.getElementById("UpdateProductAlert");
                    let alertMsg = document.getElementById("UpdateProductAlertMsg");
                    alertMsg.innerHTML = response.responseJSON.message;
                    alert.classList.remove("alert-success", "alert-info");
                    alert.classList.add("alert-danger");
                    alert.classList.remove("d-none");
                }
            });
        });

        // Update Loader
        $(document).on('click', '[id^="product-update-"]', function (e) {
            e.preventDefault();
            const update_idProduct = this.id.split("-")[2];
            console.log(update_idProduct);
            let params = new URLSearchParams({
                product: true,
                id: update_idProduct
            });
            $.ajax({
                url: '/api/v1.php?' + params,
                type: 'GET',
                success: function(response) {
                    // console.log(response);
                    let product = response.products[0];

                    console.log(product);
                    const product_id = product.idProduct;
                    const product_name = product.product_name;
                    const product_code = product.product_code;
                    const product_category = product.product_category;
                    const product_description = product.product_description;
                    const product_price_now = product.product_price_now;
                    const product_price_original = product.product_price_original;
                    const product_stock = product.product_quantity;
                    // const product_status = product.product_status;

                    let frmUpdateProduct = document.getElementById("frmUpdateProduct");
                    frmUpdateProduct.reset();

                    console.log(product);
                    console.log("Product ID: ", product_id);
                    console.log("Product Name: ", product_name);
                    console.log("Product Code: ", product_code);
                    console.log("Product Description: ", product_description);
                    console.log("Product Category: ", product_category);
                    console.log("Product Price Now: ", product_price_now);
                    console.log("Product Price Original: ", product_price_original);
                    console.log("Product Stock: ", product_stock);

                    frmUpdateProduct.elements['update_product_id'].value = product_id;
                    document.getElementById('update_product_name_view').textContent = product_name;
                    frmUpdateProduct.elements['update_product_name'].value = product_name;
                    frmUpdateProduct.elements['update_product_code'].value = product_code;
                    frmUpdateProduct.elements['update_product_description'].value = product_description;
                    frmUpdateProduct.elements['update_product_category'].value = product_category;
                    frmUpdateProduct.elements['update_product_price_now'].value = product_price_now;
                    frmUpdateProduct.elements['update_product_price_original'].value = product_price_original;
                    frmUpdateProduct.elements['update_product_quantity'].value = product_quantity;
                    // frmUpdateProduct.elements['update_product_status'].value = product_status;

                    console.log(product);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        $(document).on('click', '[id^="product-delete-"]', function (e) {
            e.preventDefault();
            const delete_idProduct = this.id.split("-")[2];
            console.log(delete_idProduct);
            let params = new URLSearchParams({
                product: true,
                id: delete_idProduct
            });
            $.ajax({
                url: '/api/v1.php?' + params,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let product = response.products[0];

                    const product_id = product.idProduct;
                    const product_name = product.product_name;
                    document.getElementById('delete_product_name_view').textContent = product_name;
                    document.getElementById('delete_product_name').textContent = product_name;
                    document.getElementById('delete_product_id').value = product_id;
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        let frmdeleteProduct = document.getElementById("frmdeleteProduct");
        frmdeleteProduct.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let delete_product_id = formData.get("delete_product_id");

            console.log("ID: ", delete_product_id);

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let deleteProductAlertMsg = document.getElementById("deleteProductAlertMsg");
                    let deleteProductAlert = document.getElementById("deleteProductAlert");
                    deleteProductAlertMsg.textContent = response.message;
                    deleteProductAlert.classList.remove("alert-danger", "alert-info");
                    deleteProductAlert.classList.add("alert-success");
                    deleteProductAlert.classList.remove("d-none");
                    setTimeout(()=>{
                        location.reload();
                    }, 2000);
                },  
                error: function(response) {
                    console.log(response.responseJSON);
                    let alert = document.getElementById("deleteProductAlert");
                    let alertMsg = document.getElementById("deleteProductAlertMsg");
                    alertMsg.innerHTML = response.responseJSON.message;
                    alert.classList.remove("alert-success", "alert-info");
                    alert.classList.add("alert-danger");
                    alert.classList.remove("d-none");
                }
            });
        });
    </script>
</html>
