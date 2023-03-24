<?php
namespace App\Lib\PersistenceHelper;

use App\Entity\ResultsRecord;
use App\Lib\Interfaces\CountApi;
use App\Lib\ScoreCalculator;
use App\Repository\ResultsRecordRepository;
use App\Lib\Enum\State;

class ScorePersistenceHelper
{
    private ResultsRecordRepository $recordRepository;
    private State $resultState;
    private ScoreCalculator $calculator;

    public function __construct (ResultsRecordRepository $recordRepository)
    {
        $this->recordRepository = $recordRepository;
    }

    public function getResult ($search_term, CountApi $api): ?ResultsRecord
    {
        $stored_result = $this->recordRepository->findOneBy(['search_term' => $search_term]);

        if (!empty($stored_result)) {
            $this->resultState = State::OK;

            return $stored_result;
        }

        $this->calculator = new ScoreCalculator($search_term, $api);
        $this->resultState = $this->calculator->getResultStatus();

        if($this->getResultStatus() == State::OK){
            $score = $this->calculator->getScore();

            return $this->store($score, $search_term);

        } else {
            $null_record = new ResultsRecord();
            $null_record->setScore(-1);
            $null_record->setSearchTerm("");

            return $null_record;
        }
    }

    public function getResultStatus(): State
    {
        return $this->resultState;
    }

    private function store ($score, $search_term): ResultsRecord
    {
        $new_results_record = new ResultsRecord();

        $new_results_record->setScore($score);
        $new_results_record->setSearchTerm($search_term);
        $this->recordRepository->save($new_results_record, true);

        return $new_results_record;
    }
}