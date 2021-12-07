<?php
//In this file will be used for logging in, registering and order summary.
if(!isset($_SESSION)) {
    session_start();
}

if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}

if(isset($_SESSION['password'])) {
    $password = $_SESSION['password'];
}

/*
if ($_SESSION['loggedin']) {
    header("Location: login.php");

} else {
    include __DIR__ . "/header.php";
    ?>
    <p1>Account pagina</p1>
<?php
}
*/
?>


