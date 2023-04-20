<?php
namespace Unit\Lib\PersistenceHelper;

use App\Entity\ResultsRecord;
use App\Lib\Enum\State;
use App\Lib\GitHub\GitHubApi;
use App\Lib\PersistenceHelper\ScorePersistenceHelper;
use App\Repository\ResultsRecordRepository;
use PHPUnit\Framework\TestCase;

class ScorePersistenceHelperTest extends TestCase
{
    public function test_it_returns_record_form_database_if_found()
    {

        $apiMock = $this->createMock(GitHubApi::class);
        $apiMock->method('getSucksCount')->willReturn(1);
        $apiMock->method('getRocksCount')->willReturn(1);
        $apiMock->method('getApiStatus')->willReturn(State::OK);

        $search_term = 'search_term';
        $resultMock = $this->createMock(ResultsRecord::class);

        $resultRepositoryMock = $this->createMock(ResultsRecordRepository::class);
        $resultRepositoryMock->method('findOneBy')->with(['search_term' => $search_term])->willReturn($resultMock);

        $scorePersistHelper = new ScorePersistenceHelper($resultRepositoryMock, $apiMock);

        $result = $scorePersistHelper->getResult($search_term);

        $this->assertSame($resultMock, $result);
    }

    public function test_it_returns_new_record_if_none_found_in_database()
    {

        $apiMock = $this->createMock(GitHubApi::class);
        $apiMock->method('getSucksCount')->willReturn(1);
        $apiMock->method('getRocksCount')->willReturn(1);
        $apiMock->method('getApiStatus')->willReturn(State::OK);

        $search_term = 'search_term';

        $resultRepositoryMock = $this->createMock(ResultsRecordRepository::class);
        $resultRepositoryMock->method('findOneBy')->with(['search_term' => $search_term])->willReturn(null);

        $scorePersistHelper = new ScorePersistenceHelper($resultRepositoryMock, $apiMock);

        $result = $scorePersistHelper->getResult($search_term);

        $this->assertSame($search_term, $result->getSearchTerm());
        $this->assertSame(5.0, $result->getScore());
    }
}
