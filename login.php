<?php
include __DIR__ . "/header.php";
include __DIR__ . "/functions.php";
include __DIR__ . "/footer.php";
?>

<form
<div class="login" style="margin-left: 25%">
    <br> <br>
    <h1>Inloggen</h1>
    <br>
    <b>Bestaande klant</b>
    <b STYLE="position: relative; left:37%;"> Nog geen account?</b>
    <br>

    <input type="text" placeholder="Gebruikersnaam" name="uname" required style="position: relative; top: 10px; width: 25%">

    <input type="text" placeholder="E-mailadres" name="uname" style="position: relative; left:20%; top: 10px; width: 25%">
    <br>
    <label for="psw"><b></b></label>
    <br>
    <input type="password" placeholder="Wachtwoord" name="psw" required style="width: 25%">
    <input class="buttonInlog buttonInlog1" type="button" value="Account aanmaken" style="margin-left: 20%; width:20%;">

    <br> <br>
</div>
<div class="inloggen" style="display: flex;">
    <input class="buttonInlog buttonInlog1" type="submit" value="Inloggen" style="width:15%;">
    <input type="checkbox" id="remember" value="Herinner mij" style="width: 30px; margin-left: 2%">
    <label for="remember" style="margin: 1%">Gebruikersnaam opslaan</label>

    <br>
</div>
<div>
    <br> <a href="wachtwoord%20vergeten.php" style="margin: 1%">Wachtwoord vergeten?</a>
</div>


</form>
