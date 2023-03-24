<?php
namespace App\Lib\Interfaces;
interface CountApi{
    public function getRocksCount($searchTerm);
    public function getSucksCount($searchTerm);
    public function getApiStatus();
}