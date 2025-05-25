

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand"><?php echo $project_name?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link" id="nav-home" href="/public">Home</a></li>
                <li class="nav-item"><a class="nav-link" id="nav-about" href="/public/about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" id="nav-contact" href="/public/contact">Contact Us</a></li>
                <?php if (isset($_COOKIE['auth_token'])) : ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/public/dashboard">All Products</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li>
                <?php endif;?>
            </ul>
            <div class="d-flex">
                <?php if (isset($_COOKIE['auth_token']) && $role_type == "standard") : ?>
                <div class="d-flex" id="standard_cart">
                    <button class="btn btn-outline-dark" type="button" id="btnCart">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill" id="cart_total">0</span>
                    </button>
                        
                    <script>
                            let this_user_id = "<?php echo $user_id?>";
                            let cart_total = document.getElementById("cart_total");
                            let btnCart = document.getElementById("btnCart");
                            btnCart.addEventListener("click", function(e) {
                                window.location.href = "/public/cart";
                            });
                            function fetchCartCount() {
                                let params = new URLSearchParams({
                                    cart: true,
                                    user_id: this_user_id,
                                    total: true
                                })
                                $.ajax({
                                    url: '/api/v1.php?' + params,
                                    type: 'GET',
                                    success: function(response) {
                                        console.log(response);
                                        let cart_count = response.cart[0].total_carts;
                                        cart_total.textContent = cart_count;
                                    },
                                });
                            };
                            fetchCartCount();
                    </script>
                <?php endif;?>
            </div>
                <?php if (isset($_COOKIE["auth_token"])):?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">My Profile</a></li>
                            <?php if (isset($_COOKIE['auth_token']) && $role_type == "standard"):?>
                            <!-- <li><a class="dropdown-item" href="#!">My Wishlist</a></li> -->
                            <li><a class="dropdown-item" href="/public/orders">My Orders</a></li>
                            <?php endif;?>
                            <?php if (isset($_COOKIE['auth_token']) && $role_type == "admin"):?>
                            <li><a class="dropdown-item" href="/public/product_management">Product Managment</a></li>
                            <li><a class="dropdown-item" href="/public/category_management">Category Managment</a></li>
                            <?php endif;?>
                            <li><a class="dropdown-item" href="#!">Settings</a></li>
                            <li><hr class="dropdown-divider" /></li>

                            <li><a class="dropdown-item" id="btnLogout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                <?php else:?>
                <div class="d-flex btn-group">
                    <a class="btn btn-outline-dark" href="/public/login">Login</a>
                    <a class="btn btn-outline-dark" href="/public/register">Register</a>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</nav>
<script>
    let btnLogout = document.getElementById("btnLogout");
    btnLogout.addEventListener("click", function(e) { // Corrected typo here
        e.preventDefault();
        $.ajax({
            url: "/api/v1.php",
            type: "POST",
            data: JSON.stringify({
                action: "logout",
            }),
            success: function(response) {
                alert(response.message);
                // Optionally redirect to login page after logout
                window.location.href = "/public/login";
            },
            error: function(response) {
                alert(response.responseJSON.message);
            }
        });
    });
</script>