<?php

namespace App\Models\AbleTo;

use App\Models\AbleTo\AbleToModel;

class QuestionsModel extends AbleToModel
{
    protected $table = 'questions';

    protected $fillable = [
        'type_id',
        'question',
    ];

    /*
    ****************************************************************************
    */
}
