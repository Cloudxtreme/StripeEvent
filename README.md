StripeEvent
===========

[![Total Downloads](https://poser.pugx.org/akkroo/stripeevent/downloads.png)](https://packagist.org/packages/akkroo/stripe-event)
[![Latest Stable Version](https://poser.pugx.org/akkroo/stripeevent/v/stable.png)](https://packagist.org/packages/akkroo/stripe-event)


Usage
-----

```php
<?php

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
```

Requirements
------------

- PHP 5.3 or higher
- Xdebug to test the methods which set headers

Submitting bugs and feature requests
------------------------------------

Bugs and feature request are tracked on [GitHub](https://github.com/Akkroo/StripeEvent/issues)

License
-------

StripeEvent is licensed under the MIT License - see the `LICENSE` file for details
