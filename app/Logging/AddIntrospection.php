<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

class AddIntrospection
{
    public function __invoke(Logger $logger): Logger
    {
        $logger->pushProcessor(new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\']));
        return $logger;
    }
}
