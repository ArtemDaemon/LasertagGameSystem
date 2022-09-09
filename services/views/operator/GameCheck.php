<?php
use entities\Game;
use entities\Base;
use entities\Mission;


$game = new Game($db);

$game->select($request->data['id']);

header("Location: /");