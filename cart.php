<?php
include __DIR__ . "/header.php";
include __DIR__ . "/cartfuncties.php";
$br = $total = 0;
$cart = getCart();
?>
<?php
if (empty($cart)) { ?>
    <br>
    <div class="CartContainer">
        <div class="Header">
            <br>
            <h3 class="Heading" >Winkelwagen is leeg</h3>
        </div>
        <br>
        <br>
        <br>
    </div>
    <br>
    <br>
    <br>
<?php } else { ?>
    <div class="CartContainer">
        <div class="Header">
            <h3 class="Heading" style="margin-top: 10px">Winkelwagen</h3>
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
                    <img src = "<?php if (!empty($itemarray["BackupImagePath"])) {
                        print("/nerdygadgets/public/stockgroupimg/" . $itemarray["BackupImagePath"]);
                    }  else { print("/nerdygadgets/public/img/nologo.png");} ?>" style ="height: 120px; margin: 6%" />
                </div>
                <div class="about" >
                    <b class="title" > <?php echo $itemarray["StockItemName"]?> </b>
                    <h3 class="subtitle" > Artikelnummer: <?php echo $itemarray["StockItemID"]?></h3>
                </div>
                <form action="cartfuncties.php" target="_self">
                    <div class="count">
                      <input type="submit" class="btn" id="cartitem" name="quantitymin" value="-">
                      <?php print($amount)?>
                      <input type="submit" class="btn" id="cartitem" name="quantityplus" value="+">
                      <input type="hidden" class="btn" id="cartitem" name="cartitemid" value="<?php print($itemarray["StockItemID"])?>">
                      <input type="hidden" class="btn" id="cartitem" name="amount" value="<?php print($amount)?>">
                    </div>
                </form>
                <div class="prices"  >
                    <form action="cartfuncties.php" target="_self">
                        <div class="amount" > <?php print($itemarray["SellPrice"] * $amount)?></div>
                        <input type="hidden" id="cartitem" name="removecartitemid" value="<?php print($itemarray["StockItemID"])?>">
                        <input type="submit" id="cartitem" class="remove" name="removecartitem" value="Verwijder">
                    </form>
                </div>
            </div>
            <br>
        <?php endforeach; ?>
        <hr>
        <!--Totaal-->
        <div class="checkout">
            <div class="total">
                <div>
                    <div class="Subtotal">Totaal</div>
                    <div class="items">Aantal: <?php print($amountarikels)?></div>
                </div>
                <div class="total-amount"><?php print("€ " . $total)?></div>
            </div>
            <a href="checkout.php" class="buttonOrange buttonOrange2" type="submit" style="padding: 2%; width: 100%">Afrekenen</a>
        </div>
    </div>
    <?php
    while($br < 8) {
        print("<br>");
        $br++;
    }
    ?>
<?php } ?>
<?php
include __DIR__ . "/footer.php";
?>
