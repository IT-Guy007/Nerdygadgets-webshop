<?php
include "cartfuncties.php";
include __DIR__ . "/header.php";

if(!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <!-- Javascript -->
    <script src="public/js/fontawesome.js"></script>
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/popper.min.js"></script>
    <script src="public/js/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="public/css/typekit.css">

    <meta charset="UTF-8">
    <title>Winkelwagen</title>
</head>
<body>

<?php
$cart = getCart();
if(is_null($cart)) {
    print_r("Lege winkelwagen");
} else {
    print_r($cart);
}
//gegevens per artikelen in $cart (naam, prijs, etc.) uit database halen
//totaal prijs berekenen
//mooi weergeven in html
//etc.

?>
</body>
</html>

<?php
include __DIR__ . "/footer.php";
?>
