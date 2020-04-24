<?php

$up = new Upload();
$up->uploadFile('fm');

var_dump($up->errorCode);

var_dump($up->errorInfo);

class Upload
{
	//文件上传存储路径
	protected $path = './upload/';
	//允许上传的文件后缀
	protected $allowSuffix = ['jpg', 'jpeg', 'png', 'gif', 'wbmp'];
	//允许的mime
	protected $allowMime = ['image/jpeg', 'image/gif', 'image/wbmp', 'image/png'];
	//允许文件的大小
	protected $maxSize = 2000000;
	//是否启用随机名称
	protected $isRandName = true;
	//上传文件前缀
	protected $prefix = 'up_';

	//错误代码和错误信息
	protected $errorCode;
	protected $errorInfo;

	//文件的信息
	protected $oldName;
	protected $suffix;
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
		if (empty($this->path)) {
			$this->setOption('errorCode', -1);
			return false;
		}

		//判断该路径是否存在,是否可写
		if (!$this->check()) {
			$this->setOption('errorCode', -2);
			return false;
		}
		
		//判断$_FILES里面的error信息是否为0, 如果为0,说明文件信息在服务器端,可以直接获取,提取信息保存到成员属性中
		$error = $_FILES[$key]['error'];
		if ($error) {
			$this->setOption('errorCode', $error);
			return false;
		} else {
			//提取文件相关信息
			$this->getFileInfo($key);
		}
		//判断文件的大小,mime,后缀是否符合
		if (!$this->checkSize() || !$this->checkMime() || !$this->checkSuffix()) {
			return false;
		}
		//得到新的文件名称
		$this->newName = $this->createNewName();
		//判断是否上传文件,并且移动上传文件
		if (is_uploaded_file($this->tmpName)) {
			if (move_uploaded_file($this->tmpName, $this->path.$this->newName)) {
				$this->setOption('errorCode', 0);
				return $this->path.$this->newName;
			} else {
				$this->setOption('errorCode', -7);
				return false;
			}
		} else {
			$this->setOption('errorCode', -6);
			return false;
		}
	}

	protected function check()
	{
		//文件夹不存在或者不是目录,则创建文件夹
		if (!file_exists($this->path) || !is_dir($this->path)) {
			//递归创建目录
			return mkdir($this->path, 0777, true);
		} 
		//判断文件是否可写
		if (!is_writeable($this->path)) {
			return chmod($this->path, 0777);
		}
		return true;
	}

	protected function getFileInfo($key)
	{
		//获取文件名
		$this->oldName = $_FILES[$key]['name'];
		//得到文件的mime类型
		$this->mime = $_FILES[$key]['type'];
		//得到文件临时路径
		$this->tmpName = $_FILES[$key]['tmp_name'];
		//得到文件大小
		$this->size = $_FILES[$key]['size'];
		//得到文件后缀
		$this->suffix = pathinfo($this->oldName)['extension'];
	}

	protected function checkSize()
	{
		if ($this->size > $this->maxSize)
		{
			$this->setOption('errorCode', -3);
			return false;
		}
		return true;
	}

	protected function checkMime()
	{
		if (!in_array($this->mime, $this->allowMime)) {
			$this->setOption('errorCode', -4);
			return false;
		}
		return true;
	}

	protected function checkSuffix()
	{
		if (!in_array($this->suffix, $this->allowSuffix)) {
			$this->setOption('errorCode', -5);
			return false;
		}
		return true;
	}

	protected function createNewName()
	{
		if ($this->isRandName) {
			$name = $this->prefix.uniqid().'.'.$this->suffix;
		} else {
			$name = $this->prefix.$this->oldName;
		}
		return $name;
		var_dump($name);
	}

	public function __get($name)
	{
		if ($name == 'errorCode') {
			return $this->errorCode;
		} else if ($name == 'errorInfo') {
			return $this->getErrorInfo();

		}
	}

	protected function getErrorInfo()
	{
		switch ($this->errorCode) {
			case -1:
				$str = '自定义错误1';
				break;
			case -2:
				$str = '自定义错误2';
				break;
			case -3:
				$str = '自定义错误3';
				break;
			case -4:
				$str = '自定义错误4';
				break;
			case -5:
				$str = '自定义错误5';
				break;
			case -6:
				$str = '自定义错误6';
				break;
			case 0:
				$str = '';
				break;
			default:
				$str = '自定义错误'.$this->errorCode;
				break;
		}
		return $str;
	}
}

?>