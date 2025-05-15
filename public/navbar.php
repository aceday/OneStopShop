

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand"><?php echo $project_name?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#!">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#!">Contact Us</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">All Products</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li>
            </ul>
            <div class="d-flex">
                <?php if (isset($_COOKIE['auth_token']) && $role_type == "standard") : ?>
                <div class="d-flex" id="standard_cart">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill">0</span>
                    </button>
                <?php endif;?>
            </div>
                <?php if (isset($_COOKIE["auth_token"])):?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">My Profile</a></li>
                            <?php if (isset($_COOKIE['auth_token']) && $role_type == "standard"):?>
                            <li><a class="dropdown-item" href="#!">My Wishlist</a></li>
                            <li><a class="dropdown-item" href="#!">My Orders</a></li>
                            <?php endif;?>
                            <?php if (isset($_COOKIE['auth_token']) && $role_type == "admin"):?>
                            <li><a class="dropdown-item" href="/public/product_management">Product Managment</a></li>
                            <?php endif;?>
                            <li><a class="dropdown-item" href="#!">Settings</a></li>
                            <li><hr class="dropdown-divider" /></li>

                            <li><a class="dropdown-item" id="btnLogout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
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