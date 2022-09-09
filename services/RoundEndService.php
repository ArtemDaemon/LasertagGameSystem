<?php
use entities\Mission;

$round = $game->getRound();
// Sound Preset
$mission = new Mission($db);
$mission->init($round['MissionId']);
$sound->setup($mission->getSoundConfig());




// Устанавливаем победителя
if(isset($request->data['forceRoundEnd']) && $request->data['forceRoundEnd']=='true') {
    $game->forceEndRound();
    $sound->playForceRoundStopSound();
} else {
    // Устанавливаем победителя
    $game->setWinner('team', $request->data['whoWin']);

    $sound->playRoundStopSound();
    if($request->data['whoWin']==0) {
        $sound->playRoundDrawSound();
    } else {
        // Название Команды победителей
        $winnerTeamName = $globalTeam[$request->data['whoWin']];
        $sound->playRoundTeamWinSound($winnerTeamName);
    }
}

// Раунд закончен
$sound->stopSound();

$sound->resetSoundTimes();
$game->playersReset();
$game->endRound();
$game->basesReset();
$game->unReadyBases();


header("Location: /?command=roundEndView&view=round-end");
exit;