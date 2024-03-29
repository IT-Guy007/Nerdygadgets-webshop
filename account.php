<?php
include __DIR__ . "/header.php";
//In this file will be used for logging in, registering and order summary.
if(!isset($_SESSION)) {
    session_start();
}
$loggedin = $_SESSION['loggedin'];
$customerid = $_SESSION['customerid'];
if($_GET['logout']) {
    //Logout
    $loggedin = false;
    $_SESSION['loggedin'] = false;
    $_SESSION['customerid'] = "";
    echo("<script>location.href = 'index.php';</script>");
} elseif ($_POST['login']) {
    //Login
    $email = $_POST['email'];
    $password = $_POST['password'];
    $email = strtolower($email);
    if (login($email, $password, $databaseConnection)) {
        echo("<script>location.href = 'account.php';</script>");
    } else {
        echo("<script>location.href = 'login.php?login=false';</script>");
    }
} elseif ($_POST['register']) {

    //Register
    $voornaam = $_POST['voornaam'];
    $tussenvoegsel = $_POST['tussenvoegsel'];
    $achternaam = $_POST['achternaam'];
    $land = $_POST['land'];
    $stad = $_POST['stad'];
    $adres = $_POST['adres'];
    $postcode = $_POST['postcode'];
    $telnumber = $_POST['telnumber'];
    $faxnummer = $_POST['faxnumber'];
    $email = $_POST['email'];
    $account = $_POST['accountsoort'];
    $website = $_POST['website'];
    $wachtwoord1 = $_POST['wachtwoord1'];
    $wachtwoord2 = $_POST['wachtwoord2'];

    $voornaam = strtolower($voornaam);
    $achternaam = strtolower($achternaam);
    $adres = strtolower($adres);
    $stad = strtolower($stad);

    $voornaam = ucfirst($voornaam);
    $achternaam = ucfirst($achternaam);
    $adres = ucfirst($adres);
    $stad = ucfirst($stad);


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
                if (createAccount($name, $adres, $postcode, $faxnummer, $stad, $land, $telnumber, $email, $wachtwoord1, $website, $account, $databaseConnection)) {
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

}elseif ($_POST['pushnaw']) {
    $naam = ($_POST['naw']);
    $email = ($_POST['email']);
    $land = ($_POST['land']);
    $stad = ($_POST['stad']);
    $adres = ($_POST['adres']);
    $postcode = ($_POST['postcode']);
    $telnumber = ($_POST['telnumber']);
    $faxnummer = ($_POST['faxnumber']);
    $email = ($_POST['email']);
    $website = ($_POST['website']);

    if(updateNAW($customerid,$naam,$email,$adres,$postcode,$stad,$land,$telnumber,$faxnummer,$website,$databaseConnection)) {
        echo("<script>location.href = 'account.php?changenawsucceeded = true';</script>");
    }   else {
        echo("<script>location.href = 'account.php?changenawfailed = true';</script>");
    }

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
            <?php
                if((($_POST['changenaw']) OR ($_POST['changenawfailed'])) AND !($_POST['changenawsucceeded'])) {
                    ?>
                    <br>
                    <h2 class="Heading"><?php if(!$_POST['changenawfailed']) {
                        ?>Mijn gegevens aanpassen
                        <?php
                    } else {
                        ?>Er ging iets fout
                        <?php
                    }
                    $customerdetails = getCustomerDetails($customerid,$databaseConnection);
                        ?>
                    </h2>
                    <br>
                    <div id="changenawaccount">
                            <form action="account.php" target="_self">
                                <label class="reglabel"  for="name">Naam*
                                    <input class="regfieldaccount" type="text" placeholder="" name="naam" value="<?php print($customerdetails['CustomerName'])?>" required >
                                </label>

                                <label class="reglabel"  for="email">E-mailadres*
                                    <input class="regfieldaccount" type="email" placeholder="" name="email" value="<?php print($customerdetails['Email'])?>" required size="20">
                                </label><br>

                                <label class="reglabel" style="width: 50%" for="country">Adres*
                                    <input class="regfieldaccount" type="text" placeholder="" name="adres" value="<?php print($customerdetails['DeliveryAddressLine1'])?>" required>
                                </label>

                                <label class="reglabel" style="width: 20%" for="country">Postcode*
                                    <input class="regfieldaccount" type="text" placeholder="" name="postcode" value="<?php print($customerdetails['DeliveryPostalCode'])?>" required>
                                </label>

                                <label class="reglabel" style="width: 40%" for="city">Stad*
                                    <input class="regfieldaccount" type="text" placeholder="" name="stad" value="<?php print($customerdetails['CityName'])?>" required>
                                </label>

                                <label class="reglabel" style="width: 40%" for="address">Land*
                                    <select class="regfieldaccount" type="text" name="land" required>
                                        <?php
                                        $countryName = getCountryName($customerdetails['countryid'],$databaseConnection);
                                        $countries = getAllCountries($databaseConnection);
                                        while($countrynumber != count($countries)) {
                                            ?><option value="<?php print($countries[$countrynumber]['CountryName'])?>"<?php if($countries[$countrynumber]['CountryName'] == $countryName['CountryName']) { print("selected");}?>><?php print($countries[$countrynumber]['CountryName'])?></option>
                                            }
                                            <?php
                                            $countrynumber++;
                                        } ?>
                                    </select>
                                </label>

                                <label class="reglabel" style="width: 40%" for="telnummer"><b>Telefoonnummer*</b>
                                    <input class="regfieldaccount" type="tel" placeholder="" name="telnumber" value="<?php print($customerdetails['PhoneNumber'])?>" required size="17">
                                </label>

                                <label class="reglabel" style="width: 40%" for="faxnummer"><b>Faxnummer</b>
                                    <input class="regfieldaccount" type="tel" placeholder="" name="faxnummer" value="<?php print($customerdetails['FaxNumber'])?>" size="17">
                                </label>

                                <label class="reglabel" style="width: 70%" for="website"><b>Website</b>
                                    <input class="regfieldaccount" type="tel" placeholder="" name="website" value="<?php print($customerdetails['WebsiteURL'])?>" size="25">
                                </label>
                            </div>
                            <button name="pushnaw" value="true" class="buttonOrange buttonOrange2">Wijzigen</button>
                            </form>
                    <?php

            } else {
            ?>
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
                <p1>Telefoonnummer: <?php print($customerdetails['PhoneNumber']);?></p1><br><br>
                <p1>Faxnummer: <?php print($customerdetails['FaxNumber']);?></p1><br><br>
                <p1>Postcode: <?php print($customerdetails['DeliveryPostalCode']);?></p1><br><br>
                <p1>Adres: <?php print($customerdetails['DeliveryAddressLine1']);?></p1><br><br>
                <p1>Stad: <?php print($customerdetails['CityName']);?></p1><br><br>
                <p1>Land: <?php print($countryName['CountryName']);?></p1><br><br>
                <p1>Website: <?php print($customerdetails['WebsiteURL']);?></p1><br><br>
                <p1>Wachtwoord: **********</p1>
            </div>
            <?php
            while($br < 7) {
                print("
<br>");
                $br++;
            }
                    ?>
            <form action="account.php">
                <input type="hidden" name="changenaw" value="true">
                <button class="buttonOrange buttonOrange2">Wijzigen</button>
            </form>
            <?php }?>

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
        while($br2 < 40) {
            print("<br>");
            $br2++;
        }

} else {
    echo("<script>location.href = 'login.php';</script>");
}

include __DIR__ . "/footer.php";
?>
