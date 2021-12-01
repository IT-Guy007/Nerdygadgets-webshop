<?php
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/header.php";
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
            <h3 class="Action" href="/cartfuncties.php" >Verwijder alle items</h3>
        </div>
        <!--Voor elke item-->
        <?php
        foreach($cart as $item => $amount):
            $itemarray = (getItemDetails($item,$databaseConnection));
            $total = $total + ($itemarray["SellPrice"] * $amount);
            ?>
            <div class="Cart-Items">
              <div class="image-box">
                <img src = "<?php if (!empty($itemarray["BackupImagePath"])) {
                    print("/nerdygadgets/public/stockgroupimg/" . $itemarray["BackupImagePath"]);
                }  else { print("/nerdygadgets/public/img/nologo.png");} ?>" style ={{ height = "120px" }} />
            </div>
            <div class="about" >
                <h2 class="title" > <?php echo $itemarray["StockItemName"]?> </h2>
                <h3 class="subtitle" > Artikelnummer: <?php echo $itemarray["StockItemID"]?></h3>
            </div>
            <div class="counter">
                <div class="btn" > - </div>
                <div class="count" > <?php print($amount)?></div>
                <div class="btn" > +</div>
            </div>
            <div class="prices" >
                <div class="amount" > <?php print($itemarray["SellPrice"] * $amount)?></div>
                <div class="remove" ><u>Verwijder</u></div>
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
                    <div class="items">Aantal: <?php print(count($cart))?></div>
                </div>
                <div class="total-amount"><?php print("€ " . $total)?></div>
            </div>
            <button href="checkout.php" class="button">Afrekenen</button></div>
    </div>
    <?php
    $br = 0;
    while($br < 8) {
        print("<br>");
        $br++;
    }

    ?>
<?php } ?>
<?php
include __DIR__ . "/footer.php";
?>
