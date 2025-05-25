<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
require_once __DIR__ . "/../base.php";

use Firebase\JWT\JWT;


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->action)) {
        echo json_encode(array(
            "status" => "error",
            "status_code" => 400,
            "message" => "Bad Request"
        ));
        http_response_code(400);
        $pdo = null;
        exit;
    }

    $action = $data->action;

    if ($action == "login") {
        if (!isset($data->username) || !isset($data->password)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 401,
                "message" => "Please input username and password"
            ));
            http_response_code(401);
            $pdo = null;
            exit;
        }
        
        $username = $data->username;
        $password = $data->password;
        $remember = isset($data->remember) ? $data->remember : 0;
        
        try {
            $cur = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $cur->execute(array(
                ":username" => $username
            ));
            $user = $cur->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $username = $user["username"];
                $password_enc = $user["password"];
                $user_id = $user["idUser"];
                $role_type = $user["role_type"];
                if (password_verify($password, $password_enc)) {
                    $token_dec = array(
                        "user_id" => $user_id,
                        "username" => $username,
                        "role_type" => $role_type,
                        "iat" => time(),
                        "exp" => time() + (60 * 60 * 24 * 1) // 1 day
                    );
                    
                    $token = JWT::encode($token_dec, $jwt_secret_key, $jwt_algorithm);

                    // Set cookie if remember me is checked
                    if ($remember) {
                        setcookie("auth_token", $token, time() + (60 * 60 * 24 * 30), "/"); // 30 days
                    } else {
                        setcookie("auth_token", $token, time() + (60 * 60 * 24), "/"); // 1 day
                    }
                    echo json_encode(array(
                        "status" => "success",
                        "status_code" => 200,
                        "message" => "Login successful",
                        "token" => $token
                    ));
                    http_response_code(200);
                    $pdo = null;
                    exit;
                } else {
                    echo json_encode(array(
                        "status" => "error",
                        "status_code" => 401,
                        "message" => "Invalid username or password"
                    ));
                    http_response_code(401);
                    $pdo = null;
                    exit;
                }
            } else {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 401,
                    "message" => "Invalid username or password"
                ));
                http_response_code(401);
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

    } else if ($action == "register" || $action == "user_create") {
        if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input username, email and password"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $username = $data->username;
        $email = $data->email;
        $password = $data->password;

        if (empty($username) || empty($email) || empty($password)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Username, email and password cannot be empty"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        try {
            // Check user existence
            $cur = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $cur->execute(array(
                ":username" => $username,
            ));
            $user_check = $cur->fetch(PDO::FETCH_ASSOC);
            if ($user_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "Username already exists"
                ));
                http_response_code(409);
                $pdo = null;
                exit;
            }

            $cur = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $cur->execute(array(
                ":username" => $username,
                ":email" => $email,
                ":password" => password_hash($password, PASSWORD_BCRYPT)
            ));

            echo json_encode(array(
                "status" => "success",
                "status_code" => 201,
                "message" => "User registered successfully"
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
    } else if ($action == "forgot_password") { 
        if (!isset($data->email)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input email"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $email = $data->email;

        if (empty($email)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Email cannot be empty"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        try {
            // Check user existence
            $cur = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $cur->execute(array(
                ":email" => $email,
            ));
            $user_check = $cur->fetch(PDO::FETCH_ASSOC);
            if (!$user_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 404,
                    "message" => "Email not found"
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

        // Send reset password email soon :>
    
    } else if ($action == "logout") {
        // Clear the cookie
        if (isset($_COOKIE["auth_token"])) {
            unset($_COOKIE["auth_token"]);
            setcookie("auth_token", "", time() - 3600, "/"); // 1 hour ago
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "message" => "Logout successful"
            ));
            http_response_code(200);
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 401,
                "message" => "Not logged in"
            ));
            http_response_code(401);
        }
        $pdo = null;
        exit;

    // Category
    } else if ($action == "category_create") {
        if (!isset($data->category_name)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input category name"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $category_name = $data->category_name;
        $category_description = isset($data->category_description) ? $data->category_description : null;
        
        if (empty($category_name)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Category name cannot be empty"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

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
    } else if ($action == "category_update") {
        if (!isset($data->idCategory) || !isset($data->category_name)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input category id and name"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $idCategory = $data->idCategory;
        $category_name = $data->category_name;
        $category_description = isset($data->category_description) ? $data->category_description : null;
    
        if (empty($idCategory) || empty($category_name)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Category id and name cannot be empty"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

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
    } else if ($action == "category_delete") {
        if (!isset($data->idCategory)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input category id"
            ));
            http_response_code(400);
        }

        $sql_cmd = "DELETE FROM categories WHERE idCategory = :idCategory";

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        $cur->bindValue(":idCategory", $data->idCategory);
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
    } else if ($action == "product_create") {
        if (
            !isset($data->code) ||
            !isset($data->name) || 
            !isset($data->category) || 
            !isset($data->price_now) || 
            !isset($data->price_original) ||
            !isset($data->description) ||
            !isset($data->quantity)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please fill up for the following fields"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $code = $data->code;
        $name = $data->name;
        $category = $data->category;
        $price_now = $data->price_now;
        $price_original = $data->price_original;
        $description = isset($data->description) ? $data->description : null;
        $quantity = $data->quantity;
        // $image = isset($data->image) ? $data->image : null;
        // $status = isset($data->status) ? $data->status : null;

        if (
            empty($code) ||
            empty($name) ||
            empty($category) ||
            empty($price_now) ||
            empty($price_original) ||
            empty($description) ||
            empty($quantity)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please fill up for the following fields"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

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
    } else if ($action == "product_update") {
        if (
            !isset($data->id) ||
            !isset($data->code) ||
            !isset($data->name) || 
            !isset($data->category) || 
            !isset($data->price_now) || 
            !isset($data->price_original) ||
            !isset($data->description) ||
            !isset($data->quantity)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please fill up for the following fields"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $idProduct = $data->id;
        $code = $data->code;
        $name = $data->name;
        $category = $data->category;
        $price_now = $data->price_now;
        $price_original = $data->price_original;
        $description = isset($data->description) ? $data->description : null;
        $quantity = $data->quantity;

        if (
            empty($idProduct) ||
            empty($code) ||
            empty($name) ||
            empty($category) ||
            empty($price_now) ||
            empty($price_original) ||
            empty($description) ||
            empty($quantity)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please fill up for the following fields"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        try {
            // Check product existence
            $cur = $pdo->prepare("SELECT *
                                FROM products
                                WHERE idProduct = :idProduct");
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
            
            // Update product
            $cur = $pdo->prepare("UPDATE products
                                    SET product_code = :code, product_name = :name, product_category = :category, product_price_now = :price_now, product_price_original = :price_original, product_description = :description, product_quantity = :quantity
                                    WHERE idProduct = :idProduct");
            $cur->execute(array(
                ":code" => $code,
                ":name" => $name,
                ":category" => $category,
                ":price_now" => $price_now,
                ":price_original" => $price_original,
                ":description" => $description,
                ":quantity" => $quantity,
                ":idProduct" => $idProduct
            ));
            // Changes by afftected row
            if ($cur->rowCount() > 0) {
                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 200,
                    "message" => "Product updated successfully"
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
        $pdo = null;
        exit;
    } else if ($action == "product_delete") {
        if (!isset($data->id) && !isset($data->code)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input product id"
            ));
            http_response_code(400);
        }

        $idProduct = isset($data->id) ? $data->id : null;
        $code = isset($data->code) ? $data->code : null;

        try {
            // Check product existence
            $cur = $pdo->prepare("SELECT *
                                FROM products
                                WHERE idProduct = :idProduct OR product_code = :code");
            $cur->execute(array(
                ":idProduct" => $idProduct,
                ":code" => $code
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

            // Delete product
            $cur = $pdo->prepare("DELETE FROM products WHERE idProduct = :idProduct OR product_code = :code");
            $cur->execute(array(
                ":idProduct" => $idProduct,
                ":code" => $code
            ));
            // Changes by afftected row
            if ($cur->rowCount() > 0) {
                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 200,
                    "message" => "Product deleted successfully"
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

    // Users

    /**
     * user_create will merged at `register`
     * 
     */
    } else if ($action == "user_update") {
        if (!isset($data->idUser)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 403,
                "message" => "User id is not present"
            ));
            http_response_code(403);
            $pdo = null;
            exit;
        }

        $id = $data->idUser;
        $username = isset($data->username) ? $data->username : null;
        $email = isset($data->email) ? $data->email : null;
        $password = isset($data->password) ? $data->password : null;
        $status = isset($data->status) ? $data->status : null;
        $role_type = isset($data->role_type) ? $data->role_type : null;

        if (empty($id)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "User id cannot be empty"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        try {
            // Update User
            $cur = $pdo->prepare("UPDATE users
                                    SET username = :username, email = :email, password = :password, status = :status, role_type = :role_type
                                    WHERE idUser = :idUser");
            $cur->execute(array(
                ":username" => $username,
                ":email" => $email,
                ":password" => password_hash($password, PASSWORD_BCRYPT),
                ":status" => $status,
                ":role_type" => $role_type,
                ":idUser" => $id
            ));
            
            // Check if any changes
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
    } else if ($action == "user_delete") {
        if (!isset($data->idUser)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input user id"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $sql_cmd = "DELETE FROM users WHERE idUser = :idUser";

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        $cur->bindValue(":idUser", $data->idUser);
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
    } else if ($action == "cart_add") {
        if (!isset($data->idUser) || !isset($data->idProduct) || !isset($data->quantity)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input user id and product id"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $idUser = $data->idUser;
        $idProduct = $data->idProduct;
        $quantity = $data->quantity;

        if (empty($idUser) || empty($idProduct)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "User id and product id cannot be empty"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        try {
            // Check product existence
            $cur = $pdo->prepare("SELECT *
                                FROM products
                                WHERE idProduct = :idProduct");
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

            // Check the quantity limit

            // Add to cart
            $cur = $pdo->prepare("INSERT INTO cart_data (user_id, product_id, quantity) VALUES (:idUser, :idProduct, :quantity)");
            $cur->execute(array(
                ":idUser" => $idUser,
                ":idProduct" => $idProduct,
                ":quantity" => $quantity
            ));
            echo json_encode(array(
                "status" => "success",
                "status_code" => 201,
                "message" => "Product added to cart successfully"
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
    


    } else if ($action == "cart_update") {
        if (!isset($data->idUser) || !isset($data->idProduct) || !isset($data->quantity) || !isset($data->idCart)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input user id and product id"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $idUser = $data->idUser;
        $idProduct = $data->idProduct;
        $quantity = $data->quantity;
        $idCart = $data->idCart;
        if (empty($idUser) || empty($idProduct) || empty($idCart)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "User id, product id and cart id cannot be empty"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        try {
            // Check product existence
            $cur = $pdo->prepare("SELECT *
                                FROM products
                                WHERE idProduct = :idProduct");
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

            // Check the quantity limit

            // Update cart
            $cur = $pdo->prepare("UPDATE cart_data
                                    SET quantity = :quantity
                                    WHERE idCart = :idCart");
            $cur->execute(array(
                ":quantity" => $quantity,
                ":idCart" => $idCart
            ));
            
            // Changes by afftected row
            if ($cur->rowCount() > 0) {
                echo json_encode(array(
                    "status" => "success",
                    "status_code" => 200,
                    "message" => "Cart updated successfully"
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
    } if ($action == "cart_delete") {
        if (!isset($data->idCart)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input cart id"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        $sql_cmd = "DELETE FROM cart_data WHERE idCart = :idCart";

        // Execute
        $cur = $pdo->prepare($sql_cmd);
        $cur->bindValue(":idCart", $data->idCart);
        $cur->execute();

        if ($cur->rowCount() > 0) {
            echo json_encode(array(
                "status" => "success",
                "status_code" => 200,
                "message" => "Cart deleted successfully"
            ));
            http_response_code(200);
            $pdo = null;
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "Cart not found"
            ));
            http_response_code(404);
            $pdo = null;
            exit;
        }
    } else if ($action == "checkout-make") {
        if (
            !isset($data->name) || 
            !isset($data->address) ||
            !isset($data->deliver_type) ||
            !isset($data->payment_type)
        ) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Missing input"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        if (empty($data->name) ||
            empty($data->address) ||
            empty($data->deliver_type) ||
            empty($data->payment_type)
        ) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please fill up for the following fields"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        if (!isset($data->products) && !isset($data->carts)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input products or carts"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        if (isset($data->products) && isset($data->carts)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input either products or carts"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

        if (isset($data->products) && !isset($data->carts) && isset($data->quantity)) {
            // Check product quantity
            $sql_cmd = "SELECT
                            *
                        FROM
                            products p
                        WHERE
                            p.idProduct = :idProduct
            ";
            $cur = $pdo->prepare($sql_cmd);
            $cur->bindValue(":idProduct", $data->products);
            $cur->execute();
            $product_check = $cur->fetch(PDO::FETCH_ASSOC);
            // echo json_encode(array(
            //     "status" => "success",
            //     "status_code" => 200,
            //     "message" => "Product quantity is enough",
            //     "products" => $product_check
            // ));
            // $pdo = null;
            // exit;
            // if ($product_check['product_quantity'] < $data->quantity) {
            //     echo json_encode(array(
            //         "status" => "error",
            //         "status_code" => 400,
            //         "message" => "Product quantity is not enough"
            //     ));
            //     http_response_code(400);
            //     $pdo = null;
            // exit;
            // }

            // Checking quantity
            $sql_cmd = "SELECT
                            *
                        FROM
                            products p
                        WHERE
                            p.idProduct = :idProduct
            ";
            $cur = $pdo->prepare($sql_cmd);
            $cur->bindValue(":idProduct", $data->products);
            $cur->execute();
            $product_check_qty = $cur->fetch(PDO::FETCH_ASSOC);
            if ($product_check_qty['product_quantity'] == 0) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 400,
                    "message" => "Out of stock"
                ));
                http_response_code(400);
                $pdo = null;
                exit;
            } else if ($product_check_qty['product_quantity'] < $data->quantity) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 400,
                    "message" => "Product quantity is not enough"
                ));
                http_response_code(400);
                $pdo = null;
                exit;
            } else {
                // Then checkout/order now

                // begin transaction

                $pdo->beginTransaction();

                try {
                    $sql_cmd = "INSERT INTO orders
                                    (product_ids, `status`, `name`, `address`, deliver_type, payment_type, contact_no, user_id)
                                VALUES
                                    (:product_ids, :status, :name, :address, :deliver_type, :payment_type, :contact_no, :user_id)
                    ";
                    $cur = $pdo->prepare($sql_cmd);
                    $cur->bindValue(":product_ids", $data->products);
                    $cur->bindValue(":status", "pending");
                    $cur->bindValue(":name", $data->name);
                    $cur->bindValue(":address", $data->address);
                    $cur->bindValue(":deliver_type", $data->deliver_type);
                    $cur->bindValue(":payment_type", $data->payment_type);
                    $cur->bindValue(":contact_no", $data->contact_no);
                    $cur->bindValue(":user_id", $data->idUser);

                    $cur->execute();

                    // Then decrase the product quantity
                    $sql_cmd = "UPDATE products
                                    SET product_quantity = product_quantity - :quantity
                                WHERE idProduct = :idProduct";
                    $cur = $pdo->prepare($sql_cmd);
                    $cur->bindValue(":quantity", $data->quantity);
                    $cur->bindValue(":idProduct", $data->products);
                    $cur->execute();

                    echo json_encode(array(
                        "status" => "success",
                        "status_code" => 201,
                        "message" => "Order created successfully"
                    ));

                    http_response_code(201);

                    // $pdo->rollBack();
                    $pdo->commit();

                } catch (PDOException $e) {
                    // Rollback transaction
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

            }
        }

        if (isset($data->carts) && !isset($data->products)) {
            $carts = $data->carts;

            // Checking the carts available
            $cart_ids = explode(";", $carts);
            foreach($cart_ids as $cart_id) {
                $sql_cmd = "SELECT
                                *
                            FROM
                                cart_data c
                            WHERE
                                c.idCart = :idCart
                ";
                $cur = $pdo->prepare($sql_cmd);
                $cur->bindValue(":idCart", $cart_id);
                $cur->execute();
                $cart_check = $cur->fetch(PDO::FETCH_ASSOC);
                if (!$cart_check) {
                    echo json_encode(array(
                        "status" => "error",
                        "status_code" => 404,
                        "message" => "This cart id was not found"
                    ));
                    http_response_code(404);
                    $pdo = null;
                    exit;
                }
                // Check the product quantity
                $sql_cmd = "SELECT
                                *
                            FROM
                                products p
                            WHERE
                                p.idProduct = :idProduct
                ";
                $cur = $pdo->prepare($sql_cmd);
                $cur->bindValue(":idProduct", $cart_check['product_id']);
                $cur->execute();
                $product_check = $cur->fetch(PDO::FETCH_ASSOC);
                if ($product_check['product_quantity'] < $cart_check['quantity']) {
                    echo json_encode(array(
                        "status" => "error",
                        "status_code" => 400,
                        "message" => "Product quantity is not enough"
                    ));
                    http_response_code(400);
                    $pdo = null;
                    exit;
                } else {
                    // Decrease the products
                    $sql_cmd = "SELECT
                                    *
                                FROM
                                    products p
                                WHERE
                                    p.idProduct = :idProduct";
                    $cur = $pdo->prepare($sql_cmd);
                    $cur->bindValue(":idProduct", $cart_check['product_id']);
                    $cur->execute();
                    $product_check = $cur->fetch(PDO::FETCH_ASSOC);

                    // foreach 
                    // echo json_encode(array(
                    //     "status" => "success",
                    //     "status_code" => 200,
                    //     "message" => "Product quantity is enough",
                    //     "products" => $product_check
                    // ));
                }
                    

                }
            }

        $user_id = $data->idUser;
        $name = $data->name;
        $address = $data->address;
        $deliver_type = $data->deliver_type;
        $payment_type = $data->payment_type;
        
    }

    else
    
    
    {
        echo json_encode(array(
            "status" => "error",
            "status_code" => 400,
            "message" => "Bad Request"
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
        if (!isset($_GET['idUser']) && !isset($_GET['username']) && !isset($_GET['email'])) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input user id"
            ));
            http_response_code(400);
            $pdo = null;
            exit;
        }

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
        $sql_cmd = "SELECT o.idOrder, o.product_ids, o.status, o.name, o.address, o.deliver_type, o.payment_type, o.contact_no, o.created_at, p.product_name, p.product_price_now
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
            $sql_cmd .= " AND status = :status";
            $params[":status"] = $_GET['status'];
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