<?php

namespace App\Transformers\Library;

use App\Transformers\AbstractTransformer;

class BookTransformer extends AbstractTransformer
{
    public function transform($book)
    {
        return [
            'user' => '',
            'type' => $book->type = 'P' ? 'Printed' : 'Audiobook',
        ];
    }

    /*
    ****************************************************************************
    */

}