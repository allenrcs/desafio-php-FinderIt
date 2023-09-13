<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DeleteBookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_book()
    {
        // se crea un usuario
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $image = UploadedFile::fake()->image('cover.jpg');

        // Crea un libro de prueba para el usuario
        $book = Book::factory()->create(
            [
                'title' => 'ipsu',
                'description' => 'lorem',
                'credit' => 1000,
                'image' => $image,
                'user_id' => $user->id
            ]
        );

        // Realiza una solicitud DELETE al endpoint de eliminación de libros
        $response = $this->delete("/api/books/{$book->id}");

        // Verifica que la respuesta sea exitosa
        $response->assertStatus(200);

        // Verifica que el libro se haya eliminado
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
