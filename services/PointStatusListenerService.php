<?php
use entities\Point;
//\classes\Logger::getLogger('log_listen')->log('Слушатель точки: ' . json_encode($_REQUEST));

$pointId = $request->data['id'];
$Type = $request->data['Type'];
$Status = $request->data['Status'];



$pointInfo = $game->getPoint($pointId);
if($pointInfo) {
    $point = new Point($pointInfo['Address'], $pointInfo['Name'], $db);

    if($Type==100) {
        $point->setStatus(Point::STATUS_OFF);
    } else {
        $point->setStatus($Status);
    }
    $point->lastListenUpdate();


    // Обрабатываем в зависимости от типа точки
    if($Type!=100) {
        require $_SERVER['DOCUMENT_ROOT'] . "/services/point_listener_handlers/type_" . $Type . '.php';
    }
}