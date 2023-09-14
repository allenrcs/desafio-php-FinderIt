<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class IndexBookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_index_books()
    {
        // se crea un usuario
        $user = User::factory()->create();
        $this->actingAs($user);

        $image = UploadedFile::fake()->image('cover.jpg');

        // Crea varios libros para el usuario
        $books = Book::factory()->count(3)->create(
            [
                'title' => 'ipsu',
                'description' => 'lorem',
                'credit' => 1000,
                'image' => $image,
                'user_id' => $user->id
            ]
        );

        // Realiza una solicitud GET al endpoint de listado de libros
        $response = $this->get('/api/books?'."user_id=$user->id");

        // Verifica que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verifica que la respuesta contenga los datos correctos
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'credit',
                    'user_id',
                    'image',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        foreach ($books as $book) {
            $response->assertJsonFragment([
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'credit' => $book->credit,
                'user_id' => $book->user_id,
            ]);
        }
    }
}
