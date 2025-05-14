<?php

$project_name = "One Stop Shop";
$project_version = "1.0.0";
$project_year_founded = 2025;
$project_year_active = date('Y');
$project_description = "The leading online shopping platform in Web Systeem Subject";


// DB Conn PDO
$host = "localhost";
$db_name = "one_stop_shop";
$db_user = "root";
$db_pass = "root";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


function head_css() {
    echo '
        <link href="/node_modules/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
        <link href="/public/css/styles.css" rel="stylesheet" />
    ';
}

function head_js() {

}

function after_js() {
    echo '
        <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    ';
}

