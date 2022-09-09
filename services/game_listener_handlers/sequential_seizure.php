<?php
// Если осталось меньше 10% времени или 10% очков
if($game->timeIsLeft($missionTime, 10) || $game->scoreIsLeft($missionScore, 10)) {
    if(!$game->isSoundRound10Played()) {
        $sound->playRound10();

        $game->soundRound10Played();
    }
}

if($whoLead = $game->whoLead()) {
    $leadPeriod = $sound->getLeadPeriod($mission->getSoundPresetId());
    $timePass = time() - strtotime($round['StartDate']);

    if ($leadPeriod!=0 && $timePass >= $leadPeriod) {
        $sound->playLead($whoLead, $mission->getSoundPresetId());
    }
}


// Встречный захват
if($mission->isTowards()) {
    $currMaxOld = $game->getMaxCurrentPrioritet();
    $currMinOld = $game->getMinCurrentPrioritet();

    $stats = $game->getStatPointByPrioritet();

    $currMaxNew = ($stats['blue']) ? ($stats['blue'] - 1) : false;
    $currMinNew = $stats['red'] + 1;

    // Если все точки захвачены, переинициализируем игру
    if($TeamTakeColorId = $game->allPointsTakeByTeam()) {
        echo 'waittime';
        if ($game->resetPauseIsset()) {
            echo 'gamereset';
            // Сброс захвата точек
            $game->pointsTakeReset();

            // Добавляем очки
            $game->teamAddScore($globalTeam[$TeamTakeColorId], $mission->getScore());

            // Инициализируем все заново
            $game->setupPoints($mission->id, $round['anyId'], $round['id1'], $round['id2'], $round['id3']);
        }

        // Прерываем, т.к. время не прошло
        exit;
    }


    // Если все точки у обеих команд захвачены, и следующая должна быть доступной для обеих команд
    if($currMaxNew==true && $game->prioritetPointsTakedAll($currMaxOld) && $game->prioritetPointsTakedAll($currMinOld) && $currMaxNew==$currMinNew) {
        if ($game->prioritetPointsTakedAll($currMinOld)) {
            echo 'first';

            $game->pointsInitByPrioritet($currMinNew, 3);
        }
    } elseif($currMaxNew==true && $game->prioritetPointsTakedAll($currMaxOld) && $game->prioritetPointsTakedAll($currMinOld) && $currMaxNew!=$currMinNew) {
        echo 'second';



        // Разрешаем инициализацию текущих точек
        $minPoint = $game->getPointByPrioritet($currMinOld);
        $minPoint = new \entities\Point($minPoint['Address'], $minPoint['Name'], $db);
        $minPoint->setCanInit(1);
        $maxPoint = $game->getPointByPrioritet($currMaxOld);
        $maxPoint = new \entities\Point($maxPoint['Address'], $maxPoint['Name'], $db);
        $maxPoint->setCanInit(1);


        // Если все точки захвачены, и последняя точка принадлежит другой стороне

        // Получаем максимальный actionTime точки
        // относительно этой точки делаем + или минус приоритету. И запускаем точку по высчитанному.
        $lastActionPoint = $game->getLastActionPoint();

        // Decreeemen or Increement point next
        $player = $game->getPlayerByTager($lastActionPoint['takeTagerId']);
        if($player['Color']==RED_TEAM) {
            $newPrioritet = $lastActionPoint['currPrioritet']+1;
        } else {
            $newPrioritet = $lastActionPoint['currPrioritet']-1;
        }


        $newP = $game->getPointByPrioritet($newPrioritet);
        $newP = new \entities\Point($newP['Address'], $newP['Name'], $db);
        $newP->setCanInit(1);


        $game->pointsInitByPrioritet($newPrioritet, 3);
    } else {
        echo 'third';
        // Проверяем захвачены ли точки с каждым из приоритетов и инициализируем с новым приоритетом
        // Инициализируем точки с цветом для каждой команды
        if ($game->prioritetPointsTakedAll($currMaxOld)) {
            echo 'maxinit';
            $point = $game->getPointByPrioritet($currMaxOld);
            $player = $game->getPlayerByTager($point['takeTagerId']);


            $game->pointsInitByPrioritet($currMaxNew, $player['Color']);
        }
        if ($game->prioritetPointsTakedAll($currMinOld)) {
            echo 'mininit';
            $point = $game->getPointByPrioritet($currMinOld);
            $player = $game->getPlayerByTager($point['takeTagerId']);
            $game->pointsInitByPrioritet($currMinNew, $player['Color']);
        }
    }


} else {
    // Проверяем, все ли точки с текущим приоритетом захвачены
    if ($game->prioritetPointsTakedAll()) {
        // Инициализируем точки с новым приоритетом
        // Если все точки захвачены, проверяем паузу и отправляем запрос на сброс
        $newPrioritet = $game->getNewPointsPrioritet();


        // Реинициализация точек, если все захвачены
        if ($newPrioritet > $game->getMaxPrioritet()) {
            if ($game->resetPauseIsset()) {
                // Сброс захвата точек
                $game->pointsTakeReset();

                // Установка нового приоритета для точек (Rounds - pointsPrioritet)
                $game->setPrioritet($game->getMinPrioritet());
            }
        } else {
            $game->pointsInitByPrioritet($newPrioritet);
            $game->setPrioritet($newPrioritet);
        }
    }
}


// Раунд Завершен
if ($game->isRoundTimeout($missionTime) || $game->isRoundScoreOut($missionScore)) {
    // Определяем победителя
    $scoreRed = $round['ScoreRed'];
    $scoreBlue = $round['ScoreBlue'];

    if ($scoreRed > $scoreBlue) {
        $winnerTeam = RED_TEAM;
    } elseif ($scoreBlue > $scoreRed) {
        $winnerTeam = BLUE_TEAM;
    } else {
        $winnerTeam = 0;
    }

    $game->setWinner('team', $winnerTeam);

    $sound->playRoundStopSound();


    if($winnerTeam==0) {
        $sound->playRoundDrawSound();
    } else {
        // Название Команды победителей
        $winnerTeamName = $globalTeam[$winnerTeam];
        $sound->playRoundTeamWinSound($winnerTeamName);
    }

    // Заканчиваем раунд.
    $sound->stopSound();

    $sound->resetSoundTimes();
    $game->unReadyBases();
    $game->endRound();
    $round = false;
}