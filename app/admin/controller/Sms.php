<?php
namespace app\admin\controller;
use think\Request;
use auth\Auth;
use think\Cache;
use app\admin\controller\Common;
use Qcloud\Sms\SmsSingleSender;

class Sms extends Common
{
	public function __construct()
    {
    	parent::__construct();
	}
	public function sms_option()
	{
		$input             = $this->request->param();
		$params['appid']   = isset($input['appid'])? $input['appid'] : $this->error('缺少appid参数');
		$params['appkey']  = isset($input['appkey'])? $input['appkey'] : $this->error('缺少appkey参数');
		$params['templateId']  = isset($input['templateId'])? $input['templateId'] : $this->error('缺少templateId参数');
		$params['smsSign']     = isset($input['smsSign'])? $input['smsSign'] : $this->error('缺少smsSign参数');
		$params['time']        = isset($input['time'])? $input['time'] : $this->error('缺少time参数');
		$params['code_lenght'] = isset($input['code_lenght'])? $input['code_lenght'] : $this->error('缺少code_lenght参数');
		$status = set_system_option('sms_tencent_template',$params);
		if ($status) 
		{
			cache('options_sms_tencent_template',null);
			$this->success('修改成功');
		}
		$this->error('修改失败');
	}
	//获取短信配置
	public function sms_option_fetch()
	{
		$res = get_system_option('sms_tencent_template');
		if ($res) 
		{
			$this->success('获取成功',$res);
		}else{
			$this->error('获取失败');
		}
	}
	//测试短信发送
	public function test()
	{
		$input   = $this->request->param();
		$phone   = isset($input['phone'])? $input['phone'] : $this->error('缺少phone参数');
		if (!check_mobile($phone)) 
		{
			$this->error('手机号码格式有误');
		}
		$this->send_sms($phone);
	}
	//发送短信
	public function send_sms($phone)
	{
		$res = get_system_option('sms_tencent_template');
		$appid = $res['appid'];
        // 短信应用SDK AppKey
        $appkey = $res['appkey'];
        // 你的手机号码。
        $phoneNumber = $phone;
        // 短信模板ID，需要在短信应用中申请
        $templateId = $res['templateId'];
        $smsSign = $res['smsSign'];
        $code = create_code($res['code_lenght']);
        $time = $res['time'];
        $ssender = new SmsSingleSender($appid, $appkey);
    	$result = $ssender->send(0, "86", $phoneNumber,"你的验证码{$code},{$time}分钟之内有效，请勿向任何单位或个人泄露。", "", "");
        $rsp = json_decode($result,true);
        if ($rsp['result']==0) 
        {
        	cache('sms_code_'.$phoneNumber,$code,$time*60);
            $this->success('发送成功',$rsp['errmsg']);
        }
        $this->error('发送失败',$rsp['errmsg']);
      
      
	}
}