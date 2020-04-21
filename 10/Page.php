<?php

$page = new Page(5, 60);
echo $page->url;

class Page
{
	//每页显示多少条数据
	protected $number;
	//一共有多少条数据
	protected $totalCount;
	//当前页数
	protected $page;
	//总页数
	protected $totalPage;
	//url
	protected $url;

	//构造函数
	public function __construct($number, $totalCount)
	{
		$this->number = $number;
		$this->totalCount = $totalCount;
		//得到总页数
		$this->totalPage = $this->getTotalPage();
		//得到当前页数
		$this->page = $this->getPage();
		//得到url
		$this->url = $this->getUrl();
	}

	public function __get($name)
	{
		if ($name == 'url'){
			return $this->url;
		}
		return false;
	}
	//得到总页数
	protected function getTotalPage()
	{
		return ceil($this->totalCount / $this->number);
	}
	//得到当前页数
	protected function getPage()
	{
		if (empty($_GET['page'])){
			$page = 1;
		} else if ($_GET['page'] > $this->totalPage){
			$page = $this->totalPage;
		} else if ($_GET['page'] < 1){
			$page = 1;
		} else {
			$page = $_GET['page'];
		}
		return $page;
	}
	//得到url
	protected function getUrl()
	{
		$scheme = $_SERVER['REQUEST_SCHEME'];
		$serverName = $_SERVER['SERVER_NAME'];
		$port = $_SERVER['SERVER_PORT'];
		$uri = $_SERVER['REQUEST_URI'];
		//中间做处理,要将page=5等字符串拼接到url中
		//先清空原来的page参数
		$uriArray = parse_url($uri);
		$path = $uriArray['path'];
		if (!empty($uriArray['query'])){
			//将请求字符串变为关联数组
			parse_str($uriArray['query'], $arr);
			//清除关联数组中的page键值对
			unset($arr['page']);
			//生成 URL-encode 之后的请求字符串
			$query = http_build_query($arr);
			if ($query != ''){
				$path = $path.'?'.$query;
			}
		}
		return $scheme.'://'.$serverName.':'.$port.$path;
	}

	public function allUrl()
	{

	}

	public function first()
	{

	}

	public function prev()
	{

	}

	public function last()
	{

	}

}

?>