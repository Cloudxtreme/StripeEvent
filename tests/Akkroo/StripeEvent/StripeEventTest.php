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
	}
	
	private function setTestInput() {
		$this->object->setInput($this->testInput, true);
	}
	
	public function testSetInput() {
		$this->object->setInput($this->testInput, true);
		$this->assertEquals($this->testInput, $this->object->getInput());
		
		$this->object->setInput($this->testInputJSON, false);
		$this->assertEquals($this->testInput, $this->object->getInput());
	}
	
	public function testGetID() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testGetType() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testGetRequest() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testTimeCreated() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testGetPendingWebhooks() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testLivemode() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testGetObject() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testGetPreviousAttributes() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
	
	public function testGetID() {
		$this->setTestInput();
		$this->assertEquals($this->testInput['id'], $this->object->getID());
	}
}
