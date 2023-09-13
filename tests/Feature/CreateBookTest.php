<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateBookTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_book()
    {
        // se crea un usuario
        $user = User::factory()->create();
        $this->actingAs($user);

        // Simula un archivo de imagen de prueba
        $image = UploadedFile::fake()->image('cover.jpg');

        // Define los datos del libro
        $data = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'credit' => $this->faker->randomNumber,
            'image' => $image,
            'user_id' => $user->id
        ];

        // Realiza una solicitud POST al endpoint de creación de libros
        $response = $this->post('/api/books', $data);

        $responseImage = $response->json('data.image');

        // Verifica que la respuesta sea exitosa (código 201)
        $response->assertStatus(201);

        // Verifica que el libro haya sido creado en la base de datos
        $this->assertDatabaseHas('books', [
            'title' => $data['title'],
            'description' => $data['description'],
            'credit' => $data['credit'],
            'user_id' => $user->id,
        ]);
        
        // Verifica que el archivo de imagen se haya almacenado correctamente
        $imagePath = 'images/books/' . $responseImage;
        $this->assertFileExists(public_path($imagePath));

        // Verifica la estructura de la respuesta JSON
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'credit',
                'user_id',
                'image',
                'created_at',
                'updated_at',
            ],
        ]);
    }
}
