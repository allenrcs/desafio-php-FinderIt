<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $books = Book::with('user')->get();
        if ($request->user_id) {
            $books = $books->where('user_id', $request->user_id);
        }
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $book = Book::create($data);

        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBookRequest  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        //
    }

    private function uploadImage($image)
    {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $imagePath = public_path('images/books/');
        $image->move($imagePath, $imageName);

        return $imageName;
    }
}
