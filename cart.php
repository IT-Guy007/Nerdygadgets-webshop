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
        <br>
        <div class="Header">
            <br>
            <br>
            <h3 class="Heading" >Winkelwagen is leeg</h3>
            <br>
            <br>
            <br>
        </div>
        <br>
        <br>
    </div>
    <?php
    while($br < 30) {
        print("<br>");
        $br++;
    }
    ?>
<?php } else { ?>
    <div class="CartContainer">
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
                      <input type="submit" class="btn" id="cartitem" name="quantitymin" value="-">
                      <input type="text" class="btn" id="cartitem" name="amount" value="<?php print($amount)?>" style="width: 50px; padding: 0px">
                      <input type="submit" class="btn" id="cartitem" name="quantityplus" value="+">
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
        <div class="checkout">
            <div class="total">
                <div>
                    <div class="Subtotal">Totaal</div>
                    <div class="items">Aantal: <?php print($amountarikels)?></div>
                </div>
                <div class="total-amount"><?php print("€ " . number_format($total,2))?></div>
            </div>
            <form action="checkout.php">
                <input type="hidden" id="changenaw" value="true">
                <button class="buttonOrange buttonOrange2">Afrekenen</button>
            </form>
        </div>
    </div>
    <?php
    while($br < 12) {
        print("<br>");
        $br++;
    }
    ?>
<?php } ?>
<?php
include __DIR__ . "/footer.php";
?>
