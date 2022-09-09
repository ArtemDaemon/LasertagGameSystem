<?php
$game->endRound();
$game->unReadyBases();
$game->offBases();


header("Location: /");
exit;