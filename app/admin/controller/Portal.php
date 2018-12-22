<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\common\lib\HttpExceptions;
use think\exception\HttpResponseException;
use think\Response;
use app\common\lib\Token;
use PHPMailer\PHPMailer\PHPMailer;
use Rcache\Rcache;


class Portal //extends Common
{
	protected $request;
	public function __construct()
    {
    	//parent::__construct();
    	$this->request = request();
	}
	/**
	 * @api {get} admin/portal/get_category 分类获取
	 * @apiName get_category
	 * @apiGroup portal
	 * @apiSuccess {String} name 分类名称.
	 * @apiSuccess {int} parent_id  父id.
	 * @apiSuccess {String} children  子分类.
	 * @apiSuccess {int} status  状态，1正常，0禁用.
	 * @apiSuccessExample {json} 例子:
	 *{
     *  "id": 1,
     *  "parent_id": 0,
     *  "name": "服装类",
     *  "status": 1,
     *  "children": [
     *      {
     *         "id": 3,
     *         "parent_id": 1,
     *         "name": "男装",
     *         "status": 1,
     *      }
     *  ]
     *}
	 */
	public function get_category()
	{
		$input              = $this->request->param();
        $data['object_id']  = isset($input['object_id'])? $input['object_id'] :  $this->success('object_id参数不存在');
		$data = db('portal_category')->select();
		$tree = $this->tree($data);
		return $tree;
		//return json($tree)->code(401)->header(['Cache-control' => 'no-cache,must-revalidate']);
		// $this ->success('qqq');
		 //return fox_password('1234567890');
	}
	public function tree($arr)
	{
	    $items = [];
	    foreach($arr as $value){
	        $items[$value['id']] = $value;
	    }
	    $tree = [];
	    foreach($items as $key => $item){
	        if(isset($items[$item['parent_id']])){
	            $items[$item['parent_id']]['children'][] = &$items[$key];
	        }else{
	            $tree[] = &$items[$key];
	        }
	    }
	    return $tree;
	}

	public function success($msg = '', $data = '', $code = '200')
 	{
 		$result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
       // echo json_encode(($result),JSON_UNESCAPED_UNICODE);
        $type = 'json';
         $response                               = Response::create($result, $type);
        throw new HttpResponseException($response);
 	}
 	 /**
     * 生成AccessToken
     * @return string
     */
    public  function buildAccessToken()
    {
    	$data = [
    		'id' => 1,
    		'name' => 2333
    	];
        return Token::buildAccessToken($data);
    }
    public function tt()
    {
    	phpinfo();
    }

    function send_email()
{
    $mail        = new PHPMailer();
    // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();
    $mail->IsHTML(true);
    //$mail->SMTPDebug = 3;
    // 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->CharSet = 'UTF-8';
    // 添加收件人地址，可以多次使用来添加多个收件人
    $mail->AddAddress('waitfun319@qq.com');
    // 设置邮件正文
    $mail->Body = '23333';
    // 设置邮件头的From字段。
    $mail->From = 'waitfun@waitfun.cn';
    // 设置发件人名字
    $mail->FromName ='waitfun';
    // 设置邮件标题
    $mail->Subject = 'hello';
    // 设置SMTP服务器。
    $mail->Host = 'smtp.exmail.qq.com';
    //by Rainfer
    // 设置SMTPSecure。
   // $Secure           = $smtpSetting['smtp_secure'];
     $mail->SMTPSecure = '';
    // 设置SMTP服务器端口。
   // $port       = $smtpSetting['port'];
    $mail->Port = '25';
    // 设置为"需要验证"
    $mail->SMTPAuth    = true;
    $mail->SMTPAutoTLS = false;
    $mail->Timeout     = 10;
    // 设置用户名和密码。
    $mail->Username = 'waitfun@waitfun.cn';
    $mail->Password = 'Family1994';
    // 发送邮件。
    if (!$mail->Send()) {
        $mailError = $mail->ErrorInfo;
        return ["error" => 1, "message" => $mailError];
    } else {
        return ["error" => 0, "message" => "success"];
    }
}


}