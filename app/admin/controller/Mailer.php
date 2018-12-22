<?php
namespace app\admin\controller;
use think\Request;
use auth\Auth;
use think\Cache;
use app\admin\controller\Common;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer extends Common
{
	//aitrzqtyixkkcagh
	public function __construct()
    {
    	parent::__construct();
	}
	/**
	 * @api {post} admin/mailer/email_post 设置邮件配置
	 * @apiName email_post
	 * @apiGroup mailer
	 * @apiParam {String} smtp_host SMTP服务器.
	 * @apiParam {String} smtp_port SMTP端口.
	 * @apiParam {String} email_username  发件箱帐号.
	 * @apiParam {String} email_password  发件箱密码：企业邮箱登录密码,个人为邮箱授权码.
	 * @apiParam {String} stmp_secure  使用加密方式登录鉴权,一般为ssl,tls:企业邮箱必须关闭,个人邮箱才需要.
	 * @apiParam {String} from_name  发件人名字.
	 * @apiParam {String} from  邮箱地址.
	 * @apiParam {String} style  邮箱类型：1企业邮箱，2个人邮箱.
	 */
	public function email_post()
	{
		$input                    = $this->request->param();
		$params['smtp_host']      = isset($input['smtp_host']) ? $input['smtp_host'] : $this->error('缺少smtp_host参数');
		$params['smtp_port']      =  isset($input['smtp_port'])? $input['smtp_port']:'25';
		$params['email_username'] =  isset($input['email_username'])? $input['email_username']:$this->error('缺少email_username参数');
		$params['email_password'] =  isset($input['email_password'])? $input['email_password']:$this->error('缺少email_password参数');
		$params['from_name']      =  isset($input['from_name'])? $input['from_name']:$this->error('缺少from_name参数');
		$params['from']           =  isset($input['from'])? $input['from']:$this->error('缺少from参数');
		$params['stmp_secure'] =  isset($input['stmp_secure'])? $input['stmp_secure']:$this->error('缺少stmp_securet参数');
		$status = set_system_option('email_site',$params);
		if ($status) 
		{
			cache('options_email_site',null);
			$this->success('修改成功');
		}
		$this->error('修改失败');
	}
	//获取邮箱配置
	public function fetch_email()
	{
		$res =get_system_option('email_site');
		if ($res) 
		{
			$this->success('获取成功',$res);
		}else{
			$this->error('获取失败');
		}
	}
	//测试邮箱
	public function test()
	{
		$input             = $this->request->param();
		$params['address'] = isset($input['address']) ? $input['address'] : $this->error('缺少address参数');
		$params['title']   = isset($input['title'])? $input['title'] : $this->error('缺少title参数');
		$params['message'] = isset($input['message'])? $input['message'] : $this->error('缺少message参数');
		$this -> send_email($params['address'], $params['title'], $params['message']);

	}
	public function send_email($address, $title, $message)
	{
		$res =get_system_option('email_site');
	    $mail        = new PHPMailer();
	    // 设置PHPMailer使用SMTP服务器发送Email
	    $mail->IsSMTP();
	    $mail->IsHTML(true);
	    //$mail->SMTPDebug = 3;
	    // 设置邮件的字符编码，若不指定，则为'UTF-8'
	    $mail->CharSet = 'UTF-8';
	    // 添加收件人地址，可以多次使用来添加多个收件人
	    $mail->AddAddress($address);
	    // 设置邮件正文
	    $mail->Body = $message;
	    // 设置邮件邮箱地址。
	    $mail->From = $res['from'];
	    // 设置发件人名字
	    $mail->FromName =  $res['from_name'];
	    // 设置邮件标题
	    $mail->Subject = $title;
	    // 设置SMTP服务器。
	    $mail->Host =  $res['smtp_host'];
	    //by Rainfer
	    // 设置SMTPSecure。
	   //设置使用ssl加密方式登录鉴权   企业邮箱必须关闭,个人邮箱才需要
	   // if ($res['style'] == 2) {
	    $mail->SMTPSecure =  $res['stmp_secure'];
	   // }
	    
	    // 设置SMTP服务器端口。
	   // $port       = $smtpSetting['port'];
	    $mail->Port =  $res['smtp_port'];
	    // 设置为"需要验证"
	    $mail->SMTPAuth    = true;
	    $mail->SMTPAutoTLS = false;
	    $mail->Timeout     = 10;
	    // 设置用户名和密码。
	    $mail->Username =  $res['email_username'];
	    //密码为企业邮箱登录密码,个人为邮箱授权码
	    $mail->Password =  $res['email_password'];
	    // 发送邮件。
	    if (!$mail->Send()) {
	        $mailError = $mail->ErrorInfo;
	        $this -> error('发送失败',$mailError);
	    } else {
	        $this -> success('发送成功');
	    }
	}
}