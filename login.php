<?php
include __DIR__ . "/header.php";
include __DIR__ . "/functions.php";
include __DIR__ . "/footer.php";
?>

<form
    <div class="login">
        <br> <br>  <h1><center>Inloggen:</center></h1>
        <br>
        <label for="uname"> <b>Gebruikersnaam:</b></label>
        <input type="text" placeholder="Vul hier uw gebruikersnaam in" name="uname" required>

        <br><br> <label  for="psw"><b>Wachtwoord:</b></label>
        <input type="password" placeholder="Vul hier uw wachtwoord in" name="psw" required >
        <br> <br>
        <br>
        <input type="submit" value="Inloggen" style="background-color: #e1e1e1"
        <br> <br><br>
        <input type="checkbox"  id="remember" value="Herinner mij" >
        <label for="remember"> Herinner mij</label>



    </div>


</form>
