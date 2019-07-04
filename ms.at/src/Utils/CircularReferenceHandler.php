<?php
declare(strict_types=1);

namespace App\Utils;

class CircularReferenceHandler
{
    public function __invoke($object)
    {
        return $object->getId();
    }
}
