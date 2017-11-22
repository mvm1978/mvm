<?php

namespace App\Models;

use Exception;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $table = NULL;
    protected $primeKey = NULL;
    protected $dropdown = NULL;
    public $timestamps = FALSE;

    /*
    ****************************************************************************
    */

    public function getDropdown()
    {
        $query = $this->select(
                    $this->primeKey,
                    $this->dropdown
                 );

        $results = $query->orderBy($this->dropdown)
                ->get()
                ->toArray();

        $keys = array_column($results, $this->primeKey);
        $values = array_column($results, $this->dropdown);

        return array_combine($values, $keys);
    }

    /*
    ****************************************************************************
    */

    protected function paginate($query, $data)
    {
        $limit = empty($data['limit']) ? env('TABLE_ROW_COUNT') : $data['limit'];
        $sort = isset($data['sort']) ? json_decode($data['sort'], TRUE) : [];
        $filter = isset($data['filter']) ? json_decode($data['filter'], TRUE) : [];

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

    public function patchField($field, $id, $payload)
    {
        try {
            $this->where($this->primeKey, $id)
                ->update([
                    $field => $payload['value'],
                ]);
        } catch (Exception $exception) {
            return FALSE;
        }

        return TRUE;
    }

    /*
    ****************************************************************************
    */

    public function insertEntry($data)
    {
        try {
            $result = $this->create($data)->toArray();
        } catch (Exception $exception) {
            return FALSE;
        }

        $primeKey = $this->primeKey;

        $return = ! $result[$primeKey] ? NULL : [
            $primeKey => $result[$primeKey],
        ];

        return $return;
    }

    /*
    ****************************************************************************
    */

}
