<?php

namespace App\Models\Library;

use App\Models\Library\LibraryModel;

class GenreModel extends LibraryModel
{
    protected $table = 'genres';

    protected $fillable = [
        'genre',
    ];

    /*
    ****************************************************************************
    */

    public function getGenres()
    {
        $results = $this->select('genre')
                ->orderBy('genre')
                ->get();

        return $results;
    }

    /*
    ****************************************************************************
    */
}
