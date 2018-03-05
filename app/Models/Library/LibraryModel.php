<?php

namespace App\Models\Library;

use App\Models\BaseModel;

abstract class LibraryModel extends BaseModel
{
    protected $database = 'library';
    protected $primeKey = 'id';
    protected $searchable = [];

    /*
    ****************************************************************************
    */

    protected function getSearchable()
    {
        return $this->searchable;
    }

    /*
    ****************************************************************************
    */

    public function getInfo($field)
    {
        $results = $this->get()->toArray();

        $keys = array_column($results, $this->primeKey);
        $values = array_column($results, $field);

        return array_combine($keys, $values);
    }

    /*
    ****************************************************************************
    */

}
