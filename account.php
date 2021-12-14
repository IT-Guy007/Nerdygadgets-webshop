<?php
include __DIR__ . "/header.php";
//In this file will be used for logging in, registering and order summary.
$loggedin = $_SESSION['loggedin'];

if((isset($_GET['logout']) ? $_GET['logout'] : '')) {
    $loggedin = false;
    $_SESSION['loggedin'] = false;
    $_SESSION['customerid'] = "";
    echo("<script>location.href = 'index.php';</script>");
}

if (!empty(isset($_GET['email']) ? $_GET['email'] : '') AND !$_SESSION['loggedin']) {
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $password = isset($_GET['password']) ? $_GET['password'] : '';
    $email = strtolower($email);
}

if (!empty($email) and (!empty($password))) {
    if (login($email, $password, $databaseConnection)) {
        echo("<script>location.href = 'account.php';</script>");
    } else {
        echo("<script>location.href = 'login.php?login=false';</script>");
    }

} elseif(!$loggedin) {
    echo("<script>location.href = 'login.php';</script>");
    print("Logingedin is false");
    die();
    //Account page

} elseif ($loggedin) {


}
include __DIR__ . "/footer.php";
?>