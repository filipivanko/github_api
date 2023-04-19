<?php

namespace App\Lib;

use App\Lib\Interfaces\CountApi;
use App\Lib\Enum\State;

class ScoreCalculator
{
    private $rocksCount;
    private $sucksCount;
    private $api;
    private $resultStatus;

    public function __construct($searchTerm, CountApi $api)
    {
        $this->api = $api;
        $this->rocksCount = $this->api->getRocksCount($searchTerm);
        $this->sucksCount = $this->api->getSucksCount($searchTerm);
        $this->resultStatus = $this->api->getApiStatus();
        $this->checkZeroResults();
    }

    public function getScore(): float
    {
        switch ($this->api->getApiStatus()) {
            case State::OK:
                $preciseScore = ($this->rocksCount/$this->getTotalCount())*10;
                $score = round($preciseScore, 2);
                break;

            case State::Error:
                $score = -1;
                break;

            case State::ZeroResults:
                $score = 0;
                break;

            default:
                $score = 3;
        }
        return $score;
    }

    public function getResultStatus() :State
    {
        return $this->resultStatus;
    }

    private function getTotalCount() :int
    {
        return $this->rocksCount + $this->sucksCount;
    }

    private function checkZeroResults(): void
    {
        if ($this->api->getApiStatus() == State::OK && $this->getTotalCount() == 0) {
            $this->resultStatus = State::ZeroResults;
        }
    }
}
