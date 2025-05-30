<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
require_once __DIR__ . "/../base.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($_POST['action']) && !isset($data->action)) {
        echo json_encode(array(
            "status" => "error",
            "status_code" => 400,
            "message" => "Invalid action"
        ));
        http_response_code(400);
        $pdo = null;
        exit;
    }

    $action = isset($data->action) ? $data->action : $_POST['action'];

    // Login
    if ($_POST['action'] == "login") {
        if (!isset($_POST['auth_username']) || !isset($_POST['auth_password']) ||
            empty($_POST['auth_username']) || empty($_POST['auth_password']))
        {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input username and password"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $username = $_POST['auth_username'];
        $password = $_POST['auth_password'];

        // Logging In
        try {
            $cur = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $cur->execute(array(
                ":username" => $username
            ));
            $user = $cur->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Generate JWT Token
                    $payload = array(
                        "user_id" => $user['idUser'],
                        "username" => $user['username'],
                        "role_type" => $user['role_type'],
                        "user_image" => $user['user_image'] ?? null,
                        "email" => $user['email'] ?? null,
                        "iat" => time(),
                        "exp" => time() + (60 * 60 * 24) // 1 day expiration
                    );
                    $jwt = JWT::encode($payload, $jwt_secret_key, $jwt_algorithm);

                    // Set Cookie
                    setcookie("auth_token", $jwt, time() + (60 * 60 * 24), "/", "", false, true);

                    echo json_encode(array(
                        "status" => "success",
                        "status_code" => 200,
                        "message" => "Login successful",
                        "token" => $jwt
                    ));
                    http_response_code(200);
                    $pdo = null;
                    exit;
                } else {
                    echo json_encode(array(
                        "status" => "error",
                        "status_code" => 401,
                        "message" => "Invalid password"
                    ));
                    http_response_code(401);
                    $pdo = null;
                    exit;
                }
            } else {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 404,
                    "message" => "User not found"
                ));
                http_response_code(404);
                $pdo = null;
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => "Internal Server Error: " . $e->getMessage()
            ));
            http_response_code(500);
            $pdo = null;
            exit;
        }
    
    // Register
    } else if ($_POST['action'] == "register") {
        if (!isset($_POST['reg_username']) || !isset($_POST['reg_email']) || !isset($_POST['reg_password']) ||
            empty($_POST['reg_username']) || empty($_POST['reg_email']) || empty($_POST['reg_password']))
        {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input username, email and password"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $username = $_POST['reg_username'];
        $email = $_POST['reg_email'];
        $password = $_POST['reg_password'];

        // Check if user already exists
        try {
            $cur = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $cur->execute(array(
                ":username" => $username,
                ":email" => $email
            ));
            $user = $cur->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "User already exists"
                ));
                http_response_code(409);
                $pdo = null;
                exit;
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $cur = $pdo->prepare("INSERT INTO users (username, email, password, role_type) VALUES (:username, :email, :password, 'standard')");
                $cur->execute(array(
                    ":username" => $username,
                    ":email" => $email,
                    ":password" => $hashed_password
                ));

                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "User registered successfully"
                ));
                http_response_code(201);
                $pdo = null;
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => "Internal Server Error: " . $e->getMessage()
            ));
            http_response_code(500);
            $pdo = null;
            exit;
        }
    // Logout
    } else if ($_POST['action'] == "logout") {
        // Clear the cookie
        setcookie("auth_token", "", time() - 3600, "/", "", false, true);
        echo json_encode(array(
            "status" => "success",
            "status_code" => 200,
            "message" => "Logout successful"
        ));
        http_response_code(200);
        $pdo = null;
        exit;
    
    // Category: Create
    } else if ($_POST['action'] == "category_create") {
        if (!isset($_POST['add_category_name']) ||
            empty($_POST['add_category_name']))
        {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input category name"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $category_name = $_POST['add_category_name'];
        $category_description = $_POST['add_category_description'] ?? null;


        try {
            // Check category existence
            $cur = $pdo->prepare("SELECT * FROM categories WHERE category_name = :category_name");
            $cur->execute(array(
                ":category_name" => $category_name,
            ));
            $category_check = $cur->fetch(PDO::FETCH_ASSOC);
            if ($category_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "Category already exists"
                ));
                http_response_code(409);
                $pdo = null;
                exit;
            }

            $cur = $pdo->prepare("INSERT INTO categories (category_name, category_description) VALUES (:category_name, :category_description)");
            $cur->execute(array(
                ":category_name" => $category_name,
                ":category_description" => $category_description
            ));
            echo json_encode(array(
                "status" => "success",
                "status_code" => 201,
                "message" => "Category created successfully"
            ));
            http_response_code(201);
            $pdo = null;
            exit;
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
            $pdo = null;
            exit;
        }
    
    // Category: Update
    } else if ($_POST['action'] == "category_update") {
        if (!isset($_POST['update_category_id']) || !isset($_POST['update_category_name']) ||
            empty($_POST['update_category_id']) || empty($_POST['update_category_name']))
        {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input category id and name"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $idCategory = $_POST['update_category_id'];
        $category_name = $_POST['update_category_name'];
        $category_description = $_POST['update_category_description'] ?? null;

        try {
            // Check category existence
            $cur = $pdo->prepare("SELECT * FROM categories WHERE idCategory = :idCategory");
            $cur->execute(array(
                ":idCategory" => $idCategory,
            ));
            $category_check = $cur->fetch(PDO::FETCH_ASSOC);
            if (!$category_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 404,
                    "message" => "Category not found"
                ));
                http_response_code(404);
                $pdo = null;
                exit;
            }
            
            $cur = $pdo->prepare("UPDATE categories
                                    SET category_name = :category_name, category_description = :category_description
                                    WHERE idCategory = :idCategory");
            $cur->execute(array(
                ":category_name" => $category_name,
                ":category_description" => $category_description,
                ":idCategory" => $idCategory
            ));

            // Changes by afftected row
            if ($cur->rowCount() > 0) {
                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 200,
                    "message" => "Category updated successfully"
                ));
                http_response_code(200);
            } else {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 403,
                    "message" => "No changes made"
                ));
                http_response_code(403);
            }
            $pdo = null;
            exit;
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
            $pdo = null;
            exit;
        }
    } else if ($_POST['action'] == "category_delete") {
        if (!isset($_POST['delete_category_id']) || empty($_POST['delete_category_id'])) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input category id"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }
        $idCategory = $_POST['delete_category_id'];

        $sql_cmd = "DELETE FROM categories WHERE idCategory = :idCategory";

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        $cur->bindValue(":idCategory", $idCategory);
        $cur->execute();

        if ($cur->rowCount() > 0) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "message" => "Category deleted successfully"
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "Category not found"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
        }

    } else if ($_POST['action'] == "product_create") {
        if (!isset($_POST['add_product_code']) ||
            !isset($_POST['add_product_name']) ||
            !isset($_POST['add_product_category']) ||
            !isset($_POST['add_product_price_now']) ||
            !isset($_POST['add_product_price_original']) ||
            !isset($_POST['add_product_description']) ||
            !isset($_POST['add_product_quantity'])
        ){
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input product code, name, category, price now, price original, description and quantity"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }
        $code = $_POST['add_product_code'];
        $name = $_POST['add_product_name'];
        $category = $_POST['add_product_category'];
        $price_now = $_POST['add_product_price_now'];
        $price_original = $_POST['add_product_price_original'];
        $description = $_POST['add_product_description'];
        $quantity = $_POST['add_product_quantity'];

        try {
            // Check product existence
            $cur = $pdo->prepare("SELECT *
                                FROM products
                                WHERE product_code = :code");
            $cur->execute(array(
                ":code" => $code,
            ));
            $product_check = $cur->fetch(PDO::FETCH_ASSOC);
            if ($product_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "Product already exists"
                ));
                http_response_code(409);
                $pdo = null;
                exit;
            }

            $cur = $pdo->prepare("INSERT INTO products (product_code, product_name, product_category, product_price_now, product_price_original, product_description, product_quantity)
                                VALUES (:code, :name, :category, :price_now, :price_original, :description, :quantity)");
            $cur->execute(array(
                ":code" => $code,
                ":name" => $name,
                ":category" => $category,
                ":price_now" => $price_now,
                ":price_original" => $price_original,
                ":description" => $description,
                ":quantity" => $quantity
            ));

            // Image
            $image_path = "";
            $extensions = ["jpg", "jpeg", "png", "gif"];

            $image_name = $_FILES['add_product_image']['name'] ?? null;
            $image_tmp_name = $_FILES['add_product_image']['tmp_name'] ?? null;
            $image_type = $_FILES['add_product_image']['type'] ?? null;
            $image_size = $_FILES['add_product_image']['size'] ?? null;

            $image_file_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            // make folder
            if (!is_dir("../data/image/" . $idProduct)) {
                mkdir("../data/image/" . $idProduct, 0777, true);
            }
            $db_media = "../data/image/" . $idProduct . "/";

            if (!in_array($image_file_type, $extensions)) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 400,
                    "message" => "Invalid image file type"
                ));
                http_response_code(400);
                $pdo = null;
                exit;
            }

            // Upload image
            $target_path = $db_media . $image_name;

            if (!move_uploaded_file($image_tmp_name, $target_path)) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 500,
                    "message" => "Failed to move uploaded file"
                ));
                http_response_code(500);
                $pdo = null;
                exit;
            }

            // Save to DB
            $product_image = "/data/image/" . $idProduct . "/" . $image_name;

            $cur = $pdo->prepare("UPDATE products
                                    SET product_image = :product_image
                                    WHERE product_code = :code");
            $cur->execute(array(
                ":product_image" => $product_image,
                ":code" => $code
            ));
            if ($cur->rowCount() <= 0) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 500,
                    "message" => "Failed to save product image"
                ));
                http_response_code(500);
                $pdo = null;
                exit;
            }
            echo json_encode(array(
                "status" => "success",
                "status_code" => 201,
                "message" => "Product created successfully"
            ));
            http_response_code(201);
            $pdo = null;
            exit;
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
            $pdo = null;
            exit;
        }
    } else if ($_POST['action'] == "product_update") {
        if (!isset($_POST['update_product_id']) || !isset($_POST['update_product_name']) ||
            empty($_POST['update_product_id']) || empty($_POST['update_product_name']))
        {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input product id and name"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $idProduct = $_POST['update_product_id'];
        $product_code = $_POST['update_product_code'] ?? null;
        $product_name = $_POST['update_product_name'];
        $product_category = $_POST['update_product_category'];
        $product_price_now = $_POST['update_product_price_now'];
        $product_price_original = $_POST['update_product_price_original'];
        $product_description = isset($_POST['update_product_description']) ? $_POST['update_product_description'] : null;
        $product_quantity = $_POST['update_product_quantity'];
        $product_product_image = $_POST['update_product_image'] ?? null;

        // echo json_encode(array(
        //     "status" => "debug",
        //     "status_code" => 200,
        //     "message" => "Debugging product update",
        //     "data" => array(
        //         "idProduct" => $idProduct,
        //         "product_code" => $product_code,
        //         "product_name" => $product_name,
        //         "product_category" => $product_category,
        //         "product_price_now" => $product_price_now,
        //         "product_price_original" => $product_price_original,
        //         "product_description" => $product_description,
        //         "product_quantity" => $product_quantity,
        //         "product_image" => $product_product_image
        //     )
        //     ));
        //     exit;
        try {
            // Check product existence
            $cur = $pdo->prepare("SELECT * FROM products WHERE idProduct = :idProduct");
            $cur->execute(array(
                ":idProduct" => $idProduct,
            ));
            $product_check = $cur->fetch(PDO::FETCH_ASSOC);
            if (!$product_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 404,
                    "message" => "Product not found"
                ));
                http_response_code(404);
                $pdo = null;
                exit;
            }
            
            $cur = $pdo->prepare("UPDATE products
                                    SET product_name = :product_name, 
                                        product_description = :product_description, 
                                        product_price_now = :product_price_now, 
                                        product_price_original = :product_price_original, 
                                        product_quantity = :product_quantity,
                                        product_code = :product_code,
                                        product_category = :product_category,
                                        product_image = :product_image
                                    WHERE idProduct = :idProduct");


            // Image
            $image_path = "";
            $extensions = ["jpg", "jpeg", "png", "gif"];

            $image_name = $_FILES['update_product_image']['name'] ?? null;
            $image_tmp_name = $_FILES['update_product_image']['tmp_name'] ?? null;
            $image_type = $_FILES['update_product_image']['type'] ?? null;
            $image_size = $_FILES['update_product_image']['size'] ?? null;

            $image_file_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

            // make folder
            if (!is_dir("../data/image/" . $idProduct)) {
                mkdir("../data/image/" . $idProduct, 0777, true);
            }
            $db_media = "../data/image/" . $idProduct . "/";

            if (!in_array($image_file_type, $extensions)) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 400,
                    "message" => "Invalid image file type"
                ));
                http_response_code(400);
                $pdo = null;
                exit;
            }

            // Upload image
            $target_path = $db_media . $image_name;

            if (!move_uploaded_file($image_tmp_name, $target_path)) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 500,
                    "message" => "Failed to move uploaded file"
                ));
                http_response_code(500);
                $pdo = null;
                exit;
            }

            // Save to DB
            $product_image = "/data/image/" . $idProduct . "/" . $image_name;

            $cur->execute(array(
                ":product_name" => $product_name,
                ":product_description" => $product_description,
                ":product_price_now" => $product_price_now,
                ":product_price_original" => $product_price_original,
                ":product_quantity" => $product_quantity,
                ":idProduct" => $idProduct,
                ":product_code" => $product_code,
                ":product_category" => $product_category,
                ":product_image" => $product_image
            ));

            // Changes by afftected row
            if ($cur->rowCount() > 0) {
                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 200,
                    "message" => "Product updated successfully"
                ));
                http_response_code(200);
                $pdo = null;
                exit;
            } else {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 403,
                    "message" => "No changes made"
                ));
                http_response_code(403);
                $pdo = null;
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
            $pdo = null;
            exit;
        }
    } else if ($_POST['action'] == "product_delete") {
        if (!isset($_POST['delete_product_id']) || empty($_POST['delete_product_id'])) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input product id"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }
        $idProduct = $_POST['delete_product_id'];

        $sql_cmd = "DELETE FROM products WHERE idProduct = :idProduct";

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        $cur->bindValue(":idProduct", $idProduct);
        $cur->execute();

        if ($cur->rowCount() > 0) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "message" => "Product deleted successfully"
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "Product not found"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
        }
    } else if ($_POST['action'] == "checkout-make") {
        // if (!isset($_POST['checkout_cart_id']) && empty($_POST['checkout_cart_id']) 
            
        // ) {
        //     echo json_encode(array(
        //         "status" => "error",
        //         "status_code" => 400,
        //         "message" => "Please input cart id"
        //     ));
        //     http_response_code(400);
        //     $pdo = null;
        //     exit;
        // }
        // $cart_id = $_POST['checkout_cart_id'];
        
        // echo json_encode(array(
        //     "status" => "success",
        //     "status_code" => 200,
        //     "message" => "Checkout successful"
        // ));
        // http_response_code(200);
        // $pdo = null;
        if (isset($_COOKIE['auth_token'])) {
            try {
                $this_user = JWT::decode($_COOKIE["auth_token"], new Key($jwt_secret_key, 'HS256'));
            } catch (Exception $e) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 401,
                    "message" => "Invalid token"
                ));
                http_response_code(401);
                $pdo = null;
                exit;
            }
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 401,
                "message" => "Unauthorized access"
            ));
            http_response_code(401);
            $pdo = null;
            exit;
        }

        $user_id = $this_user->user_id;
        $role_type = $this_user->role_type;

        if ($role_type != 'standard') {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 403,
                "message" => "Forbidden: Only standard users can make purchases"
            ));
            http_response_code(403);
            $pdo = null;
            exit;
        }

        if ($_POST['checkout_mode'] == "buy") {
            if (!isset($_POST['product_id']) || empty($_POST['product_id']) ||
                !isset($_POST['product_quantity']) || empty($_POST['product_quantity'])) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 400,
                    "message" => "Please input product id and quantity"
                ));
                http_response_code(400);
                $pdo = null;
                exit;
            }
            $pdo->beginTransaction();
            try {

                $customer_name = $_POST['customer_name'];
                $customer_address = $_POST['customer_address'];
                $customer_phone = $_POST['customer_phone'];
                $delivery_type = $_POST['delivery_type'];
                $payment_type = $_POST['payment_type'];
        
                $product_id = $_POST['product_id'];
                $product_quantity = $_POST['product_quantity'];

                $sql_cmd = "SELECT *
                            FROM products
                            WHERE idProduct = :idProduct";
                $cur = $pdo->prepare($sql_cmd);
                $cur->bindValue(":idProduct", $product_id, PDO::PARAM_INT);
                $cur->execute();
                $product = $cur->fetch(PDO::FETCH_ASSOC);
                if (!$product) {
                    echo json_encode(array(
                        "status" => "error",
                        "status_code" => 404,
                        "message" => "Product not found"
                    ));
                    http_response_code(404);
                    $pdo = null;
                    exit;
                }
                $product_quantity = $_POST['product_quantity'];
                if ($product['product_quantity'] < $product_quantity) {
                    echo json_encode(array(
                        "status" => "error",
                        "status_code" => 400,
                        "message" => "Insufficient product quantity"
                    ));
                    http_response_code(400);
                    $pdo = null;
                    exit;
                }

                // Create order so

                $cur = $pdo->prepare("INSERT INTO orders (customer_name, customer_address, customer_phone, deliver_type, payment_type, `status`, user_id, checkout_type, product_quantity, product_ids)
                                        VALUES (:customer_name, :customer_address, :customer_phone, :delivery_type, :payment_type, 'pending', :user_id, :checkout_type, :product_quantity, :product_id)");
                $cur->execute(array(
                    ":customer_name" => $customer_name,
                    ":customer_address" => $customer_address,
                    ":customer_phone" => $customer_phone,
                    ":delivery_type" => $delivery_type,
                    ":payment_type" => $payment_type,
                    ":user_id" => $user_id,
                    ":checkout_type" => "buy",
                    ":product_quantity" => $product_quantity,
                    ":product_id" => $product_id
                ));
                $order_id = $pdo->lastInsertId();

                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "Order created successfully",
                    "order_id" => $order_id,
                    "product" => array(
                        "idProduct" => $product['idProduct'],
                        "product_code" => $product['product_code'],
                        "product_name" => $product['product_name'],
                        "product_price_now" => $product['product_price_now'],
                        "product_price_original" => $product['product_price_original'],
                        "product_description" => $product['product_description'],
                        "product_quantity" => $product_quantity,
                        "total_price" => $product['product_price_now'] * $product_quantity,
                        "product_quantity" => $product['product_quantity'],
                        "product_quan_set" => $product_quantity,
                    )
                ));

                // Then decrease the product quantity
                $new_quantity = $product['product_quantity'] - $product_quantity;
                $cur = $pdo->prepare("UPDATE products
                                        SET product_quantity = :new_quantity
                                        WHERE idProduct = :idProduct");
                $cur->execute(array(
                    ":new_quantity" => $new_quantity,
                    ":idProduct" => $product_id
                ));
                if ($cur->rowCount() <= 0) {
                    echo json_encode(array(
                        "status" => "error",
                        "status_code" => 500,
                        "message" => "Failed to update product quantity"
                    ));
                    http_response_code(500);
                    $pdo = null;
                    exit;
                }
                // $pdo->rollBack();
                $pdo->commit();

            } catch (PDOException $e) {
            $pdo->rollBack();    
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 500,
                    "message" => $e->getMessage()
                ));
                http_response_code(500);
                $pdo = null;
                exit;
            }
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Invalid checkout mode"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }
        exit;
    } else if ($_POST['action'] == "user_create") {
        if (!isset($_POST['create_username']) || !isset($_POST['create_email']) ||
            !isset($_POST['create_password']) || empty($_POST['create_username']) || 
            empty($_POST['create_email']) || empty($_POST['create_password'])) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input username, email and password"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $username = $_POST['create_username'];
        $email = $_POST['create_email'];
        $password = $_POST['create_password'];

        // Check if user already exists
        try {
            $cur = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $cur->execute(array(
                ":username" => $username,
                ":email" => $email
            ));
            $user = $cur->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "User already exists"
                ));
                http_response_code(409);
                $pdo = null;
                exit;
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $cur = $pdo->prepare("INSERT INTO users (username, email, password, role_type) VALUES (:username, :email, :password, 'standard')");
                $cur->execute(array(
                    ":username" => $username,
                    ":email" => $email,
                    ":password" => $hashed_password
                ));

                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 201,
                    "message" => "User created successfully"
                ));
                http_response_code(201);
                $pdo = null;
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => "Internal Server Error: " . $e->getMessage()
            ));
            http_response_code(500);
            $pdo = null;
            exit;
        }
    } else if ($_POST['action'] == "user_update") {
        if (!isset($_POST['update_username']) || !isset($_POST['update_email']) ||
            !isset($_POST['update_password']) || empty($_POST['update_username']) || 
            empty($_POST['update_email']) || empty($_POST['update_password'])) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input username, email and password"
            ));
            $pdo = null;
            http_response_code(400);
            exit;
        }

        if (isset($_POST['update_profile_image'])) {

            $decode_jwt = JWT::decode($_COOKIE["auth_token"], new Key($jwt_secret_key, 'HS256'));
            $user_id = $decode_jwt->user_id;
            // Image
            $image_path = "";
            $extensions = ["jpg", "jpeg", "png", "gif"];
    
            $image_name = $_FILES['update_profile_image']['name'] ?? null;
            $image_tmp_name = $_FILES['update_profile_image']['tmp_name'] ?? null;
            $image_type = $_FILES['update_profile_image']['type'] ?? null;
            $image_size = $_FILES['update_profile_image']['size'] ?? null;
    
            $image_file_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    
            // Create folder if not exists
            if (!is_dir("../data/image/user/")) {
                mkdir("../data/image/user/", 0777, true);
            }
            $db_media = "../data/image/user/";
    
            if (!in_array($image_file_type, $extensions)) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 400,
                    "message" => "Invalid image file type"
                ));
                http_response_code(400);
                $pdo = null;
                exit;
            }
    
            // Upload image
            $target_path = $db_media . $image_name;
    
            if (!move_uploaded_file($image_tmp_name, $target_path)) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 500,
                    "message" => "Failed to move uploaded file"
                ));
                http_response_code(500);
                $pdo = null;
                exit;
            }
    
            // Save relative path to DB
            $user_image = "/data/image/user/" . $image_name;
        }

        $username = $_POST['update_username'];
        $email = $_POST['update_email'];
        $password = $_POST['update_password'];
        // Check username
        $sql_cmd = "SELECT *
                    FROM users u
                    WHERE u.username = :username OR u.email = :email
                    ";
        $cur = $pdo->prepare($sql_cmd);
        $cur->bindValue(":username", $username);
        $cur->bindValue(":email", $email);
        $cur->execute();

        $user = $cur->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "User not found"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
        }
        // Then update it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if (isset($_POST['update_profile_image'])) {
            $cur = $pdo->prepare("UPDATE users
                                    SET email = :email,
                                    password = :password,
                                    user_image = :user_image
                                    WHERE username = :username");
            $cur->execute(array(
                ":email" => $email,
                ":password" => $hashed_password,
                ":username" => $username,
                ":user_image" => $user_image
            ));
        } else {
            $cur = $pdo->prepare("UPDATE users
                                    SET email = :email,
                                    password = :password
                                    WHERE username = :username");
            $cur->execute(array(
                ":email" => $email,
                ":password" => $hashed_password,
                ":username" => $username
            ));
        }
        if ($cur->rowCount() > 0) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "message" => "User updated successfully"
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 403,
                "message" => "No changes made"
            ));
            http_response_code(403);
            $pdo = null;
            exit;
        }
    } else if ($_POST['action'] == "user_delete") {
        if (!isset($_POST['delete_user_id']) || empty($_POST['delete_user_id'])) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input user id"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        // Checking if only one admin account exists so decline the operation
        $sql_cmd = "SELECT COUNT(*) as total_admins
                    FROM users
                    WHERE role_type = 'admin'";
        $cur = $pdo->prepare($sql_cmd);
        $cur->execute();
        $total_admins = $cur->fetch(PDO::FETCH_ASSOC)['total_admins'];
        if ($total_admins <= 1) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 403,
                "message" => "Cannot delete the last admin account"
            ));
            http_response_code(403);
            $pdo = null;
            exit;
        }

        try {
            $user_id = $_POST['delete_user_id'];
    
            $sql_cmd = "DELETE FROM users WHERE idUser = :delete_user_id";
    
            // Execute
            $cur = $pdo->prepare($sql_cmd);
            $cur->bindValue(":delete_user_id", $user_id);
            $cur->execute();
    
            if ($cur->rowCount() > 0) {
                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 200,
                    "message" => "User deleted successfully"
                ));
                http_response_code(200);
                $pdo = null;
                exit;
            } else {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 404,
                    "message" => "User not found"
                ));
                http_response_code(404);
                $pdo = null;
                exit;
            }

        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
            $pdo = null;
            exit;
        }
    } else {
        echo json_encode(array(
            "status" => "error",
            "status_code" => 400,
            "message" => "Invalid action"
        ));
        http_response_code(400);
        $pdo = null;
        exit;
    }

} else if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['category'])) {
        $sql_cmd = "SELECT *
                    FROM categories
                    WHERE 1=1";
        $params = [];
        $types = "";

        // Category -> idCategory
        if (isset($_GET['id'])) {   
            $sql_cmd .= " AND idCategory = :idCategory";
            $params[":idCategory"] = $_GET['id'];
            $types .= "i";
        }

        // Category -> category_name
        if (isset($_GET['category_name'])) {
            $sql_cmd .= " AND category_name LIKE :category_name";
            $params[":category_name"] = "%" . $_GET['category_name'] . "%";
            $types .= "s";
        }

        // Category -> idCategory -> ASC / DESC
        if (isset($_GET['sort_id'])) {
            $sql_cmd .= " ORDER BY idCategory " . $_GET['sort'];
        }

        // Category -> category_name -> ASC / DESC
        if (isset($_GET['sort_name'])) {
            $sql_cmd .= " ORDER BY category_name " . $_GET['sort'];
        }

        // Category -> pagination
        if (isset($_GET['page']) && isset($_GET['paginate'])) {
            $page = $_GET['page'];
            $paginate = $_GET['paginate'];
            $offset = ($page - 1) * $paginate;
            $sql_cmd .= " LIMIT :offset, :paginate";
            $params[":offset"] = $offset;
            $params[":paginate"] = $paginate;
            $types .= "ii";
        }

        // Category -> total_categories
        if (isset($_GET['total'])) {
            $sql_cmd = "SELECT COUNT(*) as total_categories FROM categories";
            $cur = $pdo->prepare($sql_cmd);
            $cur->execute();
            $total_categories = $cur->fetch(PDO::FETCH_ASSOC);
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "total_categories" => $total_categories
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        }

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        foreach ($params as $key => $value) {
            $cur->bindValue($key, $value);
        }
        $cur->execute();
        $total_categories = $cur->rowCount();
        $categories = $cur->fetchAll(PDO::FETCH_ASSOC); // Use fetchAll for PDO
        
        if ($categories) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "categories" => $categories
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "No categories available"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
        }
    } else if (isset($_GET['product'])) {
        // if (!isset($_GET['id']) && !isset($_GET['code']) && !isset($_GET['name'])) {
        //     echo json_encode(array(
        //         "status" => "error",
        //         "status_code" => 400,
        //         "message" => "Please input product id"
        //     ));
        //     http_response_code(400);
        //     $pdo = null;
        // exit;
        // }

        $sql_cmd = "SELECT *
                    FROM products
                    WHERE 1=1";
        $params = [];
        $types = "";

        // Product -> idProduct
        if (isset($_GET['id'])) {   
            $sql_cmd .= " AND idProduct = :idProduct";
            $params[":idProduct"] = $_GET['id'];
            $types .= "i";
        }

        if (isset($_GET['code'])) {
            $sql_cmd .= " AND product_code = :code";
            $params[":code"] = $_GET['code'];
            $types .= "s";
        }

        // Product -> product_name
        if (isset($_GET['name'])) {
            $sql_cmd .= " AND product_name LIKE CONCAT(:product_name)";
            $params[":product_name"] = "%" . $_GET['name'] . "%";
            $types .= "s";
        }

        // Product -> category
        if (isset($_GET['category'])) {
            $sql_cmd .= " AND category = :category";
            $params[":category"] = $_GET['category'];
            $types .= "s";
        }

        // Product -> idProduct -> ASC / DESC
        if (isset($_GET['sort_id'])) {
            $sql_cmd .= " ORDER BY idProduct " . $_GET['sort'];
        }

        // Product -> product_name -> ASC / DESC
        if (isset($_GET['sort_name'])) {
            $sql_cmd .= " ORDER BY product_name " . $_GET['sort'];
        }

        // Product -> pagination
        if (isset($_GET['page']) && isset($_GET['paginate'])) {
            $page = $_GET['page'];
            $paginate = $_GET['paginate'];
            $offset = ($page - 1) * $paginate;
            $sql_cmd .= " LIMIT :offset, :paginate";
            $params[":offset"] = $offset;
            $params[":paginate"] = $paginate;
            $types .= "ii";
        }

        // Product -> total_products
        if (isset($_GET['total'])) {
            $sql_cmd = "SELECT COUNT(*) as total_products FROM products";
            $cur = $pdo->prepare($sql_cmd);
            $cur->execute();
            $total_products = $cur->fetch(PDO::FETCH_ASSOC);
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "total_products" => $total_products
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        }

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        foreach ($params as $key => $value) {
            $cur->bindValue($key, $value);
        }
        $cur->execute();
        $total_products = $cur->rowCount();
        $products = $cur->fetchAll(PDO::FETCH_ASSOC);
        if ($products) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "products" => $products
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "No products available"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
        }
    } else if (isset($_GET['user'])) {
        // if (!isset($_GET['idUser']) && !isset($_GET['username']) && !isset($_GET['email'])) {
        //     echo json_encode(array(
        //         "status" => "error",
        //         "status_code" => 400,
        //         "message" => "Please input user id"
        //     ));
        //     http_response_code(400);
        //     $pdo = null;
        //     exit;
        // }

        $sql_cmd = "SELECT idUser, username, email, status, role_type
                    FROM users
                    WHERE 1=1";
        $params = [];
        $types = "";

        // User -> idUser
        if (isset($_GET['idUser'])) {   
            $sql_cmd .= " AND idUser = :idUser";
            $params[":idUser"] = $_GET['idUser'];
            $types .= "i";
        }

        // User -> username
        if (isset($_GET['username'])) {
            $sql_cmd .= " AND username LIKE :username";
            $params[":username"] = "%" . $_GET['username'] . "%";
            $types .= "s";
        }

        // User -> email
        if (isset($_GET['email'])) {
            $sql_cmd .= " AND email LIKE :email";
            $params[":email"] = "%" . $_GET['email'] . "%";
            $types .= "s";
        }

        // User -> idUser -> ASC / DESC
        if (isset($_GET['sort_id'])) {
            $sql_cmd .= " ORDER BY idUser " . $_GET['sort'];
        }

        // User -> username -> ASC / DESC
        if (isset($_GET['sort_username'])) {
            $sql_cmd .= " ORDER BY username " . $_GET['sort'];
        }

        // User -> pagination
        if (isset($_GET['page']) && isset($_GET['paginate'])) {
            $page = $_GET['page'];
            $paginate = $_GET['paginate'];
            $offset = ($page - 1) * $paginate;
            $sql_cmd .= " LIMIT :offset, :paginate";
            $params[":offset"] = $offset;
            $params[":paginate"] = $paginate;
            $types .= "ii";
        }

        // User -> total_users
        if (isset($_GET['total'])) {
            $sql_cmd = "SELECT COUNT(*) as total_users FROM users";
            $cur = $pdo->prepare($sql_cmd);
            $cur->execute();
        }

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        foreach ($params as $key => $value) {
            $cur->bindValue($key, $value);
        }
        $cur->execute();
        $total_users = $cur->rowCount();
        $users = $cur->fetchAll(PDO::FETCH_ASSOC);
        if ($users) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "users" => $users
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "No users available"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
        }
    } else if (isset($_GET['cart'])) {
        $sql_cmd = "SELECT c.idCart, c.user_id, c.product_id, c.quantity, p.product_name, p.product_price_now
                    FROM cart_data c
                    JOIN products p ON c.product_id = p.idProduct
                    WHERE 1=1";
            $params = [];
            $types = "";

            if (isset($_GET['idCart'])) {   
            $sql_cmd .= " AND c.idCart = :idCart";
            $params[":idCart"] = $_GET['idCart'];
            $types .= "i";
            }

            if (isset($_GET['user_id'])) {
            $sql_cmd .= " AND c.user_id = :user_id";
            $params[":user_id"] = $_GET['user_id'];
            $types .= "i";
            }

            if (isset($_GET['product_id'])) {
            $sql_cmd .= " AND c.product_id = :product_id";
            $params[":product_id"] = $_GET['product_id'];
            $types .= "i";
            }

            // Cart -> idCart -> ASC / DESC
            if (isset($_GET['sort_id'])) {
            $sql_cmd .= " ORDER BY c.idCart " . $_GET['sort'];
            }

            // Cart -> user_id -> ASC / DESC
            if (isset($_GET['sort_user'])) {
            $sql_cmd .= " ORDER BY c.user_id " . $_GET['sort'];
            }

            // Cart -> product_id -> ASC / DESC
            if (isset($_GET['sort_product'])) {
            $sql_cmd .= " ORDER BY c.product_id " . $_GET['sort'];
            }

            // Cart -> pagination
            if (isset($_GET['page']) && isset($_GET['paginate'])) {
            $page = $_GET['page'];
            $paginate = $_GET['paginate'];
            $offset = ($page - 1) * $paginate;
            $sql_cmd .= " LIMIT :offset, :paginate";
            $params[":offset"] = $offset;
            $params[":paginate"] = $paginate;
            $types .= "ii";
            }

            // Cart -> total_cart for user
            if (isset($_GET['total'])) {
            $sql_cmd = "SELECT COUNT(c.idCart) as total_carts
                        FROM cart_data c
                        WHERE c.user_id = :user_id";
            }

            // Execute
            $cur = $pdo->prepare($sql_cmd);
            foreach ($params as $key => $value) {
            $cur->bindValue($key, $value);
            }
            $cur->execute();
            $total_cart = $cur->rowCount();
            $cart = $cur->fetchAll(PDO::FETCH_ASSOC);
            if ($cart) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "cart" => $cart
            ));
            http_response_code(200);
            $pdo = null;
            exit;

            } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "No cart available"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
            }

    } else if (isset($_GET['orders'])) {
        $sql_cmd = "SELECT o.idOrder, o.product_ids, o.status, o.customer_name, o.customer_address, o.deliver_type, o.payment_type, o.customer_phone, o.created_at, p.product_name, p.product_price_now
                    FROM orders o
                    LEFT JOIN products p ON o.product_ids = p.idProduct
                    WHERE 1=1";
        $params = [];
        $types = "";

        // Order -> idOrder
        if (isset($_GET['id'])) {   
            $sql_cmd .= " AND idOrder = :idOrder";
            $params[":idOrder"] = $_GET['id'];
            $types .= "i";
        }

        // Order -> status
        if (isset($_GET['status'])) {
            $sql_cmd .= " AND status LIKE :status";
            $params[":status"] = "%" . $_GET['status'] . "%";
            $types .= "s";
        }
        

        // Order -> user_id
        if (isset($_GET['user_id'])) {
            $sql_cmd .= " AND user_id = :user_id";
            $params[":user_id"] = $_GET['user_id'];
            $types .= "i";
        }

        // Order -> pagination
        if (isset($_GET['page']) && isset($_GET['paginate'])) {
            $page = $_GET['page'];
            $paginate = $_GET['paginate'];
            $offset = ($page - 1) * $paginate;
            $sql_cmd .= " LIMIT :offset, :paginate";
            $params[":offset"] = $offset;
            $params[":paginate"] = $paginate;
            $types .= "ii";
        }

        // Order -> total_orders
        if (isset($_GET['total'])) {
            $sql_cmd = "SELECT COUNT(*) as total_orders FROM orders";
            $cur = $pdo->prepare($sql_cmd);
            $cur->execute();
            $total_orders = $cur->fetch(PDO::FETCH_ASSOC);
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "total_orders" => $total_orders
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        }

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        foreach ($params as $key => $value) {
            $cur->bindValue($key, $value);
        }
        $cur->execute();
        $total_orders = $cur->rowCount();

        $orders = $cur->fetchAll(PDO::FETCH_ASSOC);
        if ($orders) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "orders" => $orders,
                "total_orders" => $total_orders
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "No orders available"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
        }
    }
} 

else 

{
    echo json_encode(array(
        "status" => "error",
        "status_code" => 405,
        "message" => "Method Not Allowed"
    ));
    http_response_code(405);
    $pdo = null;
    exit;
}