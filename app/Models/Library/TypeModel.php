<?php

namespace App\Models\Library;

use App\Models\Library\LibraryModel;

class TypeModel extends LibraryModel
{
    protected $table = 'types';
    protected $primeKey = 'id';
    protected $dropdown = 'type';
    protected $fillable = [
        'type',
    ];

    /*
    ****************************************************************************
    */

    public function getTableData($data)
    {
        $query = $this->select(
                    'id',
                    'type'
                );

        return $this->paginate($query, $data);
    }

    /*
    ****************************************************************************
    */

}
