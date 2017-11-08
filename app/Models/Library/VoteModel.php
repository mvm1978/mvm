<?php

namespace App\Models\Library;

use App\Models\Library\GenreModel;
use App\Models\Library\LibraryModel;
use Illuminate\Support\Facades\DB;

class VoteModel extends LibraryModel
{
    protected $table = 'quiz_results';

    protected $fillable = [
        'user_id',
        'quiz_date',
        'author_id',
        'genre_id',
    ];

    /*
    ****************************************************************************
    */

    public function create($data, $userID)
    {
        DB::beginTransaction();

        $this->where([
            ['user_id', $userID],
            ['quiz_date', date('Y-m-d')],
        ])->delete();

        foreach ($data as $authorID => $genres) {
            foreach ($genres as $genreID) {
                $this->insert([
                    'user_id' => $userID,
					'quiz_date' => date('Y-m-d'),
                    'author_id' => $authorID,
                    'genre_id' => $genreID,
                ]);
            }
        }

        DB::commit();

        return [];
    }

    /*
    ****************************************************************************
    */

    public function getTotals($userID)
    {

        $genreModel = new GenreModel();

        $results = $this
                ->select('genre', DB::raw('COUNT(genre_id) as total'))
                ->join('genres', 'genres.id', '=', 'quiz_results.genre_id')
                ->where('user_id', '=', $userID)
                ->groupBy('genre_id', 'genre')
                ->orderBy('genre_id')
                ->get();

        $dateResults = $this
                ->select(DB::raw('CONCAT_WS(" - ", MIN(quiz_date), MAX(quiz_date)) as date_range'))
                ->where('user_id', '=', $userID)
                ->get();

        $genreTotals = $totals = $return = [];

        foreach ($results->toArray() as $result) {

            $genre = $result['genre'];

            $totals[$genre] = $result['total'];
        }

        $typeInfo = $genreModel->getTypeInfo();

        foreach ($typeInfo as $type => $values) {
            foreach ($values as $info) {

                $genre = $info['genre'];

                $genreTotals[$type][$genre] = isset($totals[$genre]) ?
                        $totals[$genre] : 0;
            }
        }

        foreach ($genreTotals as $type => $values) {
            $return[] = [
                'caption' => $type,
                'dates' => $dateResults->toArray()[0]['date_range'],
                'labels' => array_keys($values),
                'data' => array_values($values),
            ];
        }

        return $return;
    }

    /*
    ****************************************************************************
    */

}
