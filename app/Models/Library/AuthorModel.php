<?php

namespace App\Models\Library;

use App\Models\Library\LibraryModel;

class AuthorModel extends LibraryModel
{
    protected $table = 'authors';
    protected $primeKey = 'id';
    protected $dropdown = 'author';
    protected $fillable = [
        'author',
        'description',
        'picture',
    ];

    /*
    ****************************************************************************
    */

    public function getTableData($data)
    {
        $query = $this->select(
                    'id',
                    'author',
                    'description',
                    'picture'
                );

        return $this->paginate($query, $data);
    }

    /*
    ****************************************************************************
    */

    public function getByName($author)
    {
        $result = $this->select('*')
                ->where('author', $author)
                ->first();

        return $result ? $result->toArray() : [];
    }

    /*
    ****************************************************************************
    */
}
