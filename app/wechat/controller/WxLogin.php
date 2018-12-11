<?php
namespace app\wechat\controller;
use think\Request;
use think\Db;
use app\common\lib\wechat\WxUser;

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
    //小程序登录
    public function login()
    {

    	$code = $this->request->param('code');
    	// $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->appid}&secret={$this->secret}&js_code={$code}&grant_type=authorization_code";
    	// $info = $this -> curl_get($url);
    	// return $info;
    	// 微信登录 (获取session_key)
        $WxUser = new WxUser($this->appid, $this->secret);
        if (!$session = $WxUser->sessionKey($code)) {
            //throw new BaseException(['msg' => $WxUser->getError()]);
            return ['msg' => $WxUser->getError()];
        }
        return $session;
    }
    
}