<?php

var_dump($_SERVER);


$url = 'http://www.baidu.com:80/index.php?username=xiaoming';
//解析 URL，返回其组成部分
$arr = parse_url($url);
var_dump($arr);



$str = 'username=xiaoming&password=123';
//将字符串解析成多个变量
parse_str($str, $arr);
var_dump($arr);


$arr = ['username' => 'xiaoming', 'password' => '123'];
//生成 URL-encode 之后的请求字符串
$str = http_build_query($arr);
var_dump($str);

?>
