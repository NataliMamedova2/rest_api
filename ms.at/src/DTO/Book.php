<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

class Book
{
    /**
     * @var integer
     * @Assert\NotBlank()
     * @SWG\Property(type="integer", property="id")
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @SWG\Property(type="string", property="title")
     */
    public $title;
}