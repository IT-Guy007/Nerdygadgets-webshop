<!doctype html>
<html lang="en">
<head>
    <?php
    include __DIR__ . "/header.php";
    $loggedin = $_SESSION['loggedin'];
    $customerid = $_SESSION['customerid'];
    ?>
    <title>Rating system 2</title>

</head>
<body>

<?php
$loggedin = true;
if ($loggedin) { ?>
    <div style="background: rgb(36, 41, 54); padding: 50px">
        <form class="ratingForm" action="ratings2.php" method="POST">
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

<?php
function insertRating($rating, $databaseConnection){

    $query = "
    INSERT INTO ratings
    VALUES (NULL, )
    
    "


}

function getRating($databaseConnection){

    $query = "
            SELECT StockItemID, ROUND(AVG(Rating),2) AS rating_average 
            FROM ratings 
            WHERE StockItemID = 1";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
}

print("De beoordeling is " . getRating($databaseConnection));


?>


</body>
</html>