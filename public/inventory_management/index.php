<?php
include __DIR__ . "/../../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$page_name = "Inventory Management";

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

                </div>
                <div class="row mb-4" id="order_list">
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

    <!-- Success Order -->
    <div class="modal fade" id="successOrderModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmCancelOrder">
                <input type="hidden" name="action" value="order_cancel">
                <input type="hidden" name="cancel_order_id" id="cancel_order_id">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="cancelOrderTitle">
                            Confirm Claim Order
                        </h5>
                    </div>
                    <div class="modal-body p-4 px-6" id="cancelOrderBody">
                        <div class="mb-4">
                            <div class="mb-2">
                                <div class="alert alert-danger w-100 d-none" id="cancelOrderAlert">
                                    <span id="cancelOrderAlertMsg"></span>
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
                                Do you want to mark as claim this Order?
                            </label>
                        </div>
                        <div class="modal-footer" id="cancelOrderFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger" id="btncancelOrder">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cancel Order -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog"  aria-hidden="true" style="overflow-y:auto">
        <div class="modal-dialog" role="document">
            <form method="POST" id="frmCancelOrder">
                <input type="hidden" name="action" value="order_cancel">
                <input type="hidden" name="cancel_order_id" id="cancel_order_id">
                <div class="modal-content">
                    <div class="modal-header bg-orange-custom d-flex justify-content-start">
                        <h5 class="modal-title fw-bold" id="cancelOrderTitle">
                            Confirm Cancel Order
                        </h5>
                    </div>
                    <div class="modal-body p-4 px-6" id="cancelOrderBody">
                        <div class="mb-4">
                            <div class="mb-2">
                                <div class="alert alert-danger w-100 d-none" id="cancelOrderAlert">
                                    <span id="cancelOrderAlertMsg"></span>
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
                                Do you want to mark as cancel this order?
                            </label>
                        </div>
                        <div class="modal-footer" id="cancelOrderFooter">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger" id="btncancelOrder">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        var page = 1;
        var paginate = 10;
        var order_status = "";
        var delivery_type = ""; 

        let order_list = document.getElementById("order_list");
        function fetchOrderList() {
            let param = new URLSearchParams({
                orders: true,
                page: page,
                paginate: paginate,
                status: order_status,
                delivery_type: delivery_type,
                claim: 0
            });
            $.ajax({
                url: '/api/v1.php?' + param,
                type: 'GET',
                success: function(response) {
                    let orders = response.orders;
                    console.log(response);
                    order_list.innerHTML = "";
                    orders.forEach(order => {
                        if (order.status == "pending") {
                            order_list.innerHTML += `
                                <div class="col-12 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="text-start">
                                                <h5 class="fw-bolder">${order.product_name}<div class="badge badge-suceess">Deliver</divf></h5>
                                                <div class="badge bg-info">${order.status}</div>
                                                <p>Order Date: ${order.created_at}</p>
                                                <p>Total Amount: ${order.total_payment}</p>
                                                <div class="row">
                                                    <div class="col">
                                                        <a class="btn btn-secondary" href="/public/product/?id=${order.product_ids}">View Product</a>
                                                        <a class="btn btn-secondary" id="order-success-${order.idOrder}" data-bs-toggle="modal" data-bs-target="#successOrderModal">Claim</a>
                                                        <a class="btn btn-danger" id="order-cancel-${order.idOrder}" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">Cancel Order</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else if (order.status == "success") {
                            order_list.innerHTML += `
                                <div class="col-12 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="text-start">
                                                <h5 class="fw-bolder">${order.product_name}<div class="badge badge-suceess">Deliver</divf></h5>
                                                <div class="badge bg-success">${order.status}</div>
                                                <p>Order Date: ${order.created_at}</p>
                                                <p>Total Amount: ${order.total_payment}</p>
                                                <div class="row">
                                                    <div class="col">
                                                        <a class="btn btn-secondary" href="/public/product/?id=${order.product_ids}">View Product</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else if (order.status == "cancelled") {
                            order_list.innerHTML += `
                                <div class="col-12 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="text-start">
                                                <h5 class="fw-bolder">${order.product_name}<div class="badge badge-suceess">Deliver</divf></h5>
                                                <div class="badge bg-danger">${order.status}</div>
                                                <p>Order Date: ${order.created_at}</p>
                                                <p>Total Amount: ${order.total_payment}</p>
                                                <div class="row">
                                                    <div class="col">
                                                        <a class="btn btn-secondary" href="/public/product/?id=${order.product_ids}">View Product</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    });
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    let response = xhr.responseJSON;
                    order_list.innerHTML = `
                        <div class="col-12 card p-4">
                                <span class="text-center fw-bold">${response.message}</span>
                        </div>
                    `;
                }
            });
        }
        fetchOrderList();

        $(document).on("click", "[id^='order-cancel-']", function() {
            let orderId = this.id.split("-")[2];
            console.log(orderId);

            let cancelOrderAlert = document.getElementById("cancelOrderAlert");
            let cancelOrderAlertMsg = document.getElementById("cancelOrderAlertMsg");
            cancelOrderAlert.classList.add("d-none");

            let param = new URLSearchParams({
                orders: true,
                id: orderId,
                claim: 0,
                status: "pending"
            });
            $.ajax({
                url: '/api/v1.php?'+ param,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    let cancel_order_id = document.getElementById("cancel_order_id");
                    cancel_order_id.value = orderId;
                },
                error: function(xhr, status, error) {
                    let response = xhr.responseJSON;
                    
                }
            })
        });

        let frmCancelOrder = document.getElementById("frmCancelOrder");
        frmCancelOrder.addEventListener("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: '/api/v1.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    let cancelOrderAlert = document.getElementById("cancelOrderAlert");
                    let cancelOrderAlertMsg = document.getElementById("cancelOrderAlertMsg");
                    cancelOrderAlertMsg.textContent = response.message;
                    cancelOrderAlert.classList.remove("alert-info", "alert-danger");
                    cancelOrderAlert.classList.add("alert-success");
                    cancelOrderAlert.classList.remove("d-none");
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                },
                error: function(response) {
                    let cancelOrderAlert = document.getElementById("cancelOrderAlert");
                    let cancelOrderAlertMsg = document.getElementById("cancelOrderAlertMsg");
                    cancelOrderAlertMsg.textContent = response.responseJSON.message;
                    cancelOrderAlert.classList.remove("alert-info", "alert-success");
                    cancelOrderAlert.classList.add("alert-danger");
                    cancelOrderAlert.classList.remove("d-none");
                    setTimeout(() => {
                        cancelOrderAlert.classList.add("d-none");
                    }, 2000);
                }
            });
        });
        // View Order
        // let frmupdateOrder = document.getElementById("frmupdateOrder");
        // frmupdateOrder.addEventListener("submit", function(e) {
        //     e.preventDefault();
        //     let formData = new FormData(this);
        //     let update_cat_id = formData.get("update_cat_id");
        //     let update_cat_name = formData.get("update_cat_name");
        //     let update_cat_description = formData.get("update_cat_description");

        //     $.ajax({
        //         url: '/api/v1.php',
        //         type: 'POST',
        //         data: JSON.stringify({
        //             action: "Order_update",
        //             idOrder: update_cat_id,
        //             Order_name: update_cat_name,
        //             Order_description: update_cat_description
        //         }),
        //         success: function(response) {
        //             let updateOrderAlert = document.getElementById("updateOrderAlert");
        //             let updateOrderAlertMsg = document.getElementById("updateOrderAlertMsg");
        //             updateOrderAlertMsg.textContent = response.message;
        //             updateOrderAlert.classList.remove("alert-info", "alert-danger");
        //             updateOrderAlert.classList.add("alert-success");
        //             updateOrderAlert.classList.remove("d-none");
        //             setTimeout(() => {
        //                 location.reload();
        //             }, 2000);
        //         },
        //         error: function(response) {
        //             let updateOrderAlert = document.getElementById("updateOrderAlert");
        //             let updateOrderAlertMsg = document.getElementById("updateOrderAlertMsg");
        //             updateOrderAlertMsg.textContent = response.responseJSON.message;
        //             updateOrderAlert.classList.remove("alert-info", "alert-success");
        //             updateOrderAlert.classList.add("alert-danger");
        //             updateOrderAlert.classList.remove("d-none");
        //             setTimeout(() => {
        //                 updateOrderAlert.classList.add("d-none");
        //             }, 2000);
        //         }
        //     });
        // });
    </script>
</html>
