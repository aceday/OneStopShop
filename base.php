<?php
require_once __DIR__ . "/vendor/autoload.php";

$project_name = "One Stop Shop";
$project_version = "1.0.0";
$project_year_founded = 2025;
$project_year_active = date('Y');
$project_description = "The leading online shopping platform in Web Systeem Subject";


// DB Conn PDO
// PDO
$host = "localhost";
$db_name = "one_stop_shop";
$db_user = "root";
$db_pass = "root";
$charset = "utf8mb4";
$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try
{
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} 

catch (\PDOException $e) 

{
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
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
        <script src="/node_modules/jquery/dist/jquery.js"></script>
        <script src="/node_modules/bootstrap/dist/js/bootstrap.bundle.js"></script>
    ';
}

