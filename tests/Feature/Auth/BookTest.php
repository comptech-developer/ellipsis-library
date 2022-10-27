<?php

namespace Tests\Feature\Auth;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_create_book()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST','api/v1/addbook',[
            'title' => 'Test book',
            'author' => 'myself',
            'body' => 'this body book'
        ]);

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test update book.
     *
     * @return void
     */
    public function test_update_book()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('POST','api/v1/editbook',[
            'title' => 'Test book2',
            'author' => 'myself2',
            'body' => 'this body book2',
            'book_id'=>5239
        ]);

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test find book.
     *
     * @return void
     */
    public function test_find_book()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','api/v1/book/9');

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test get all books.
     *
     * @return void
     */
    public function test_get_all_book()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','api/v1/books');

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    /**
     * test delete books.
     *
     * @return void
     */
    public function test_delete_book()
    {
        $token = $this->authenticate();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
        ])->json('GET','api/v1/deletebook/5239');

        //Write the response in laravel.log
        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    protected function authenticate()
    {
        $user = User::create([
            'name' => 'test',
            'role_id'=>2,
            'email' => rand(12345,678910).'test@gmail.com',
            'password' => Hash::make('secret123'),
        ]);

        if (!auth()->attempt(['email'=>$user->email, 'password'=>'secret123'])) {
            return response(['message' => 'Login credentials are invaild']);
        }

        return $accessToken = auth()->user()->createToken('authToken')->accessToken;
    }
}
