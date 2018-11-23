<?php
namespace app\wechat\controller;
use think\Request;
use think\Db;

class WxLogin
{
	protected $request;
	protected $appid;
	protected $secret;
	public function __construct(Request $request)
    {
		$this->request = $request;
		$this ->secret = "71c9e10b38f1296576a0ca4303faac42";
		$this ->appid = "wx5efad005f07f5815";
    }
    public function login()
    {

    	$code = $this->request->param('code');
    	$url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->secret}&js_code={$code}&grant_type=authorization_code";
    	$info = $this -> curl_get($url);
    	return $info;
    }
    public function curl_get($url){
	  $info=curl_init();
	  curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
	  curl_setopt($info,CURLOPT_HEADER,0);
	  curl_setopt($info,CURLOPT_NOBODY,0);
	  curl_setopt($info,CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($info,CURLOPT_SSL_VERIFYHOST, false);
	  curl_setopt($info,CURLOPT_URL,$url);
	  $output= json_decode(curl_exec($info));
	  curl_close($info);
	  return $output;
	}
}