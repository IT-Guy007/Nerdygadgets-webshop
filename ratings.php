<!doctype html>
<html lang="en">
<head>
    <?php
    include __DIR__ . "/header.php";
    $loggedin = $_SESSION['loggedin'];
    $customerid = $_SESSION['customerid'];

    ?>

    <meta charset="UTF-8">
    <title>Rating system</title>
    <script src="https://kit.fontawesome.com/c24ff09cab.js" crossorigin="anonymous"></script>

</head>
<body>
    <div style="background: rgb(36, 41, 54); padding: 50px">
        <i class="fa fa-star fa-2x" data-index="0"></i>
        <i class="fa fa-star fa-2x" data-index="1"></i>
        <i class="fa fa-star fa-2x" data-index="2"></i>
        <i class="fa fa-star fa-2x" data-index="3"></i>
        <i class="fa fa-star fa-2x" data-index="4"></i>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>


        var ratedIndex = -1;

        $(document).ready(function () {
            resetStarColors();

            <?php
            $loggedin=true;
            if($loggedin){ ?>

            $('.fa-star').on('click', function (){
                ratedIndex = parseInt($(this).data('index'));
                localStorage.setItem('ratedIndex', ratedIndex);
            });

            $('.fa-star').mouseover(function () {
               resetStarColors();
                var currentIndex = parseInt($(this).data('index'));

                for (var i = 0; i <= currentIndex; i++)
                    $('.fa-star:eq(' + i + ')').css('color', 'orange');
            });

            <?php }
            else{ ?>
                resetStarColors();
                <?php } ?>

            $('.fa-star').mouseleave(function () {
               resetStarColors();

               if(ratedIndex != -1)
                   for (var i = 0; i <= ratedIndex; i++)
                       $('.fa-star:eq(' + i + ')').css('color', 'orange');
            });
        });
        function resetStarColors(){
            $('.fa-star').css('color', 'white');
        }

    </script>
</body>



</html>