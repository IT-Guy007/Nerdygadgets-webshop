<?php
include __DIR__ . "/header.php";
?>
    <body>
    <form action="">
        <div><br><h1 style="font-size: 25px; text-align: center">Account registratie</h1></div>
        <div id="registerfield">
            <div id="nawfield">
                <br>
                <h1 style="font-size: 20px">Persoonsinformatie</h1>
                <p>Alle velden met een * zijn verplicht</p>
                <br>
                <div>
                    <b>Aanhef**</b><br><br>
                    <input type="radio" value="De heer" name="aanhef" required/>De heer
                    <input style="margin-left: 10px" type="radio" value="Mevrouw" name="aanhef"/>Mevrouw
                </div>
                <br>

                <label for="name"><b>Naam*</b></label><br>
                <input class="regfield" type="text" placeholder="" name="voornaam" required><br>

                <label for="middle"><b>Tussenvoegsel</b></label>
                <input class="regfield" type="text" placeholder="" name="tussenvoegsel" >

                <label for="lastname"><b>Achternaam*</b></label>
                <input class="regfield" type="text" placeholder="" name="achternaam"  required>

                <label for="birthdate"><b>Geboortedatum</b></label>
                <input class="regfield" type="date" placeholder="" name="geboortedatum">


                <label for="country"><b>Land*</b></label>
                <input class="regfield" type="text" placeholder="" name="land" required>

                <label for="province"><b>Provincie</b></label>
                <input class="regfield" type="text" placeholder="" name="provincie">

                <label for="city"><b>Stad*</b></label>
                <input class="regfield" type="text" placeholder="" name="stad" required>

                <label for="address"><b>Adres*</b></label>
                <input class="regfield" type="text" placeholder="" name="adres" required>

                <label for="telnummer"><b>Telefoonnummer</b></label>
                <input class="regfield" type="number" placeholder="" name="telnummber">

            </div>
            <div id="accountfield">
                <br>
                <h1 style="font-size: 20px">Account informatie</h1>
                <br><br><br><br><br>
                <br>
                <label for="email"><b>E-mailadres*</b></label>
                <input class="regfield" type="email" placeholder="" name="email" required>

                <label for="password"><b>Wachtwoord*</b></label>
                <input class="regfield" type="password" placeholder="" name="Wachtwoord" required>

                <label for="password"><b>Bevestig wachtwoord*</b></label>
                <input class="regfield" type="password" placeholder="" name="Wachtwoord herhalen" required>


                <p>Door een account aan te maken ga je akkoord met onze <a href="#">Terms & Privacy</a>.</p>
                <button type="submit" class="buttonOrange buttonOrange3">Registreren</button>
            </div>

        </div>

    </form>
    </body>
<?php
include __DIR__ . "/footer.php";
?>