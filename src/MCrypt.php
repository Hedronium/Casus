<?php
namespace hedronium\Casus;

class MCrypt extends Generator 
{
	protected $secure = true;
	protected $source = MCRYPT_DEV_URANDOM;
	
	public function integer($min = 0, $max = PHP_INT_MAX)
	{
		$bytes = mcrypt_create_iv(PHP_INT_SIZE, $this->source);
		$num = 0;

		for ($i = 0; $i < PHP_INT_SIZE; $i++) {
			$num = ($num<<8)|ord($bytes[$i]);
		}

		if ($min>=0 && $max>=0) {
			$num = abs($num);
		}

		return $min+($num%($max-$min+1));
	}

	public function byte($secure = true)
	{
		return mcrypt_create_iv(1, $this->source);
	}

	public function byteString($length = 32, $secure = true)
	{
		return mcrypt_create_iv($length, $this->source);
	}
}