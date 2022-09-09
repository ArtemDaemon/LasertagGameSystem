<?php
use entities\Player;

// Если осталось меньше 10% времени или 10% очков
if($game->timeIsLeft($missionTime, 10) || $game->scoreIsLeft($missionScore, 10)) {
    if(!$game->isSoundRound10Played()) {
        $sound->playRound10();

        $game->soundRound10Played();
    }
}
$missionInfo = $mission->getMission();


$redFlagPlayer = $game->getPlayerWithFlagRed($missionInfo['RedTimeTake']);
$blueFlagPlayer = $game->getPlayerWithFlagBlue($missionInfo['BlueTimeTake']);


// Если прошло достаточное количество времени, сбрасываем флаг с игрока
if($redFlagPlayer && $redFlagPlayer['flagLostTime']==0) {
    $player = new Player($db);
    $player->init($redFlagPlayer['Game'], $redFlagPlayer['Color'], $redFlagPlayer['TagerId']);

    $player->lostFlag();
    $sound->playFlagMiss($globalTeam[$redFlagPlayer['Color']]);

    // Точка, которая принимает флаг
    $pointAim = $game->getPoint($missionInfo[$globalTeam[$redFlagPlayer['Color']].'Aim']);
    $game->flagAimPointToggle($pointAim, $redFlagPlayer['Color'], 0);


    // Активируем точку - базу флага
    $pointBase = $game->getPoint($missionInfo[$globalTeam[$redFlagPlayer['Color']].'Base']);
    $game->flagBasePointToggle($pointBase, $redFlagPlayer['Color'], 1);
}
if($blueFlagPlayer && $blueFlagPlayer['flagLostTime']==0) {
    $player = new \entities\Player($db);
    $player->init($blueFlagPlayer['Game'], $blueFlagPlayer['Color'], $blueFlagPlayer['TagerId']);

    $player->lostFlag();
    $sound->playFlagMiss($globalTeam[$blueFlagPlayer['Color']]);

    // Точка, которая принимает флаг
    $pointAim = $game->getPoint($missionInfo[$globalTeam[$blueFlagPlayer['Color']].'Aim']);
    $game->flagAimPointToggle($pointAim, $blueFlagPlayer['Color'], 0);


    // Активируем точку - базу флага
    $pointBase = $game->getPoint($missionInfo[$globalTeam[$blueFlagPlayer['Color']].'Base']);
    $game->flagBasePointToggle($pointBase, $blueFlagPlayer['Color'], 1);
}


// Если игрок захватил достаточное количество точек инициализируем точку - чтобы поставить флаг
if($redFlagPlayer['pointsTake']>=$missionInfo['RedNPoint']) {
    $pointAim = $game->getPoint($missionInfo[$globalTeam[$redFlagPlayer['Color']].'Aim']);
    $game->flagAimPointToggle($pointAim, $redFlagPlayer['Color'], 1, $redFlagPlayer['TagerId']);

    $sound->playFlagAimReady($globalTeam[$redFlagPlayer['Color']]);
}
if($blueFlagPlayer['pointsTake']>=$missionInfo['BlueNPoint']) {
    $pointAim = $game->getPoint($missionInfo[$globalTeam[$blueFlagPlayer['Color']].'Aim']);
    $game->flagAimPointToggle($pointAim, $blueFlagPlayer['Color'], 1, $blueFlagPlayer['TagerId']);

    $sound->playFlagAimReady($globalTeam[$blueFlagPlayer['Color']]);
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