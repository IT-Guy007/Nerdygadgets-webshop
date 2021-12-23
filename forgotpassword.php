<?php
include __DIR__ . "/header.php";
$loggedin = $_SESSION['loggedin'];

if(!$loggedin) {
    echo("<script>location.href = 'account.php';</script>");
}
?>
<section class="myform-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-area login-form">
                    <div class="form-content">
                        <h2>Update wachtwoord</h2>
                        <br>
                        <p>Vul de volgende informatie in om uw wachtwoord te wijzigen</p>
                    </div>
                    <div class="form-input">
                        <h2>Update wachtwoord
                            <?php
                            if((isset($_GET['same']) ? $_GET['same'] : '') == "false") {
                                print(" --Wachtwoorden komen niet overeen--");
                            }
                            ?>
                        </h2>
                        <form action="login.php" target="_self">
                            <div class="form-group">
                                <input type="email"  id="" name="email" required>
                                <label>Email</label>
                            </div>
                            <div class="form-group">
                                <input type="password" id="" name="password1" required>
                                <label>Wachtwoord</label>
                            </div>
                            <div class="form-group">
                                <input type="password" id="" name="password2" required>
                                <label>Herhaal wachtwoord</label>
                            </div>
                            <div class="myform-button">
                                <button class="myform-btn">Update wachtwoord</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include __DIR__ . "/footer.php";
?>
