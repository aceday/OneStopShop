<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "User Management";

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
                        <h2 class="fw-bolder mb-4">Users</h2>
                    </div>
                    <div class="col-6 mb-4 d-flex justify-content-end">
                        <div>
                            <a href="" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#AddUserModal">
                                <i class="bi bi-plus"></i>
                                Add User
                            </a>
                        </div>
                    </div>
                </div>
                <div class="w-100">
                    <div class="input-group mb-4">
                        <div class="input-group-text">
                            <span class="px-2">
                                <i class="bi bi-search"></i>
                            </span>
                        </div>
                        <div data-mdb-input-init class="form-floating">
                            <input type="text" id="search_users" name="search_users" class="form-control" placeholder="Search Users" />
                            <label class="form-label" for="search_users">Search Users</label>
                        </div>
                    </div>
                </div>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center" id="users_list">
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

    <!-- Add User -->
    <div class="modal fade" id="AddUserModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmAddUser" class="needs-validation" novalidate>
                <input type="hidden" name="action" id="action" value="user_create">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="AddUserTitle">Add User</h5>
                    </div>
                    <div class="modal-body py-4 px-6" id="AddUserBody">
                    <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="updateUserAlert">
                                <span id="updateUserAlertMsg"></span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="create_username">Username</label>
                            <input type="text" name="create_username" id="create_username" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a username.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="create_email">Email</label>
                            <input type="email" name="create_email" id="create_email" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="create_password">Password</label>
                            <input type="password" name="create_password" id="create_password" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a password.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="create_password1">Password</label>
                            <input type="password" name="create_password1" id="create_password1" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a password.
                            </div>
                        </div>
                        <div class="modal-footer" id="AddUserFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success" id="btnAddUser">
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
    <div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:autox">
        <div class="modal-dialog" role="document">
            <form id="frmupdateUser" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="action" id="update_action" value="user_update">    
            <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="AddUserTitle">Update User: <span id="update_username_view"></span></h5>
                            <input type="hidden" id="update_user_id" name="update_user_id">
                    </div>
                    <div class="modal-body py-4 px-6" id="AddUserBody">
                        <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="updateUserAlert">
                                <span id="updateUserAlertMsg"></span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="update_username">Username</label>
                            <input type="text" name="update_username" id="update_username" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a username.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="update_email">Email</label>
                            <input type="email" name="update_email" id="update_email" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="update_password">Password</label>
                            <input type="password" name="update_password" id="update_password" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a password.
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="update_password1">Password</label>
                            <input type="password" name="update_password1" id="update_password1" class="form-control" required>
                            <div class="invalid-feedback">
                                Please enter a password.
                            </div>
                        </div>
                        <div class="modal-footer" id="AddUserFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-secondary" id="btnAddUser">
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
    <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmdeleteUser">
                <input type="hidden" name="action" id= "action" value="user_delete">
                <input type="hidden" name="delete_user_id" id="delete_user_id" class="d-none">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold">
                            Delete User: <span id="delete_username_view"></span>
                        </h5>
                    </div>
                    <div class="modal-body p-4 px-6" id="deleteCategoryBody">
                        <div class="mb-2">
                            <div class="alert alert-danger w-100 d-none" id="deleteUserAlert">
                                <span id="deleteUserAlertMsg"></span>
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
                                Do you want to delete this user <strong><span id="delete_username"></span></strong>?
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
        (() => {
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
                }, false)
            })
        })()
        var page = 1;
        var paginate = 10;
        var search_name = "";

        let search_users = document.getElementById("search_users");
        search_users.addEventListener("keyup", function(e) {
            search_name = e.target.value;
            fetchUsers();
        });

        let category_list = document.getElementById("category_list");

        let users_list = document.getElementById("users_list");

        function fetchUsers() {
            let params = new URLSearchParams({
                user: true,
                page: page,
                paginate: paginate,
                username: search_name
            });

            $.ajax({
                url: '/api/v1.php?' + params,
                type: 'GET',
                success: function(response) {
                    users_list.innerHTML = "";
                    let users = response.users;
                    console.log(response);
                    users.forEach(user => {
                        let user_id = user.idUser;
                        let username = user.username;
                        let user_email = user.email;
                        let role_type = user.role_type;
                        let user_active = user.status;
                        users_list.innerHTML += `
                            <div class="col mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">${user_name_badge(username=username, role_type=role_type, active=user_active)}</h5>
                                        <p class="card-text">Email: ${user_email}</p>
                                        <p class="card-text">${user_role_badge(username=username, role_type=role_type, active=user_active)}</p>
                                        <div class="d-flex gap-2 justify-content-between">
                                            <button class="btn btn-secondary" id="user-update-${user_id}" data-bs-toggle="modal" data-bs-target="#updateUserModal">
                                                <i class="bi bi-pencil-square" id="user-update-${user_id}"></i> Update
                                            </button>
                                            <button class="btn btn-dark" id="user-delete-${user_id}" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                                <i class="bi bi-trash-fill"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                },
                error: function(xhr, status, error) {
                    let response = xhr.responseJSON;
                    users_list.innerHTML = `
                        <div class="w-100 card p-4 mb-4">
                                <span class="fw-bold text-center">${response.message}</span>
                        </div>
                    `;
                    console.error("Error fetching users:", error);
                }
                            
            });
        }
        fetchUsers();

        // Add User
        let frmAddUser = document.getElementById("frmAddUser");
        frmAddUser.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let addUserAlert = document.getElementById("updateUserAlert");
                    let addUserAlertMsg = document.getElementById("updateUserAlertMsg");
                    addUserAlertMsg.textContent = response.message;
                    addUserAlert.classList.remove("alert-info", "alert-danger");
                    addUserAlert.classList.add("alert-success");
                    addUserAlert.classList.remove("d-none");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    console.log(response);
                    let addUserAlert = document.getElementById("updateUserAlert");
                    let addUserAlertMsg = document.getElementById("updateUserAlertMsg");
                    addUserAlertMsg.textContent = response.responseJSON.message;
                    addUserAlert.classList.remove("alert-info", "alert-success");
                    addUserAlert.classList.add("alert-danger");
                    addUserAlert.classList.remove("d-none");
                    setTimeout(() => {
                        addUserAlert.classList.add("d-none");
                    }, 2000);
                }
            });
        });

        // Update Loader
        $(document).on('click', '[id^="user-update-"]', function (e) {
            e.preventDefault();
            const update_idUser = this.id.split("-")[2];
            console.log("Update User ID:", update_idUser);  
            let params = new URLSearchParams({
                user: true,
                idUser: update_idUser
            });
            $.ajax({
                url: '/api/v1.php?' + params,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let user = response.users[0];
                    let form = document.getElementById("frmupdateUser");
                    form.reset();
                    form.classList.remove("was-validated");
                    form.elements["action"].value = "user_update";
                    document.getElementById("update_username_view").textContent = user.username;
                    form.elements["update_user_id"].value = user.idUser;
                    form.elements["update_username"].value = user.username;
                    form.elements["update_email"].value = user.email;
                },
                error: function(response) {
                    console.log(response);
                }
            });
        
        });
        
        let frmupdateUser = document.getElementById("frmupdateUser");
        frmupdateUser.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let updateUserAlert = document.getElementById("updateUserAlert");
                    let updateUserAlertMsg = document.getElementById("updateUserAlertMsg");
                    updateUserAlertMsg.textContent = response.message;
                    updateUserAlert.classList.remove("alert-info", "alert-danger");
                    updateUserAlert.classList.add("alert-success");
                    updateUserAlert.classList.remove("d-none");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    let updateUserAlert = document.getElementById("updateUserAlert");
                    let updateUserAlertMsg = document.getElementById("updateUserAlertMsg");
                    updateUserAlertMsg.textContent = response.responseJSON.message;
                    updateUserAlert.classList.remove("alert-info", "alert-success");
                    updateUserAlert.classList.add("alert-danger");
                    updateUserAlert.classList.remove("d-none");
                    setTimeout(() => {
                        updateUserAlert.classList.add("d-none");
                    }, 2000);
                }
            });
        });

        // Delete Loader
        $(document).on('click', '[id^="user-delete-"]', function (e) {
            e.preventDefault();
            const delete_idCategory = this.id.split("-")[2];
            let params = new URLSearchParams({
                user: true,
                idUser: delete_idCategory
            });
            console.log("Delete User ID:", delete_idCategory);
            $.ajax({
                url: '/api/v1.php?' + params,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let user = response.users[0];
                    document.getElementById("delete_user_id").value = user.idUser;
                    document.getElementById("delete_username_view").textContent = user.username;
                    document.getElementById("delete_username").textContent = user.username;
                },
                error: function(response) {
                    console.log(response);
                }
            });
        });

        let frmDeleteCategory = document.getElementById("frmdeleteUser");
        frmDeleteCategory.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            console.log("Form Data:", formData.get("delete_user_id"));
            let deleteUserAlert = document.getElementById("deleteUserAlert");
            let deleteUserAlertMsg = document.getElementById("deleteUserAlertMsg");
            deleteUserAlert.classList.add("d-none");
            deleteUserAlertMsg.textContent = "";
            
            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    deleteUserAlertMsg.textContent = response.message;
                    deleteUserAlert.classList.remove("alert-info", "alert-danger");
                    deleteUserAlert.classList.add("alert-success");
                    deleteUserAlert.classList.remove("d-none");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    console.log(response);
                    let deleteUserAlert = document.getElementById("deleteCategoryAlert");
                    let deleteUserAlertMsg = document.getElementById("deleteCategoryAlertMsg");
                    deleteUserAlertMsg.textContent = response.responseJSON.message;
                    deleteUserAlert.classList.remove("alert-info", "alert-success");
                    deleteUserAlert.classList.add("alert-danger");
                    deleteUserAlert.classList.remove("d-none");
                }
            });
        });
    </script>
</html>
