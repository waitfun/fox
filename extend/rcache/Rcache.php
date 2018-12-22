<?php

namespace Rcache;
use Rcache\CacheBase;

class Rcache
{
	public static function prefix_rm($name)
	{
		$r =new CacheBase();
    	return $r->prefix_rm($name);
   }
}