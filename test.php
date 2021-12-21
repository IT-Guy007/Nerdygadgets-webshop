<?php
include __DIR__ . "/database.php";
$databaseConnection = connectToDatabase();
$password = "Millenium";
$email = "me@jeroendenotter.nl";

$query = "
                SELECT CustomerID, Password
                FROM accounts
                WHERE Email = '$email'
                    ";

$statement = mysqli_prepare($databaseConnection, $query);
mysqli_stmt_execute($statement);
$output = mysqli_stmt_get_result($statement);
$output = mysqli_fetch_all($output, MYSQLI_ASSOC);

foreach ($output as $key => $value) {
    if (empty($value)) {
        unset($output[$key]);
    }
}

$hash = $output[0]['Password'];
if (!empty($output)) {
    if (password_verify($password, $hash)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['customerid'] = $output[0]['CustomerID'];
        print("Correct!");

    } else {
        $_SESSION['loggedin'] = false;
        print("Niet correct!");
    }
}