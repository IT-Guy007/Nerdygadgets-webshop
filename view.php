<?php
include __DIR__ . "/header.php";
include __DIR__ . "/functions.php";

$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);

if (isset($_GET["id"])) {
    $stockItemID = $_GET["id"];
} else {
    $stockItemID = 0;
}
?>

<div id="CenteredContent">
    <?php
    if ($StockItem != null) {
        ?>
        <?php
        if (isset($StockItem['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $StockItem['Video']; ?>
            </div>
        <?php }
        ?>


        <div id="ArticleHeader">
            <?php
            if (isset($StockItemImage)) {
                // één plaatje laten zien
                if (count($StockItemImage) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('public/stockitemimg/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php
                } else if (count($StockItemImage) >= 2) { ?>
                    <!-- meerdere plaatjes laten zien -->
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <li data-target="#ImageCarousel"
                                        data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                    <?php
                                } ?>
                            </ul>

                            <!-- slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="public/stockitemimg/<?php print $StockItemImage[$i]['ImagePath'] ?>">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- knoppen 'vorige' en 'volgende' -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="ImageFrame"
                     style="background-image: url('public/stockgroupimg/<?php print $StockItem['BackupImagePath']; ?>'); background-size: cover;"></div>
                <?php
            }
            ?>
            <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName2">
                <?php print $StockItem['StockItemName']; ?>
            </h2>
            <div class="QuantityText"><?= getVoorraadTekst($StockItem['QuantityOnHand']); ?></div>
            <div class="rating"><?php print("Gemiddelde beoordeling is ★ " . getRating($databaseConnection));?></div>

            <?php
            $loggedin = true;
            if ($loggedin) { ?>
                <div style="background: rgb(36, 41, 54); padding: 50px">
                    <form class="ratingForm" action="">
                        <button class="buttonStar" type="submit" name="rating[5]" value="5">★</button>
                        <button class="buttonStar" type="submit" name="rating[4]" value="4">★</button>
                        <button class="buttonStar" type="submit" name="rating[3]" value="3">★</button>
                        <button class="buttonStar" type="submit" name="rating[2]" value="2">★</button>
                        <button class="buttonStar" type="submit" name="rating[1]" value="1">★</button>
                    </form>
                </div>
            <?php } else { ?>
                <b>Je moet ingelogd zijn om te kunnen beoordelen</b>
            <?php } ?>

            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $StockItem['SellPrice']); ?></b></p>
                        <h6> Inclusief BTW </h6>
                        <br>
                        <br>
                        <br>
                        <h6> Als uw bestelling boven de 50 euro bedraagd, wordt het gratis bezorgd</h6>
                    </div>
                </div>
            </div>
        </div>

        <div id="StockItemDescription">
            <h3>Artikel beschrijving</h3>
            <p><?php print $StockItem['SearchDetails']; ?></p>
            <br>
            <?php
            if ( $stockItemID >= 220 && $stockItemID <= 227) {
                print("<p>Wij koelen dit product huidig op: " . temperatuur($databaseConnection) . " °C</p>");
            }
            ?>
        </div>
        <div id="StockItemSpecifications">
            <h3>Artikel specificaties</h3>
            <?php
            $CustomFields = json_decode($StockItem['CustomFields'], true);
            if (is_array($CustomFields)) { ?>
                <table>
                <thead>
                <th>Naam</th>
                <th>Data</th>
                </thead>
                <?php
                foreach ($CustomFields as $SpecName => $SpecText) { ?>
                    <tr>
                        <td>
                            <?php print $SpecName; ?>
                        </td>
                        <td>
                            <?php
                            if (is_array($SpecText)) {
                                foreach ($SpecText as $SubText) {
                                    print $SubText . " ";
                                }
                            } else {
                                print $SpecText;
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </table><?php
            } else { ?>

                <p><?php print $StockItem['CustomFields']; ?>.</p>
                <?php
            }
            ?>
        </div>
        <?php
    } else {
        $noproduct = true;
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2>
        <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <?php
    } ?>
</div>

<?php
include "cartfuncties.php";
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Artikelpagina (geef ?id=.. mee)</title>
</head>
<body>


<?php if(!$noproduct){ ?>

<form method="post">
    <input type="number" name="stockItemID" value="<?php print($stockItemID) ?>" hidden>
    <input class="buttonOrange buttonOrange1" type="submit" name="submit" value="Voeg toe aan winkelmandje" style="margin:3%;margin-left:35%;width: 30%;">
</form>

<?php }?>
<?php
if (isset($_POST["submit"])) {              // zelfafhandelend formulier
    $stockItemID = $_POST["stockItemID"];
    addProductToCart($stockItemID);         // maak gebruik van geïmporteerde functie uit cartfuncties.php
}
?>


<?php
include __DIR__ . "/footer.php";
?>
