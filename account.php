<?php
include __DIR__ . "/header.php";
//In this file will be used for logging in, registering and order summary.
if(!isset($_SESSION)) {
    session_start();
}
$loggedin = $_SESSION['loggedin'];
$customerid = $_SESSION['customerid'];

if((isset($_GET['logout']) ? $_GET['logout'] : '')) {
    //Logout
    $loggedin = false;
    $_SESSION['loggedin'] = false;
    $_SESSION['customerid'] = "";
    echo("<script>location.href = 'index.php';</script>");
}

if (!empty(isset($_GET['password']) ? $_GET['password'] : '') AND !$_SESSION['loggedin']) {
    //Login
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $password = isset($_GET['password']) ? $_GET['password'] : '';
    $email = strtolower($email);
    if (login($email, $password, $databaseConnection)) {
        echo("<script>location.href = 'account.php';</script>");
    } else {
        echo("<script>location.href = 'login.php?login=false';</script>");
    }

} elseif (!empty(isset($_GET['voornaam']) ? $_GET['voornaam'] : '') AND !$_SESSION['loggedin']) {
    //Register
    $voornaam = (isset($_GET['voornaam']) ? $_GET['voornaam'] : '');
    $tussenvoegsel = (isset($_GET['tussenvoegsel']) ? $_GET['tussenvoegsel'] : '');
    $achternaam = (isset($_GET['achternaam']) ? $_GET['achternaam'] : '');
    $land = (isset($_GET['land']) ? $_GET['land'] : '');
    $stad = (isset($_GET['stad']) ? $_GET['stad'] : '');
    $adres = (isset($_GET['adres']) ? $_GET['adres'] : '');
    $postcode = (isset($_GET['postcode']) ? $_GET['postcode'] : '');
    $telnumber = (isset($_GET['telnumber']) ? $_GET['telnumber'] : '');
    $faxnummer = (isset($_GET['faxnummer']) ? $_GET['faxnummer'] : '');
    $email = (isset($_GET['email']) ? $_GET['email'] : '');
    $account = (isset($_GET['account']) ? $_GET['account'] : '');
    $website = (isset($_GET['website']) ? $_GET['website'] : '');
    $accounttype = (isset($_GET['accountsoort']) ? $_GET['accountsoort'] : '');
    $wachtwoord1 = (isset($_GET['wachtwoord1']) ? $_GET['wachtwoord1'] : '');
    $wachtwoord2 = (isset($_GET['wachtwoord2']) ? $_GET['wachtwoord2'] : '');

    $voornaam = strtolower($voornaam);
    $achternaam = strtolower($achternaam);
    $voornaam = strtolower($voornaam);
    $adres = strtolower($adres);
    $stad = strtolower($stad);

    $voornaam = ucfirst($voornaam);
    $achternaam = ucfirst($achternaam);
    $voornaam = ucfirst($voornaam);
    $adres = ucfirst($adres);
    $stad = ucfirst($stad);

    //------------------//
    if (empty($tussenvoegsel)) {
        $name = ($voornaam . " " . $achternaam);
    } else {
        $name = ($voornaam . " " . $tussenvoegsel . " " . $achternaam);
    }
    if (empty($telnumber)) {
        $telnumber = "-";
    }
    if ($wachtwoord1 === $wachtwoord2) {
        if (!(checkIfUserAlreadyExists($name, $databaseConnection))) {
            if (!(checkIfEmailAlreadyExists($email, $databaseConnection))) {
              if (createAccount($name, $adres, $postcode, $faxnummer, $stad, $land, $telnumber, $email, $wachtwoord1, $website, $accounttype, $databaseConnection)) {
                   if (login($email, $wachtwoord1, $databaseConnection)) {
                       echo("<script>location.href = 'account.php?register=true';</script>");
                    } else {
                        echo("<script>location.href = 'login.php?login=false';</script>");
                    }
              } else {
                   echo("<script>location.href = 'register.php?register=false';</script>");
              }
            } else {
                echo("<script>location.href = 'register.php?emailalreadyexists=true';</script>");
            }
        } else {
            echo("<script>location.href = 'register.php?useralreadyexists=true';</script>");
        }
    }

} elseif(!$loggedin) {
    //Not logged in
    echo("<script>location.href = 'login.php';</script>");
    die();

} elseif ($loggedin) {
    //Loggedin
    $last3orders = getLast3Orders($customerid,$databaseConnection);
    if(!empty($last3orders)) {
        $orders = count($last3orders);
    }
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
                $countryName = getCountryName($customerdetails['countryid'],$databaseConnection);
            ?>
            <br>
            <div class="AccountData">
                <p1>Klantnummer: <?php print($customerdetails['CustomerID']);?></p1><br><br>
                <p1>Accountsoort: <?php print($customerdetails['CustomerCategoryName']);?></p1><br><br>
                <p1>Voornaam: <?php print($customerdetails['CustomerName']);?></p1><br><br>
                <p1>Emailadres: <?php print($customerdetails['Email']);?></p1><br><br>
                <p1>Postcode: <?php print($customerdetails['DeliveryPostalCode']);?></p1><br><br>
                <p1>Adres: <?php print($customerdetails['DeliveryAddressLine1']);?></p1><br><br>
                <p1>Stad: <?php print($customerdetails['CityName']);?></p1><br><br>
                <p1>Land: <?php print($countryName['CountryName']);?></p1><br><br>
                <p1>Telefoonnummer: <?php print($customerdetails['PhoneNumber']);?></p1><br><br>
                <p1>Wachtwoord: **********</p1>
            </div>
            <?php
            while($br < 7) {
                print("<br>");
                $br++;
            }
            ?>
            <form action="account.php">
            <input type="hidden" id="changenaw" value="true">
                <button class="buttonOrange buttonOrange2">Wijzigen</button>
            </form>

        </div>
        <?php if(empty($last3orders)) {?>
        <div class="AccountRow">
            <br>
            <h2 class="Heading">Mijn bestellingen</h2>
            <div class="AccountData">
                <p1>Geen bestellingen gevonden... helaas</p1>
            </div>
            <?php
            while($br < 23) {
            print("<br>");
            $br++;
            }
            ?>
        </div>
    <?php } else {?>
        <div class="AccountRow">
            <br>
            <h2 class="Heading">Mijn bestellingen</h2>
            <br>
                <?php
                    for ($i=0;$i !=$orders;$i++) {
                        ?>
                        <div class="Cart-Items">
                            <div class="image-box">
                                <img src = "
                                <?php
                                $image = getStockItemIDImageFromOrderID($last3orders[$i]['OrderID'],$databaseConnection);
                                if(!empty($image[$i])) {
                                    print("/public/stockitemimg/" . $image[$i]);
                                } else {
                                    $stockGroupURL = stockitemIDToStockGroupImgURL($last3orders[$i]['OrderID'],$databaseConnection);
                                   print("/public/stockgroupimg/" . $stockGroupURL);
                                }
                                ?> " style ="height: 120px; margin: 6%" >
                            <?php
                            print($last3orders[$i]['OrderID']);
                            ?>
                            </div>
                            <div class="about" >
                                <b class="title" > Ordernummer: <?php echo $last3orders[$i]['OrderID']?></b>
                                <br>
                                <h3 class="subtitle" > Orderdate: <?php echo $last3orders[$i]['OrderDate']?></h3>
                                <h3 class="subtitle" > Aantal artikelen: <?php echo getAmountOfItemsInOrder($last3orders[$i]['OrderID'],$databaseConnection)?></h3>
                            </div>
                                <div class="count">
                                    <input type="text" class="btn" id="cartitem" name="amount" value="<?php ?>" style="width: 50px;">
                                </div>
                            <div class="prices"  >
                                    <div class="amount">
                                        <?php print("€ " . getOrderTotalPrice($last3orders[$i]['OrderID'],$databaseConnection))?>
                                    </div>
                            </div>
                        </div>
                        <br>
                        <?php
                    }
                ?>
            </div>
        </div>
        <?php
    }
        while($br2 < 37) {
            print("<br>");
            $br2++;
}
include __DIR__ . "/footer.php";

}?>
