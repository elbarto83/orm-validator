<?php namespace EllipseSynergie\OrmValidator;

use EllipseSynergie\OrmValidator\Services\Validation;
use Mockery as m;

/**
 * Test case for Service validation HTTP response
 */
class ValidationTest extends \PHPUnit_Framework_TestCase {
	
	public function testValidateSuccess()
	{
		$mockValidator = m::mock('Validator');
		$mockValidator->shouldReceive('fails')->once()->andReturn(false);
		
		$mockValidatorFactory = m::mock('ValidatorFactory');
		$mockValidatorFactory->shouldReceive('make')->once()->andReturn($mockValidator);
		
		$validation = new ValidationStub(array(), $mockValidatorFactory);	
		$this->assertTrue($validation->create());
	}
	
	/**
	 * @expectedException EllipseSynergie\OrmValidator\Services\ValidateException
	 */
	public function testValidateFail()
	{
		$mockValidator = m::mock('Validator');
		$mockValidator->shouldReceive('fails')->once()->andReturn(true);
		
		$mockValidatorFactory = m::mock('ValidatorFactory');
		$mockValidatorFactory->shouldReceive('make')->once()->andReturn($mockValidator);
		
		$validation = new ValidationStub(array(), $mockValidatorFactory);	
		$validation->create();
	}
}

class ValidationStub extends Validation {
	
	public $rules =  array(
		'user_id' => array('required', 'integer'),
		'company_name' => array('required', 'max:200')
	);
	
	public function create()
	{	
		$this->validate();
		
		return true;
	}
}