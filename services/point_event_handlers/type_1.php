<?php
use entities\Mission;
use entities\Player;
use entities\Point;

$Status = $request->data['Status'];
$FirstTake = $request->data['FirstTake'];
$LastTake = $request->data['LastTake'];
$TeamColorId = $Status;

// Player First
$playerFirstTake = new Player($db);
$playerFirstTake->init($gameData['id'], $TeamColorId, $FirstTake);

// Добавляем игроку очки за захват точки
$score = $pointSetup[$globalTeam[$TeamColorId].'FirstTake']; // RedFirstTake or BlueFirstTake
$playerFirstTake->addScore($score);

// Добавляем к количеству захватов точек для игрока
$playerFirstTake->takePoint();


// Player Last
$playerLastTake = new Player($db);
$playerLastTake->init($gameData['id'], $TeamColorId, $LastTake);

// Добавляем игроку очки за захват точки
$score = $pointSetup[$globalTeam[$TeamColorId].'LastTake']; // RedFirstTake or BlueFirstTake
$playerLastTake->addScore($score);

if($playerFirstTake->id != $playerLastTake->id) {
    // Добавляем к количеству захватов точек для игрока
    $playerLastTake->takePoint();
}


// Добавляем игроку количество захваченных точек за раунд
$playerLastTake->takePointForCurrRound();



// Добавляем команде очки за захват точки
$game->teamAddScore($globalTeam[$TeamColorId], $pointSetup['ScoreComplite']);


// Захватваем точку
$teamPointTake = 'N'.$globalTeam[$TeamColorId].'Take'; // NRedTake or NBlueTake
$point->takePoint($pointId, $playerFirstTake->getTager(), $teamPointTake, $Status);

// Добавляем к количеству захваченных точек первым
$game->plusFirstTakePoint($FirstTake);

// Добавляем к количеству захваченных точек последним
$game->plusTakePoint($LastTake);

if($mission->pointsType() == Mission::MISSION_CAPTURE_THE_FLAG) {
    $sound->playTakePoint($globalTeam[$TeamColorId]);

    // Delay
    sleep(1);
    // Reinit Point Without Current Player
    $game->resetWithoutTager($pointId, $LastTake);
} elseif(
    $mission->pointsType() == Mission::MISSION_SEQUENTIAL_SEIZURE ||
    $mission->pointsType() == Mission::MISSION_SHOOTOUT_BY_TURNS
) {
    // Если все точки у обеих команд захвачены, и следующая должна быть доступной для обеих команд, делаем ее инициализируемой
    $currMaxOld = $game->getMaxCurrentPrioritet();
    $currMinOld = $game->getMinCurrentPrioritet();

    $stats = $game->getStatPointByPrioritet();

    $currMaxNew = ($stats['blue']) ? ($stats['blue'] - 1) : false;
    $currMinNew = $stats['red'] + 1;

    if($currMaxNew==true && $game->prioritetPointsTakedAll($currMaxOld) && $game->prioritetPointsTakedAll($currMinOld) && $currMaxNew==$currMinNew) {
        $commonPoint = $game->getPointByPrioritet($currMinNew);
        $commonPoint = new Point($commonPoint['Address'], $commonPoint['Name'], $db);
        $commonPoint->setCanInit(1);
    }

} else {
    $sound->playSoundByPoint('SoundPoint1', $mission->getSoundPresetId(), $globalTeam[$TeamColorId] . 'Sound', $pointId);
}