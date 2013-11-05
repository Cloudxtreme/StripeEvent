<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
// start output buffering for headers tests
ob_start();

$loader = class_exists('\Composer\Autoload\ClassLoader') ?
	new \Composer\Autoload\ClassLoader() :
	require_once __DIR__ . "/../vendor/autoload.php";
$loader->add('Akkroo\\', __DIR__);