<?php

$image = new Image();
$image->water('mn1.jpg', 'niu.png', 9, 50);

class Image
{
	//路径
	protected $path;
	//是否启用随机名字
	protected $isRandName;
	//要保存的图像类型
	protected $type;

	function __construct($path = './', $isRandName = true, $type = 'png')
	{
		$this->path = $path;
		$this->isRandName = $isRandName;
		$this->type = $type;
	}

	//对外公开的水印方法
	//water 水印图片路径
	//postion 水印图片的位置
	public function water($image, $water, $postion, $tmd = 100, $prefix = 'water_')
	{
		//1.判断俩个图片是否存在
		if ((!file_exists($image)) || (!file_exists($water))) {
			die('图片资源不存在');
		}
		//2.得到俩个图片的宽度和高度
		$imageInfo = self::getImageInfo($image);
		$waterInfo = self::getImageInfo($water);
		//3.判断水印图片能否贴上来
		if (!$this->checkImage($imageInfo, $waterInfo)) {
			die('水印图片大小超过原图');
		}
		//4.打开图片
		$imageRes = self::openAnyImage($image);
		if (!$imageRes) {
			die('打开原图失败');
		}
		$waterRes = self::openAnyImage($water);
		if (!$waterRes) {
			die('打开水印图失败');
		}
		//5.计算水印图片的坐标
		$pos = $this->getPostion($postion, $imageInfo, $waterInfo);
		//6.将水印图片贴过来
		imagecopymerge($imageRes, $waterRes, $pos['x'], $pos['y'], 0, 0, $waterInfo['width'], $waterInfo['height'], $tmd);
		//7.得到要保存图片的文件名
		$newName = $this->createNewName($image, $prefix);
		//8.得到保存图片的路径 注意后面是否带'/'
		$newPath = rtrim($this->path, '/').'/'.$newName;
		//9.保存图片
		$this->saveImage($imageRes, $newPath);
		//10.销毁资源
		imagedestroy($imageRes);
		imagedestroy($waterRes);

		return $newPath;
	}

	//对外公开的缩放方法
	public function suofang()
	{
		return false;
	}

	protected function saveImage($imageRes, $newPath)
	{
		//png, gif, wbmp
		$func = 'image'.$this->type;
		//通过变量函数进行保存
		$func($imageRes, $newPath);
	}

	protected function createNewName($image, $prefix)
	{
		if ($this->isRandName) {
			$name = $prefix.uniqid().'.'.$this->type;
		} else {
			$name = $prefix.pathinfo($imagePath)['filename'].$this->type;
		}
		return $name;
	}

	protected function getPostion($postion, $imageInfo, $waterInfo)
	{
		switch ($postion) {
			case 1:
				$x = 0;
				$y = 0;
				break;
			case 2:
				$x = ($imageInfo['width'] - $waterInfo['width']) / 2;
				$y = 0;
				break;
			case 3:
				$x = ($imageInfo['width'] - $waterInfo['width']);
				$y = 0;
				break;
			case 4:
				$x = 0;
				$y = ($imageInfo['height'] - $waterInfo['height']) / 2;
				break;
			case 5:
				$x = ($imageInfo['width'] - $waterInfo['width']) / 2;
				$y = ($imageInfo['height'] - $waterInfo['height']) / 2;
				break;
			case 6:
				$x = ($imageInfo['width'] - $waterInfo['width']);
				$y = ($imageInfo['height'] - $waterInfo['height']) / 2;
				break;
			case 7:
				$x = 0;
				$y = ($imageInfo['height'] - $waterInfo['height']);
				break;
			case 8:
				$x = ($imageInfo['width'] - $waterInfo['width']) / 2;
				$y = ($imageInfo['height'] - $waterInfo['height']);
				break;
			case 9:
				$x = ($imageInfo['width'] - $waterInfo['width']);
				$y = ($imageInfo['height'] - $waterInfo['height']);
				break;
			case 0:
				$x = mt_rand(0, $imageInfo['width'] - $waterInfo['width']);
				$y = mt_rand(0, $imageInfo['height'] - $waterInfo['height']);
		}
		return ['x' => $x, 'y' => $y];
	}

	//判断水印图片是否大于原图
	protected function checkImage($imageInfo, $waterInfo)
	{
		if (($waterInfo['width'] > $imageInfo['width']) || ($waterInfo['height'] > $imageInfo['height'])) {
			return false;
		}
		return true;
	}

	//静态方法,根据图片的路径得到图片的信息,宽度,高度,mime类型
	static function getImageInfo($imagePath)
	{
		$arr = getimagesize($imagePath);
		$data['width'] = $arr[0];
		$data['height'] = $arr[1];
		$data['mime'] = $arr['mime'];
		return $data;
	}

	//静态方法,打开图片
	static function openAnyImage($imagePath)
	{
		$mime = self::getImageInfo($imagePath)['mime'];
		//根据不同类型调用不同打开方式
		switch ($mime) {
			case 'image/png' : $image = imagecreatefrompng($imagePath);
				break;
			case 'image/gif' : $image = imagecreatefromgif($imagePath);
				break;
			case 'image/jpeg' : $image = imagecreatefromjpeg($imagePath);
				break;
			case 'image/wbmp' : $image = imagecreatefromwbmp($imagePath);
				break;
			default:
				return false;
		}
		return $image;
	}
}

?>