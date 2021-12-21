<!doctype html>
<html lang="en">
<head>
    <?php
    include __DIR__ . "/header.php";
    $loggedin = $_SESSION['loggedin'];
    $customerid = $_SESSION['customerid'];
    ?>
    <meta charset="UTF-8">
    <title>Rating system 2</title>

</head>
<body>

<?php
$loggedin = true;
if ($loggedin) { ?>
    <div style="background: rgb(36, 41, 54); padding: 50px">
        <form class="ratingForm" action="" method="POST">
            <button class="buttonStar" type="submit" name="rating[rating]" value="5">★</button>
            <button class="buttonStar" type="submit" name="rating[rating]" value="4">★</button>
            <button class="buttonStar" type="submit" name="rating[rating]" value="3">★</button>
            <button class="buttonStar" type="submit" name="rating[rating]" value="2">★</button>
            <button class="buttonStar" type="submit" name="rating[rating]" value="1">★</button>
        </form>
    </div>
<?php }

?>


</body>
</html>