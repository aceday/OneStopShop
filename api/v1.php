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
                
                if (password_verify($password, $password_enc)) {
                    $token_dec = array(
                        "user_id" => $user_id,
                        "username" => $username,
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
                    exit;
                } else {
                    echo json_encode(array(
                        "status" => "error",
                        "status_code" => 401,
                        "message" => "Invalid username or password"
                    ));
                    http_response_code(401);
                    exit;
                }
            } else {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 401,
                    "message" => "Invalid username or password"
                ));
                http_response_code(401);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
            exit;
        }

    } else if ($action == "register") {
        if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Please input username, email and password"
            ));
            http_response_code(400);
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
            exit;
        }

        try {
            // Check user existence
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(array(
                ":username" => $username,
            ));
            $user_check = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "Username already exists"
                ));
                http_response_code(409);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->execute(array(
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
            exit;
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
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
            exit;
        }

        try {
            // Check user existence
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(array(
                ":email" => $email,
            ));
            $user_check = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$user_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 404,
                    "message" => "Email not found"
                ));
                http_response_code(404);
                exit;
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
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
            exit;
        }

        $category_name = $data->category_name;

        if (empty($category_name)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Category name cannot be empty"
            ));
            http_response_code(400);
            exit;
        }

        try {
            // Check category existence
            $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_name = :category_name");
            $stmt->execute(array(
                ":category_name" => $category_name,
            ));
            $category_check = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($category_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 409,
                    "message" => "Category already exists"
                ));
                http_response_code(409);
                exit;
            }
            $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (:category_name)");
            $stmt->execute(array(
                ":category_name" => $category_name
            ));

            echo json_encode(array(
                "status" => "success",
                "status_code" => 201,
                "message" => "Category created successfully"
            ));
            http_response_code(201);
            exit;
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
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
            exit;
        }

        $idCategory = $data->idCategory;
        $category_name = $data->category_name;

        if (empty($idCategory) || empty($category_name)) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 400,
                "message" => "Category id and name cannot be empty"
            ));
            http_response_code(400);
            exit;
        }

        try {
            // Check category existence
            $stmt = $pdo->prepare("SELECT * FROM categories WHERE idCategory = :idCategory");
            $stmt->execute(array(
                ":idCategory" => $idCategory,
            ));
            $category_check = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$category_check) {
                echo json_encode(array(
                    "status" => "error",
                    "status_code" => 404,
                    "message" => "Category not found"
                ));
                http_response_code(404);
                exit;
            }
            
            $stmt = $pdo->prepare("UPDATE categories SET category_name = :category_name WHERE idCategory = :idCategory");
            $stmt->execute(array(
                ":category_name" => $category_name,
                ":idCategory" => $idCategory
            ));

            // Changes by afftected row
            if ($stmt->rowCount() > 0) {
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
            exit;
        } catch (PDOException $e) {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 500,
                "message" => $e->getMessage()
            ));
            http_response_code(500);
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
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "Category not found"
            ));
            http_response_code(404);
            exit;
        }
    }
    
    
    else {
        echo json_encode(array(
            "status" => "error",
            "status_code" => 400,
            "message" => "Bad Request"
        ));
        http_response_code(400);
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
            exit;
        } else {
            echo json_encode(array(
                "status" => "error",
                "status_code" => 404,
                "message" => "No categories available"
            ));
            http_response_code(404);
            exit;
        }
    }
} else {
    echo json_encode(array(
        "status" => "error",
        "status_code" => 405,
        "message" => "Method Not Allowed"
    ));
    http_response_code(405);
    exit;
}