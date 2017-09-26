<?php

namespace App\Models\AbleTo;

use App\Models\AbleTo\AbleToModel;

class AnswersModel extends AbleToModel
{
    protected $table = 'answers';

    protected $fillable = [
        'answer',
    ];

    /*
    ****************************************************************************
    */

    public function getTypeInfo()
    {
        $results = $this
                ->select('types.type', 'answers.id', 'answers.answer')
                ->join('quiz_questions', 'quiz_questions.answer_id', '=', 'answers.id')
                ->join('questions', 'questions.id', '=', 'quiz_questions.question_id')
                ->join('types', 'types.id', '=', 'questions.type_id')
                ->groupBy('answers.id', 'types.type', 'answers.answer')
                ->get();

        $return = [];

        foreach ($results->toArray() as $result) {

            $type = array_shift($result);

            $return[$type][] = $result;
        }

        return $return;
    }

    /*
    ****************************************************************************
    */
}
