<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Consumers\Attributes\MessageFactory;
use Micromus\KafkaBus\Consumers\Attributes\MessageMiddleware;
use Micromus\KafkaBus\Consumers\Messages\NativeMessageFactory;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Testing\Messages\Middlewares\ConsumerMessageLoggerFakerMiddleware;

final class ConsumerHandlerFaker
{
    #[MessageFactory(NativeMessageFactory::class)]
    #[MessageMiddleware(ConsumerMessageLoggerFakerMiddleware::class)]
    public function execute(ConsumerMessageInterface $message): void
    {
        echo $message->payload() . PHP_EOL;
    }
}
