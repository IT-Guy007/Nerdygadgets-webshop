<?php
include __DIR__ . "/header.php";
include __DIR__ . "/functions.php";
include __DIR__ . "/footer.php";
?>

<form
<div class="login" style="margin-left: 40%">
    <br> <br>
    <h1>Inloggen</h1>
    <b>Bestaande klant</b>
    <b STYLE="position: relative; left:300px;"> Nog geen account?</b>
    <br>

    <input type="text" placeholder="Gebruikersnaam" name="uname" required style="position: relative; top: 10px; width: 40%">

    <input type="text" placeholder="E-mailadres" name="uname"  style="position: relative; left:90px; top: 10px; width: 40%">
    <br>
    <label for="psw"><b></b></label>
    <br>
    <input type="password" placeholder="Wachtwoord" name="psw" required style="width: 40%">
    <input style="width:20%; position: relative; left 300px;" class="buttonInlog buttonInlog1" type="button" value="Account aanmaken">

    <br> <br>
</div>
<div class="inloggen" style="display: flex;">

    <input class="buttonInlog buttonInlog1" type="submit" value="Inloggen" style="width:15%;">
    <input type="checkbox" id="remember" value="Herinner mij" style="width: 25px; margin-left: 2%">
    <label for="remember" style="margin: 1%">Gebruikersnaam opslaan</label>


</div>
<div>
    <br> <a href="wachtwoord%20vergeten.php" style="position: left: 10px;margin-top: 1%">Wachtwoord vergeten?</a>
</div>


</form>
