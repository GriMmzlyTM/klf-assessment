<?php

///////////////////////////////////////
/// Includes
///
include 'order.php';


$region = "qc";
$coupon = "BREXIT";

$order = new Order($region, 35.99, $coupon);

echo $order->Charge();