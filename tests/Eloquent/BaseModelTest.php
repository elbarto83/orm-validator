<?php namespace EllipseSynergie\OrmValidator\Eloquent;


use Mockery as m;

/**
 * Test for eloquent base model
 */
class BaseModelTest extends \PHPUnit_Framework_TestCase {
	
	/**
     * @expectedException EllipseSynergie\OrmValidator\ObserverNotFound
     */
	public function testObserverNotFoundException()
	{
		$model = new BaseModelStubObserverNotFound;
	}
	
	/**
     * @expectedException EllipseSynergie\OrmValidator\ValidationServiceNotFound
     */
	public function testValidationServiceNotFound()
	{
		$model = new BaseModelStubValidationServiceNotFound;
		$model->validate();
	}
	
	/**
     * @expectedException EllipseSynergie\OrmValidator\ValidationMethodNotFound
     */
	public function testValidationMethodNotFound()
	{
		$model = new BaseModelStubValidationMethodNotFound;
		$model->validate();
	}
}

class BaseModelStubObserverNotFound extends BaseModel {}
class BaseModelStubValidationServiceNotFound extends BaseModel {
	
	protected static $observer = '\\EllipseSynergie\\OrmValidator\\Observer';
	
}
class BaseModelStubValidationMethodNotFound extends BaseModel {
	
	protected static $observer = '\\EllipseSynergie\\OrmValidator\\Observer';
	protected static $validationService = '\\EllipseSynergie\\OrmValidator\\Eloquent\\ValidationServiceMock';
	
	protected function factoryValidator()
	{
	}
	
}

class ValidationServiceMock {}