<?php
function connectToDatabase() {
    $Connection = null;
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
    try {
        $Connection = mysqli_connect("127.0.0.1", "root", "root", "nerdygadgets");
        mysqli_set_charset($Connection, 'latin1');
        $DatabaseAvailable = true;
    } catch (mysqli_sql_exception $e) {
        $DatabaseAvailable = false;
    }
    if (!$DatabaseAvailable) {
        ?><p1>Gast stop met dat klote wachtwoord eruit te halen.</p1><?php
        die();
    }

    return $Connection;
}
function getHeaderStockGroups($databaseConnection) {
    $Query = "
                SELECT StockGroupID, StockGroupName, ImagePath
                FROM stockgroups 
                WHERE StockGroupID IN (
                                        SELECT StockGroupID 
                                        FROM stockitemstockgroups
                                        ) AND ImagePath IS NOT NULL
                ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $HeaderStockGroups = mysqli_stmt_get_result($Statement);
    return $HeaderStockGroups;
}

function getStockGroups($databaseConnection) {
    $Query = "
            SELECT StockGroupID, StockGroupName, ImagePath
            FROM stockgroups 
            WHERE StockGroupID IN (
                                    SELECT StockGroupID 
                                    FROM stockitemstockgroups
                                    ) AND ImagePath IS NOT NULL
            ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $StockGroups = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $StockGroups;
}

function getStockItem($id, $databaseConnection) {
    $Result = null;

    $Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            CONCAT('Voorraad: ',QuantityOnHand)AS QuantityOnHand,
            SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}

function getStockItemImage($id, $databaseConnection) {

    $Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}

function getItemDetails($id,$databaseConnection) {
    $query = "
                    SELECT SI.StockItemID, SI.StockItemName, SI.MarketingComments, TaxRate, RecommendedRetailPrice,
                    ROUND(SI.TaxRate * SI.RecommendedRetailPrice  / 100 + SI.RecommendedRetailPrice,2) as SellPrice,
                    QuantityOnHand,
                    (SELECT ImagePath FROM stockitemimages WHERE StockItemID  = SI.StockItemID  LIMIT 1) as ImagePath,
                    (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockGroupID = SI.StockItemID LIMIT 1) as BackupImagePath
                    FROM stockitems AS SI
                    JOIN stockitemholdings SIH USING(stockitemid)
                    JOIN stockitemstockgroups USING(StockItemID)
                    JOIN stockgroups ON stockitemstockgroups.StockGroupID = stockgroups.StockGroupID
                    WHERE SI.StockItemID = $id
                    GROUP BY StockItemID";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

    return $output[0];
}

function login($email,$password,$databaseConnection) {
    $query = "
                    SELECT CustomerID, Password
                    FROM accounts
                    WHERE Email = '$email' AND Password = '$password'
                    ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

    foreach($output as $key => $value) {
        if(empty($value)) {
            unset($output[$key]);
        }
    }
    if(!empty($output)) {
        $_SESSION['loggedin'] = true;
        $_SESSION['customerid'] = $output[0]['CustomerID'];
    } else {
        $_SESSION['loggedin'] = false;
    }
}

function forgotPassword($email,$newPassword,$databaseConnection) {
    $query = "
                UPDATE accounts
                SET Password = '$newPassword'
                WHERE Email = '$email'
             ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
}

function temperatuur($databaseConnection) {
    $query = "
                SELECT coldroomtemperatures.Temperature
                FROM nerdygadgets.coldroomtemperatures
                WHERE (SELECT MAX(coldroomtemperatures.RecordedWhen)
                FROM nerdygadgets.coldroomtemperatures
                GROUP BY coldroomtemperatures.RecordedWhen
                ORDER  BY coldroomtemperatures.RecordedWhen)
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $resultaat = mysqli_stmt_get_result($statement);
    $resultaat = mysqli_fetch_all($resultaat, MYSQLI_ASSOC);
}

function updateStock($databaseConnection) {
    $cart = getCart();
    foreach($cart as $item => $amount) {
        $query = "
                    SELECT QuantityOnHand
                    FROM stockitemholdings
                    WHERE StockItemID = '$item'
                 ";
        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
        $currentStock = mysqli_stmt_get_result($statement);
        $currentStock = mysqli_fetch_all($currentStock, MYSQLI_ASSOC);


        foreach($currentStock as $quantityOnHand => $amountBefore) {
            $newQuantity = $quantityOnHand - $amount;
            $query = "
                UPDATE stockitemholdings
                SET QuantityOnHand = '$newQuantity'
                WHERE StockItemID = '$item'
                ";
            $statement2 = mysqli_prepare($databaseConnection, $query);
            mysqli_stmt_execute($statement2);
        }
    }
}

function getCustomerName($id,$databaseConnection) {
    $query = "
                SELECT CustomerName
                FROM customers
                WHERE CustomerID = '$id'
    ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

    $customerid = $output[0]['CustomerName'];
    return $customerid;

}

function createOrder($customerID) {
    $query = "
                SELECT MAX(OrderID) AS max
                FROM orders
    ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

    $highestOrderNumber = $output[0]['max'];
    $ordernumber = $highestOrderNumber + 1;
    $customerID = $_SESSION['customerid'];
    $date = date('y-m-d');

    $query = "
                INSERT INTO orders(OrderID,CustomerID,OrderDate)
                VALUES('$ordernumber','$customerID','$date')
    ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);

}

function getCustomerDetails($customerID,$databaseConnection) {
    $query = "
                SELECT C.CustomerName, C.DeliveryPostalCode, C.DeliveryAddressLine1, CI.CityName, C.PhoneNumber
                FROM customers AS C
                JOIN cities CI ON C.DeliveryCityID = CI.CityID
                WHERE CustomerID = '$customerID'
             ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

    return $output[0];
}