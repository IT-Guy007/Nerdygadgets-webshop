<?php
include __DIR__ . "/header.php";
?>
<section class="myform-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-area login-form">
                    <div class="form-content">
                        <h2>Login</h2>
                        <p>Met een account kan u makkelijk uw bestellingen bij houden en hoeft u niet elke keer alle informatie in te vullen.</p>
                    </div>
                    <div class="form-input">
                        <h2>Inloggen</h2>
                        <form action="account.php">
                            <div class="form-group">
                                <input type="email"  id="" name="email" required>
                                <label>email</label>
                            </div>
                            <div class="form-group">
                                <input type="password" id="" name="wachtwoord" required>
                                <label>wachtwoord</label>
                            </div>
                            <div class="myform-button">
                                <button class="myform-btn">Inloggen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
include __DIR__ . "/footer.php" ;
?>
