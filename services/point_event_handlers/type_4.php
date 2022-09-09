<?php
use entities\Point;
use entities\Player;

$TakeId = $request->data['TakeId'];
$TeamColorId = $request->data['ColorId'];


// Player First
$player = new Player($db);
$player->init($gameData['id'], $TeamColorId, $TakeId);

// Добавляем игроку очки за захват точки
$score = $pointSetup[$globalTeam[$TeamColorId].'FirstTake']; // RedFirstTake or BlueFirstTake

$player->addScore($score);

// Добавляем к количеству захватов точек для игрока
$player->takePoint();


// Добавляем команде очки за захват точки
$game->teamAddScore($globalTeam[$TeamColorId], $pointSetup['ScoreComplite']);

// Захватваем точку
$teamPointTake = 'N'.$globalTeam[$TeamColorId].'Take'; // NRedTake or NBlueTake
$point->takePoint($pointId, $player->getTager(), $teamPointTake, $Status);


$sound->playSoundByPoint('SoundTakeHome', $mission->getSoundPresetId(), 'File', $pointId);