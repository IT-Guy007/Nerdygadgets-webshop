<?php
include __DIR__ . "/header.php";
//In this file will be used for logging in, registering and order summary.

if (!($_SESSION['loggedin'])) {
    header("Location: login.php");

} elseif($_SESSION['loggedin']) {

    //Account page
    print("Logged in Session: " . $_SESSION['loggedin'] . " this should be true");

} elseif (isset($_SESSION['email']) and isset($_SESSION['password'])) {

    $email = $_SESSION['email'];
    $password = $_SESSION['password'];

    if(login($email,$password,$databaseConnection)) {
        $_SESSION['loggedin'] = TRUE;
    } else {
        header("Location: login.php?login=false");
    }
} else {
    ?>
    <h1>Something went wrong</h1>
    <?php
    print("loggedin: " . $_SESSION['loggedin']);
}

?>