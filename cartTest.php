<?php
include "CartFuncties.php";

$cart = getCart();
print_r($cart);

addProductToCart(3,1);
$cart = getCart();
print_r($cart);
$empty = array();
saveCart($empty);

addProductToCart(4,-1);
$cart = getCart();
print_r($cart);
$empty = array();
saveCart($empty);

addProductToCart(0,0);
$cart = getCart();
print_r($cart);
$empty = array();
saveCart($empty);

addProductToCart(-4,2);
$cart = getCart();
print_r($cart);
$empty = array();
saveCart($empty);

addProductToCart(4,0);
$cart = getCart();
print_r($cart);
$empty = array();
saveCart($empty);