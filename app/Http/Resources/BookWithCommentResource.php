<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Rating;
use Illuminate\Http\Resources\Json\JsonResource;

class BookWithCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'author'=>$this->author,
            'title'=>$this->title,
            'body'=>$this->body,
            'likes'=>Rating::where('book_id',$this->id)->where('like',1)->count() ?? 0,
            'marked'=>Rating::where('book_id',$this->id)->where('marked',1)->count() ?? 0,
            'comments'=>Comment::where('book_id',$this->id)->count() ?? 0,
            'usercomments'=>$this->comments
            
        ];
    }
}
