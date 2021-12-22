<?php
include __DIR__ . "/header.php";
$loggedin = $_SESSION['loggedin'];
if($loggedin) {
    echo("<script>location.href = 'account.php';</script>");
}
?>
    <body>
    <form action="account.php" target="_self">
        <div><br><h1 style="font-size: 25px; text-align: center">Account registratie <?php
                if((isset($_GET['register']) ? $_GET['register'] : '') == "true") {
                    print(" -er ging iets fout-");
                } elseif((isset($_GET['emailalreadyexists']) ? $_GET['emailalreadyexists'] : '') == "true") {
                    print(" -Account met het email bestaat al-");
                } elseif((isset($_GET['useralreadyexists']) ? $_GET['useralreadyexists'] : '') == "true") {
                    print(" -Account met deze naam bestaat al-");
                }
                ?></h1>
        <div id="registerfield">
            <div id="nawfield">
                <br>
                <h1 style="font-size: 20px;" class="registertext">Persoonsinformatie</h1>
                <p class="registertext">Alle velden met een * zijn verplicht</p>
                <br>
                <br>

                <label class="reglabel" style="width: 70%" for="name">Voornaam*
                    <input class="regfield" type="text" placeholder="" name="voornaam" required>
                </label><br>
                <label class="reglabel" style="width: 20%" for="middle">Tussenvoegsel
                    <input class="regfield" type="text" placeholder="" name="tussenvoegsel" >
                </label>

                <label class="reglabel" style="width: 49%" for="lastname">Achternaam*
                    <input class="regfield" type="text" placeholder="" name="achternaam" required>
                </label>

                <label class="reglabel" style="width: 50%" for="country">Adres*
                    <input class="regfield" type="text" placeholder="" name="adres" required>
                </label>

                <label class="reglabel" style="width: 20%" for="country">Postcode*
                    <input class="regfield" type="text" placeholder="" name="postcode" required>
                </label>

                <label class="reglabel" style="width: 40%" for="city">Stad*
                    <input class="regfield" type="text" placeholder="" name="stad" required>
                </label>


                <label class="reglabel" style="width: 40%" for="address">Land*
                    <select class="regfield" type="text" name="land" required>
                    <?php
                    $countries = getAllCountries($databaseConnection);
                    print("Test: " . $countries[0][1]);
                    while($countrynumber != count($countries)) {
                        ?><option value="<?php print($countries[$countrynumber]['CountryName'])?>"><?php print($countries[$countrynumber]['CountryName'])?></option>
                        <?php
                        $countrynumber++;
                    } ?>
                    </select>
                </label>

                <label class="reglabel" style="width: 40%" for="telnummer"><b>Telefoonnummer</b>
                    <input class="regfield" type="tel" placeholder="" name="telnumber" size="17">
                </label>

                <label class="reglabel" style="width: 40%" for="faxnummer"><b>Faxnummer</b>
                    <input class="regfield" type="tel" placeholder="" name="faxnummer" size="17">
                </label>

                <label class="reglabel" style="width: 70%" for="website"><b>Website</b>
                    <input class="regfield" type="tel" placeholder="" name="website" size="25">
                </label>



            </div>

            <div id="accountfield">
                <br>
                <h1 style="font-size: 20px">Account informatie</h1>
                <br><br>
                <br>

                <label class="reglabel2" for="registerfield">Ik ben een
                    <select class="regfield" type="text" name="accountsoort" required>
                        <option value="1" name="Agent">Vertegenwoordiger</option>
                        <option value="2" name="Wholesaler">Groothandelaar</option>
                        <option value="3" name="Novelty shop">Novelty Shop</option>
                        <option value="4" name="Supermarket">Supermarkt</option>
                        <option value="5" name="Computer store">Computerwinkel</option>
                        <option value="6" name="Gift store">Cadeauwinkel</option>
                        <option value="7" name="Corporate">Bedrijf</option>
                        <option value="8" name="General Retailer">Detailhandelaar</option>
                        <option value="9" selected name="Customer">Consument</option>
                    </select>
                </label>
                <br>

                <label class="reglabel2"  for="email">E-mailadres*
                    <input class="regfield" type="email" placeholder="" name="email" required size="26">
                </label>
                <br>
                <label class="reglabel2"  for="password">Wachtwoord*
                    <input class="regfield" type="password" placeholder="" name="wachtwoord1" required size="26">
                </label>
                <br>
                <label class="reglabel2"  for="password">Bevestig wachtwoord*
                    <input class="regfield" type="password" placeholder="" name="wachtwoord2" required size="20">
                </label>

                <p>Door een account aan te maken ga je akkoord met onze <a href="#">Terms & Privacy</a>.</p>
                <button type="submit" class="buttonOrange buttonOrange3">Registreren</button>
            </div>
        </div>
    </form>
    </body>
<?php
include __DIR__ . "/footer.php";
?>