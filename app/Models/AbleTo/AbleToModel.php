<?php

namespace App\Models\AbleTo;

use App\Models\AbstractModel;

class AbleToModel extends AbstractModel
{
    protected $primeKey = 'id';

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
