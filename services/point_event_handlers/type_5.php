<?php
use entities\Point;
use entities\Player;

$Status = $request->data['Status'];
$TakeId = $request->data['TakeId'];
$TeamColorId = $request->data['ColorId'];


// Player First
$player = new Player($db);
$player->init($gameData['id'], $TeamColorId, $TakeId);

// Добавляем игроку очки за захват точки
$score = $pointSetup['ScorePlayer'];

$player->addScore($score);

// Добавляем к количеству захватов точек для игрока
$player->takePoint();
$point->setTakeTager($TakeId);

// Если это финальная точка, завершаем игру
if($pointId == $mission->getFinalPoint()) {
    $game->setWinner('player', $player->id);

    $sound->playRoundStopSound();

    // Заканчиваем раунд.
    $sound->stopSound();

    $game->unReadyBases();
    $game->endRound();
    $round = false;
}