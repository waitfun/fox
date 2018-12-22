<?php

namespace Rcache;

/**
 * 缓存基础类
 */
abstract class Driver  
{
    /**
     * 驱动句柄
     * @var object
     */
    protected $handler = null;

    /**
     * 缓存参数
     * @var array
     */
    protected $options = [];
     protected $host = '';

    /**
     * 判断缓存是否存在
     * @access public
     * @param  string $name 缓存变量名
     * @return bool
     */
      abstract public function has($name);

    /**
     * 根据前缀删除缓存
     * @access public
     * @param  string $name 缓存变量名
     * @return boolean
     */
    abstract public function prefix_rm($name);

    /**
     * 清除缓存
     * @access public
     * @param  string $tag 标签名
     * @return boolean
     */
   // abstract public function clear($tag = null);

   
    /**
     * 获取实际的缓存标识
     * @access protected
     * @param  string $name 缓存名
     * @return string
     */
    protected function getCacheKey($name)
    {
        return $this->options['prefix'] . $name;
    }

   

    /**
     * 返回句柄对象，可执行其它高级方法
     *
     * @access public
     * @return object
     */
    public function handler()
    {
        return $this->handler;
    }

}
