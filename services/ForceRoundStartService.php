<?php

use entities\Mission;

$game->getGame();
$mission = new Mission($db);
$mission->init($request->data['missionId']);
$round = $game->getRound();
$game->setReadyAllBases();
$game->basesReinit($request->data['missionId']);

if($round) {
    $game->rememberSettings($request->data['anyId'], $request->data['id1'], $request->data['id2'], $request->data['id3']);
    $game->roundStart();

    $sound->setup($mission->getSoundConfig());
    $sound->playRoundStartSound();
    $sound->playStartBg();

    $game->setupPoints(
        $mission->id,
        $request->data['anyId'] ?: 0,
        $request->data['id1'] ?: 0,
        $request->data['id2'] ?: 0,
        $request->data['id3'] ?: 0
    );
    header("Location: /?command=roundControlView&view=round-control&missionId=".$mission->id);
    exit;
} else {
    echo 'Раунда не существует.';
}