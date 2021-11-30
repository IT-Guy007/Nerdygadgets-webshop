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
    <br>

    <input type="text" placeholder="Vul hier uw gebruikersnaam in" name="uname" required style="width: 30%">
    <br>
    <label for="psw"><b></b></label>
    <br>
    <input type="password" placeholder="Vul hier uw wachtwoord in" name="psw" required style="width: 30%">
    <br> <br>
</div>
<div class="inloggen" style="display: flex;">
    <input type="submit" value="Inloggen" style="width:20%;background-color:#e1e1e1;">
    <input type="checkbox" id="remember" value="Herinner mij" style="width: 40px; margin-left: 2%">
    <label for="remember" style="margin: 1%">Gebruikersnaam opslaan</label>
    <br>
</div>
<div>
    <a href="login.php">Wachtwoord vergeten?</a>
</div>


</form>
