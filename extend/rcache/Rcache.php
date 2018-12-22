<?php

namespace rcache;
use rcache\CacheBase;

class Rcache
{
	public static function prefix_rm($name)
	{
		$r =new CacheBase();
    	return $r->prefix_rm($name);
   }
}