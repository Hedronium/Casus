<?php
include_once __DIR__.'/../vendor/autoload.php';
include_once __DIR__.'/GeneratorTest.php';

class MCryptTest extends \GeneratorTest
{
	public function setUp() {
		$this->casus = new \hedronium\Casus\MCrypt();
	}
}