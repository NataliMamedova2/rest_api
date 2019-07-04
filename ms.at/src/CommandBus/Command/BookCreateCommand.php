<?php
declare(strict_types=1);

namespace App\CommandBus\Command;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Swagger\Annotations as SWG;

class BookCreateCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @SWG\Property(type="string", property="title")
     * @Groups("book.create")
     */
    public $title;

    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $authorId;

    /**
     * @var array
     * @SWG\Schema(
     *   type="array",
     *   @SWG\Items(
     *       type="object",
     *       @SWG\Property(property="id", type="integer"),
     *       @SWG\Property(property="name", type="string")
     *   )
     * )
     */
    public $authors;
}