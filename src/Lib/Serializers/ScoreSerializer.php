<?php

namespace App\Lib\Serializers;

use App\Entity\ResultsRecord;

class ScoreSerializer
{
    public function convertToResponseResource(ResultsRecord $result): array
    {
        $term = $result->getSearchTerm();
        $score = $result->getScore();
        return [
          "data"=>[
              "term"=>$term,
            "score"=>$score
          ]
        ];
    }

    public function cantReachRemoteApi()
    {
        return ['message' => 'data source not available'];
    }

    public function noResults()
    {
        return ['message' => 'your query returned no results'];
    }
}
