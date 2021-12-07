<?php

include __DIR__ . "/header.php";
$StockGroups = getStockGroups($databaseConnection);

?>
<div id="Wrap">
    <?php if (isset($StockGroups)) {
        $i = 0;
        foreach ($StockGroups as $StockGroup) {
            if ($i < 6) {
                ?>
                <a href="<?php print "browse.php?category_id=";
                print $StockGroup["StockGroupID"]; ?>">
                    <div id="StockGroup<?php print $i + 1; ?>"
                         style="background-image: url('public/stockgroupimg/<?php print $StockGroup["ImagePath"]; ?>')"
                         class="StockGroups">
                        <h1><?php print $StockGroup["StockGroupName"]; ?></h1>
                    </div>
                </a>
                <?php
            }
            $i++;
        }
    } ?>
</div>

<br> <br> <br>
<?php
include __DIR__ . "/footer.php";
?>
