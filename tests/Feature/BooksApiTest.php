<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;



class BooksApiTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    /** @test */
    function can_get_all_books()
    {
        $books = Book::factory(4)->create();

        // dd(route('books.index'));//"http://localhost/api/books"

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title'=>$books[0]->title
        ])->assertJsonFragment([
            'title'=>$books[1]->title
        ])->assertJsonFragment([
            'title'=>$books[2]->title
        ])->assertJsonFragment([
            'title'=>$books[3]->title
        ]);

    }

    /** @test */
    function can_get_one_books()
    {
        $book = Book::factory()->create();

        // dd(route('books.show', $book));//http://localhost/api/books/5

        $response = $this->getJson(route('books.index', $book));

        $response->assertJsonFragment([
            'title'=>$book->title
        ]);

    }

    /** @test */
    function can_create_books()
    {
        /* validaciones del body */
        $this->postJson(route('books.store'),[])
        ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'),[
            'title' => 'My new book'
        ])->assertJsonFragment([
            'title' => 'My new book'
        ]);

        /* verifica la database */
        $this->assertDatabaseHas('books',[
            'title' => 'My new book'
        ]);

    }

    /** @test */
    function can_update_books()
    {
        $book = Book::factory()->create();

        /* validaciones del body */
        $this->patchJson(route('books.update', $book),[])
        ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book),[
            'title' => 'Book Edited'
        ])->assertJsonFragment([
            'title' => 'Book Edited'
        ]);

        /* verifica la database */
        $this->assertDatabaseHas('books',[
            'title' => 'Book Edited'
        ]);

    }
    /** @test */
    function can_delete_books()
    {
        $book = Book::factory()->create();

        /* validaciones de la ruta eliminar*/
        $this->deleteJson(route('books.destroy', $book))
             ->assertNoContent();

        /* verifica la database */
        $this->assertDatabaseCount('books', 0);

    }


}
