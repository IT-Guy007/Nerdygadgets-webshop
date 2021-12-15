<?php
include __DIR__ . "/header.php";
$loggedin = $_SESSION['loggedin'];
if($loggedin) {
    echo("<script>location.href = 'account.php';</script>");
}
//forgot password function called in forgotpassword.php
if (!empty(isset($_GET['password1']) ? $_GET['password1'] : '')) {
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $password1 = isset($_GET['password1']) ? $_GET['password1'] : '';
    $password2 = isset($_GET['password2']) ? $_GET['password2'] : '';
    $email = strtolower($email);
    if ($password1 === $password2) {
        $password3 = $password2 = $password1;
        forgotPassword($email,$password3,$databaseConnection);
        echo("<script>location.href = 'account.php';</script>");
    } else {
        echo("<script>location.href = 'forgotpassword.php';</script>");
    }
}

?>
<section class="myform-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-area login-form">
                    <div class="form-content">
                        <h2>Login</h2>
                        <br>
                        <p>Met een account kan u makkelijk uw bestellingen bij houden en hoeft u niet elke keer alle informatie in te vullen. </p>
                    </div>
                    <div class="form-input">
                        <h2>Inloggen
                            <?php
                            if((isset($_GET['login']) ? $_GET['login'] : '') == "false") {
                                print(" -verkeerde combinatie-");
                            }
                            ?>
                        </h2>
                        <form action="account.php" target="_self">
                            <div class="form-group">
                                <input type="email"  id="" name="email" required>
                                <label>email</label>
                            </div>
                            <div class="form-group">
                                <input type="password" id="" name="password" required>
                                <label>wachtwoord</label>
                            </div>
                            <div class="myform-button">
                                <button class="myform-btn">Inloggen</button>
                            </div>
                        </form>
                        <form action="forgotpassword.php"
                        <div class="myform-button">
                            <button class="myform-btn">Wachtwoord vergeten?</button>
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
