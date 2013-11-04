<?php

/*
 * Copyright (C) 2013 Akkroo Solutions Ltd
 * 
 */

require_once __DIR__ . "/../vendor/autoload.php";

$stripeEvent = new \Akkroo\StripeEvent\StripeEvent();

switch($stripeEvent->getType()) {
	case 'account.updated':
		echo "Account updated:\n";
		var_dump($stripeEvent->getObject());
		echo "\nPreviously:\n";
		var_dump($stripeEvent->getPreviousAttributes());
		break;
	default:
		echo 'unknown event';
}