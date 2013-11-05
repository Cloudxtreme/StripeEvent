<?php

/*
 * Copyright (C) 2013 Akkroo Solutions Ltd
 * 
 */

namespace Akkroo\StripeEvent;

/**
 * Description of StripeEventTest
 *
 * @author nick
 */
class StripeEventTest extends \PHPUnit_Framework_TestCase {
	private $object;
	
	private $testInputJSON = '{ "id": "evt_2saQtFheCmTFa8", "created": 1383578814, "livemode": false, "type": "customer.subscription.updated", "data": { "object": { "id": "su_2VjeLpmIoKgkZz", "plan": { "interval": "month", "name": "S4 Plan", "amount": 5000, "currency": "usd", "id": "s4", "object": "plan", "livemode": false, "interval_count": 1, "trial_period_days": null }, "object": "subscription", "start": 1378308381, "status": "active", "customer": "cus_2VjextyjAaLV84", "cancel_at_period_end": false, "current_period_start": 1383578781, "current_period_end": 1386170781, "ended_at": null, "trial_start": null, "trial_end": null, "canceled_at": null, "quantity": 1, "application_fee_percent": null }, "previous_attributes": { "current_period_start": 1380900381, "current_period_end": 1383578781 } }, "object": "event", "pending_webhooks": 0, "request": null }';
	private $testInput;
	
	protected function setUp() {
		parent::setUp();
		$this->object = new StripeEvent();
		$this->testInput = json_decode($this->testInputJSON, JSON_OBJECT_AS_ARRAY);
		ob_start();
	}
	
	protected function tearDown() {
//		header_remove();
		parent::tearDown();
	}
	
	private function setTestInput() {
		$this->object->setInput($this->testInput, true);
	}
	
	public function testSetInput() {
		$this->object->setInput($this->testInput, true);
		$this->assertEquals($this->testInput, $this->object->getInput());
		
		$this->object->setInput($this->testInputJSON, false);
		$this->assertEquals($this->testInput, $this->object->getInput());
		
		try {
			$this->object->setInput($this->testInputJSON.'dfgdsg', false);
			$this->fail('Expected exception');
		} catch (Exceptions\StripeEventException $e) {
			$this->assertEquals(Exceptions\StripeEventException::INPUT_DECODE_FAILED, $e->getCode());
		}
		
		$data = $this->testInput;
		unset($data['type']);
		try {
			$this->object->setInput($data, true);
			$this->fail('Expected exception');
		} catch (Exceptions\StripeEventException $e) {
			$this->assertEquals(Exceptions\StripeEventException::NO_TYPE, $e->getCode());
		}
		
		$data = $this->testInput;
		unset($data['data']);
		try {
			$this->object->setInput($data, true);
			$this->fail('Expected exception');
		} catch (Exceptions\StripeEventException $e) {
			$this->assertEquals(Exceptions\StripeEventException::NO_DATA, $e->getCode());
		}
	}
	
	public function testGetID() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testGetType() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['type'], $this->object->getType());
	}
	
	public function testGetRequest() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['request'], $this->object->getRequest());
	}
	
	public function testTimeCreated() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['created'], $this->object->getTimeCreated());
	}
	
	public function testGetPendingWebhooks() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['pending_webhooks'], $this->object->getNumPendingWebhooks());
	}
	
	public function testLivemode() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['livemode'], $this->object->livemode());
	}
	
	public function testGetObject() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['data']['object'], $this->object->getObject());
	}
	
	public function testGetPreviousAttributes() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['data']['previous_attributes'], $this->object->getPreviousAttributes());
	}
	
	public function testSendExceptionResponse() {
		$e = new Exceptions\StripeEventException('test', Exceptions\StripeEventException::INPUT_DECODE_FAILED);
		ob_start();
		$this->object->sendExceptionResponse($e);
		$output = ob_get_clean();
		$headers = xdebug_get_headers();
		$this->assertEquals(400, http_response_code());
		$this->assertTrue(in_array('Content-Type: application/json', $headers), 'Content-Type header not set correctly');
		$op = json_decode($output, JSON_OBJECT_AS_ARRAY);
		if(!$op) $this->fail('Could not decode error response');
		$this->assertArrayHasKey('success', $op);
		$this->assertEquals(false, $op['success']);
		$this->assertArrayHasKey('errorCode', $op);
		$this->assertEquals($e->getCode(), $op['errorCode']);
		$this->assertArrayHasKey('errorDescription', $op);
		$this->assertEquals($e->getMessage(), $op['errorDescription']);
	}
	
	public function testSendSuccessResponse() {
		ob_start();
		$this->object->sendSuccessResponse();
		$output = ob_get_clean();
		$headers = xdebug_get_headers();
		$this->assertEquals(200, http_response_code());
		$this->assertTrue(in_array('Content-Type: application/json', $headers), 'Content-Type header not set correctly');
		$op = json_decode($output, JSON_OBJECT_AS_ARRAY);
		if(!$op) $this->fail('Could not decode error response');
		$this->assertArrayHasKey('success', $op);
		$this->assertEquals(true, $op['success']);
	}
}
