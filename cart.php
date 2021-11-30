<?php
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/header.php";
$databaseConnection = connectToDatabase();
$br = 0;
if (isset($_GET['emptycart'])) {
    $cart = array();
}
?>
<?php
$cart = getCart();
if (empty($cart)) { ?>
<br>
<div class="CartContainer">
    <div class="Header">
        <br>
        <h3 class="Heading" >Winkelwagen is leeg</h3>
        <br>
        <br>
    </div>
</div>
    <?php
    while($br < 5) {
        print("<br>");
        $br++;
    }
    ?>

<?php } else { ?>
    <div class="CartContainer">
        <div class="Header">
            <h3 class="Heading" style="margin-left: ">Winkelwagen</h3>
            <h5 href="cart.php?emptycart=true" class="Action" >Verwijder alle items</h5>
        </div>

        <!--Voor elke item-->
        <?php
        foreach($cart as $item => $value):
            ?>
            <div class="Cart-Items">
              <div class="image-box">
                <img src = "source" style ={{ height = "120px" }} />
            </div>
            <div class="about" >
                <h1 class="title" > <?php print("Artikelnaam")?> </h1>
                <h3 class="subtitle" > Artikelnummer <?php print $item?></h3>
            </div>
            <div class="counter">
                <div class="btn" > - </div>
                <div class="count" > 1</div>
                <div class="btn" > +</div>
            </div>
            <div class="prices" >
                <div class="amount" > €0</div>
                <div class="remove" ><u > Verwijder</u ></div>
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
                <div class="total-amount">€0</div>
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
