<?php
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


