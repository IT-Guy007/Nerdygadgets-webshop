<?php
session_start();
include "database.php";
$databaseConnection = connectToDatabase();
if(!isset($_SESSION)) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NerdyGadgets</title>
    <meta name="robots" content="noindex">

    <!-- Javascript -->
    <script src="public/js/fontawesome.js"></script>
    <script src="public/js/jquery.min.js"></script>
    <script src="public/js/bootstrap.min.js"></script>
    <script src="public/js/popper.min.js"></script>
    <script src="public/js/resizer.js"></script>
    <script src="public/js/js.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="public/css/style.css" type="text/css">
    <link rel="stylesheet" href="public/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="public/css/typekit.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bitter:400,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,900">
    <link rel="stylesheet" href="public/css/styleheader.css">

</head>
<body>
<div class="loader-wrapper">
    <span class="loader"><span class="loader-inner"></span></span>
</div>
<script>
    $(window).on("load",function(){
        $(".loader-wrapper").fadeOut("slow");
    });
</script>

<div>
    <!--Nav bar1-->
    <div class="header-dark">
        <nav class="navbar navbar-dark navbar-expand-md navigation-clean-search">
            <div class="container">
                <div class="collapse navbar-collapse"
                     id="navcol-1">
                    <ul class="nav navbar-nav">
                        <li class="nav-item" role="presentation"><a class="nav-link" </a></li> <!-- Om in het midden te zetten -->
                        <li class="nav-item" role="presentation"><a class="nav-link" </a></li> <!-- Om in het midden te zetten -->
                        <li class="nav-item" role="presentation"><a class="nav-link" </a></li> <!-- Om in het midden te zetten -->
                        <li class="nav-item" role="presentation"><a class="nav-link" </a></li> <!-- Om in het midden te zetten -->
                        <li class="nav-item" role="presentation"><a class="nav-link" href="categories.php">Categorieën</a></li>
                    </ul>
                    <a href="index.php"> <img alt="cart" src="public/img/logo.png"</a>
                    <a class="navbar-brand"> </a>
                    <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <form class="form-inline mr-auto" target="_self">
                        <div class="form-group">
                            <label for="search-field"><i class="fa fa-search"></i></label>
                            <input class="form-control search-field" type="search" name="search" id="search-field" placeholder="Zoeken..."  value="<?php print (isset($_GET['search_string'])) ? $_GET['search_string'] : ""; ?> "></div>
                    </form>
                    <span class="navbar-text"><a href="login.php" class="login">Log In</a></span>
                    <a href="cart.php"> <img alt="cart" src="public/img/cart-white.png" style="width:30px;height:30px;"</a>
                </div>
            </div>
        </nav>
    </div>

    <!--Nav bar2 with categories-->
    <div class="header-dark" >
        <nav class="navbar navbar-dark navbar-expand-md navigation-clean-search">
            <div class="container">
                <div class="collapse navbar-collapse"
                     id="navcol-1">
                    <ul class="nav navbar-nav">

                        <?php
                        $HeaderStockGroups = getHeaderStockGroups($databaseConnection);
                        foreach ($HeaderStockGroups as $HeaderStockGroup) {
                            ?>
                            <li class="nav-item" role="presentation"><a class="nav-link"
                                <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                                   class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>


