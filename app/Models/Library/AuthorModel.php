<?php

namespace App\Models\Library;

use App\Models\Library\LibraryModel;
use App\Models\Library\Reports\AuthorReportModel;

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
        $query = $this->getQuery();

        return $this->paginate($query, $data);
    }

    /*
    ****************************************************************************
    */

    public function getQuery()
    {
        return $this->select(
                    'id',
                    'author',
                    'description',
                    'picture'
                );
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

    public function createReport($info, $file)
    {
        $reportModel = new AuthorReportModel();

        $query = $this->getQuery();

        $results = $this->applySortAndFilter($query, $info['outputSettings'])
                ->get()
                ->toArray();

        $reportModel->createReport($results, $info, $file);
    }

    /*
    ****************************************************************************
    */

}
