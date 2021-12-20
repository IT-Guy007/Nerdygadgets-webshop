<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
$cart = getCart();
$loggedin = $_SESSION['loggedin'];
$customerid = $_SESSION['customerid'];
$customerdetails = getCustomerDetails($customerid,$databaseConnection);
$countryName = getCountryName($customerdetails['countryid'],$databaseConnection);
?>
<div class="CheckoutHeader">
    <p>Checkout</p>
</div>
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
                    if($countries[$countrynumber]['Country'] == ($countryName['CountryName'])) {
                        ?><option value="<?php print($countries[$countrynumber]['CountryName'])?>" selected><?php print($countries[$countrynumber]['CountryName'])?></option>
                        <?php
                    } else {
                        ?><option value="<?php print($countries[$countrynumber]['CountryName'])?>"><?php print($countries[$countrynumber]['CountryName'])?></option>
                    <?php
                    }
                    $countrynumber++;
                } ?>
            </select>
        </label>

        <label for="telnummer"><b>Telefoonnummer</b>
            <input class="regfield" type="tel" placeholder="" name="telnumber" value="<?php print($customerdetails['PhoneNumber']);?>" size="17">
        </label>

    </div>
    <div class="CartContainerCheckout">
        <br>
        <div class="Header">
            <h3 class="HeadingFull">Winkelwagen</h3>
            <form action="cartfuncties.php" target="_self">
                <input type="submit" id="cartitem" class="remove" name="emptycart" value="Verwijder alle items">
            </form>
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
                <form action="cartfuncties.php" target="_self">
                    <div class="count">
                        <input type="text" class="btn" id="cartitem" name="amount" value="<?php print($amount)?>" style="width: 50px; padding: 0px">
                        <input type="hidden" class="btn" id="cartitem" name="cartitemid" value="<?php print($itemarray["StockItemID"])?>">
                    </div>
                </form>
                <div class="prices"  >
                    <form action="cartfuncties.php" target="_self">
                        <div class="amount" > <?php print(number_format($itemarray["SellPrice"] * $amount,2))?></div>
                        <input type="hidden" id="cartitem" name="removecartitemid" value="<?php print($itemarray["StockItemID"])?>">
                        <input type="submit" id="cartitem" class="remove" name="removecartitem" value="Verwijder">
                    </form>
                </div>
            </div>
            <br>
        <?php endforeach; ?>
        <hr>

        <!--Totaal-->
        <div class="checkoutfinal">
            <div class="total">
                <div>
                    <div class="Subtotal">Totaal</div>
                    <div class="items">Aantal: <?php print($amountarikels)?></div>
                </div>
                <div class="total-amount"><?php print("€ " . number_format($total,2))?></div>

            </div>
            <!--Link naar de iDEAL pagina-->
            <form action="checkout.php">
                <input type="hidden" id="changenaw" value="true">
                <button class="buttonOrange buttonOrange2">Afrekenen</button>
            </form>
        </div>
    </div>
</div>
<?php
while($br < 16) {
    print("<br>");
    $br++;
}
include __DIR__ . "/footer.php";
?>