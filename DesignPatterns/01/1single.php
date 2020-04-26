<?php

class Dog
{
	private static $obj;
	private function __construct(){

	}
	private function __clone(){

	}
	static public function getInstance(){
		if (!is_object(self::$obj)){
			self::$obj = new Dog();
		}
		return self::$obj;
	}

}

//$dog1 = new Dog();
//var_dump($dog1);

$dog2 = Dog::getInstance();
var_dump($dog2);

$dog3 = clone $dog2;

var_dump($dog3);