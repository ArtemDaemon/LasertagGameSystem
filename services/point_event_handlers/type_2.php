<?php

use entities\Player;

$TagerId = $request->data['PlayerId'];
$TeamColorId = $request->data['ColorId'];


// Принадлежит ли точка команде игрока.
if($pointId == $missionSetup[$globalTeam[$TeamColorId].'Base']) {
    $player = new Player($db);
    $player->init($gameData['id'], $TeamColorId, $TagerId);


    // Если игроки из команды не брали флаг
    //if(!$player->hasFlag() && !$game->teamHasFlag($TeamColorId)) {
    if(!$player->hasFlag()) {
        // Reset Old Flag
        if($TeamColorId == RED_TEAM) {
            $prevFlagPlayer = $game->getPlayerWithFlagRed($missionSetup['RedTimeTake']);
        } else {
            $prevFlagPlayer = $game->getPlayerWithFlagBlue($missionSetup['BlueTimeTake']);
        }
        if($prevFlagPlayer) {
            $prevPlayer = new Player($db);
            $prevPlayer->init($prevFlagPlayer['Game'], $prevFlagPlayer['Color'], $prevFlagPlayer['TagerId']);
            $prevPlayer->lostFlag();
        }


        // Reset Current Player Points Take && Take Flag
        $player->nullPoints();
        $player->takeFlag();
        $sound->playFlagTake($globalTeam[$TeamColorId]);


        // Деактивируем точку - базу флага
        //$pointBase = $game->getPoint($pointId);
        //$game->flagBasePointToggle($pointBase, $TeamColorId, 0);

        // Реинициализируем точки
        $game->pointsFlagCaptureReinit();
    }
}