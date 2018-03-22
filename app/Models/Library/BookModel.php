<?php

namespace App\Models\Library;

use Illuminate\Support\Facades\DB;

use App\Models\Library\LibraryModel;
use App\Models\Library\Reports\BookReportModel;

class BookModel extends LibraryModel
{
    const CHART_MAX_SEGMENT = 5;

    protected $table = 'books';

    protected $fillable = [
        'author_id',
        'genre_id',
        'upload_user_id',
        'uploaded_on',
        'type_id',
        'title',
        'description',
        'length',
        'picture',
        'source',
        'downloads',
        'upvotes',
        'downvotes',
        'approved',
        'approve_date',
        'remove_date',
    ];
    protected $searchable = [
        'title',
        'description',
    ];

    /*
    ****************************************************************************
    */

    public function genre()
    {
        return $this->belongsTo(__NAMESPACE__ . '\GenreModel', 'genre_id');
    }

    /*
    ****************************************************************************
    */

    public function author()
    {
        return $this->belongsTo(__NAMESPACE__ . '\AuthorModel', 'author_id');
    }

    /*
    ****************************************************************************
    */

    public function type()
    {
        return $this->belongsTo(__NAMESPACE__ . '\TypeModel', 'type_id');
    }

    /*
    ****************************************************************************
    */

    public function getTableData($data)
    {
        $query = $this->getQuery();

        if (isset($data['search'])) {
            $data['search'] = [
                'info' => [
                    'authors' => [
                        'author'
                    ],
                    'books' => $this->getSearchable(),
                ],
                'value' => $data['search'],
            ];
        }

        return $this->paginate($query, $data);
    }

    /*
    ****************************************************************************
    */

    public function getQuery()
    {
        return $this->select(
                    'books.id',
                    'authors.author',
                    'genres.genre',
                    'books.upload_user_id',
                    DB::raw('DATE(books.uploaded_on) AS uploaded_on'),
                    'types.type',
                    'books.title',
                    'books.description',
                    'books.length',
                    'books.picture',
                    'books.source',
                    'books.downloads',
                    'books.upvotes',
                    'books.downvotes',
                    'books.approved',
                    'books.approve_date',
                    'books.remove_date',
                    DB::raw('books.upvotes - books.downvotes AS rating')
                )
                ->join('authors', 'authors.id', 'books.author_id')
                ->join('genres', 'genres.id', 'books.genre_id')
                ->join('types', 'types.id', 'books.type_id');
    }

    /*
    ****************************************************************************
    */

    public function vote($id, $previousVote, $vote)
    {
        $votes = $this->select(
                    'upvotes',
                    'downvotes'
                )
                ->where($this->primeKey, $id)
                ->first()
                ->toArray();

        $field = $vote . 'votes';
        $antiField = $vote == 'up' ? 'downvotes' : 'upvotes';

        if (! isset($previousVote['vote'])) {
            $votes[$field]++;
        } elseif ($previousVote['vote'] != $vote) {
            $votes[$field]++;
            $votes[$antiField]--;
        }

        $votes[$field] = max(0, $votes[$field]);
        $votes[$antiField] = max(0, $votes[$antiField]);

        $this->where($this->primeKey, $id)
            ->update($votes);

        return $votes;
    }

    /*
    ****************************************************************************
    */

    public function getCharts()
    {
        $return = [];

        foreach (['author', 'genre'] as $field) {

            $table = $field . 's';

            foreach (['rating', 'downloads'] as $prefix) {
                $return[$table][$prefix] = $this->getChartTallies($prefix, $field);
            }
        }

        $return['books']['rating'] = $this->getBookChartTalliesByVotes();
        $return['books']['downloads'] = $this->getBookChartTalliesByDownloads();

        return $return;
    }

    /*
    ****************************************************************************
    */

    public function getBookChartTalliesByVotes()
    {
        $results = $this->select(
                    'title',
                    DB::raw('SUM(upvotes) AS upvotes'),
                    DB::raw('SUM(downvotes) AS downvotes'),
                    DB::raw('SUM(upvotes - downvotes) AS booksRating')
                )
                ->where('upvotes', '>', '0')
                ->orWhere('downvotes', '>', '0')
                ->groupBy('title')
                ->orderBy('booksRating', 'desc')
                ->orderBy('title', 'asc')
                ->take(BookModel::CHART_MAX_SEGMENT)
                ->get()
                ->toArray();

        return [
            'labels' => array_column($results, 'title'),
            'data' => [
                [
                    'data' => array_column($results, 'upvotes'),
                    'label' => 'Upvotes',
                ],
                [
                    'data' => array_column($results, 'downvotes'),
                    'label' => 'Downvotes',
                ],
            ],
        ];
    }

    /*
    ****************************************************************************
    */

    public function getBookChartTalliesByDownloads()
    {
        $results = $this->select(
                    'title',
                    DB::raw('SUM(downloads) AS tally')
                )
                ->where('downloads', '>', '0')
                ->groupBy('title')
                ->orderBy('tally', 'desc')
                ->orderBy('title', 'asc')
                ->take(BookModel::CHART_MAX_SEGMENT)
                ->get()
                ->toArray();

        return [
            'labels' => array_column($results, 'title'),
            'data' => [
                [
                    'data' => array_column($results, 'tally'),
                    'label' => 'Downloads',
                ],
            ],
        ];
    }

    /*
    ****************************************************************************
    */

    public function getChartTallies($prefix, $field)
    {
        $count = 0;
        $info = [];

        $table = $field . 's';

        $aggregated = $prefix == 'rating' ? 'COUNT(' . $this->table . '.id)' :
                'SUM(' . $this->table . '.downloads)';

        $results = $this->select(
                    $table . '.' . $field,
                    DB::raw($aggregated . ' AS tally')
                )
                ->join($table, $table . '.id', $this->table . '.' . $field . '_id')
                ->groupBy($table . '.' . $field)
                ->orderBy('tally', 'desc')
                ->get()
                ->toArray();

        foreach ($results as $result) {

            $isOther = $count > BookModel::CHART_MAX_SEGMENT || ! $result['tally'];
            $index = min(BookModel::CHART_MAX_SEGMENT, $count);

            $info['labels'][$index] = $isOther ? 'Other' : $result[$field];
            $info['data'][$index] = $info['data'][$index] ?? 0;

            $info['data'][$index] = $isOther ?
                    $info['data'][$index] + $result['tally'] : $result['tally'];

            $count += $isOther ? 0 : 1;
        }

        return $info;
    }

    /*
    ****************************************************************************
    */

    public function getChartImages($charts)
    {
        foreach ($charts as &$info) {

            $content = substr($info['file'], strpos($info['file'], ',') + 1);

            $chartContent = base64_decode($content);
            $fileName = round(microtime(TRUE) * 10000);

            $info['file'] = \App\Models\BaseModel::getTempFolder() . $fileName . '.png';

            file_put_contents($info['file'], $chartContent);
        }

        return $charts;
    }

    /*
    ****************************************************************************
    */

    public function createReport($info, $file)
    {
        $reportModel = new BookReportModel();

        $query = $this->getQuery();

        $results = $this->applySortAndFilter($query, $info['outputSettings'])
                ->get()
                ->toArray();

        $reportModel->createReport($results, $info, $file);
    }

    /*
    ****************************************************************************
    */

    public function createPDF($title, $author, $file)
    {
        $reportModel = new BookReportModel();

        $reportModel->createPDF($title, $author, $file);
    }

    /*
    ****************************************************************************
    */

    public function getTop($amount)
    {
        return $this->select(
                    'books.title',
                    'authors.author',
                    'books.description',
                    'books.picture',
                    'books.source',
                    DB::raw('
                        CONCAT_WS(
                            " ", books.length, IF(type = "Paper", "pages", "minutes")
                        ) AS length
                    '),
                    DB::raw('books.upvotes - books.downvotes AS rating')
                )
                ->join('authors', 'authors.id', 'books.author_id')
                ->join('types', 'types.id', 'books.type_id')
                ->orderBy('rating', 'desc')
                ->orderBy('books.id', 'desc')
                ->limit($amount)
                ->get()
                ->toArray();
    }

    /*
    ****************************************************************************
    */

    public function downloadIncrement($fileName)
    {
        $this->where('source', $fileName)
            ->increment('downloads');
    }

    /*
    ****************************************************************************
    */

}