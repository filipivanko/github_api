<?php
// src/Controller/ScoreController.php
namespace App\Controller;

use App\Lib\Enum\State;
use App\Lib\GitHub\GitHubApi;
use App\Lib\PersistenceHelper\ScorePersistenceHelper;
use App\Repository\ResultsRecordRepository;
use App\Lib\Serializer\ScoreSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ScoreController extends AbstractController
{
    public function score(ResultsRecordRepository $recordRepository, $search_term): Response
    {
        $gitHubApi = new GitHubApi();
        $scorePersistenceHelper = new ScorePersistenceHelper($recordRepository);
        $result = $scorePersistenceHelper->getResult($search_term, $gitHubApi);
        $scoreSerializer = new ScoreSerializer();

        switch ($scorePersistenceHelper->getResultStatus()){
            case State::OK:
                return new JsonResponse($scoreSerializer->convertToResponseResource($result),Response::HTTP_OK);
            case State::Error:
                return new JsonResponse($scoreSerializer->cantReachRemoteApi(),Response::HTTP_SERVICE_UNAVAILABLE);
            case State::ZeroResults:
                return new JsonResponse($scoreSerializer->noResults(),Response::HTTP_OK);
        }
    }
}