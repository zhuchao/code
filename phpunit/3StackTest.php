<?php
class StackTest extends PHPUnit_Framework_TestCase 
{
	public function testEmpty()
	{
		$arr = array();
		$this->assertEmpty($arr);
		return $arr;
		
	}		

	/**
	 *
	 *@depends testEmpty
	 */
	public function testPush(array $arr)
	{
		array_push($arr, 'foo');
		$this->assertEquals('foo', $stack[count($arr)-1]);
		$this->assertNotEmpty($arr);
		return $arr;
	}

	/**
	 *
	 *@depends testPush
	 */
	public function testPop(array $arr)
	{
		$this->assertEquals('foo', array_pop($arr));
		$this->assertEmpty($arr);
	
	
	
	}

	/**
	 *@test 
	 */
	public function indexEquals()
	{
		$arr = array(1,2,3,4);
		$this->assertEquals(2, $arr[0]);

	}
}


?>
