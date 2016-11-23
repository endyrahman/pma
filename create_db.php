<?php
define('DB_HOST', '127.0.0.1'); // use ip address instead of `localhost`
// existing user that has permission to create database and grant access
define('DB_ROOT_USER', 'root');
define('DB_ROOT_PASS', '');

// the database you want to create
$dbname = $_POST['dbname'];
// specific user for this particular database
$dbuser = 'root';
$dbpass = '';

try {
    // login with root user
    $dbh = new PDO('mysql:host='.DB_HOST, DB_ROOT_USER, DB_ROOT_PASS);

    // create database
    if('mysql:dbname'!=$dbname){
        $dbh->exec("CREATE DATABASE `$dbname`; CREATE USER '$dbuser'@'localhost' IDENTIFIED BY '$dbpass';GRANT ALL ON `$dbname`.* TO '$dbuser'@'localhost';FLUSH PR IVILEGES;");    
        header('Location: index.php');
    } else {
        echo "string";
    }
} catch (PDOException $e) {
    die("DB ERROR: ". $e->getMessage());
}



?>

