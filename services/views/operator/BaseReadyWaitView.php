<?php
use entities\Game;
use entities\Mission;
use entities\Base;


$game = new Game($db);
$game->getGame();
$round = $game->getRound();
$points = $game->getAllPoints();


$mission = new Mission($db);
$mission->init($request->data['missionId']);
$missionInfo = $mission->getMission();

$readyBases = $game->getAllBases();
$readyCount = 0;
if($readyBases) {
    foreach($readyBases as $item) {
        if($item['isReady']==1) {
            $readyCount++;
        }
    }
}

$bases = [];
foreach($game->getAllBases() as $baseConfig) {
    if($baseConfig['id']==$mission->getRedBase()) {
        $bases['red'] = new Base($baseConfig['Address'], $baseConfig['Name'], $db);
    }

    if($baseConfig['id']==$mission->getBlueBase()) {
        $bases['blue'] = new Base($baseConfig['Address'], $baseConfig['Name'], $db);
    }
}


// Если раунд начат переадресовываем
if($round['StartDate'] && !$request->isAjax()) {
    header("Location: /?command=roundControlView&view=round-control&missionId=".$mission->id);
    exit;
}

// Если раунд не начат по каким то причинам, но базы уже готовы, начинаем раунд автоматически
if($readyCount==2 && !$request->isAjax()) {
    //header("Location: /?command=forceRoundStart&missionId=".$mission->id."&anyId=".$request->data['anyId']."&id1=".$request->data['id1']."&id2=".$request->data['id2']."&id3=".$request->data['id3']);
    //exit;
}

// Запоминаем настройки
$game->rememberSettings($request->data['anyId'], $request->data['id1'], $request->data['id2'], $request->data['id3']);

require($_SERVER['DOCUMENT_ROOT'] . '/view/index.php');