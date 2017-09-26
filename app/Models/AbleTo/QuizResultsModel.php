<?php

namespace App\Models\AbleTo;

use App\Models\AbleTo\AnswersModel;
use App\Models\AbleTo\AbleToModel;
use Illuminate\Support\Facades\DB;

class QuizResultsModel extends AbleToModel
{
    protected $table = 'quiz_results';

    protected $fillable = [
        'user_id',
        'quiz_date',
        'question_id',
        'answer_id',
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

        foreach ($data as $questionID => $answers) {
            foreach ($answers as $answerID) {
                $this->insert([
                    'user_id' => $userID,
					'quiz_date' => date('Y-m-d'),
                    'question_id' => $questionID,
                    'answer_id' => $answerID,
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

        $answerModel = new AnswersModel();

        $results = $this
                ->select('answer', DB::raw('COUNT(answer_id) as total'))
                ->join('answers', 'answers.id', '=', 'quiz_results.answer_id')
                ->where('user_id', '=', $userID)
                ->groupBy('answer_id', 'answer')
                ->orderBy('answer_id')
                ->get();

        $dateResults = $this
                ->select(DB::raw('CONCAT_WS(" - ", MIN(quiz_date), MAX(quiz_date)) as date_range'))
                ->where('user_id', '=', $userID)
                ->get();

        $answerTotals = $totals = $return = [];

        foreach ($results->toArray() as $result) {

            $answer = $result['answer'];

            $totals[$answer] = $result['total'];
        }

        $typeInfo = $answerModel->getTypeInfo();

        foreach ($typeInfo as $type => $values) {
            foreach ($values as $info) {

                $answer = $info['answer'];

                $answerTotals[$type][$answer] = isset($totals[$answer]) ?
                        $totals[$answer] : 0;
            }
        }

        foreach ($answerTotals as $type => $values) {
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
