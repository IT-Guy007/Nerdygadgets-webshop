<?php
include __DIR__ . "/header.php";
if(!isset($_SESSION)) {
    session_start();
}
$customerID = $_SESSION['customerid'];
?>
<br>
<br>
<div class="AccountContainer">
    <br>
    <div class="Header">
        <br>
        <br>
        <h3 class="Heading" >Uw betaling was succesvol!</h3>
        <br>
        <br>
        <br>
    </div>
    <br>
    <h3 class="Heading">Uw bestelnummer: <?php print(getLatestOrderID($customerID,$databaseConnection))?></h3>
    <h3 class="Heading">Uw bestelling wordt zo spoedig mogelijk bezorgd</h3>
    <br>
    <br>
</div>
<?php
while($br < 5) {
    print("<br>");
    $br++;
}
include __DIR__ . "/footer.php";
?>
