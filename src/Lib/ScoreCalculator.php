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

    public function __construct($search_term, CountApi $api)
    {
        $this->api = $api;
        $this->rocksCount = $this->api->getRocksCount($search_term);
        $this->sucksCount = $this->api->getSucksCount($search_term);
        $this->resultStatus = $this->api->getApiStatus();
        $this->checkZeroResults();
    }

    public function getScore(){
        switch ($this->api->getApiStatus()){
            case State::OK:
                $precise_score = ($this->rocksCount/$this->getTotalCount())*10;

                return round($precise_score, 2);

            case State::Error:

                return -1;

            case State::ZeroResults:

                return 0;
        }
    }

    public function getResultStatus(){
        return $this->resultStatus;
    }

    private function getTotalCount(){
        return $this->rocksCount + $this->sucksCount;
    }

    private function checkZeroResults(): void
    {
        if($this->api->getApiStatus() == State::OK && $this->getTotalCount() == 0 ){
            $this->resultStatus = State::ZeroResults;
        }
    }
}