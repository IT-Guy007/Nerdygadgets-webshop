<?php
include __DIR__ . "/header.php";
$lastsold = getLastStockItemSold($databaseConnection);
?>
<div class="IndexStyle">
    <div class="col-11">
        <div class="TextPrice">
                <div class="TextMain">
                    <?php print("Laatst verkocht:<br><br>" . $lastsold[0]['SearchDetails'])?>
                </div>
                <ul id="ul-class-price">
                    <br>
                    <li class="HomePagePrice">€
                        <?php
                        $taxrate = $lastsold[0]['TaxRate'];
                        $Price = $lastsold[0]['RecommendedRetailPrice'];
                        print(number_format(((($taxrate / 100) + 1) * $Price),2));
                    ?>
                    </li>
                </ul>
        </div>
        <div style="background-image: url('/public/stockitemimg/<?php print($lastsold[0]['ImagePath'])?>');background-size: 100% 100%;width: 477px;height: 477px;background-repeat: no-repeat;margin-left: 60%;margin-top: -25%;"></div>
    </div>
</div>
<br><br><br>
<?php
include __DIR__ . "/footer.php";
?>

