<?php

namespace App\Models;

use Exception;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseModel extends Model
{
    protected $database = NULL;
    protected $table = NULL;
    protected $primeKey = NULL;
    protected $dropdown = NULL;
    public $timestamps = FALSE;

    /*
    ****************************************************************************
    */

    public function getDropdown()
    {
        $results = $this->select(
                    $this->primeKey,
                    $this->dropdown
                )
                ->orderBy($this->dropdown)
                ->get()
                ->toArray();

        $keys = array_column($results, $this->primeKey);
        $values = array_column($results, $this->dropdown);

        return array_combine($values, $keys);
    }

    /*
    ****************************************************************************
    */

    public function getCount()
    {
        $table = $this->table;
        $field = $this->dropdown;

        return $this->select(
                    $table . '.' . $this->primeKey,
                    DB::raw($table . '.' . $field . ' AS name'),
                    DB::raw('COUNT(books.id) AS `count`')
                )
                ->join('books', 'books.' .$field . '_id', $table . '.id')
                ->groupBy($table . '.' . $this->primeKey)
                ->groupBy($field)
                ->orderBy($field, 'ASC')
                ->get()
                ->toArray();
    }

    /*
    ****************************************************************************
    */

    protected function paginate($query, $data)
    {
        $limit = $data['limit'] ?? env('TABLE_ROW_COUNT');

        return $this->applySortAndFilter($query, $data)->paginate($limit);
    }

    /*
    ****************************************************************************
    */

    protected function applySortAndFilter($query, $data=[])
    {
        $sort = $filter = [];

        if (isset($data['sort'])) {
            $sort = is_array($data['sort']) ? $data['sort'] :
                json_decode($data['sort'], TRUE);
        }

        if (isset($data['filter'])) {
            $filter = is_array($data['filter']) ? $data['filter'] :
                json_decode($data['filter'], TRUE);
        }

        $query->where(function($query) use ($filter) {
            foreach ($filter as $field => $filterInfo) {
                $query = $this->applyCustomFilter($query, $field, $filterInfo);
            }
        });

        foreach ($sort as $filed => $order) {

            $sortOrder = $order ? strtolower($order) : env('TABLE_ORDER');

            $query->orderBy($filed, $sortOrder);
        }

        return $query;
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

        if (strpos($field, '.') === FALSE) {
            $field = $this->table . '.' . $field;
        }

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
        $fields = array_keys($data);

        $maxLength = $this->getFieldsMaxLength($fields);

        array_walk($data, function(&$value, $field) use ($maxLength) {
            $value = ! $maxLength[$field] ? $value :
                    str_limit($value, $maxLength[$field] - 5, ' ...');
        });

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

    private function getFieldsMaxLength($fields)
    {
        $result = DB::table('information_schema.columns')
                ->select(DB::raw('
                    COLUMN_NAME AS fieldName,
                    CHARACTER_MAXIMUM_LENGTH AS maxSize
                '))
                ->where([
                    ['table_schema', $this->database],
                    ['table_name', $this->getTable()],
                ])
                ->whereIn('COLUMN_NAME', $fields)
                ->get()
                ->toArray();

        $fieldNames = array_column($result, 'fieldName');
        $maxSizes = array_column($result, 'maxSize');

        return array_combine($fieldNames, $maxSizes);
    }

    /*
    ****************************************************************************
    */

    public function getStorageFolder()
    {
        return storage_path() . DIRECTORY_SEPARATOR . 'app' .
                DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
    }

    /*
    ****************************************************************************
    */

    public function getTempFolder()
    {
        return $this->getStorageFolder() . 'temp' . DIRECTORY_SEPARATOR;
    }

    /*
    ****************************************************************************
    */

}
