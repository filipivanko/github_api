<?php
// src/Controller/ScoreController.php
namespace App\Controller;

use App\Lib\Enum\State;
use App\Lib\GitHub\GitHubApi;
use App\Lib\PersistenceHelper\ScorePersistenceHelper;
use App\Repository\ResultsRecordRepository;
use App\Lib\Serializers\ScoreSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ScoreController extends AbstractController
{
    public function score(ResultsRecordRepository $recordRepository, $searchTerm): Response
    {
        $gitHubApi = new GitHubApi();
        $scorePersistenceHelper = new ScorePersistenceHelper($recordRepository, $gitHubApi);
        $result = $scorePersistenceHelper->getResult($searchTerm);
        $scoreSerializer = new ScoreSerializer();

        return match ($scorePersistenceHelper->getResultStatus()) {
            State::OK => new JsonResponse($scoreSerializer->convertToResponseResource($result), Response::HTTP_OK),
            State::Error => new JsonResponse(
                $scoreSerializer->cantReachRemoteApi(),
                Response::HTTP_SERVICE_UNAVAILABLE
            ),
            State::ZeroResults => new JsonResponse($scoreSerializer->noResults(), Response::HTTP_OK),
            default => new JsonResponse("", Response::HTTP_OK),
        };
    }
}
