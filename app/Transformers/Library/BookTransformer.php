<?php

namespace App\Transformers\Library;

use App\Transformers\AbstractTransformer;

class BookTransformer extends AbstractTransformer
{
    public function transform($book)
    {
        return [
            'genre' => object_get($book, 'genre.genre', ''),
            'author' => object_get($book, 'author.author', ''),
            'user' => '',
            'uploaded_on' => substr($book->uploaded_on, 0, 10),
            'type' => $book->type = 'P' ? 'Page' : 'Audiobook',
            'title' => $book->title,
            'description' => $book->description,
            'length' => $book->length,
            'upvotes' => $book->upvotes,
            'downvotes' => $book->downvotes,
            'approved' => $book->approved,
        ];
    }

    /*
    ****************************************************************************
    */

}