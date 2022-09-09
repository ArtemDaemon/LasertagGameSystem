<?php

use entities\Game;
use entities\Mission;

$mission = new Mission($db);
$mission->init($request->data['missionId']);
$sound->setup($mission->getSoundConfig());

// Закрываем старое
$game->roundDelete();
$game->offPoints();
$game->offBases();

// Инициализация баз
$game->setupBasesForReady($request->data['missionId']);
$game->roundCreate($mission->id);
$game->roundSoundTimeCalculate($mission->getMaxTime(), $sound->getSoundTimes());
$game->setBasesLegendScreen(); // Set Default Legend Base Screen
if(!isset($request->data['anyId'])) $request->data['anyId'] = 0;
if($request->data['anyId']=='on') $request->data['anyId'] = 1;

header("Location: /?command=missionStartView&view=base-ready-wait&missionId={$mission->id}&anyId={$request->data['anyId']}&id1={$request->data['id1']}&id2={$request->data['id2']}&id3={$request->data['id3']}");
exit;