<?php

namespace App\Models\Library;

use App\Models\Library\LibraryModel;

class GenreModel extends LibraryModel
{
    protected $table = 'genres';
    protected $primeKey = 'id';
    protected $fillable = [
        'genre',
    ];

    /*
    ****************************************************************************
    */

    public function getTableData($data)
    {
        $query = $this->select(
                    'id',
                    'genre'
                );

        return $this->paginate($query, $data);
    }

    /*
    ****************************************************************************
    */

    public function getByName($genre)
    {
        $result = $this->select('*')
                ->where('genre', $genre)
                ->first();

        return $result ? $result->toArray() : [];
    }

    /*
    ****************************************************************************
    */

    public function createGenre($genre)
    {
        $this->create([
            'genre' => $genre,
        ]);
    }

    /*
    ****************************************************************************
    */

}
