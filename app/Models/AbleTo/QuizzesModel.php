<?php

namespace App\Models\AbleTo;

use App\Models\AbleTo\AbleToModel;
use App\Models\AbleTo\QuestionsModel;
use App\Models\AbleTo\AnswersModel;

class QuizzesModel extends AbleToModel
{
    protected $table = 'quiz_questions';

    protected $fillable = [
        'question_id',
        'answer_id',
    ];

    /*
    ****************************************************************************
    */

    public function getQuizzes()
    {
        $typesModel = new TypesModel();
        $questionsModel = new QuestionsModel();
        $answersModel = new AnswersModel();

        $types = $typesModel->getInfo('type');
        $questions = $questionsModel->getInfo('question');
        $questionTypes = $questionsModel->getInfo('type_id');
        $answers = $answersModel->getInfo('answer');

        $results = $this->get()->toArray();

        $return = [];

        foreach ($results as $result) {

            $questionID = $result['question_id'];
            $answerID = $result['answer_id'];
            $typeID = $questionTypes[$questionID];

            if (! isset($return[$questionID]['question_id'])) {
                $return[$questionID]['question_id'] = $questionID;
                $return[$questionID]['question'] = $questions[$questionID];
                $return[$questionID]['type'] = $types[$typeID];
            }

            $return[$questionID]['answers'][] = [
                'id' => $answerID,
                'answer' => $answers[$answerID],
            ];
        }

        return array_values($return);
    }

    /*
    ****************************************************************************
    */

}
