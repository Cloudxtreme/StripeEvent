<?php

/*
 * Copyright (C) 2013 Akkroo Solutions Ltd
 * 
 */

namespace Akkroo\StripeEvent;

use Akkroo\StripeEvent\Exceptions\StripeEventException;

/**
 * Description of StripeEvent
 *
 * @author nick
 */

class StripeEvent {
	/**
	 *
	 * @var array The webhook raw input array 
	 */
	private $input;
	
	/**
	 * 
	 * @param boolean $fetchInput	If true, the webbhook data will be automatically fetched from the PHP input stream on construct
	 */
	public function __construct($fetchInput = true) {
		if($fetchInput) $this->ensureInput();
	}
	
	/**
	 * Set the WebHook input
	 * 
	 * You can use the class to process an input after the fact by setting it here.  Otherwise it is automatically fetched from the HTTP request body
	 * 
	 * @param string|array $input
	 * @param boolean $isDecoded
	 * @throws StripeEventException
	 * @return static
	 */
	public function setInput($input, $isDecoded = false) {
		if(!$isDecoded)
			$input = json_decode($input, true);
		if(!$input) {
			throw new StripeEventException('Could not decode input', StripeEventException::INPUT_DECODE_FAILED);
		}
		if(empty($input['data']['object'])) {
			throw new StripeEventException('No data object in input data', StripeEventException::NO_DATA);
		}
		if(empty($input['type'])) {
			throw new StripeEventException('No type in input data', StripeEventException::NO_TYPE);
		}
		$this->input = $input;
		return $this;
	}
	
	/**
	 * Get the raw input object
	 * 
	 * @throws StripeEventException
	 * @return array The raw input object as an associative array
	 */
	public function getInput() {
		return $this->ensureInput();
	}
	
	/**
	 * Get the input from the HTTP request body
	 * 
	 * @throws StripeEventException
	 * @return array The raw input object as an associative array
	 */
	private function getInputFromHTTP() {
		$input = file_get_contents('php://input');
		$this->setInput($input);
		return $this->input;
	}
	
	/**
	 * Ensure the input payload exists, or throw an exception if not
	 * 
	 * @throws StripeEventException Exception if input could not be fetched
	 * @return array The input array
	 */
	private function ensureInput() {
		if(!$this->input && !$this->getInputFromHTTP())
			throw new StripeEventException('Could not fetch input', StripeEventException::INPUT_FETCH_FAILED);
		return $this->input;
	}
	
	/**
	 * Get the event ID
	 * @return string
	 */
	public function getID() {
		return isset($this->input['id']) ? $this->input['id'] : null;
	}
	
	/**
	 * Get the input type
	 * 
	 * @throws StripeEventException
	 * @return string|null The webhook type, e.g. 'customer.subscription.updated'
	 */
	public function getType() {
		$this->ensureInput();
		return isset($this->input['type']) ? $this->input['type'] : null;
	}
	
	/**
	 * Get the ID of the API request that caused the event
	 * 
	 * @throws StripeEventException
	 * @return string|null 
	 */
	public function getRequest() {
		$this->ensureInput();
		return isset($this->input['request']) ? $this->input['request'] : null;
	}
	
	/**
	 * Get the webhook timestamp
	 * 
	 * @throws StripeEventException
	 * @return string|integer|null	The webhook timestamp
	 */
	public function getTimeCreated() {
		$this->ensureInput();
		return isset($this->input['created']) ? $this->input['created'] : null;
	}
	
	/**
	 * Get the number of pending webhooks
	 * 
	 * @throws StripeEventException
	 * @return integer	The webhook timestamp
	 */
	public function getNumPendingWebhooks() {
		$this->ensureInput();
		return isset($this->input['pending_webhooks']) ? $this->input['pending_webhooks'] : null;
	}
	
	/**
	 * Get if the webhook came from livemode
	 * 
	 * @throws StripeEventException
	 * @return boolean
	 */
	public function livemode() {
		return isset($this->input['livemode']) ? $this->input['livemode'] : true;
	}
	
	/**
	 * Get the object from the WebHook
	 * 
	 * @throws StripeEventException
	 * @return array The webhook object as an associative array
	 */
	public function getObject() {
		return $this->input['data']['object'];
	}
	
	/**
	 * Get the values of the changed attributes previously
	 * 
	 * @throws StripeEventException
	 * @return array The values of the changed attributes previously as an associative array
	 */
	public function getPreviousAttributes() {
		return isset($this->input['data']['previous_attributes']) ? $this->input['data']['previous_attributes'] : null;
	}
	
	/**
	 * Send a HTTP response to Stripe
	 * 
	 * @param string $code	The HTTP response string to send
	 * @param array $data	The data to send in the response body
	 */
	private static function sendHTTPResponse($code, $data) {
		header('HTTP/1.1 '.$code);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	/**
	 * Send a HTTP response for an exception
	 * 
	 * @param \Akkroo\APIClient\Exceptions\StripeEventException $e
	 */
	public static function sendExceptionResponse(StripeEventException $e) {
		$httpCode = '400 Bad Request';
		switch($e->getCode()) {
			case StripeEventException::INPUT_FETCH_FAILED:
			case StripeEventException::INPUT_DECODE_FAILED:
			case StripeEventException::NO_DATA:
			case StripeEventException::NO_TYPE:
				$httpCode = '400 Bad Request';
				break;
		}
		static::sendHTTPResponse($httpCode, ['success' => false, 'errorCode' => $e->getCode(), 'errorDescription' => $e->getMessage()]);
	}
	
	/**
	 * Send a HTTP success response
	 */
	public static function sendSuccessResponse() {
		static::sendHTTPResponse('200 OK', ['success' => true]);
	}
	
}
