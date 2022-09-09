<?php
use entities\Point;

// Если осталось меньше 10% времени или 10% очков
if($game->timeIsLeft($missionTime, 10) || $game->scoreIsLeft($missionScore, 10)) {
    if(!$game->isSoundRound10Played()) {
        $sound->playRound10();

        $game->soundRound10Played();
    }
}

// Удаляем бомбу с игроков, с истекшим интервалом
$playersWithBomb = $game->getPlayersWithBomb();
$bombRemoved = false;
if($playersWithBomb) {
    foreach($playersWithBomb as $item) {
        $timePass = time() - strtotime($item['add_date']);

        if($timePass>=$mission->getBombTimeForPlant()) {
            $player = $game->getPlayerByTager($item['tagerId']);

            $game->removeBomb($item['id']);
            $sound->playLostBomb($globalTeam[$player['Color']]);
            $bombRemoved = true;
        }
    }
}


// Реинициализируем только если бомба снята
if($bombRemoved) {
    $game->reinitPointsBomb($mission->id);
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