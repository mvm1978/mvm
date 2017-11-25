<?php

namespace App\Models\Library;

use App\Models\Library\LibraryModel;

class VoteModel extends LibraryModel
{
    protected $table = 'votes';

    protected $fillable = [
        'user_id',
        'book_id',
    ];

    /*
    ****************************************************************************
    */

    public function vote($userID, $bookID, $vote)
    {
        $previousVote = $this->firstOrCreate([
                'user_id' => $userID,
                'book_id' => $bookID,
            ])->toArray();

        $this->where([
                'user_id' => $userID,
                'book_id' => $bookID,
            ])
            ->update([
                'vote' => $vote,
            ]);


        return $previousVote;
    }

    /*
    ****************************************************************************
    */

}
