<?php

namespace App\Models\Library;

use App\Models\Library\LibraryModel;
use App\Models\Library\Reports\GenreReportModel;

class GenreModel extends LibraryModel
{
    protected $table = 'genres';
    protected $primeKey = 'id';
    protected $dropdown = 'genre';
    protected $fillable = [
        'genre',
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
                    'genre'
                );
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

    public function createReport($info, $file)
    {
        $reportModel = new GenreReportModel();

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
