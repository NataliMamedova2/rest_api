<?php
declare(strict_types=1);

namespace App\CommandBus\Middleware;

use App\CommandBus\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationMiddleware implements MessageBusMiddleware
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Dependency Injection constructor.
     *
     * @param LoggerInterface $logger
     * @param ValidatorInterface $validator
     */
    public function __construct(LoggerInterface $logger, ValidatorInterface $validator)
    {
        $this->logger = $logger;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        $violations = $this->validator->validate($message);

        if (count($violations) !== 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            $this->logger->error('Validation exception', $errors);
            throw new ValidationException($errors);
        }

        $next($message);
    }
}