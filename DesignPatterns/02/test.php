<?php
//namespace SimpleFactory;
include 'SimpleFactory.php';
include 'Bicycle.php';

$factory = new SimpleFactory();
$bicycle = $factory->createBicycle();
$bicycle->driveTo('Paris');
var_dump($bicycle);