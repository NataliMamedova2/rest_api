<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BooksTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->client = $client = static::createClient();
    }

    public function testBookList()
    {

        $this->client->request('GET', '/api/v1/books/');
        $response = $this->client->getResponse();

        $this->assertEquals(
            Response::HTTP_OK,
            $response->getStatusCode(),
            sprintf('The %s URL is correctly.', '/api/v1/books/')
        );
    }

    public function testCreateBook()
    {
        $data = '{ "title": "The best book"}';
        $this->client->request('POST', '/api/v1/books/authors/5', [], [], ['CONTENT_TYPE' => 'application/json'], $data);
        $response = $this->client->getResponse();

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $response->getStatusCode(),
            sprintf('The %s URL is correctly.', '/api/v1/books/authors/5')
        );
    }
}