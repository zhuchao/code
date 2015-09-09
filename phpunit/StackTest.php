<?php
class StackTest extends PHPUnit_Framework_TestCase 
{
	public function testPushAndPop()
	{
		$stack = array();
		
		$this->assertEquals(0, count($stack));
	
		array_push($stack, 'foo');


		$this->assertEquals('foo', $stack[count($stack)-1]);
		$this->assertEquals(1, count($stack));
		$this->assertEquals('foo', array_pop($stack));
		$this->assertEquals(0, count($stack));
	}

	/**
	 *@test 
	 */
	public function indexEquals()
	{
		$arr = array(1,2,3,4);
		$this->assertEquals(5, $arr[0]);
		
	}
}


?>
