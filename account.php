<?php
include __DIR__ . "/header.php";
//In this file will be used for logging in, registering and order summary.
$loggedin = $_SESSION['loggedin'];
$customerid = $_SESSION['customerid'];


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
    if (login($email, $password, $databaseConnection)) {
        echo("<script>location.href = 'account.php';</script>");
    } else {
        echo("<script>location.href = 'login.php?login=false';</script>");
    }

} elseif(!$loggedin) {
    echo("<script>location.href = 'login.php';</script>");
    die();

} elseif ($loggedin) {
?>
    <div class="AccountContainer">
        <br>
        <div class="Header">
            <br>
            <br>
            <h3 class="Heading" >Account</h3>
            <br>
            <br>
        </div>
        <br>
        <h3 class="Heading">Welkom terug <?php print(getCustomerName($customerid,$databaseConnection));?></h3>
        <br>
        <br>
    </div>
    <br>
    <br>
        <div class="AccountRow">
            <br>
            <h2 class="Heading">Mijn gegevens</h2>
            <?php
                $customerdetails = getCustomerDetails($customerid,$databaseConnection);
            ?>
            <br>
            <div class="AccountData">
                <p1>Voornaam: <?php print($customerdetails['CustomerName']);?></p1><br><br>
                <p1>Postcode: <?php print($customerdetails['DeliveryPostalCode']);?></p1><br><br>
                <p1>Adres: <?php print($customerdetails['DeliveryAddressLine1']);?></p1><br><br>
                <p1>Stad: <?php print($customerdetails['CityName']);?></p1><br><br>
                <p1>Telefoonnummer: <?php print($customerdetails['PhoneNumber']);?></p1><br><br>
            </div>
            <br>
            <br>
            <form action="register.php"
                <button class="AccountChangeDataButton">Wijzigen</button>
            </form>


        </div>
        <div class="AccountRow">
            <br>
            <h2 class="Heading">Mijn bestellingen</h2>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>

        </div>
    <?php
    while($br < 33) {
        print("<br>");
        $br++;
    }
}
include __DIR__ . "/footer.php";
?>