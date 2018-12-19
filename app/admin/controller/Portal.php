<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\common\lib\HttpExceptions;
use think\exception\HttpResponseException;
use think\Response;
use app\common\lib\Token;

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
    	$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1NDUxMTg0NDgsImV4cCI6MTU0NTcyMzI0OCwiZGF0YSI6eyJpZCI6MiwibmFtZSI6InVzZXIwMSIsInBhc3N3b3JkIjoiZGVlMTJiYTc3Y2M3NDUxODBlODE1NGM0NDc2ZDg5MGMiLCJuaWNrbmFtZSI6bnVsbCwiZW1haWwiOm51bGwsInBob25lIjpudWxsLCJhdmF0YXIiOiJodHRwOlwvXC9pbWc0LmltZ3RuLmJkaW1nLmNvbVwvaXRcL3U9OTY3Mzk1NjE3LDM2MDEzMDIxOTUmZm09MjYmZ3A9MC5qcGciLCJjcmVhdGVfdGltZSI6bnVsbCwidXBkYXRlX3RpbWUiOm51bGwsInN0YXR1cyI6MCwicm9sZV9pZCI6Miwicm9sZV9uYW1lIjoiXHU2NjZlXHU5MDFhXHU3YmExXHU3NDA2XHU1NDU4IiwibG9naW5faXAiOiIxMjcuMC4wLjEiLCJsb2dpbl90aW1lIjoiMjAxOC0xMi0xNiAxNzoxMjoxMSIsImxvZ2luX2FkZHJlc3MiOiIgICAgIn19.2SLcHE2KN0P-MX6qPV-wekmVC20uMGQxLkBJQIpAqtA";
    	return Token::decryptAccessToken($token);
    }
}