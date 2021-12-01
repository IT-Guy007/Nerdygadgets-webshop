<?php
include __DIR__ . "/cartfuncties.php";
include __DIR__ . "/header.php";
$br = 0;
$cart = getCart();
?>
<?php
if (empty($cart)) { ?>
<br>
<div class="CartContainer">
    <div class="Header">
        <br>
        <h3 class="Heading" >Winkelwagen is leeg</h3>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </div>
</div>
    <?php } else { ?>
    <div class="CartContainer">
        <div class="Header">
            <h3 class="Heading" style="margin-top: 10px">Winkelwagen</h3>
            <h5 class="Action" >Verwijder alle items</h5>
        </div>
        <!--Voor elke item-->
        <?php
        print_r($cart);
        foreach($cart as $item => $value):
            $itemarray = array();
            if(!empty(getItemDetails($item,$databaseConnection))) {
                $itemarray = (getItemDetails($item,$databaseConnection));
            }
            $total = $total + $itemarray[4]
            ?>
            <div class="Cart-Items">
              <div class="image-box">
                <img src = "source" style ={{ height = "120px" }} />
            </div>
            <div class="about" >
                <h1 class="title" > <?php if(!empty($itemarray[0])) {
                                            print("Artikelnaam" . $itemarray[0]);
                                            }
                                            ?> </h1>
                <h3 class="subtitle" > Artikelnummer <?php print $item?></h3>
            </div>
            <div class="counter">
                <div class="btn" > - </div>
                <div class="count" > 1</div>
                <div class="btn" > +</div>
            </div>
            <div class="prices" >
                <div class="amount" > <?php print("€ " . $itemarray[2])?></div>
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
                <div class="total-amount"><?php print("€ " . $total)?></div>
            </div>
            <button href="checkout.php" class="button">Afrekenen</button></div>
    </div>
       }
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
