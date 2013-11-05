<?php

/*
 * Copyright (C) 2013 Akkroo Solutions Ltd
 * 
 */

require_once __DIR__ . "/../vendor/autoload.php";

use \Akkroo\StripeEvent\StripeEvent;

try {
	$stripeEvent = new StripeEvent();
} catch(\Akkroo\StripeEvent\Exceptions\StripeEventException $e) {
	StripeEvent::sendExceptionResponse($e);
	exit(1);
}

switch($stripeEvent->getType()) {
	case 'account.updated':
		// account updated
		// perform action
		break;
	case 'charge.succeeded':
		// charge succeeded
		// perform action
		break;
	default:
		echo 'Unhandled event';
		break;
}

StripeEvent::sendSuccessResponse();
exit(0);