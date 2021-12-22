<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
$cart = getCart();
$loggedin = $_SESSION['loggedin'];
$customerid = $_SESSION['customerid'];
$customerdetails = getCustomerDetails($customerid,$databaseConnection);
$countryName = getCountryName($customerdetails['countryid'],$databaseConnection);
$countryName2 = $countryName['CountryName'];

if(empty($cart)) {
    echo("<script>location.href = 'cart.php';</script>");
}

//Checkout
if((isset($_GET['checkout']) ? $_GET['checkout'] : '') == true) {
    $naam = (isset($_GET['naam']) ? $_GET['naam'] : '');
    $adres = (isset($_GET['adres']) ? $_GET['adres'] : '');
    $postcode = (isset($_GET['postcode']) ? $_GET['postcode'] : '');
    $stad = (isset($_GET['stad']) ? $_GET['stad'] : '');
    $land = (isset($_GET['land']) ? $_GET['land'] : '');
    $telnumber = (isset($_GET['tel']) ? $_GET['tel'] : '');
    $countryName = getCountryName($customerdetails['countryid'],$databaseConnection);

    if ($loggedin) {
        createOrder($customerid,$databaseConnection);
        echo("<script>location.href = 'iDEAL.php';</script>");

    } else {
        createOrderGuest($naam,$adres,$postcode,$stad,$land,$telnumber,$databaseConnection);
        echo("<script>location.href = 'iDEAL.php';</script>");
    }
}

?>
<form action="checkout.php" target="_self">
<div class="CheckoutHeader">
    <p>Checkout</p>
</div>
    <?php
    if(!$loggedin) {
    ?>
<div id="checkout">
    <div id="nawfieldcheckout">
        <br>
        <h1 style="font-size: 20px">Persoonsinformatie</h1>
        <p>Alle velden met een * zijn verplicht</p>
        <br>
        <br>
        <label for="name">Volledige naam*
            <input class="regfield" type="text" placeholder="" name="naam" value="<?php print($customerdetails['CustomerName']);?>" required>
        </label>
        <label for="address">Adres*
            <input class="regfield" type="text" placeholder="" name="adres" value="<?php print($customerdetails['DeliveryAddressLine1']);?>" required size="25">
        </label>

        <label for="postal">Postcode*
            <input class="regfield" type="text" placeholder="" name="postcode" value="<?php print($customerdetails['DeliveryPostalCode']);?>" required>
        </label>

        <label for="city">Stad*
            <input class="regfield" type="text" placeholder="" name="stad" value="<?php print($customerdetails['CityName']);?>" required size="20">
        </label>

        <label for="country">Land*
            <select class="regfield" type="text" name="land" value="<?php print($countryName['CountryName']);?>" required>
                <?php
                $countries = getAllCountries($databaseConnection);
                while($countrynumber != count($countries)) {
                        ?><option value="<?php print($countries[$countrynumber]['CountryName'])?>"<?php if($countries[$countrynumber]['CountryName'] == $countryName['CountryName']) { print("selected");}?>><?php print($countries[$countrynumber]['CountryName'])?></option>
                    }
                    <?php
                    $countrynumber++;
                } ?>
            </select>
        </label>

        <label for="telnummer"><b>Telefoonnummer</b>
            <input class="regfield" type="tel" placeholder="" name="telnumber" value="<?php print($customerdetails['PhoneNumber']);?>" size="17">
        </label>

    <?php
    } else {
        ?>

        <?php
    }
    ?>

    </div>
    <div class="CartContainerCheckout">
        <br>
        <div class="Header">
            <h3 class="HeadingFull">Winkelwagen</h3>
        </div>
        <!--Voor elke item-->
        <?php
        $amountarikels = 0;
        foreach($cart as $item => $amount):
            $itemarray = (getItemDetails($item,$databaseConnection));
            $total = $total + ($itemarray["SellPrice"] * $amount);
            $amountarikels = $amountarikels + $amount;
            ?>
            <div class="Cart-Items">
                <div class="image-box">
                    <img src = "<?php if (empty($itemarray["ImagePath"])) {
                        print("/public/img/nologo.png" );
                    }  else {
                        print("/public/stockitemimg/". $itemarray["ImagePath"]);
                    } ?>" style ="height: 120px; margin: 6%" />
                </div>
                <div class="about" >
                    <b class="title" > <?php echo $itemarray["StockItemName"]?> </b>
                    <h3 class="subtitle" > Artikelnummer: <?php echo $itemarray["StockItemID"]?></h3>
                </div>
                    <div class="count">
                        <input type="text" class="btn" id="cartitem" name="" value="<?php print($amount)?>" style="width: 50px; padding: 0px">
                    </div>
                <div class="prices"  >
                        <div class="amount" > <?php print(number_format($itemarray["SellPrice"] * $amount,2))?></div>
                </div>
            </div>
            <br>
        <?php endforeach; ?>
        <hr>
        <!--Totaal-->
        <?php
        $shippingCostsApplied = false;
        if($total < 50) {
            $shippingCostsApplied = true;
            $total += 5;
        }
        ?>
        <div class="checkoutfinal">
            <div class="total">
                <div>
                    <div class="Subtotal">Totaal</div>
                        <div class="items">
                            Aantal: <?php print($amountarikels)?>
                        </div>
                </div>
                <div class="total-amount">
                    <?php print("€" . number_format($total,2))?>
                </div>

            </div>
            <!--Link naar de iDEAL pagina-->
                <p1 style="color: black"> Door op afrekenen te klikken gaat u akkoord met de algemene voorwaarden</p1>
                <button type="submit" name="checkout" value="true" class="buttonOrange buttonOrange2">Afrekenen</button>
        </div>
    </div>
</div>
</form>
<?php
while($br < 8) {
    print("<br>");
    $br++;
}
include __DIR__ . "/footer.php";
?>