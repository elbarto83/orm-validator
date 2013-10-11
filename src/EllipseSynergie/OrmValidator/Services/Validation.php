<?php namespace EllipseSynergie\OrmValidator\Services;

/**
 * Validation service, extend this class to create a new validation service for your model
 * 
 * Base one example http://jasonlewis.me/article/laravel-advanced-validation
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
abstract class Validation {
	
	/**
	 * Validator object.
	 *
	 * @var object
	 */
	protected $validator;
	
	/**
	 * Array of extra data.
	 *
	 * @var array
	 */
	protected $data;
	
	/**
	 * Array of validating input.
	 *
	 * @var array
	 */
	protected $input;
	
	/**
	 * Array of rules.
	 *
	 * @var array
	 */
	public $rules = array ();
	
	/**
	 * Array of messages.
	 *
	 * @var array
	 */
	public $messages = array ();
	
	/**
	 * Create a new validation service instance.
	 *
	 * @param array $input        	
	 * @return void
	 */
	public function __construct($input, $validator) 
	{
		$this->input = $input;
		$this->validatorFactory = $validator;
	}
	
	/**
	 * Validates the input.
	 *
	 * @throws ValidateException
	 * @return void
	 */
	protected function validate() 
	{
		//Create the validator
		$this->validator = $this->validatorFactory->make ( $this->input, $this->rules, $this->messages );
		
		//If the validator fails, trhow a exception
		if ($this->validator->fails ()) {
			throw new ValidateException ( get_called_class(), $this->validator );
		} else {
			return true;
		}
	}
	
	/**
	 * Default validation for before creating
	 * 
	 * @throws ValidateException
	 */
	public function creating()
	{		
		$this->validate();
	}

	/**
	 * Default validation for before updating
	 *
	 * @throws ValidateException
	 */
	public function updating()
	{
		$this->validate();
	}
}