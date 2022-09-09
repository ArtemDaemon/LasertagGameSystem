<?php
namespace entities;

class Round {
    public $gameId;
    public $missionId;
    public $scoreRed;
    public $scoreBlue;
    public $whoWin;
    public $playerWinner;
    public $startDate;
    public $endDate;

    public function __construct(
        $gameId,
        $missionId,
        $scoreRed,
        $scoreBlue,
        $whoWin,
        $playerWinner,
        $startDate,
        $endDate
    )
    {
        $this->gameId = $gameId;
        $this->missionId = $missionId;
        $this->scoreRed = $scoreRed;
        $this->scoreBlue = $scoreBlue;
        $this->whoWin = $whoWin;
        $this->playerWinner = $playerWinner;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function getGameTime()
    {
        if($this->whoWin==null && $this->startDate==null) {
            return 'Рануд не начат';
        }

        if($this->whoWin==null) {
            return 'Раунд прерван';
        }

        $start = strtotime($this->startDate);
        $end = strtotime($this->endDate);

        $gameTime = date("i:s", $end-$start);

        return $gameTime;
    }

    public function getPlayerWinner()
    {
        return $this->playerWinner;
    }

    public function getBlueScore()
    {
        return $this->scoreBlue;
    }
    public function getRedScore()
    {
        return $this->scoreRed;
    }
    public function getStartDate()
    {
        $this->startDate;
    }
}