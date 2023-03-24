<?php

namespace App\Lib\Serializer;

use App\Entity\ResultsRecord;

class ScoreSerializer
{
    public function convertToResponseResource(ResultsRecord $result): array
    {
        $term = $result->getSearchTerm();
        $score = $result->getScore();
        $resource = [
          "data"=>[
              "term"=>$term,
            "score"=>$score
          ]
        ];

        return $resource;
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