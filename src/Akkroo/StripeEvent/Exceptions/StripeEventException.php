<?php

/*
 * Copyright (C) 2013 Akkroo Ltd
 * 
 */

namespace Akkroo\StripeEvent\Exceptions;

class StripeEventException extends \Exception {
	/**
	 * The HTTP request body could not be fetched, or was empty
	 */
	const INPUT_FETCH_FAILED = 30;
	/**
	 * Decoding the webhook JSON body failed
	 */
	const INPUT_DECODE_FAILED = 40;
	/**
	 * No data object in input data
	 */
	const NO_DATA = 70;
	/**
	 * No type was found in input data
	 */
	const NO_TYPE = 80;
	/**
	 * An internal server error occurred
	 */
	const INTERNAL_SERVER_ERROR = 500;
}