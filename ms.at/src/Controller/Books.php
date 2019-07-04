<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\BookRepository;
use App\CommandBus\Command\BookCreateCommand;
use App\CommandBus\Command\BookUpdateCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @Route("/api/v1", name="api_v1_")
 */
class Books extends AbstractController
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/books", name="books_list", methods={"GET"})
     * @Operation(
     *     description="Book list",
     *     tags={"My test app"},
     *     summary="Book list",
     *     @SWG\Response(
     *          description="Returned value",
     *          response="200",
     *          @SWG\Schema(ref=@Model(type="App\Entity\Book"))
     *     )
     * )
     *
     * @param BookRepository $repository
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    public function getList(BookRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $data = $repository->findAll();
            $arData = $serializer->normalize($data);
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($arData, Response::HTTP_OK);
    }

    /**
     * @Route("/books/authors/{id}", name="create_book", methods={"POST"})
     * @Operation(
     *     description="Create Book with author",
     *     tags={"My test app"},
     *     summary="Create Book with author",
     *     @SWG\Response(
     *          description="Create Book",
     *          response="204"
     *     ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          @SWG\Schema(ref=@Model(type="App\CommandBus\Command\BookCreateCommand", groups={"book.create"}))
     *     )
     * )
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function createBook(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            $command = $serializer->deserialize($request->getContent(), BookCreateCommand::class, 'json');
            $command->authorId = $id;

            $this->commandBus->handle($command);
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/books/{id}", name="get_book", methods={"GET"})
     * @Operation(
     *     description="Get book by ID",
     *     tags={"My test app"},
     *     summary="Get book by ID",
     *     @SWG\Response(
     *          description="Returned value",
     *          response="200",
     *          @SWG\Schema(ref=@Model(type="App\Entity\Book"))
     *     )
     * )
     *
     * @param int $id
     * @param BookRepository $repository
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    public function getBookById(int $id, BookRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        try {
            $data = $repository->find($id);

            if($data === null){
               throw new BadRequestHttpException('There is no such book');
            }

            $arData = $serializer->normalize($data);
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return $this->json($arData, Response::HTTP_OK);
    }

    /**
     * @Route("/books/{id}", name="update_book", methods={"PATCH"})
     * @Operation(
     *     description="Update book title",
     *     tags={"My test app"},
     *     summary="Update book title",
     *     @SWG\Response(
     *          description="Update Book",
     *          response="204"
     *     ),
     *     @SWG\Parameter(
     *          in="body",
     *          name="body",
     *          @SWG\Schema(ref=@Model(type="App\CommandBus\Command\BookUpdateCommand", groups={"book.update"}))
     *     )
     * )
     * @param int $id
     * @param Request $request
     * @param SerializerInterface $serializer
     *
     * @return JsonResponse
     */
    public function updateTitleBook(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            $command = $serializer->deserialize($request->getContent(), BookUpdateCommand::class, 'json');
            $command->id = $id;

            $this->commandBus->handle($command);
        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}