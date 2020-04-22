<?php

class Upload
{
	//文件上传存储路径
	protected $path = './upload';
	//允许上传的文件后缀
	protected $allowSuffix = ['jpg', 'jpeg', 'png', 'gif', 'wbmp'];
	//允许的mime
	protected $allowMime = ['image/jpeg', 'image/gif', 'image/wbmp', 'image/png'];
	//允许文件的大小
	protected $maxSize = 2000000;
	//是否启用随机名称
	protected $isRandName = true;
	//上传文件前缀
	protected $profix = 'up_';

	//错误代码和错误信息
	protected $errorCode;
	protected $errorInfo;

	//文件的信息
	protected $oldName;
	protected $soffix;
	protected $size;
	protected $mime;
	protected $tmpName;

	//文件新名字
	protected $newName;

	public function __construct($arr = [])
	{
		foreach ($arr as $key => $value){
			$this->setOption($key, $value);
		}
	}

	//判断这个$key是不是我的成员属性,再赋值
	protected function setOption($key, $value)
	{	
		//得到所有的成员属性
		$keys = array_keys(get_class_vars(__CLASS__));
		//
		if (in_array($key, $keys)){
			$this->$key = $value;
		}
	}

	//文件上传函数
	//$key 是你input框中的name属性值
	public function uploadFile($key)
	{
		//判断有没有设置路径 path
		//判断该路径是否存在,是否可写
		//判断$_FILES里面的error信息是否为0, 如果为0,说明文件信息在服务器端,可以直接获取,提取信息保存到成员属性中
		//判断文件的大小,mime,后缀是否符合
		//得到新的文件名称
		//判断是否上传文件,并且移动上传文件
	}
}

?>