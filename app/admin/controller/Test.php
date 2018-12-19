<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\common\lib\HttpExceptions;

class Test //extends Common
{
	/**
	 * @api {} 状态码说明
	 * @apiName status_code
	 * @apiGroup status_code
	 * @apiParam {int} 401 未授权.
	 * @apiParam {int} 403  没有权限.
	 * @apiParam {int} 405  请求方法错误，一般为get,post.
	 * @apiParam {int} 200  请求成功.
	 * @apiParam {int} 101  请求失败.
	 */
}