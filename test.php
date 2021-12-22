<?php
include __DIR__ . "/database.php";
$databaseConnection = connectToDatabase();
$query = "
                SELECT StockItemID
                FROM orderlines
                ORDER BY OrderLineID DESC
                LIMIT 1
            ";
$statement = mysqli_prepare($databaseConnection, $query);
mysqli_stmt_execute($statement);
$output = mysqli_stmt_get_result($statement);
$output = mysqli_fetch_all($output,MYSQLI_ASSOC);
$stockItemID = $output[0]['StockItemID'];

$query = "
                SELECT RecommendedRetailPrice, TaxRate, SearchDetails, s.ImagePath
                FROM stockitems
                JOIN stockitemimages s on stockitems.StockItemID = s.StockItemID
                WHERE s.StockItemID = '$stockItemID'
            ";
$statement = mysqli_prepare($databaseConnection, $query);
mysqli_stmt_execute($statement);
$output = mysqli_stmt_get_result($statement);
$output = mysqli_fetch_all($output,MYSQLI_ASSOC);
return $output;