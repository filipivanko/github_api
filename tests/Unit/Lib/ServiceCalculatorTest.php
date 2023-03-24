<?php
namespace Unit\Lib;

use App\Lib\Enum\State;
use App\Lib\GitHub\GitHubApi;
use App\Lib\ScoreCalculator;
use PHPUnit\Framework\TestCase;

class ServiceCalculatorTest extends TestCase
{
    public function test_it_calculates_correct_score(){

        $apiMock = $this->createMock(GitHubApi::class);
        $apiMock->method('getSucksCount')->willReturn(1);
        $apiMock->method('getRocksCount')->willReturn(1);
        $apiMock->method('getApiStatus')->willReturn(State::OK);

        $search_term = "search_term";

        $apiMock->expects($this->once())->method('getSucksCount')->with($search_term);
        $apiMock->expects($this->once())->method('getRocksCount')->with($search_term);

        $scoreCalculator = new ScoreCalculator($search_term, $apiMock);

        $result = $scoreCalculator->getScore();

        $this->assertSame(5.0, $result);
    }

}