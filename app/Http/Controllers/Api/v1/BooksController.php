<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookWithCommentResource;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth as FacadesAuth;
class BooksController extends Controller
{
    //
    public $successStatus = 200;
    public function LoadBook()
    {
        return BookResource::collection(Book::paginate(5));
    }
    public function LoadBookWithMoreLike()
    {
        return BookResource::collection(Book::withCount('rating')->orderBy('created_at', 'desc')->paginate(10));
    }
    public function AddBook(BookRequest $request)
    {
        $validator = $request->validated();
        try {
            $book = Book::create([
                'author' =>  $request->author,
                'title' => $request->title,
                'body' => $request->body
            ]);
                $success['message'] = 'Book Added Succefully...';
                $success['status'] = 1;
                $success['book'] =BookResource::make($book);
                return response()->json($success, $this->successStatus);

                } catch (\Throwable $e) {
                    $response['message'] = $e->getMessage();
                    $response['status'] = 2;
                    $response['token'] = '';
                    $response['book'] = null;
                    return response()->json($response, $this->successStatus);
                }
    }
    public function EditBook(BookRequest $request)
    {
        $validator = $request->validated();
        try {
            $book = Book::find($request->book_id)->update([
                'author' =>  $request->author,
                'title' => $request->title,
                'body' => $request->body
            ]);
                $success['message'] = 'Book edited Succefully...';
                $success['status'] = 1;
                $success['book'] =BookResource::make(Book::find($request->book_id));
                return response()->json($success, $this->successStatus);
            } catch (\Throwable $e) {
                $response['message'] = $e->getMessage();
                $response['status'] = 2;
                $response['token'] = '';
                $response['book'] = null;
                return response()->json($response, $this->successStatus);

            }
    }
    public function DeleteBook($book_id)
    {
        try {
               Book::find($book_id)->delete();
                $success['message'] = 'Book deleted Succefully...';
                $success['status'] = 1;
                return response()->json($success, $this->successStatus);

            } catch (\Throwable $e) {
                $response['message'] = $e->getMessage();
                $response['status'] = 2;
                $response['token'] = '';
                $response['book'] = null;
                return response()->json($response, $this->successStatus);

            }
    }

    public function ViewBook($book_id)
    {
        try {
            $book = Book::find($book_id);
                $success['message'] = 'Succefully...';
                $success['status'] = 1;
                $success['book'] =BookWithCommentResource::make($book);
                return response()->json($success, $this->successStatus);

        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $response['status'] = 2;
            $response['token'] = '';
            $response['book'] = null;
            return response()->json($response, $this->successStatus);

        }
    }

     public function AddComment(Request $request)
     {
        try {
            Comment::create([
                'comment'=>$request->comment,
                'book_id'=>$request->book_id,
                'user_id'=>FacadesAuth::id()
            ]);
                $success['message'] = 'comment added Succefully...';
                $success['status'] = 1;
                return response()->json($success, $this->successStatus);

        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $response['status'] = 2;
            $response['token'] = '';
            $response['book'] = null;
            return response()->json($response, $this->successStatus);

        }

     }
     public function LikeBook($book_id)
     {
        try {
            $like = $this->CheckLikeBook($book_id);
            $user_id = FacadesAuth::id();
            if($this->CheckBook($book_id)){
                Rating::where([
                    ['book_id',$book_id],
                    ['user_id',$user_id]
               ])->update([
                    'like'=>$like,
                ]);
            }else{
                Rating::create([
                    'like'=>$like,
                    'book_id'=>$book_id,
                    'user_id'=>$user_id
                ]);
            }
                $success['message'] = 'Succefully...';
                $success['status'] = 1;
                return response()->json($success, $this->successStatus);

        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $response['status'] = 2;
            $response['token'] = '';
            $response['book'] = null;
            return response()->json($response, $this->successStatus);

        }

     }
     public function MarkBook($book_id)
     {
        try {
            $mark = $this->CheckMarkBook($book_id);
            $user_id = FacadesAuth::id();
            if($this->CheckBook($book_id)){
                Rating::where([
                    ['book_id',$book_id],
                    ['user_id',$user_id]
               ])->update([
                    'mark'=>$mark,
                ]);
            }else{
                Rating::create([
                    'mark'=>$mark,
                    'book_id'=>$book_id,
                    'user_id'=>$user_id
                ]);
            }
                $success['message'] = 'Bookmarked Succefully...';
                $success['status'] = 1;
                return response()->json($success, $this->successStatus);

        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $response['status'] = 2;
            $response['token'] = '';
            $response['book'] = null;
            return response()->json($response, $this->successStatus);

        }

     }
     private function CheckBook($book_id)
     {
        $user_id = FacadesAuth::id();
        $rating = Rating::where([
            ['book_id',$book_id],
            ['user_id',$user_id]
       ])->first();

       if(!is_null($rating)){
        return true;
       }else{
          return false;
       }
     }

     private function CheckLikeBook($book_id)
     {
        $user_id = FacadesAuth::id();
        $rating = Rating::where([
            ['book_id',$book_id],
            ['user_id',$user_id]
       ])->first();
       if(!is_null($rating)){
            $like = ($rating->like == 0)? 1: 0;
       }else{
          return 1;
       }
     }
     private function CheckMarkBook($book_id)
     {
        $user_id = FacadesAuth::id();
        $rating = Rating::where([
            ['book_id',$book_id],
            ['user_id',$user_id]
       ])->first();
       if(!is_null($rating)){
        $mark = ($rating->mark == 0)? 1: 0;
        }else{
            return 1;
        }
      
     }


}
