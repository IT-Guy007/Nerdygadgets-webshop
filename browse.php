<html>
<body>
<?php
include __DIR__ . "/header.php";
include __DIR__ . "/functions.php"
?>
<?php
if(count($ReturnableResult) > 0){
    $resultaten=TRUE;
}
else {
    $resultaten=FALSE;
}
?>
<div id="page-container">
    <div id="content-wrap">
        <form style="">
            <?php if($resultaten){ ?>
            <div id="FilterFrame1">
                <b class="FilterTopMargin"><i class="fas fa-list-ol"></i> Aantal producten op pagina</b>
                <input type="hidden" name="category_id" id="category_id"
                       value="<?php print (isset($_GET['category_id'])) ? $_GET['category_id'] : ""; ?>">
                <select name="products_on_page" id="products_on_page" onchange="this.form.submit()"
                        style="height: 40px; border-radius: 10px">>
                    <option value="25" <?php if ($_SESSION['products_on_page'] == 25) {
                        print "selected";
                    } ?>>25
                    </option>
                    <option value="50" <?php if ($_SESSION['products_on_page'] == 50) {
                        print "selected";
                    } ?>>50
                    </option>
                    <option value="100" <?php if ($_SESSION['products_on_page'] == 100) {
                        print "selected";
                    } ?>>100
                    </option>
                </select>
            </div>
            <div id="FilterFrame2">
                <b class="FilterTopMargin"><i class="fas fa-sort"></i> Sorteren</b>
                <select name="sort" id="sort" onchange="this.form.submit()" style="height: 40px;border-radius: 10px">>
                    <option value="price_low_high" <?php if ($_SESSION['sort'] == "price_low_high") {
                        print "selected";
                    } ?>>Prijs oplopend
                    </option>
                    <option value="price_high_low" <?php if ($_SESSION['sort'] == "price_high_low") {
                        print "selected";
                    } ?>>Prijs aflopend
                    </option>
                    <option value="name_low_high" <?php if ($_SESSION['sort'] == "name_low_high") {
                        print "selected";
                    } ?>>Naam oplopend
                    </option>
                    <option value="name_high_low" <?php if ($_SESSION['sort'] == "name_high_low") {
                        print "selected";
                    } ?>>Naam aflopend
                    </option>
                </select>
            </div>
        </form>
        <?php }
        ?>


        <div id="ResultsArea" class="Browse">
            <?php
            if (isset($ReturnableResult) && count($ReturnableResult) > 0) {
                foreach ($ReturnableResult as $row) {
                    ?>
                    <a class="ListItem" href='view.php?id=<?php print $row['StockItemID']; ?>'>

                        <div id="ProductFrame">
                            <?php
                            if (isset($row['ImagePath'])) { ?>
                                <div class="ImgFrame"
                                     style="background-image: url('<?php print "public/stockitemimg/" . $row['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;"></div>
                            <?php } else if (isset($row['BackupImagePath'])) { ?>
                                <div class="ImgFrame"
                                     style="background-image: url('<?php print "public/stockgroupimg/" . $row['BackupImagePath'] ?>'); background-size: cover;"></div>
                            <?php }
                            ?>

                            <div id="StockItemFrameRight">
                                <div class="CenterPriceLeftChild">
                                    <h1 class="StockItemPriceText"><?php print sprintf(" %0.2f", berekenVerkoopPrijs($row["RecommendedRetailPrice"], $row["TaxRate"])); ?></h1>
                                    <h6>Inclusief BTW </h6>
                                </div>
                            </div>
                            <h1 class="StockItemID">Artikelnummer: <?php print $row["StockItemID"]; ?></h1>
                            <p class="StockItemName"><?php print $row["StockItemName"]; ?></p>
                            <p class="StockItemComments2"><?php print $row["MarketingComments"]; ?></p>
                            <h4 class="ItemQuantity"><?php print getVoorraadTekst($row["QuantityOnHand"]); ?></h4>
                        </div>
                    </a>
                <?php } ?>
                <form id="PageSelector">
                    <input type="hidden" name="search_string" id="search_string"
                           value="<?php if (isset($_GET['search_string'])) {
                               print ($_GET['search_string']);
                           } ?>">
                    <input type="hidden" name="sort" id="sort" value="<?php print ($_SESSION['sort']); ?>">
                    <input type="hidden" name="category_id" id="category_id"
                           value="<?php if (isset($_GET['category_id'])) {
                               print ($_GET['category_id']);
                           } ?>">
                    <input type="hidden" name="result_page_numbers" id="result_page_numbers"
                           value="<?php print (isset($_GET['result_page_numbers'])) ? $_GET['result_page_numbers'] : "0"; ?>">
                    <input type="hidden" name="products_on_page" id="products_on_page"
                           value="<?php print ($_SESSION['products_on_page']); ?>">

                    <?php
                    if ($AmountOfPages > 0) {
                        for ($i = 1; $i <= $AmountOfPages; $i++) {
                            if ($PageNumber == ($i - 1)) {
                                ?>
                                <div id="SelectedPage"><?php print $i; ?></div><?php
                            } else { ?>
                                <button id="page_number" class="PageNumber" value="<?php print($i - 1); ?>"
                                        type="submit"
                                        name="page_number"><?php print($i); ?></button>
                            <?php }
                        }
                    }
                    ?>
                </form>
                <?php
            } else {
                $br = 0;
                while ($br < 8) {
                    print("<br>");
                    $br++;
                }
                ?>
                <h2 id="NoSearchResults">
                    Yarr, er zijn geen resultaten gevonden.

                </h2>

                <?php
                $br = 0;
                while ($br < 15) {
                    print("<br>");
                    $br++;
                }
            }
            ?>
        </div>
    </div>
    <?php
    include __DIR__ . "/footer.php";
    ?>
</div>
</body>

</html>