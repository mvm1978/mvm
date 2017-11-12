<?php

namespace App\Models\Library;

use App\Models\Library\LibraryModel;
use App\Models\Library\AuthorModel;
use App\Models\Library\GenreModel;

class BookModel extends LibraryModel
{
    protected $table = 'books';

    protected $fillable = [
        'author_id',
        'genre_id',
        'upload_user_id',
        'uploaded_on',
        'type',
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

    public function getTableData($data)
    {
        $query = $this->select(
                    'author_id',
                    'genre_id',
                    'upload_user_id',
                    'uploaded_on',
                    'type',
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
                    'remove_date'
                );

        return $this->paginate($query, $data);
    }

    /*
    ****************************************************************************
    */

    public function getBooks()
    {
        $typesModel = new TypesModel();
        $authorsModel = new AuthorModel();
        $genresModel = new GenreModel();

        $types = $typesModel->getInfo('type');
        $authors = $authorsModel->getInfo('author');
        $authorTypes = $authorsModel->getInfo('type_id');
        $genres = $genresModel->getInfo('genre');

        $results = $this->get()->toArray();

        $return = [];

        foreach ($results as $result) {

            $authorID = $result['author_id'];
            $genreID = $result['genre_id'];
            $typeID = $authorTypes[$authorID];

            if (! isset($return[$authorID]['author_id'])) {
                $return[$authorID]['author_id'] = $authorID;
                $return[$authorID]['author'] = $authors[$authorID];
                $return[$authorID]['type'] = $types[$typeID];
            }

            $return[$authorID]['genres'][] = [
                'id' => $genreID,
                'genre' => $genres[$genreID],
            ];
        }

        return array_values($return);
    }

    /*
    ****************************************************************************
    */

}
