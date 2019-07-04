<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use App\DTO\Author;

/**
 * @Route("/api/v1", name="api_v1_")
 */
class Authors extends AbstractController
{
    /**
     * @Route("/authors", name="authors_list", methods={"GET"})
     * @Operation(
     *     description="Author list",
     *     tags={"My test app"},
     *     summary="Author list",
     *     @SWG\Response(
     *          description="Returned value",
     *          response="200",
     *          @SWG\Schema(ref=@Model(type="App\DTO\Author"))
     *     )
     * )
     *
     * @param AuthorRepository $repository
     * 
     * @return JsonResponse
     */
    public function getList(AuthorRepository $repository): JsonResponse
    {
        try {
            $data = $repository->getAuthorsWithBooks();
            $dtoData = [];
            foreach ($data as $item){
                $author = new Author($item);
                $dtoData[] = $author;
            }

        } catch (\Throwable $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json($dtoData, Response::HTTP_OK);
    }
}