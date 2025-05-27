<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "Category Management";

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
                        <h2 class="fw-bolder mb-4">Categories Listing</h2>
                    </div>
                    <div class="col-6 mb-4 d-flex justify-content-end">
                        <div>
                            <a href="" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddCategoryModal">
                                <i class="bi bi-plus"></i>
                                Add Category
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="category_list">
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

    <!-- Add Category -->
    <div class="modal fade" id="AddCategoryModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmAddCategory">
                <input type="hidden" name="action" id="action" value="category_create">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="AddCategoryTitle">Add Category</h5>
                    </div>
                    <div class="modal-body py-4 px-6" id="AddCategoryBody">
                        <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="addCategoryAlert">
                                <span id="addCategoryAlertMsg"></span>
                            </div>
                        </div>
                        <div class="alert alert-info w-100" id="add_cat_name_alert">
                            <span id="add_cat_name_alert_msg">
                                <span>
                                    <i class="bi bi-info-circle-fill"></i>
                                </span>
                                Only one word for naming category
                            </span>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="text" name="add_category_name" id="add_category_name" class="form-control" placeholder="Name" required>
                                <label for="add_category_name">Name</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <textarea name="add_category_description" id="add_category_description" class="form-control" placeholder="Description"></textarea>
                                <label for="add_category_description">Description</label>
                            </div>
                        </div>
                        <div class="modal-footer" id="AddCategoryFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="btnAddCategory">
                                <i class="bi bi-floppy-fill"></i>
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Update Employee -->
    <div class="modal fade" id="updateCategoryModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:autox">
        <div class="modal-dialog" role="document">
            <form id="frmupdateCategory" method="POST">
                <input type="hidden" name="action" id="action" value="category_update">
            <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="AddCategoryTitle">Update Category: <span id="update_cat_name_view"></span></h5>
                            <input type="hidden" id="update_category_id" name="update_category_id">
                    </div>
                    <div class="modal-body py-4 px-6" id="AddCategoryBody">
                        <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="updateCategoryAlert">
                                <span id="updateCategoryAlertMsg"></span>
                            </div>
                        </div>
                        <div class="alert alert-info w-100" id="update_cat_name_alert">
                            <span id="update_cat_name_alert_msg">
                                <span>
                                    <i class="bi bi-info-circle-fill"></i>
                                </span>
                                Only one word for naming category
                            </span>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <input type="text" name="update_category_name" id="update_category_name" class="form-control" placeholder="Name" required>
                                <label for="update_category_name">Name</label>
                            </div>
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-view-stacked"></i></div>
                            <div class="form-floating">
                                <textarea name="update_category_description" id="update_category_description" class="form-control" placeholder="Description"></textarea>
                                <label for="update_category_description">Description</label>
                            </div>
                        </div>
                        <div class="modal-footer" id="AddCategoryFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-secondary" id="btnAddCategory">
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
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmdeleteCategory">
                <input type="hidden" name="action" id= "action" value="category_delete">
                <input type="text" name="delete_category_id" id="delete_category_id" class="d-none">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="deleteCategoryTitle">
                            Delete Category: <span id="delete_cat_name_view"></span>
                        </h5>
                    </div>
                    <div class="modal-body p-4 px-6" id="deleteCategoryBody">
                        <div class="mb-4">
                            <div class="mb-2">
                                <div class="alert alert-danger w-100 d-none" id="deleteCategoryAlert">
                                    <span id="deleteCategoryAlertMsg"></span>
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
                            <input type="hidden" name="delete_cat_id" id="delete_cat_id">
                            <label class="form-label">
                                Do you want to delete this category <strong><span id="delete_cat_name"></span></strong>?
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
        var paginate = 10;
        let category_list = document.getElementById("category_list");

        function fetchCategory() {
            let params = new URLSearchParams({
                category: true,
                page: page,
                paginate: paginate
            });
            $.ajax({
                url: "/api/v1.php?" + params,
                type: "GET",
                success: function(response) {
                    console.log(response);
                    let categories = response.categories;
                    categories.forEach(category => {
                        let category_card = `
                            <div class="card mb-4 w-100" style="min-height: 30px;">
                                <div class="row p-3">
                                    <div class="col-8">
                                        <h5>${category.category_name}</h5>
                                        <p>${category.category_description ? category.category_description : "No content"}</p>
                                    </div>
                                    <div class="col-4">
                                        <a
                                        class="btn btn-secondary"
                                        id="category-update-${category.idCategory}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateCategoryModal"
                                        ><i class="bi bi-pen"></i> Update</a>
                                        <a
                                        class="btn btn-danger"
                                        id="category-delete-${category.idCategory}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteCategoryModal"
                                        ><i class="bi bi-trash"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        category_list.innerHTML += category_card;
                    });
                },
                error: function(response) {
                    category_list.innerHTML = `
                        <div class="card w-100 d-flex align-items-center justify-content-center" style="min-height: 200px;">
                            <div class="text-center fw-bold">
                                ${response.responseJSON.message}
                            </div>
                        </div>
                    `;
                }
            })
        }
        fetchCategory();

        let frmAddCategory = document.getElementById("frmAddCategory");
        frmAddCategory.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let add_cat_name = formData.get("add_cat_name");
            let add_cat_description = formData.get("add_cat_description");

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let addCategoryAlert = document.getElementById("addCategoryAlert");
                    let addCategoryAlertMsg = document.getElementById("addCategoryAlertMsg");
                    addCategoryAlertMsg.textContent = response.message;
                    addCategoryAlert.classList.remove("alert-info", "alert-danger");
                    addCategoryAlert.classList.add("alert-success");
                    addCategoryAlert.classList.remove("d-none");

                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    let addCategoryAlert = document.getElementById("addCategoryAlert");
                    let addCategoryAlertMsg = document.getElementById("addCategoryAlertMsg");
                    addCategoryAlertMsg.textContent = response.responseJSON.message;
                    addCategoryAlert.classList.remove("alert-info", "alert-success");
                    addCategoryAlert.classList.add("alert-danger");
                    addCategoryAlert.classList.remove("d-none");
                }
            });
        });

        // Update Loader
        $(document).on('click', '[id^="category-update-"]', function (e) {
            e.preventDefault();
            const update_idCategory = this.id.split("-")[2];
            let params = new URLSearchParams({
                category: true,
                id: update_idCategory
            });
            $.ajax({
                url: '/api/v1.php?' + params,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let category = response.categories[0];

                    document.getElementById("update_category_id").value = category.idCategory;
                    document.getElementById("update_category_name").value = category.category_name;
                    document.getElementById("update_cat_name_view").textContent = category.category_name;
                    document.getElementById("update_category_description").value = category.category_description;
                },
                error: function(response) {
                    console.log(response);
                }
            });
        
        });
        
        let frmupdateCategory = document.getElementById("frmupdateCategory");
        frmupdateCategory.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let updateCategoryAlert = document.getElementById("updateCategoryAlert");
                    let updateCategoryAlertMsg = document.getElementById("updateCategoryAlertMsg");
                    updateCategoryAlertMsg.textContent = response.message;
                    updateCategoryAlert.classList.remove("alert-info", "alert-danger");
                    updateCategoryAlert.classList.add("alert-success");
                    updateCategoryAlert.classList.remove("d-none");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    let updateCategoryAlert = document.getElementById("updateCategoryAlert");
                    let updateCategoryAlertMsg = document.getElementById("updateCategoryAlertMsg");
                    updateCategoryAlertMsg.textContent = response.responseJSON.message;
                    updateCategoryAlert.classList.remove("alert-info", "alert-success");
                    updateCategoryAlert.classList.add("alert-danger");
                    updateCategoryAlert.classList.remove("d-none");
                    setTimeout(() => {
                        updateCategoryAlert.classList.add("d-none");
                    }, 2000);
                }
            });
        });

        // Delete Loader
        $(document).on('click', '[id^="category-delete-"]', function (e) {
            e.preventDefault();
            const delete_idCategory = this.id.split("-")[2];
            let params = new URLSearchParams({
                category: true,
                id: delete_idCategory
            });
            $.ajax({
                url: '/api/v1.php?' + params,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let category = response.categories[0];
                    document.getElementById("delete_category_id").value = category.idCategory;
                    document.getElementById("delete_category_name").textContent = category.category_name;
                    document.getElementById("delete_cat_name_view").textContent = category.category_name;
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        let frmdeleteCategory = document.getElementById("frmdeleteCategory");
        frmdeleteCategory.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let deleteCategoryAlert = document.getElementById("deleteCategoryAlert");
                    let deleteCategoryAlertMsg = document.getElementById("deleteCategoryAlertMsg");
                    deleteCategoryAlertMsg.textContent = response.message;
                    deleteCategoryAlert.classList.remove("alert-info", "alert-danger");
                    deleteCategoryAlert.classList.add("alert-success");
                    deleteCategoryAlert.classList.remove("d-none");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    let deleteCategoryAlert = document.getElementById("deleteCategoryAlert");
                    let deleteCategoryAlertMsg = document.getElementById("deleteCategoryAlertMsg");
                    deleteCategoryAlertMsg.textContent = response.responseJSON.message;
                    deleteCategoryAlert.classList.remove("alert-info", "alert-success");
                    deleteCategoryAlert.classList.add("alert-danger");
                    deleteCategoryAlert.classList.remove("d-none");
                }
            });
        });
    </script>
</html>
