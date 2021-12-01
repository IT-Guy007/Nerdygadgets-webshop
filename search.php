<?php
$search = isset($_GET['search']) ? $_GET['search'] : '';
header("Location: browse.php?search_string="  . $search . "&category_id=6&products_on_page=25&sort=price_low_high");
exit();