<?php

interface Skill
{
	function family();
	function buy();
}

class Person implements Skill
{
	function family()
	{
		echo '人族在辛苦的伐木<br/>';
	}

	function buy()
	{
		echo '人族在用人民币买房子<br/>';
	}
}

class JingLing implements Skill
{
	function family()
	{
		echo '精灵族在砍树<br/>';
	}

	function buy()
	{
		echo '精灵族在用火币买房子<br/>';
	}
}

class Factory
{
	static function createHero($type)
	{
		switch ($type) {
			case 'Person':
				return new Person();
				break;
			case 'JingLing':
				return new JingLing();
				break;
		}
	}
}

$person = Factory::createHero('Person');
$jingling = Factory::createHero('JingLing');

