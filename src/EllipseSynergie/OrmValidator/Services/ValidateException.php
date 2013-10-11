<?php namespace EllipseSynergie\OrmValidator\Services;

/**
 * Validation exception
 * 
 * Base one example http://jasonlewis.me/article/laravel-advanced-validation
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 * 
 */
class ValidateException extends \Exception {

	/**
	 * Errors object.
	 *
	 * @var Laravel\Messages
	 */
	private $errors;

	/**
	 * Create a new validate exception instance.
	 * 
	 * @param string $serviceName
	 * @param Validator $validator
	 */
	public function __construct($serviceName, $validator)
	{
		$this->validator = $validator;
		$this->serviceName = $serviceName;

		parent::__construct(null);
	}

	/**
	 * Get the validator object.
	 */
	public function getValidator()
	{
		return $this->validator;
	}
	
	/**
	 * Gets the errors array.
	 * 
	 * @return array
	 */
	public function errors()
	{
		//Default
		$errors = array();
		
		//For each rules failed
		foreach ($this->validator->failed() as $attribute => $rules) {			
			
			foreach($rules as $key => $rule) {
				
				//Get message
				$messages = $this->validator->messages()->get($attribute);
				
				//If we only have one message
				if(count($messages) == 1){
					$messages = $messages[0];
				}
				
				$errors[$this->serviceName . self::studly($attribute) . self::studly($key) . 'Exception'] = $messages;
			}
		}

    	return $errors;
	}
	
	/**
	 * Convert a value to studly caps case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function studly($value)
	{
		$value = ucwords(str_replace(array('-', '_'), ' ', $value));

		return str_replace(' ', '', $value);
	}
	
/**
	 * Convert a value to camel case.
	 *
	 * @param  string  $value
	 * @return string
	 */
	public static function camel($value)
	{
		return lcfirst(static::studly($value));
	}
}