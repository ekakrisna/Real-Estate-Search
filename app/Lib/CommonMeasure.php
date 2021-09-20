<?php
namespace App\Lib;

class CommonMeasure
{ 
    private $resultArray;
    private $resultTime;
    private $startTime;

    public function startMeasure(){
        $this->startTime = microtime(true);
    }

    public function endMeasure(){
        $this->resultTime = microtime(true) - $this->startTime;
        $this->resultArray[] = $this->resultTime;
    }

    public function getLatestResult(){
        return $this->resultTime;
    }

    public function getTotalResult(){
        return array_sum($this->resultArray);
    }
    
    public function getTotalCount(){
        return count($this->resultArray);
    }
}
