<?php
// Если осталось меньше 10% времени или 10% очков
if($game->timeIsLeft($missionTime, 10) || $game->scoreIsLeft($missionScore, 10)) {
    if(!$game->isSoundRound10Played()) {
        $sound->playRound10();

        $game->soundRound10Played();
    }
}



// Проверяем, все ли точки с текущим приоритетом захвачены
if($game->prioritetPointsTakedAll()) {
    // Инициализируем точки с новым приоритетом
    // Если все точки захвачены, проверяем паузу и отправляем запрос на сброс
    $newPrioritet = $game->getNewPointsPrioritet();


    // Реинициализация точек, если все захвачены
    if($newPrioritet > $game->getMaxPrioritet()) {
        if($game->resetPauseIsset()) {
            // Сброс захвата точек
            $game->pointsTakeReset();

            // Установка нового приоритета для точек (Rounds - pointsPrioritet)
            $game->setPrioritet($game->getMinPrioritet());
        }
    } else {
        echo $newPrioritet;
        $game->pointsInitByPrioritet($newPrioritet);
        $game->setPrioritet($newPrioritet);
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