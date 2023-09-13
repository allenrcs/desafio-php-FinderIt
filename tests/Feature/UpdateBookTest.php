<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateBookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_book()
    {
        // se crea un usuario
        $user = User::factory()->create();
        $this->actingAs($user);

        $image = UploadedFile::fake()->image('cover.jpg');

        // Crea un libro para el usuario
        $book = Book::factory()->create(
            [
                'title' => 'ipsu',
                'description' => 'lorem',
                'credit' => 1000,
                'image' => $image,
                'user_id' => $user->id
            ]
        );

        // Crea una nueva imagen
        $newImage = UploadedFile::fake()->image('new_cover.jpg');

        $newData = [
            'title' => 'New Title',
            'description' => 'New Description',
            'credit' => 12345,
            'image' => $newImage,
        ];

        // Realiza una solicitud PUT al endpoint de actualización de libros
        $response = $this->put("/api/books/{$book->id}", $newData);

        $updateImage = $response->json('data.image');

        // Verifica que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verifica que el libro se hayan actualizado en la base de datos
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => $newData['title'],
            'description' => $newData['description'],
            'credit' => $newData['credit'],
            'user_id' => $user->id,
            'image' => $updateImage,
        ]);

        // Verifica que la nueva imagen se haya almacenado correctamente
        $this->assertFileExists(public_path('images/books/' . $updateImage));

        // Verifica que la imagen anterior se haya eliminado
        $this->assertFileDoesNotExist(public_path('images/books/' . $book->image));
    }
}
