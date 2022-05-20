<?php
function connectToDatabase() {
    $Connection = null;
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
    try {
        $Connection = mysqli_connect("192.168.1.1", "group4", "Qwerty1@", "nerdygadgets");
        mysqli_set_charset($Connection, 'latin1');
        $DatabaseAvailable = true;
    } catch (mysqli_sql_exception $e) {
        $DatabaseAvailable = false;
    }
    if (!$DatabaseAvailable) {
        ?><h1>Verbinding met database is mislukt</h1><?php
        die();
    }
*
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
            CONCAT(QuantityOnHand)AS QuantityOnHand,
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
                WHERE Email = '$email'
                    ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output, MYSQLI_ASSOC);

    foreach ($output as $key => $value) {
        if (empty($value)) {
            unset($output[$key]);
        }
    }

    $hash = $output[0]['Password'];
    if (!empty($output)) {
        if (password_verify($password, $hash)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['customerid'] = $output[0]['CustomerID'];
            return true;

        } else {
            $_SESSION['loggedin'] = false;
            return false;
        }
    }
}

function forgotPassword($email,$newPassword,$databaseConnection) {
    $newPassword = encryptPassword($newPassword);
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
                SELECT Temperature
                FROM coldroomtemperatures
                ORDER BY ColdRoomTemperatureID DESC
                LIMIT 1
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $resultaat = mysqli_stmt_get_result($statement);
    $resultaat = mysqli_fetch_all($resultaat, MYSQLI_ASSOC);
    $output = implode(" ", $resultaat[0]);
    return $output;
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

        $quantityOnHand = $currentStock[0]['QuantityOnHand'];

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

function createOrder($customerID,$databaseConnection) {
    //create order
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
    $date = date('y-m-d');
    $deliverytime = date('y-m-d', strtotime("+1 day"));

    $query = "
                INSERT INTO orders(OrderID,CustomerID,OrderDate,ExpectedDeliveryDate,ContactPersonID,IsUndersupplyBackordered,LastEditedBy,LastEditedWhen,SalespersonPersonID)
                VALUES('$ordernumber','$customerID','$date','$deliverytime','$customerID',1,1,'$date',2000)
    ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);

    //Orderlines
    $cart = getCart();

    foreach ($cart as $item => $amount) {
        //Get max orderlineID
        $query = "
                SELECT MAX(OrderLineID) AS max
                FROM orderlines
            ";

        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
        $output = mysqli_stmt_get_result($statement);
        $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

        $highestOrderlineNumber = $output[0]['max'];
        $orderLine = $highestOrderlineNumber + 1;
        $now = date("Y-m-d H:i:s");

        //get description of item
        $query = "
                SELECT StockItemName
                FROM stockitems
                WHERE StockItemID = '$item'
                ";

        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
        $output = mysqli_stmt_get_result($statement);
        $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

        $description = $output[0]['StockItemName'];
        //Insert item into orderlines
        $query = "
                INSERT INTO orderlines(OrderLineID,OrderID,Description,StockItemID,Quantity,PackageTypeID,PickedQuantity,TaxRate,LastEditedBy,LastEditedWhen)
                VALUES('$orderLine','$ordernumber','$description','$item','$amount',15,0,15.000,1,'$now')
                ";

        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
    }
    updateStock($databaseConnection);
    emptyCart($cart);
    return true;

}

function createOrderGuest($name,$address,$zipcode,$city,$country,$telnumber,$databaseConnection) {
    //create customer
    $customerID = createCustomerID($databaseConnection);
    $deliveryCityID = getCityID($city,$databaseConnection);
    $countryID = getCountryID($country,$databaseConnection);
    $now = date("Y-m-d H:i:s");
    $now2 = date("Y-m-d");
    $name = $name . " " . rand(1000000000,9999999999);
    //Insert data into customers
    $query = "
            INSERT INTO customers(CustomerID,CustomerName,DeliveryAddressLine1,PhoneNumber,DeliveryCityID,countryid,BillToCustomerID,CustomerCategoryID,PrimaryContactPersonID,DeliveryMethodID,PostalCityID,AccountOpenedDate,StandardDiscountPercentage,IsStatementSent,IsOnCreditHold,PaymentDays,FaxNumber,WebsiteURL,PostalAddressLine1,PostalPostalCode,DeliveryPostalCode,LastEditedBy,ValidFrom,ValidTo)
            VALUES      ('$customerID','$name','$address','$telnumber','$deliveryCityID','$countryID','$customerID',10,'$customerID',3,'$deliveryCityID','$now2',0.000,0,0,7,'000000000','guest.nerdygadgets.nl','-','-','$zipcode',1,'$now','9999-12-31 23:59:59')
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);

    //Create order
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
    $date = date('y-m-d');
    $deliverytime = date('y-m-d', strtotime("+1 day"));

    $query = "
                INSERT INTO orders(OrderID,CustomerID,OrderDate,ExpectedDeliveryDate,ContactPersonID,IsUndersupplyBackordered,LastEditedBy,LastEditedWhen,SalespersonPersonID)
                VALUES('$ordernumber','$customerID','$date','$deliverytime','$customerID',1,1,'$date',2000)
    ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);

    //Orderlines
    $cart = getCart();

    foreach ($cart as $item => $amount) {
        //Get max orderlineID
        $query = "
                SELECT MAX(OrderLineID) AS max
                FROM orderlines
            ";

        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
        $output = mysqli_stmt_get_result($statement);
        $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

        $highestOrderlineNumber = $output[0]['max'];
        $orderLine = $highestOrderlineNumber + 1;
        $now = date("Y-m-d H:i:s");

        //get description of item
        $query = "
                SELECT StockItemName
                FROM stockitems
                WHERE StockItemID = '$item'
                ";

        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
        $output = mysqli_stmt_get_result($statement);
        $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

        $description = $output[0]['StockItemName'];
        //Insert item into orderlines
        $query = "
                INSERT INTO orderlines(OrderLineID,OrderID,Description,StockItemID,Quantity,PackageTypeID,PickedQuantity,TaxRate,LastEditedBy,LastEditedWhen)
                VALUES('$orderLine','$ordernumber','$description','$item','$amount',15,0,15.000,1,'$now')
                ";

        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
    }
    updateStock($databaseConnection);
    emptyCart();
    return true;

}

function getCustomerDetails($customerID,$databaseConnection) {
    $query = "
                SELECT C.CustomerID, C.CustomerName, C.DeliveryPostalCode, C.DeliveryAddressLine1, CI.CityName, C.PhoneNumber, A.Email, CU.CustomerCategoryName, C.countryid, C.WebsiteURL, C.FaxNumber
                FROM customers AS C
                JOIN cities AS CI ON C.DeliveryCityID = CI.CityID
                JOIN accounts AS A on C.CustomerID = A.CustomerID
                JOIN customercategories CU on CU.CustomerCategoryID = C.CustomerCategoryID

                WHERE C.CustomerID = '$customerID'
             ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

    return $output[0];
}

function getCountryName ($countryID, $databaseConnection) {
    $query = "
            SELECT CountryName
            FROM nerdygadgets.countries
            WHERE CountryID = '$countryID'
            
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);

    return $output[0];
}

function getAllCountries($databaseConnection) {
    $countries = array();
    $query = "
            SELECT CountryName
            FROM countries
            ";

    $result = mysqli_query($databaseConnection,$query);
    while($row = mysqli_fetch_assoc($result)) {
        $countries[] = $row;
    }
    return $countries;
}

function getAllCustomerCategories($databaseConnection) {
    $categories = array();
    $query = "
            SELECT CustomerCategoryID,CustomerCategoryName
            FROM customercategories
            ORDER BY CustomerCategoryID ASC
            ";

    $result = mysqli_query($databaseConnection,$query);
    while($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    return $categories;
}

function checkIfEmailAlreadyExists($email,$databaseConnection) {
    $query = "
                SELECT customerID
                FROM accounts
                WHERE Email = '$email'
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

    if(!empty($output[0]['CustomerID'])) {
        return true;
    } else {
        return false;
    }
}

function checkIfUserAlreadyExists($name,$databaseConnection) {
    $query = "
                SELECT CustomerID as name
                FROM customers
                WHERE CustomerName = '$name'
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $output = $output[0]['name'];

    if($name == $output) {
        return true;
    } else {
        return false;
    }
}

function createCustomerID($databaseConnection) {
    $query = "
                SELECT MAX(CustomerID) AS max
                FROM customers
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $highestCustomerNumber = $output[0]['max'];
    $customerID = $highestCustomerNumber + 1;
    return  $customerID;
}

function getCityID($city,$databaseConnection) {
    $query = "
                SELECT CityID
                FROM cities
                WHERE CityName = '$city'
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

    if(empty($output[0]['CityID'])) {
        //Create new cityID with the cityname
        $query = "
                SELECT MAX(cityID) AS max
                FROM cities
                ";

        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
        $output = mysqli_stmt_get_result($statement);
        $output = mysqli_fetch_all($output, MYSQLI_ASSOC);

        $highestCityID = $output[0]['max'];
        $cityID = $highestCityID + 1;
        $city = strtolower($city);
        $city = ucfirst($city);
        $now = date("Y-m-d H:i:s");

        $query = "
            INSERT INTO cities(CityID,CityName,StateProvinceID,LastEditedBy,ValidFrom,ValidTo)
            VALUES('$cityID','$city',100,1,'$now','9999-12-31 23:59:59')
                ";

        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);

        return $cityID;

    } else {
        $cityID = $output[0]['CityID'];
        return $cityID;
    }
}

function getCountryID($country,$databaseConnection) {

    $query = "
                SELECT countryID
                FROM countries
                WHERE CountryName = '$country'
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $countryID = $output[0]['countryID'];
    return $countryID;
}


function createAccount($name,$address,$postcode,$fax,$city,$country,$phonenumber,$email,$password,$website,$accounttype,$databaseConnection) {

    $customerID = createCustomerID($databaseConnection);
    $deliveryCityID = getCityID($city,$databaseConnection);
    $countryID = getCountryID($country,$databaseConnection);
    $now = date("Y-m-d H:i:s");
    $password = encryptPassword($password);

    //Insert data into customers
    $query = "
            INSERT INTO customers(CustomerID,CustomerName,DeliveryAddressLine1,PhoneNumber,DeliveryCityID,countryid,BillToCustomerID,CustomerCategoryID,PrimaryContactPersonID,DeliveryMethodID,PostalCityID,AccountOpenedDate,StandardDiscountPercentage,IsStatementSent,IsOnCreditHold,PaymentDays,FaxNumber,WebsiteURL,PostalAddressLine1,PostalPostalCode,DeliveryPostalCode,LastEditedBy,ValidFrom,ValidTo)
            VALUES      ('$customerID','$name','$address','$phonenumber','$deliveryCityID','$countryID','$customerID','$accounttype','$customerID',3,'$deliveryCityID','$now',0.000,0,0,7,'$fax','$website','-','-','$postcode',1,'$now','9999-12-31 23:59:59')
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);

    //Insert data into accounts
    $query = "
            INSERT INTO accounts(customerid, email, password)
            VALUES('$customerID','$email','$password')
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    return true;
}

function getLast3Orders($customerID, $databaseConnection) {
    $query = "
                SELECT OrderID,OrderDate
                FROM orders
                WHERE CustomerID = '$customerID'
                ORDER BY OrderID DESC 
                LIMIT 3
            ";

    $result = mysqli_query($databaseConnection,$query);
    while($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    return $orders;
}

function getStockItemIDImageFromOrderID($orderID,$databaseConnection) {
    $query = "
                SELECT StockItemID
                FROM orderlines
                WHERE OrderID = '$orderID'
                ORDER BY OrderLineID
                LIMIT 3
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $stockItemID = $output[0]['StockItemID'];
    $items = count($output);

    for($i=0;$i != $items; $i++) {
        $query = "
                SELECT ImagePath
                FROM stockitemimages
                WHERE StockItemID = '$stockItemID'
            ";
        $statement = mysqli_prepare($databaseConnection, $query);
        mysqli_stmt_execute($statement);
        $output = mysqli_stmt_get_result($statement);
        $output = mysqli_fetch_all($output, MYSQLI_ASSOC);

        $imageurl = array();
        $imageurl[] = $output[$i]['ImagePath'];
    }
    return ($imageurl);
}

function getPriceOfStockItemID($stockItemID,$databaseConnection) {
    $query = "
                SELECT TaxRate, RecommendedRetailPrice
                FROM stockitems
                WHERE StockItemID = '$stockItemID'
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $taxRate = $output[0]['TaxRate'];
    $recommendedRetailPrice = $output[0]['RecommendedRetailPrice'];
    $taxRate = (($taxRate / 100) + 1);
    $sellPrice = (number_format($recommendedRetailPrice * $taxRate,2));
    return $sellPrice;
}

function getOrderTotalPrice($orderID,$databaseConnection) {
    $query = "
                SELECT StockItemID,Quantity
                FROM orderlines
                WHERE OrderID = '$orderID'
                ORDER BY OrderLineID
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $itemsInOrder = count($output);
    for($i = 0;$i != $itemsInOrder; $i++) {
        $stockItemPrice = getPriceOfStockItemID($output[$i]['StockItemID'],$databaseConnection);
        $quantity = $output[$i]['Quantity'];
        $totalprice = $totalprice + ($stockItemPrice * $quantity);
    }
    if($totalprice < 50) {
        $totalprice = $totalprice + 5;
        return $totalprice;
    } else {
        return $totalprice;
    }
}

function getAmountOfItemsInOrder($orderID,$databaseConnection) {
    $query = "
                SELECT Quantity
                FROM orderlines
                WHERE OrderID = '$orderID'
                ORDER BY OrderLineID
            ";

    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $itemsInOrder = count($output);
    for($i = 0;$i != $itemsInOrder; $i++) {
        $quantity = $output[$i]['Quantity'];
        $amount = $amount + $quantity;
    }
    return $amount;
}

function encryptPassword($password) {
    $password = password_hash($password,PASSWORD_DEFAULT);
    return $password;
}

function getLastStockItemSold($databaseConnection) {
    $query = "
                SELECT StockItemID
                FROM orderlines
                ORDER BY OrderLineID DESC
                LIMIT 1
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $stockItemID = $output[0]['StockItemID'];

    $query = "
                SELECT RecommendedRetailPrice, TaxRate, SearchDetails, s.ImagePath
                FROM stockitems
                JOIN stockitemimages s on stockitems.StockItemID = s.StockItemID
                WHERE s.StockItemID = '$stockItemID'
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    return $output;
}

function stockitemIDToStockGroupImgURL($orderID,$databaseConnection) {
    $query = "
                SELECT StockItemID
                FROM orderlines
                WHERE OrderID = '$orderID'
                ORDER BY StockItemID DESC
                LIMIT 1
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $stockItemID = $output[0]['StockItemID'];

    $query = "
                SELECT StockGroupID
                FROM stockitemstockgroups
                WHERE StockItemID = '$stockItemID'
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $stockGroup = $output[0]['StockGroupID'];

    $query = "
                SELECT ImagePath
                FROM stockgroups
                WHERE StockGroupID = '$stockGroup'
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $imagePath = $output[0]['ImagePath'];

    return $imagePath;
}

function updateNAW($customerid,$name,$email,$adres,$postcode,$stad,$land,$tel,$fax,$website,$databaseConnection) {
    $cityid = getCityID($stad,$databaseConnection);
    $countryid = getCountryID($land,$databaseConnection);

    $query = "
                UPDATE customers
                SET CustomerName = '$name', DeliveryAddressLine1 = '$adres', PostalAddressLine1 = '$adres', DeliveryPostalCode = '$postcode', PostalAddressLine1 = '$postcode',countryID = '$countryid', PhoneNumber = '$tel', FaxNumber = '$fax', WebsiteURL = '$website', DeliveryCityID = '$cityid', PostalCityID = '$cityid'
                WHERE customerID = '$customerid'
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);

    $query = "
                UPDATE nerdygadgets.accounts
                SET Email = '$email'
                WHERE customerID = '$customerid'
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    return true;
}

function getLatestOrderID($customderID,$databaseConnection) {
    $query = "
                SELECT OrderID
                FROM orders
                WHERE CustomerID = '$customderID'
                ORDER BY OrderID DESC
                LIMIT 1
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $orderID = $output[0]['OrderID'];

    return $orderID;
}

function getRating($stockItemID,$databaseConnection) {
$query = "  
            SELECT ROUND(AVG(Rating),2) AS average_rating
            FROM ratings 
            WHERE StockItemID = '$stockItemID'
            ";
$statement = mysqli_prepare($databaseConnection, $query);
mysqli_stmt_execute($statement);
$resultaat = mysqli_stmt_get_result($statement);
$resultaat = mysqli_fetch_all($resultaat, MYSQLI_ASSOC);
$output = $resultaat[0]['average_rating'];

return $output;
}

 function insertRating($stockItemID, $rating, $customerID,$databaseConnection) {
    $query= "
              INSERT INTO ratings
              VALUES (NULL, '$stockItemID', '$rating','$customerID')
    ";

     $statement = mysqli_prepare($databaseConnection, $query);
     mysqli_stmt_execute($statement);
}

function countRating($stockItemID, $databaseConnection){
    $query= "
             SELECT COUNT(*) AS ratings
             FROM ratings
             WHERE StockItemID='$stockItemID';
    ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $resultaat = mysqli_stmt_get_result($statement);
    $resultaat = mysqli_fetch_all($resultaat, MYSQLI_ASSOC);
    $output = $resultaat[0]['ratings'];

    return $output;
}

function getLastOrder($databaseConnection) {
    $query = "
                SELECT OrderID
                FROM orders
                WHERE OrderID = (SELECT max(OrderID) FROM orders)
            ";
    $statement = mysqli_prepare($databaseConnection, $query);
    mysqli_stmt_execute($statement);
    $output = mysqli_stmt_get_result($statement);
    $output = mysqli_fetch_all($output,MYSQLI_ASSOC);
    $orderID = $output[0]['OrderID'];

    return $orderID;
}
