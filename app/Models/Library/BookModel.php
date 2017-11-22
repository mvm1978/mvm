<?php

namespace App\Models\Library;

use Illuminate\Support\Facades\DB;

use App\Models\Library\LibraryModel;

class BookModel extends LibraryModel
{
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
        $query = $this->select(
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
                    'books.remove_date'
                )
                ->join('authors', 'authors.id', 'books.author_id')
                ->join('genres', 'genres.id', 'books.genre_id')
                ->join('types', 'types.id', 'books.type_id');

        return $this->paginate($query, $data);
    }

    /*
    ****************************************************************************
    */

}
