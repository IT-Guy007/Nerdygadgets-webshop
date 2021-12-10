<?php
if(!isset($_SESSION)) {
    session_start();
}

function getCart(){
    if(isset($_SESSION['cart'])){
        $cart = $_SESSION['cart'];
    } else{
        $cart = array();
    }
    return $cart;
}

function saveCart($cart){
    $_SESSION["cart"] = $cart;
}

function addProductToCart($stockItemID){
    $cart = getCart();

    if(array_key_exists($stockItemID, $cart)){
        $cart[$stockItemID] += 1;
    }else{
        $cart[$stockItemID] = 1;
    }

    saveCart($cart);
}

function emptyCart() {
    $cart = array();
    header('location: cart.php');
    saveCart($cart);
}

function removeCartItem() {
    $cart = getCart();
    $itemid = isset($_GET['removecartitemid']) ? $_GET['removecartitemid'] : '';
    unset($cart[$itemid]);
    saveCart($cart);
    header('location: cart.php');
}

function addQuanitity() {
    $cart = getCart();
    $itemid = isset($_GET['cartitemid']) ? $_GET['cartitemid'] : '';
    $amount = isset($_GET['amount']) ? $_GET['amount'] : '';
    $amount = (int)$amount;
    $cart[$itemid] = $amount + 1;
    saveCart($cart);
    checkIfEmpty();
    header('location: cart.php');
}

function lowerQuantity() {
    $cart = getCart();
    $itemid = isset($_GET['cartitemid']) ? $_GET['cartitemid'] : '';
    $amount = isset($_GET['amount']) ? $_GET['amount'] : '';
    if($amount>1) {
        $amount = (int)$amount - 1;
        $cart[$itemid] = $amount;
        saveCart($cart);
        checkIfEmpty();
        header('location: cart.php');
    }
    else{
        header('location: cart.php');
    }
}

function checkIfEmpty() {
    $cart = getCart();
    foreach ($cart as $item => $amount) {
        if ($amount == 0) {
            unset($cart[$item]);
        }
    saveCart($cart);
    }
}

///  Callen

if (isset($_GET['removecartitemid']) == 'Verwijder') {
    removeCartItem();
}

if (isset($_GET['emptycart']) == 'Verwijder+alle+items') {
    emptyCart();
}

if (isset($_GET['quantitymin']) == '-') {
    lowerQuantity();
}

if (isset($_GET['quantityplus']) == '%2B') {
    addQuanitity();
}