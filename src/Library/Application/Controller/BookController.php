<?php

namespace App\Library\Application\Controller;

use App\Library\Application\Exception\BookWasFoundException;
use App\Library\Application\Exception\CannotCreateBookBecauseOfMissingParametersException;
use App\Library\Application\Exception\InvalidUuidFormatException;
use App\Library\Application\UseCase\CreateBookUseCase;
use App\Library\Application\UseCase\GetBooksUseCase;
use App\Library\Application\UseCase\GetBookUseCase;
use App\Library\Domain\Entity\Book;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class BookController extends Controller
{
    /**
     * @FOSRest\Get("/items")
     * @return array
     */
    public function list(Request $request)
    {
        $limit = $request->query->get('limit', 5);
        $offset = $request->query->get('offset', 0);

        $getBooksUseCase = new GetBooksUseCase(
            $this->getDoctrine()->getRepository('Library:Book')
        );

        $bookListItems = $getBooksUseCase->run($limit, $offset);

        return View::create($bookListItems, Response::HTTP_OK, []);
    }

    /**
     * @FOSRest\Get("/items/{uuid}")
     * @return array
     */
    public function detail($uuid)
    {
        try {
            $getBookUseCase = new GetBookUseCase($this->getDoctrine()->getRepository('Library:Book'));

            $bookDetailItem = $getBookUseCase->run($uuid);

            return View::create($bookDetailItem, Response::HTTP_OK, []);
        } catch (InvalidUuidFormatException $e) {
            return View::create(array('message' => $e->getMessage()), Response::HTTP_BAD_REQUEST, []);
        } catch (BookWasFoundException $e) {
            return View::create(array('message' => $e->getMessage()), Response::HTTP_NOT_FOUND, []);
        }
    }

    /**
     * @FOSRest\Post("/items")
     * @return array
     */
    public function create(Request $request)
    {
        $title = $request->get('title');
        $price = $request->get('price');
        $image = $request->get('image');
        $author = $request->get('author');

        try {
            if (empty($title) || empty($price) || empty($image) || empty($author)) {
                throw new CannotCreateBookBecauseOfMissingParametersException($request);
            }

            $book = new Book($title, $image, $author, $price);

            $createBookUseCase = new CreateBookUseCase($this->getDoctrine()->getRepository('Library:Book'));

            $bookDetailItem = $createBookUseCase->run($book);

            return View::create($bookDetailItem, Response::HTTP_CREATED, []);
        } catch (CannotCreateBookBecauseOfMissingParametersException $e) {
            return View::create(array('message' => $e->getMessage()), Response::HTTP_BAD_REQUEST, []);
        }
    }
}