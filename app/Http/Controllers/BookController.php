<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Gate;

/**
 * @group Books
 */
class BookController extends Controller
{
    /**
     * Get books.
     *
     * this endpoint provides the books of a user
     *
     * @authenticated
     *
     * @queryParam user_id int Id user. Example: 1
     * 
     * @return BookResource
     */
    public function index(Request $request)
    {
        $userId = $request->user_id;

        $books = Book::getBooksByUser($userId);

        return BookResource::collection($books);
    }

    /**
     * Get published books.
     *
     * This endpoint provides a list of published books
     *
     * @return BookResource
     */
    public function getBooksDetails(Request $request)
    {
        $books = Book::query()->with('user');

        if ($request->title) {
            $books->where('title', 'LIKE', $request->title);
        }

        if ($request->user_id) {
            $books->where('user_id', $request->user_id);
        }

        return BookResource::collection($books->get());
    }

    /**
     * Create Book.
     *
     * It returns the created book.
     *
     * @authenticated
     *
     * @BodyParam title string required Book title.
     * @BodyParam description string required Description title.
     * @BodyParam credit int required Credit title.
     * @BodyParam image file required Cover image.
     * @BodyParam user_id int required Id user.
     *
     * @return BookResource
     */
    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user_id;

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $book = Book::create($data);

        return new BookResource($book);
    }

    /**
     * Update Book.
     *
     * It returns the created book.
     *
     * @authenticated
     *
     * @BodyParam title string Book title.
     * @BodyParam description string Description title.
     * @BodyParam credit int Credit title.
     * @BodyParam image file Cover image.
     *
     * @return BookResource
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $data = $request->validated();

        $book->update($data);

        if ($request->hasFile('image')) {
            if ($book->image) {
                $this->deleteImage($book->image);
            }

            $data['image'] = $this->uploadImage($request->file('image'));
            $book->update($data);
        }

        return new BookResource($book);
    }

    /**
     * Delete book.
     *
     * This endpoint deletes a book with the specified ID in the URL.
     *
     * @authenticated
     *
     * @return JsonResponse
     */
    public function destroy(Book $book)
    {
        if (Gate::allows('delete', $book)) {
            $book->delete();
            return response()->json(['message' => 'Book deleted successfully']);
        } else {
            return response()->json(['message' => 'You do not have permission to delete this book'], 403);
        }
    }

    private function uploadImage($image)
    {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $imagePath = public_path('images/books/');
        $image->move($imagePath, $imageName);

        return $imageName;
    }

    private function deleteImage($imageName)
    {
        $imagePath = public_path('images/books/');

        $imageFullPath = $imagePath . $imageName;

        if (file_exists($imageFullPath)) {
            unlink($imageFullPath);

            $book->update(['image' => null]);
        }
    }

}
