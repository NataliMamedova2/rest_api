<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;

class Author
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
     * @SWG\Property(type="string", property="name")
     */
    public $name;

    /**
     * @var string[]
     * @SWG\Schema(
     *   type="array",
     *   @SWG\Items(
     *       type="object",
     *       @SWG\Property(property="title", type="string")
     *   )
     * )
     */
    public $books;

    /**
     * Author constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];

        $bookId = json_decode($data['booksid'], true);
        $title = json_decode($data['books'], true);

        foreach ($bookId as $key => $value) {
            $book = new Book();
            $book->id = $value;
            $book->title = $title[$key];

            $this->books[] = $book;
        }
    }
}