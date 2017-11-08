<?php

namespace App\Models;

use Exception;

use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model
{
    protected $table = NULL;
    protected $primeKey = NULL;
    public $timestamps = FALSE;

    /*
    ****************************************************************************
    */

    protected function paginate($query, $data)
    {
        $limit = empty($data['limit']) ? env('TABLE_ROW_COUNT') : $data['limit'];
        $sort = isset($data['sort']) ? json_decode($data['sort'], TRUE) : NULL ;
        $filter = isset($data['filter']) ? json_decode($data['filter'], TRUE) : NULL;

        $query->where(function($query) use ($filter) {
            foreach ($filter as $field => $filterInfo) {
                $query = $this->applyCustomFilter($query, $field, $filterInfo);
            }
        });

        foreach ($sort as $filed => $order) {

            $sortOrder = $order ? strtolower($order) : env('TABLE_ORDER');

            $query->orderBy($filed, $sortOrder);
        }

        return $query->paginate($limit);
    }

    /*
    ****************************************************************************
    */

    private function applyCustomFilter($query, $field, $filterInfo)
    {
        $query->where(function($query) use ($field, $filterInfo) {

            $operator = 'AND';

            foreach ($filterInfo as $info) {

                $query = $this->applyCustomClause($query, $field, $info, $operator);

                $operator = $info['operator'];
            }
        });

        return $query;
    }

    /*
    ****************************************************************************
    */

    private function applyCustomClause($query, $field, $info, $operator)
    {
        $clause = $operator == 'OR' ? 'orWhere' : 'where';

        switch ($info['condition']) {
            case 'equals-to':
                $query->$clause($field, $info['value']);
                break;
            case 'contains':
                $query->$clause($field, 'like', '%' . $info['value'] . '%');
                break;
            case 'starts-with':
                $query->$clause($field, 'like', $info['value'] . '%');
                break;
            case 'ends-with':
                $query->$clause($field, 'like', '%' . $info['value']);
                break;
            default:
                break;
        }

        return $query;
    }

    /*
    ****************************************************************************
    */

    public function patchField($data, $id)
    {
        try {
            $this->where($this->primeKey, $id)
                ->update([
                    $data['field'] => $data['value'],
                ]);
        } catch (Exception $exception) {
            return FALSE;
        }

        return TRUE;
    }

    /*
    ****************************************************************************
    */
}
