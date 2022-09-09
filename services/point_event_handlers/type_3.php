<?php

use entities\Player;

$TagerId = $request->data['PlayerId'];
$TeamColorId = $request->data['ColorId'];

$missionInfo = $mission->getMission();

// Проверяем принадлежит ли текущая точка комнаде игрока
if($pointId == $missionSetup[$globalTeam[$TeamColorId].'Aim']) {
    $player = new Player($db);
    $player->init($gameData['id'], $TeamColorId, $TagerId);

    // Достаточно ли точек захватил игрок, для того чтобы поставить флаг
    if($player->takedPointsCount() >= $missionInfo[$globalTeam[$TeamColorId].'NPoint']) {
        // Добавляем игроку очки за захват точки
        $score = $pointSetup[$globalTeam[$TeamColorId] . 'FirstTake']; // RedFirstTake or BlueFirstTake
        $player->addScore($score);
        $player->nullPoints();
        // Добавляем к количеству установленных флагов для игрока
        $player->flagCapture();

        // Добавляем команде очки за установку флага
        $teamScore = $missionSetup[$globalTeam[$TeamColorId] . 'ScoreFlag']; // RedScoreFlag or BlueScoreFlag
        $game->teamAddScore($globalTeam[$TeamColorId], $teamScore);

        $sound->playFlagComplete($globalTeam[$TeamColorId]);

        // Реинициализируем точки
        $game->pointsFlagCaptureReinit();


        // Точка, которая принимает флаг
        $pointAim = $game->getPoint($missionInfo[$globalTeam[$TeamColorId].'Aim']);
        $game->pointCanInit($pointAim['id'], 1);
        $game->flagAimPointToggle($pointAim, $TeamColorId, 0);

        // Активируем точку - базу флага
        $pointBase = $game->getPoint($missionInfo[$globalTeam[$TeamColorId].'Base']);
        $game->flagBasePointToggle($pointBase, $TeamColorId, 1);
    }
}