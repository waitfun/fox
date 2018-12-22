<?php

namespace Rcache;
use Rcache\Driver;
use think\facade\Config;

class CacheBase extends Driver
{

	
	public function __construct()
	{
		 $this->options = [
	        'host'       => Config::get('cache.host'), 
	        'port'       => Config::get('cache.port'), 
	        'password'   => Config::get('cache.password'), 
	        'select'     =>Config::get('cache.select'), 
	        'timeout'    => Config::get('cache.timeout'), 
	        'expire'     => Config::get('cache.expire'), 
	        'persistent' => false,
	        'prefix'     => Config::get('cache.prefix'), 
	    ];
		$this->handler = new \Redis;

        if ($this->options['persistent']) {
            $this->handler->pconnect($this->options['host'], $this->options['port'], $this->options['timeout'], 'persistent_id_' . $this->options['select']);
        } else {
            $this->handler->connect($this->options['host'], $this->options['port'], $this->options['timeout']);
        }

        if ('' != $this->options['password']) {
            $this->handler->auth($this->options['password']);
        }
	}
	 /**
     * 根据前缀删除缓存
     * @access public
     * @param  string $name 缓存变量名
     * @return boolean
     */
	public  function prefix_rm($name)
	{
		$keys = $this->handler->keys($this->getCacheKey($name));
		foreach ($keys as $key => $v) 
		{
			$this->handler->delete($v);
		}
		if ($keys == null) 
		{
			return true;
		}
	}
	public function has($name)
    {
        return $this->handler->exists($this->getCacheKey($name));
    }

}