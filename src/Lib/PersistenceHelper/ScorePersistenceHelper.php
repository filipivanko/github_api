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
    private CountApi $api;

    public function __construct(ResultsRecordRepository $recordRepository, CountApi $api)
    {
        $this->recordRepository = $recordRepository;
        $this->api = $api;
    }

    public function getResult($searchTerm): ?ResultsRecord
    {
        $storedResult = $this->recordRepository->findOneBy(['search_term' => $searchTerm]);

        if (!empty($storedResult)) {
            $this->resultState = State::OK;

            return $storedResult;
        }

        $this->calculator = new ScoreCalculator($searchTerm, $this->api);
        $this->resultState = $this->calculator->getResultStatus();

        if ($this->getResultStatus() == State::OK) {
            $score = $this->calculator->getScore();

            return $this->store($score, $searchTerm);
        } else {
            $nullRecord = new ResultsRecord();
            $nullRecord->setScore(-1);
            $nullRecord->setSearchTerm("");

            return $nullRecord;
        }
    }

    public function getResultStatus(): State
    {
        return $this->resultState;
    }

    private function store($score, $searchTerm): ResultsRecord
    {
        $newResultsRecord = new ResultsRecord();

        $newResultsRecord->setScore($score);
        $newResultsRecord->setSearchTerm($searchTerm);
        $this->recordRepository->save($newResultsRecord, true);

        return $newResultsRecord;
    }
}
