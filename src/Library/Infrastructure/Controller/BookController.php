<?php

namespace App\Library\Infrastructure\Controller;

use App\Library\Application\Exception\BookWasFoundException;
use App\Library\Application\Exception\CannotCreateBookBecauseOfMissingParametersException;
use App\Library\Application\Exception\InvalidUuidFormatException;
use App\Library\Application\UseCase\CreateBookUseCase;
use App\Library\Application\UseCase\GetBooksUseCase;
use App\Library\Application\UseCase\GetBookUseCase;
use App\Library\Domain\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/v1")
 */
class BookController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'];
        $price = $data['price'];
        $image = $data['image'];
        $author = $data['author'];

        try {
            if (empty($title) || empty($price) || empty($image) || empty($author)) {
                throw new CannotCreateBookBecauseOfMissingParametersException($request);
            }

            $book = new Book($title, $image, $author, $price);

            $createBookUseCase = new CreateBookUseCase($this->getDoctrine()->getRepository('Library:Book'));

            $bookDetailItem = $createBookUseCase->run($book);

            $encoders = [new JsonEncoder()];
            $normalizers = [new PropertyNormalizer()];

            $serializer = new Serializer($normalizers, $encoders);
            $jsonContent = $serializer->serialize($bookDetailItem, 'json');

            return JsonResponse::fromJsonString($jsonContent, Response::HTTP_CREATED, []);
        } catch (CannotCreateBookBecauseOfMissingParametersException $e) {
            return new JsonResponse(array('message' => $e->getMessage()), Response::HTTP_BAD_REQUEST, []);
        }
    }

    /**
     * @return JsonResponse
     */
    public function detail($uuid)
    {
        try {
            $getBookUseCase = new GetBookUseCase($this->getDoctrine()->getRepository('Library:Book'));

            $bookDetailItem = $getBookUseCase->run($uuid);

            $encoders = [new JsonEncoder()];
            $normalizers = [new PropertyNormalizer()];

            $serializer = new Serializer($normalizers, $encoders);
            $jsonContent = $serializer->serialize($bookDetailItem, 'json');

            return JsonResponse::fromJsonString($jsonContent, Response::HTTP_OK, []);
        } catch (InvalidUuidFormatException $e) {
            return new JsonResponse(array('message' => $e->getMessage()), Response::HTTP_BAD_REQUEST, []);
        } catch (BookWasFoundException $e) {
            return new JsonResponse(array('message' => $e->getMessage()), Response::HTTP_NOT_FOUND, []);
        }
    }

    /**
     * @return JsonResponse
     */
    public function list(Request $request)
    {
        $limit = $request->query->get('limit', 5);
        $offset = $request->query->get('offset', 0);

        $getBooksUseCase = new GetBooksUseCase(
            $this->getDoctrine()->getRepository('Library:Book')
        );

        $bookListItems = $getBooksUseCase->run($limit, $offset);

        $encoders = [new JsonEncoder()];
        $normalizers = [new PropertyNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($bookListItems, 'json');
        return JsonResponse::fromJsonString($jsonContent, Response::HTTP_OK, []);
    }
}