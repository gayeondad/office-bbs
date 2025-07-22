<?php
/**
 * monolog configuration
 */
// template loading
$logPath = $_SERVER['DOCUMENT_ROOT'] . "/log";

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

// create a log channel
$logger = new Logger('my_logger');
// $logger->pushHandler(new StreamHandler($logPath . '/my_app.log', Level::Warning));	// Level::Debug
$handler = new StreamHandler($logPath . '/my_app.log', Level::Warning);
$handler->setFormatter(new LineFormatter("[%datetime%] %channel%.%level_name%: %message% : %context% : %extra%\n", "Y-m-d H:i:s"));
$logger->pushHandler($handler);


// You can now use your logger
$logger->info('My logger is now ready');
// $logger->debug('Welcome To Monolog');
// $logger->info('Welcome To Monolog');
// $logger->notice('Welcome To Monolog');
// $logger->warning('Welcome To Monolog');
// $logger->error('Welcome To Monolog');
// $logger->alert('Welcome To Monolog');
// $logger->emergency("Welcome To Monolog");
