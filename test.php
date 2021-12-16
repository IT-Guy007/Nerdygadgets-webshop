<?php
include __DIR__ . "/header.php";
print("---------------");
$categories = (getAllCustomerCategories($databaseConnection));
print_r($categories);
$countries = getAllCountries($databaseConnection);
print_r($countries);