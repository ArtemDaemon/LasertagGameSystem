<?php
namespace entities;

use classes\Logger;
use classes\PointHelper;

class Game {
    public $id;

    private $_db;
    private $_data;
    private $_round;
    private $_inited = false;

    private $_points;
    private $_bases;
    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function createNewGame($number, $name)
    {
        $this->_db->query("INSERT INTO `GameList` VALUES ('' ,'{$number}' ,'{$name}')");
        $this->id = $this->_db->insertId();
    }

    public function select($id)
    {
        $gameById = $this->_db->getRow("SELECT * FROM `GameList` WHERE `id`='{$id}'");
        if($gameById) {
            $this->_db->query("UPDATE `GameList` SET `opened`='0'");
            $this->_db->query("UPDATE `GameList` SET `opened`='1' WHERE `id`={$id}");
        }
    }

    public function getGame()
    {
        $this->_data = $this->_db->getRow("SELECT * FROM `GameList` WHERE `opened`='1'");
        $this->_round = $this->getRound();

        if($this->_round) {
            $this->init();
        }

        return $this->_data;
    }
    public function name()
    {
        return $this->_data['Name'];
    }


    // Output Data
    public function getAllPoints()
    {
        return $this->_db->getAll("SELECT * FROM `PointList`");
    }
    public function getAllPointsInGame()
    {
        $noInGame = Point::STATUS_NOT_PLAY;
        return $this->_db->getAll("SELECT * FROM `PointList` WHERE `status`!='{$noInGame}'");
    }
    public function getAllMissions()
    {
        return $this->_db->getAll("SELECT * FROM `MissionList`");
    }
    public function getAllPlayersInGame()
    {
        $game = $this->getGame();
        $players = $this->_db->getAll("SELECT * FROM `PlayersList` WHERE `Game`='{$game['id']}'");

        foreach($players as $k=>$player) {
            $info = $this->_db->getRow("SELECT * FROM `PlayerNameList` WHERE `id`='{$player['id']}'");
            $players[$k]['PlayerName'] = $info['PlayerName'];
        }

        return $players;
    }

    public function getAllPlayers()
    {
        return $this->_db->getAll("SELECT * FROM `PlayerNameList`");
    }


    public function getLegend($missionId, $column)
    {
        $result = $this->_db->getRow("SELECT `{$column}` FROM `MissionList` WHERE `id`='{$missionId}'");

        return $result[$column];
    }

    // Scores
    public function teamAddScore($teamName, $score)
    {
        $round = $this->getRound();

        $this->_db->query("UPDATE `Rounds` SET `Score{$teamName}`=`Score{$teamName}`+'{$score}' WHERE `id`='{$round['id']}'");
    }
    public function getBlueScore()
    {
        $round = $this->getRound();
        return $round['ScoreBlue'];
    }
    public function getRedScore()
    {
        $round = $this->getRound();
        return $round['ScoreRed'];
    }
    public function setWinner($type='team', $id)
    {
        $round = $this->getRound();
        if($type=='team') {
            $this->_db->query("UPDATE `Rounds` SET `WhoWin`='{$id}' WHERE `id`='{$round['id']}'");
        } else {
            $this->_db->query("UPDATE `Rounds` SET `PlayerWinner`='{$id}' WHERE `id`='{$round['id']}'");
        }
    }
    public function forceEndRound()
    {
        $round = $this->getRound();
        $this->_db->query("UPDATE `Rounds` SET `WhoWin`=NULL WHERE `id`='{$round['id']}'");
    }
    public function plusFirstTakePoint($FirstTake)
    {
        $player = $this->getPlayerByTager($FirstTake);

        $this->_db->query("UPDATE `PlayerStat` SET `NFirstTake`=`NFirstTake`+'1' WHERE `PlayerId`='{$player['Name']}'");
    }
    public function getPlayerName($id)
    {
        $player = $this->_db->getRow("SELECT * FROM `PlayerNameList` WHERE `id`={$id}");
        return $player['PlayerName'];
    }
    public function plusTakePoint($LastTake)
    {
        $player = $this->getPlayerByTager($LastTake);

        $this->_db->query("UPDATE `PlayerStat` SET `NLastTake`=`NLastTake`+1 WHERE `PlayerId`='{$player['Name']}'");
    }

    public function getPlayerByTager($tagerId)
    {
        $game = $this->getGame();
        return $this->_db->getRow("SELECT * FROM `PlayersList` WHERE `TagerId`={$tagerId} AND `Game`='{$game['id']}'");
    }


    // Round
    public function roundCreate($missionId)
    {
        $game = $this->getGame();
        $this->_db->query("INSERT INTO `Rounds` VALUES ('' ,'{$game['id']}' ,'{$missionId}' ,'0' ,'0' ,'0' ,'0' ,NULL ,NULL ,'0' ,'0' ,'0' ,'0' ,NULL ,NULL)");
        return $this->_db->insertId();
    }
    public function roundSoundTimeCalculate($missionTime, $soundTimes)
    {
        if($soundTimes) {
            foreach($soundTimes as $item) {
                if($item['TimeToEnd']>$missionTime) {
                    $this->_db->query("UPDATE `SoundTime` SET `isPlayed`='1' WHERE `id`='{$item['id']}'");
                }
            }
        }
    }
    public function roundDelete()
    {
        $round = $this->getRound();
        $this->_db->query("DELETE FROM `Rounds` WHERE `id`='{$round['id']}'");
    }
    public function rememberSettings($anyId, $id1, $id2, $id3)
    {
        $round = $this->getRound();
        $this->_db->query("UPDATE `Rounds` SET `anyId`='{$anyId}', `id1`='{$id1}', `id2`='{$id2}', `id3`='{$id3}' WHERE `id`='{$round['id']}'");
    }
    public function roundStart()
    {
        $round = $this->getRound();
        $minPointPrioritet = $this->getMinPrioritet();
        $this->_db->query("UPDATE `Rounds` SET `pointsPrioritet`='{$minPointPrioritet}', `StartDate`=NOW() WHERE `id`='{$round['id']}' AND `StartDate` IS NULL");
        $this->init();
    }
    public function setBasesLegendScreen()
    {
        $round = $this->getRound();
        $bases = $this->getAllBases();

        foreach($bases as $item) {
            $base = new Base($item['Address'], $item['Name'], $this->_db);
            $colorId = $base->getBaseColorId($round['MissionId']);

            // Set Bases Legend Default
            if ($base->getCurrentView()) {
                $base->removeCurrentView();
            } else {
                // Легенда
                if($colorId == RED_TEAM) {
                    $legend = $this->getLegend($round['MissionId'], 'RedLegend');
                } else {
                    $legend = $this->getLegend($round['MissionId'], 'BlueLegend');
                }

                $base->setCurrentView($legend);
            }
        }
    }
    public function getRound()
    {
        if(!$this->_round) {
            $this->_round = $this->_db->getRow("SELECT * FROM `Rounds` WHERE `EndDate` IS NULL AND `id`=(SELECT MAX(id) FROM `Rounds`)");
        }
        return $this->_round;
    }
    public function getLastEndedRound()
    {
        return $this->_db->getRow("SELECT * FROM `Rounds` ORDER BY `id` DESC LIMIT 1");
    }
    public function endRound()
    {
        $round = $this->getRound();

        if($round) {
            $this->_db->query("UPDATE `Rounds` SET `pointsPrioritet`=NULL, `anyId`='0', `id1`='0', `id2`='0', `id3`='0', `EndDate`=NOW() WHERE `id`='{$round['id']}'");

            $this->_db->query("DELETE FROM `TagerBomb`");
            $this->_db->query("UPDATE `SoundTakeFlag` SET `RedReadySetupPlayed`='0', `BlueReadySetupPlayed`='0'");
            $this->_db->query("UPDATE `SoundPoint2` SET `lastPeriod`=NULL");

            // Отключаем точки
            $this->offPoints();
            $this->offBases();
        }
        $this->playersReset();
    }
    public function getRoundTime($missionTime = null)
    {
        $round = $this->getRound();
        $roundTime = time() - strtotime($round['StartDate']);

        if($missionTime) {
            $roundTime = $missionTime - $roundTime;
        }

        if($roundTime<=0) {
            $roundTime = 0;
        }

        return $roundTime;
    }
    public function soundRound10Played()
    {
        $round = $this->getRound();

        if($round) {
            $this->_db->query("UPDATE `Rounds` SET `Sound10Played`='1' WHERE `id`='{$round['id']}'");
        }
    }
    public function isSoundRound10Played() {
        $round = $this->getRound();

        if($round) {
            return $this->_db->getRow("SELECT * FROM `Rounds` WHERE `Sound10Played`='1' AND `id`='{$round['id']}'");
        }
        return false;
    }
    public function timeIsLeft($missionTime, $percent = 10)
    {
        $tenPercentTime = $missionTime * ($percent/100);
        $timeLeft = $this->roundTimeLeft($missionTime);

        return $timeLeft < $tenPercentTime;
    }
    public function roundTimeLeft($missionTime)
    {
        $dateStart = strtotime($this->_round['StartDate']);
        $dateEnd = $dateStart + $missionTime;

        return $dateEnd - time();
    }
    public function isRoundTimeout($missionTime)
    {
        $dateStart = strtotime($this->_round['StartDate']);
        $dateEnd = $dateStart + $missionTime;

        return (time() >= $dateEnd);
    }
    public function scoreIsLeft($score, $percent=10)
    {
        $round = $this->getRound();
        $scoresPass = $score - ($score * ($percent/100));
        $scoreRed = $round['ScoreRed'];
        $scoreBlue = $round['ScoreBlue'];

        if($scoreRed>=$scoresPass || $scoreBlue>=$scoresPass) {
            return true;
        }
        return false;
    }
    public function isRoundScoreOut($score)
    {
        $round = $this->getRound();
        $scoreRed = $round['ScoreRed'];
        $scoreBlue = $round['ScoreBlue'];

        return ($scoreRed>=$score || $scoreBlue>=$score);
    }
    public function isRoundStarted()
    {
        return $this->getRound();
    }

    public function getResource($id)
    {
        return $this->_db->getRow("SELECT * FROM `ResourceList` WHERE `id`='{$id}'");
    }
    public function getPresetMain($id)
    {
        return $this->_db->getRow("SELECT * FROM `PresetMain` WHERE `id`='{$id}'");
    }


    // Points & Bases
    public function setupPoints($missionId, $anyId = 0, $id1 = 0, $id2 = 0, $id3 = 0)
    {
        $this->initPoints();

        $missionConfig = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$missionId}'");

        foreach($this->_points as $id => $point) {
            $pointSetup = $point->getSetup($missionId);
            $preset = $this->getPresetMain($pointSetup['MainPresetId']);


            // Если точка используется в миссии
            if($pointSetup) {
                $resource = $this->getResource($pointSetup['ResourceTakeId']);

                $result = false;
                if($missionConfig['TypePoint'] == Mission::MISSION_TIME) {
                    $result = $point->setup([
                        'Type' => Point::TYPE_1,
                        'id' => $id,
                        'Armor' => $preset['Armor'],
                        'SumDamage' => $preset['SumDamage'],
                        'TimeResetSum' => $preset['TimeResetSum'],
                        'NeedTime' => $pointSetup['NeedTime'],
                        'TypeWork' => $pointSetup['TypeWork'],
                        'ResourceTakeId' => $resource['SendId'],
                        'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                        'ColorId' => 3,
                        'TimeReset' => $pointSetup['TimeReset'],
                        'AnyId' => $anyId,
                        'id1' => $id1,
                        'id2' => $id2,
                        'id3' => $id3,
                    ]);
                }

                if($missionConfig['TypePoint'] == Mission::MISSION_SHOOTOUT_BY_TURNS) {
                    if($pointSetup['Order']==$this->getMinPrioritet()) {
                        $result = $point->setup([
                            'Type' => Point::TYPE_1,
                            'id' => $id,
                            'Armor' => $preset['Armor'],
                            'SumDamage' => $preset['SumDamage'],
                            'TimeResetSum' => $preset['TimeResetSum'],
                            'NeedTime' => $pointSetup['NeedTime'],
                            'TypeWork' => 1,
                            'ResourceTakeId' => $resource['SendId'],
                            'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                            'ColorId' => $missionConfig['WhoCanId'],
                            'TimeReset' => $pointSetup['TimeReset'],
                            'AnyId' => $anyId,
                            'id1' => $id1,
                            'id2' => $id2,
                            'id3' => $id3,
                        ]);
                    }

                    $point->setCanInit(1);
                }

                if($missionConfig['TypePoint'] == Mission::MISSION_SEQUENTIAL_SEIZURE) {
                    // Встречный режим
                    if($missionConfig['Towards']==1) {
                        // Если приоритет первый или последний
                        if ($pointSetup['Order'] == $this->getMinPrioritet()) {
                            $result = $point->setup([
                                'Type' => Point::TYPE_1,
                                'id' => $id,
                                'Armor' => $preset['Armor'],
                                'SumDamage' => $preset['SumDamage'],
                                'TimeResetSum' => $preset['TimeResetSum'],
                                'NeedTime' => $pointSetup['NeedTime'],
                                'TypeWork' => 1,
                                'ResourceTakeId' => $resource['SendId'],
                                'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                                'ColorId' => 1,
                                'TimeReset' => $pointSetup['TimeReset'],
                                'AnyId' => $anyId,
                                'id1' => $id1,
                                'id2' => $id2,
                                'id3' => $id3,
                            ]);
                        } else if ($pointSetup['Order'] == $this->getMaxPrioritet()) {
                            $result = $point->setup([
                                'Type' => Point::TYPE_1,
                                'id' => $id,
                                'Armor' => $preset['Armor'],
                                'SumDamage' => $preset['SumDamage'],
                                'TimeResetSum' => $preset['TimeResetSum'],
                                'NeedTime' => $pointSetup['NeedTime'],
                                'TypeWork' => 1,
                                'ResourceTakeId' => $resource['SendId'],
                                'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                                'ColorId' => 2,
                                'TimeReset' => $pointSetup['TimeReset'],
                                'AnyId' => $anyId,
                                'id1' => $id1,
                                'id2' => $id2,
                                'id3' => $id3,
                            ]);
                        } else {
                            $point->off();
                        }

                        $point->setCanInit(1);
                    } else {
                        // Инициализируем только с минимальным приоритетом
                        if($pointSetup['Order'] == $this->getMinPrioritet()) {
                            $result = $point->setup([
                                'Type' => Point::TYPE_1,
                                'id' => $id,
                                'Armor' => $preset['Armor'],
                                'SumDamage' => $preset['SumDamage'],
                                'TimeResetSum' => $preset['TimeResetSum'],
                                'NeedTime' => $pointSetup['NeedTime'],
                                'TypeWork' => 1,
                                'ResourceTakeId' => $resource['SendId'],
                                'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                                'ColorId' => $missionConfig['WhoCanId'],
                                'TimeReset' => $pointSetup['TimeReset'],
                                'AnyId' => $anyId,
                                'id1' => $id1,
                                'id2' => $id2,
                                'id3' => $id3,
                            ]);
                        } else {
                            $point->off();
                        }
                        $point->setCanInit(1);
                    }
                }


                if($missionConfig['TypePoint'] == Mission::MISSION_CAPTURE_THE_FLAG) {
                    // Точки
                    $result = $point->setup([
                        'Type' => Point::TYPE_1,
                        'id' => $id,
                        'Armor' => $preset['Armor'],
                        'SumDamage' => $preset['SumDamage'],
                        'TimeResetSum' => $preset['TimeResetSum'],
                        'NeedTime' => $pointSetup['NeedTime'],
                        'TypeWork' => 1,
                        'ResourceTakeId' => $resource['SendId'],
                        'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                        'ColorId' => 3,
                        'TimeReset' => 5,
                        'AnyId' => 0,
                        'id1' => 0,
                        'id2' => 0,
                        'id3' => 0,
                    ]);
                    $point->setCanInit(1);
                }

                if($missionConfig['TypePoint'] == Mission::MISSION_HOME_DESTROING) {
                    $result = $point->setup([
                        'Type' => Point::TYPE_4,
                        'id' => $id,
                        'Armor' => $preset['Armor'],
                        'SumDamage' => $preset['SumDamage'],
                        'TimeReset' => $pointSetup['TimeReset'],
                        'ResourceTakeId' => $resource['SendId'],
                        'WhoseHome' => $pointSetup['WhoseHome'],
                        'AnyId' => $anyId,
                        'id1' => $id1,
                        'id2' => $id2,
                        'id3' => $id3,
                    ]);
                }

                if($missionConfig['TypePoint'] == Mission::MISSION_SURVIVAL) {
                    $result = $point->setup([
                        'Type' => Point::TYPE_5,
                        'id' => $id,
                        'Armor' => $preset['Armor'],
                        'SumDamage' => $preset['SumDamage'],
                        'TimeResetSum' => $preset['TimeResetSum'],
                        'ResourceTakeId' => $resource['SendId'],
                        'TimeStartWeapon' => $pointSetup['TimeStartWeapon'],
                        'PeriodWeapon' => $missionConfig['PeriodWeapon'],
                        'NTake' => $pointSetup['NTake'],
                        'TimeReset' => $pointSetup['TimeReset'],
                        'TimeStartRed' => $pointSetup['TimeStartRad'],
                        'IntervalRed' => $pointSetup['IntervalRad'],
                        'DamageRed' => $pointSetup['DamageRag'],
                        'AnyId' => $anyId,
                        'id1' => $id1,
                        'id2' => $id2,
                        'id3' => $id3,
                    ]);
                }

                if($missionConfig['TypePoint'] == Mission::MISSION_BOMB_PLANT) {
                    $result = $point->setup([
                        'Type' => Point::TYPE_6,
                        'id' => $id,
                        'TypeBomb' => $pointSetup['TypeBomb'],
                        'TimerBomb' => $missionConfig['TimerBomb'],
                        'PeriodBomb' => $missionConfig['PeriodBomb'],
                        'AnyId' => ($pointSetup['TypeBomb']==4 || $pointSetup['TypeBomb']==3) ? 0 : $anyId,
                        'id1' => $id1,
                        'id2' => $id2,
                        'id3' => $id3,
                    ]);
                    $point->setCanInit(1);
                }



                if(!$result) {
                    $point->setStatus(Point::STATUS_ERROR);
                } else {
                    $point->setStatus(Point::STATUS_NEUTRAL);
                }

                // Устанавливаем приоритеты точек
                if($pointSetup['Order']!=0) {
                    $point->setPrioritet($pointSetup['Order']);
                }


                // Status Not Play
                if($pointSetup['Active'] == 0) {
                    $point->setStatus(Point::STATUS_NOT_PLAY);
                }
            } else {
                // Отключаем точки, не учавствующие в миссии
                $point->off();
                $point->setStatus(Point::STATUS_NOT_PLAY);
            }
        }
        // Инициализация точек для захвата флага, которых нет в MissionPointSetup
        if($missionConfig['TypePoint'] == Mission::MISSION_CAPTURE_THE_FLAG) {
            $this->pointsFlagCaptureInit(
                $missionConfig['RedAllow'],
                $missionConfig['BlueAllow'],
                $missionConfig['RedBase'],
                $missionConfig['RedAim'],
                $missionConfig['BlueBase'],
                $missionConfig['BlueAim'],
                $anyId,
                $id1
            );
        }
    }
    public function pointsFlagCaptureInit($redAllow, $blueAllow, $redBase, $redAim, $blueBase, $blueAim, $anyId=0, $id1=0, $id2=0, $id3=0)
    {
        // Red Base
        if($redAllow) {
            $pointRedBase = $this->getPoint($redBase);
            $pointRedBase = new Point($pointRedBase['Address'], $pointRedBase['Name'], $this->_db);
            $resultRed = $pointRedBase->setup([
                'Type' => Point::TYPE_2,
                'id' => $redBase,
                'Team' => RED_TEAM,
                'Status' => 1,
                'AnyId' => $anyId,
                'id1' => $id1,
                'id2' => $id2,
                'id3' => $id3,
            ]);
            if(!$resultRed) {
                $pointRedBase->setStatus(Point::STATUS_ERROR);
            } else {
                $pointRedBase->setStatus(Point::STATUS_NEUTRAL);
            }

            // Red Aim [status=0]
            $pointRedAim = $this->getPoint($redAim);
            $pointRedAim = new Point($pointRedAim['Address'], $pointRedAim['Name'], $this->_db);
            $resultRed = $pointRedAim->setup([
                'Type' => Point::TYPE_3,
                'id' => $redAim,
                'Team' => RED_TEAM,
                'Status' => 0,
                'AnyId' => $anyId,
                'id1' => $id1
            ]);
            if(!$resultRed) {
                $pointRedAim->setStatus(Point::STATUS_ERROR);
            } else {
                $pointRedAim->setStatus(Point::STATUS_NEUTRAL);
            }
            $pointRedAim->setCanInit(1);
        }

        if($blueAllow) {
            // Blue Base
            $pointBlueBase = $this->getPoint($blueBase);
            $pointBlueBase = new Point($pointBlueBase['Address'], $pointBlueBase['Name'], $this->_db);
            $resultBlue = $pointBlueBase->setup([
                'Type' => Point::TYPE_2,
                'id' => $blueBase,
                'Team' => BLUE_TEAM,
                'Status' => 1,
                'AnyId' => $anyId,
                'id1' => $id1,
                'id2' => $id2,
                'id3' => $id3,
            ]);
            if (!$resultBlue) {
                $pointBlueBase->setStatus(Point::STATUS_ERROR);
            } else {
                $pointBlueBase->setStatus(Point::STATUS_NEUTRAL);
            }

            // Blue Aim [status=0]
            $pointBlueAim = $this->getPoint($blueAim);
            $pointBlueAim = new Point($pointBlueAim['Address'], $pointBlueAim['Name'], $this->_db);
            $resultBlue = $pointBlueAim->setup([
                'Type' => Point::TYPE_3,
                'id' => $blueAim,
                'Team' => BLUE_TEAM,
                'Status' => 0,
                'AnyId' => $anyId,
                'id1' => $id1
            ]);
            if (!$resultBlue) {
                $pointBlueAim->setStatus(Point::STATUS_ERROR);
            } else {
                $pointBlueAim->setStatus(Point::STATUS_NEUTRAL);
            }
            $pointBlueAim->setCanInit(1);
        }
    }
    public function pointsTakeReset()
    {
        $round = $this->getRound();

        $this->_db->query("UPDATE `PointList` SET `status`=NULL, `time`='0', `health`='0', `currPrioritet`=NULL, `action_date`=NULL, `takeTagerId`=NULL, `canInit`='0'");
        $this->offPoints();
        $this->setupPoints($round['MissionId'], $round['anyId'], $round['id1'], $round['id2'], $round['id3']);
    }
    public function resetPauseIsset()
    {
        $round = $this->getRound();
        $missionConfig = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$round['MissionId']}'");

        $pause = $missionConfig['TimeResetTogether'];

        $lastAction = $this->getPointsLastActionDate();
        $timePass = time() - strtotime($lastAction);

        return $timePass>=$pause;
    }
    public function getPointsLastActionDate()
    {
        $actionDate = $this->getLastActionPoint();
        return $actionDate['action_date'];
    }
    public function getLastActionPoint()
    {
        return $this->_db->getRow("SELECT * FROM `PointList` ORDER BY `action_date` DESC");
    }
    public function pointsInitByPrioritet($prioritet, $setColorId = false)
    {
        $round = $this->getRound();
        $this->initPoints();

        $missionConfig = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$round['MissionId']}'");

        foreach($this->_points as $id => $point) {
            $pointSetup = $point->getSetup($round['MissionId']);
            $preset = $this->getPresetMain($pointSetup['MainPresetId']);
            $colorId = $missionConfig['WhoCanId'];

            // Если точка используется в миссии
            if($pointSetup) {

                $result = false;
                if($point->canInit() && $pointSetup['Order']==$prioritet) {
                    $resource = $this->getResource($pointSetup['ResourceTakeId']);

                    // Обнуляем точку
                    $point->reset();

                    if($pointSetup['Order']!=0) {
                        $point->setPrioritet($pointSetup['Order']);
                    }

                    // Автоопределение цвета следующей инициализации
                    if($setColorId!==false) {
                        $colorId = $setColorId;
                    }


                    $result = $point->setup([
                        'Type' => Point::TYPE_1,
                        'id' => $id,
                        'Armor' => $preset['Armor'],
                        'SumDamage' => $preset['SumDamage'],
                        'TimeResetSum' => $preset['TimeResetSum'],
                        'NeedTime' => $pointSetup['NeedTime'],
                        'TypeWork' => 1,
                        'ResourceTakeId' => $resource['SendId'],
                        'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                        'ColorId' => $colorId,
                        'TimeReset' => $pointSetup['TimeReset'],
                        'AnyId' => $round['anyId'],
                        'id1' => $round['id1'],
                        'id2' => $round['id2'],
                        'id3' => $round['id3'],
                    ]);
                    $point->setCanInit(0);
                }


                /*if(!$result) {
                    $point->setStatus(Point::STATUS_ERROR);
                } else {
                    $point->setStatus(Point::STATUS_NEUTRAL);
                }


                // Status Not Play
                if($pointSetup['Active'] == 0) {
                    $point->setStatus(Point::STATUS_NOT_PLAY);
                }*/
            }
        }
    }
    public function allPointsTakeByTeam()
    {
        $pointsTaked = false;
        $totalPoints = 0;
        $redPointsHave = 0;
        $bluePointsHave = 0;
        $pointsInGame = $this->getAllPointsInGame();

        foreach($pointsInGame as $row) {
            if($row['takeTagerId']) {
                $player = $this->getPlayerByTager($row['takeTagerId']);

                if($player['Color']==RED_TEAM) $redPointsHave++;
                if($player['Color']==BLUE_TEAM) $bluePointsHave++;

                $totalPoints++;
            }
        }
        if($pointsInGame && $totalPoints==count($pointsInGame) && ($redPointsHave==0 || $bluePointsHave==0)) {
            $pointsTaked = true;
        }


        if($pointsTaked) {
            $randomTakedPoint = $this->_db->getRow("SELECT * FROM `PointList` WHERE `takeTagerId` IS NOT NULL");
            if($randomTakedPoint) {
                $player = $this->getPlayerByTager($randomTakedPoint['takeTagerId']);
                return $player['Color'];
            }
            return false;
        }
        return false;
    }
    public function noEmptyPointsInGame()
    {
        $pointsTaked = false;
        $taggersHave = 0;
        $pointsInGame = $this->getAllPointsInGame();
        foreach($pointsInGame as $row) {
            if($row['takeTagerId']) {
                $taggersHave++;
            }
        }


        if($pointsInGame && $taggersHave==count($pointsInGame)) {
            $pointsTaked = true;
        }
        return $pointsTaked;
    }
    public function prioritetPointsTakedAll($prioritet = false)
    {
        if(!$prioritet) {
            $round = $this->getRound();
            $prioritet = $round['pointsPrioritet'];
        }
        $totalPoints = $this->_db->getAll("SELECT * FROM `PointList` WHERE `currPrioritet`='{$prioritet}'");
        $takedPoints = $this->_db->getAll("SELECT * FROM `PointList` WHERE `currPrioritet`='{$prioritet}' AND `takeTagerId` IS NOT NULL");
        return count($takedPoints) == count($totalPoints);
    }
    public function getNewPointsPrioritet()
    {
        $round = $this->getRound();

        $newPrioritet = $round['pointsPrioritet']+1;

        return $newPrioritet;
    }
    public function getPointByPrioritet($prioritet)
    {
        return $this->_db->getRow("SELECT * FROM `PointList` WHERE `currPrioritet`='{$prioritet}'");
    }
    public function getMaxCurrentPrioritet()
    {
        $missionPoints = $this->_db->getRow("SELECT MAX(`currPrioritet`) FROM `PointList` WHERE `takeTagerId` IS NOT NULL AND `currPrioritet` IS NOT NULL");

        return ($missionPoints['MAX(`currPrioritet`)']) ? $missionPoints['MAX(`currPrioritet`)'] : $this->getMaxPrioritet();
    }
    public function getStatPointByPrioritet()
    {
        $pointsInGame = $this->getAllPointsInGame();

        $redPrioritets = [];
        $bluePrioritets = [];
        foreach($pointsInGame as $point) {
            if($point['takeTagerId']) {
                $player = $this->getPlayerByTager($point['takeTagerId']);

                if($player['Color']==RED_TEAM) {
                    $redPrioritets[] = $point['currPrioritet'];
                }
                if($player['Color']==BLUE_TEAM) {
                    $bluePrioritets[] = $point['currPrioritet'];
                }
            }
        }

        return [
            'red' => ($redPrioritets) ? max($redPrioritets) : $this->getMinPrioritet(),
            'blue' => ($bluePrioritets) ? min($bluePrioritets) : false,
        ];
    }
    public function getMinCurrentPrioritet()
    {
        $maxPointPrioritetId = $this->_db->getRow("SELECT * FROM `PointList` WHERE `takeTagerId` IS NOT NULL AND `currPrioritet` IS NOT NULL");
        $missionPoints = $this->_db->getRow("SELECT MIN(`currPrioritet`) FROM `PointList` WHERE `id`!='{$maxPointPrioritetId['id']}' AND `takeTagerId` IS NOT NULL AND `currPrioritet` IS NOT NULL");

        return ($missionPoints['MIN(`currPrioritet`)']) ? $missionPoints['MIN(`currPrioritet`)'] : $this->getMinPrioritet();
    }
    public function getMaxPrioritet()
    {
        $round = $this->getRound();
        $missionPoints = $this->_db->getRow("SELECT MAX(`Order`) FROM `MissionPointSetup` WHERE `MissionId`='{$round['MissionId']}'");

        return $missionPoints['MAX(`Order`)'];
    }
    public function getMinPrioritet()
    {
        $round = $this->getRound();
        $missionPoints = $this->_db->getRow("SELECT MIN(`Order`) FROM `MissionPointSetup` WHERE `MissionId`='{$round['MissionId']}'");

        return $missionPoints['MIN(`Order`)'];
    }
    public function setPrioritet($prioritet)
    {
        $round = $this->getRound();
        $this->_db->query("UPDATE `Rounds` SET `pointsPrioritet`='{$prioritet}' WHERE `id`='{$round['id']}'");
    }
    public function resetWithoutTager($pointId, $tagerId)
    {
        $red = $this->getPlayerWithFlagRed();
        $blue = $this->getPlayerWithFlagBlue();

        if($red['TagerId']==$tagerId) {
            $red['TagerId'] = 0;
        }
        if($blue['TagerId']==$tagerId) {
            $blue['TagerId'] = 0;
        }


        $round = $this->getRound();
        $this->initPoints();

        $missionConfig = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$round['MissionId']}'");

        foreach($this->_points as $id => $point) {
            if($id == $pointId) {
                $pointSetup = $point->getSetup($round['MissionId']);
                $preset = $this->getPresetMain($pointSetup['MainPresetId']);
                $resource = $this->getResource($pointSetup['ResourceTakeId']);


                $point->setup([
                    'Type' => Point::TYPE_1,
                    'id' => $id,
                    'Armor' => $preset['Armor'],
                    'SumDamage' => $preset['SumDamage'],
                    'TimeResetSum' => $preset['TimeResetSum'],
                    'NeedTime' => $pointSetup['NeedTime'],
                    'TypeWork' => 1,
                    'ResourceTakeId' => $resource['SendId'],
                    'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                    'ColorId' => 3,
                    'TimeReset' => $pointSetup['TimeReset'],
                    'AnyId' => 0,
                    'id1' => ($red) ? $red['TagerId'] : 0,
                    'id2' => ($blue) ? $blue['TagerId'] : 0,
                    'id3' => 0,
                ]);
            }
        }
    }

    public function pointsFlagCaptureReinit()
    {
        $round = $this->getRound();
        $this->initPoints();

        $missionConfig = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$round['MissionId']}'");


        $red = $this->getPlayerWithFlagRed();
        $blue = $this->getPlayerWithFlagBlue();

        foreach($this->_points as $id => $point) {

            $pointSetup = $point->getSetup($round['MissionId']);
            $preset = $this->getPresetMain($pointSetup['MainPresetId']);


            // Если точка используется в миссии
            if($pointSetup) {
                $resource = $this->getResource($pointSetup['ResourceTakeId']);

                $result = false;
                if($missionConfig['TypePoint'] == Mission::MISSION_CAPTURE_THE_FLAG) {
                    // Точки
                    $result = $point->setup([
                        'Type' => Point::TYPE_1,
                        'id' => $id,
                        'Armor' => $preset['Armor'],
                        'SumDamage' => $preset['SumDamage'],
                        'TimeResetSum' => $preset['TimeResetSum'],
                        'NeedTime' => $pointSetup['NeedTime'],
                        'TypeWork' => 1,
                        'ResourceTakeId' => $resource['SendId'],
                        'ResourceIdComlite' => $pointSetup['ResourceIdComlite'],
                        'ColorId' => 3,
                        'TimeReset' => 5,
                        'AnyId' => 0,
                        'id1' => ($red) ? $red['TagerId'] : 0,
                        'id2' => ($blue) ? $blue['TagerId'] : 0,
                        'id3' => 0,
                    ]);
                    $point->setCanInit(1);
                }


                if(!$result) {
                    $point->setStatus(Point::STATUS_ERROR);
                } else {
                    $point->setStatus(Point::STATUS_NEUTRAL);
                }


                // Status Not Play
                if($pointSetup['Active'] == 0) {
                    $point->setStatus(Point::STATUS_NOT_PLAY);
                }
            }

        }
    }
    public function offPoints()
    {
        $this->initPoints();

        foreach($this->_points as $id => $point) {
            $point->off();
            $point->setStatus(Point::STATUS_OFF);
        }
    }
    public function getPoint($id)
    {
        return $this->_db->getRow("SELECT * FROM `PointList` WHERE `id`='{$id}'");
    }
    public function playersReset()
    {
        $this->_db->query("UPDATE `PlayersList` SET `hasFlag`='0', `pointsTake`='0', `flagsCaptured`='0', `actionTime`=NULL");
    }
    public function pointsReset()
    {
        $this->_db->query("UPDATE `PointList` SET `status`=NULL, `time`='0', `health`='0', `currPrioritet`=NULL, `action_date`=NULL, `NBlueTake`='0', `NRedTake`='0', `takeTagerId`=NULL, `canInit`='0'");
    }
    public function pointCanInit($pointId, $canInit)
    {
        $this->_db->query("UPDATE `PointList` SET `canInit`='{$canInit}' WHERE `id`='{$pointId}'");
    }


    public function whoLead()
    {
        $round = $this->getRound();
        if($round['ScoreRed'] > $round['ScoreBlue']) {
            return 'Red';
        } elseif($round['ScoreRed'] < $round['ScoreBlue']) {
            return 'Blue';
        } else {
            return false;
        }
    }


    // Base
    public function basesReset()
    {
        $this->_db->query("UPDATE `BaseList` SET `type`=NULL, `status`='0', `isReady`='0', `currentView`=NULL, `resources`=NULL, `nRessurections`=NULL, `last_ressurection`=NULL, `last_ressurection_interval_calculate`=NULL, `ressurectionInterval`=NULL, `TimeOver`='0'");
    }


    public function basesReinit($missionId)
    {
        $this->initBases();

        $missionConfig = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$missionId}'");
        $baseRed = $missionConfig['RedBaseId'];
        $baseBlue = $missionConfig['BlueBaseId'];
        $typeRed = $missionConfig['IdTypeBaseRed'];
        $typeBlue = $missionConfig['IdTypeBaseBlue'];

        $this->_db->query("UPDATE `BaseList` SET `type`='{$typeRed}' WHERE `id`='{$baseRed}'");
        $this->_db->query("UPDATE `BaseList` SET `type`='{$typeBlue}' WHERE `id`='{$baseBlue}'");

        foreach($this->_bases as $id => $base) {
            $config = [];
            $config['Type'] = ($id == $baseRed) ? $typeRed : $typeBlue;
            $config['id'] = $id;
            $config['ColorId'] = ($id == $baseRed) ? RED_TEAM : BLUE_TEAM;
            $config['TimePushBase'] = ($id == $baseRed) ? $missionConfig['TimePushBaseRed'] : $missionConfig['TimePushBaseBlue'];

            if($base->getType() == Base::TYPE_REVIVAL_BY_TIME) {
                $config['Status'] = 0;
            }

            if($base->getType()==Base::TYPE_LIMITED_REVIVAL) {
                $config['StartReBase'] = ($id == $baseRed) ? $missionConfig['StartReBaseRed'] : $missionConfig['StartReBaseBlue'];
                $config['IntervalReBase'] = ($id == $baseRed) ? $missionConfig['IntervalReBaseRed'] : $missionConfig['IntervalReBaseBlue'];
            }

            if($base->getType()==Base::TYPE_STOCK) {
                if($id == $baseRed) {
                    $ReBase = ($missionConfig['StartReBaseRed']>0) ? 1 : 0;
                    $WeaponBase = ($missionConfig['StartReWeaponBaseRed']>0) ? 1 : 0;

                    $base->setResourcesDefault($missionConfig['StartReBaseRed'], $missionConfig['StartReWeaponBaseRed']);
                } else {
                    $ReBase = ($missionConfig['StartReBaseBlue']>0) ? 1 : 0;
                    $WeaponBase = ($missionConfig['StartReWeaponBaseBlue']>0) ? 1 : 0;

                    $base->setResourcesDefault($missionConfig['StartReBaseBlue'], $missionConfig['StartReWeaponBaseBlue']);
                }

                $config['ReBase'] = $ReBase;
                $config['WeaponBase'] = $WeaponBase;
                $config['Achive'] = 0;
            }

            $base->setup($config);
            if($id == $baseRed) {
                $base->setType($typeRed);
            }
            if($id == $baseBlue) {
                $base->setType($typeBlue);
            }

            //$base->setStatus(Base::STATUS_ON);
        }
    }

    public function offBases()
    {
        $this->initBases();

        foreach($this->_bases as $id => $base) {
            $base->off();
            $base->setStatus(Base::STATUS_OFF);
        }
    }

    public function setupBasesForReady($missionId)
    {
        $this->initBases();

        $missionConfig = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$missionId}'");
        $baseRed = $missionConfig['RedBaseId'];
        $baseBlue = $missionConfig['BlueBaseId'];
        foreach($this->_bases as $id => $base) {
            $base->setup([
                'Type' => Base::TYPE_PRESS_WAIT,
                'id' => $id,
                'ColorId' => ($baseRed==$id) ? RED_TEAM : BLUE_TEAM,
            ]);

            $base->setType(Base::TYPE_PRESS_WAIT);
            //$base->setStatus(Base::STATUS_ON);
        }
    }
    public function baseReady($baseId)
    {
        $this->_db->query("UPDATE `BaseList` SET `isReady`='1' WHERE `id`='{$baseId}'");
    }
    public function setReadyAllBases()
    {
        $this->_db->query("UPDATE `BaseList` SET `isReady`='1'");
    }
    public function unReadyBases()
    {
        $this->_db->query("UPDATE `BaseList` SET `isReady`='0'");
    }
    public function allBasesReady()
    {
        $bases = $this->_db->getAll("SELECT * FROM `BaseList` WHERE `isReady`='1'");
        return (count($bases)==2);
    }
    public function getAllBases()
    {
        return $this->_db->getAll("SELECT * FROM `BaseList`");
    }
    public function getBaseById($id)
    {
        return $this->_db->getRow("SELECT * FROM `BaseList` WHERE `id`='{$id}'");
    }
    public function setTypeToAllBases($type)
    {
        $bases = $this->getAllBases();
        foreach($bases as $base) {
            $this->_db->query("UPDATE `BaseList` SET `type`='{$type}' WHERE `id`='{$base['id']}'");
        }
    }

    public function reinitPointsBomb($missionId, $player = false)
    {
        $this->initPoints();
        $missionConfig = $this->_db->getRow("SELECT * FROM `MissionList` WHERE `id`='{$missionId}'");

        $redPointInitIds = [];
        $bluePointInitIds = [];
        /**
         * Перебираем игроков обеих команд для сбора id1, id2, id3 - для инициализации точек
         */
        foreach ($this->getPlayersWithBomb() as $item) {
            $info = $this->getPlayerByTager($item['tagerId']);

            if ($info['Color'] == RED_TEAM) {
                $redPointInitIds[] = $item['tagerId'];
            }

            if ($info['Color'] == BLUE_TEAM) {
                $bluePointInitIds[] = $item['tagerId'];
            }
        }

        /**
         * Добавляем игрока к инициализируемым
         */
        if($player && !in_array($player->tagerId, $bluePointInitIds)) {
            if ($player->colorId == RED_TEAM) {
                if(count($redPointInitIds)==3) {
                    $redPointInitIds[0] = $player->tagerId;
                } else {
                    $redPointInitIds[] = $player->tagerId;
                }
            }

            if ($player->colorId == BLUE_TEAM) {
                if(count($bluePointInitIds)==3) {
                    $bluePointInitIds[0] = $player->tagerId;
                } else {
                    $bluePointInitIds[] = $player->tagerId;
                }
            }
        }

        $i = 0;
        foreach($this->_points as $id => $point) {
            if(!$point->canInit()) continue;

            $pointSetup = $point->getSetup($missionId);

            // Если точка используется в миссии
            if($pointSetup) {
                $id1 = 0;
                $id2 = 0;
                $id3 = 0;
                $reinit = false;


                if($pointSetup['TypeBomb'] == Point::TYPE_BOMB_RED_AIM) {
                    $id1 = isset($redPointInitIds[0]) ? $redPointInitIds[0] : 0;
                    $id2 = isset($redPointInitIds[1]) ? $redPointInitIds[1] : 0;
                    $id3 = isset($redPointInitIds[2]) ? $redPointInitIds[2] : 0;
                    $reinit = true;
                }

                if($pointSetup['TypeBomb'] == Point::TYPE_BOMB_BLUE_AIM) {
                    $id1 = isset($bluePointInitIds[0]) ? $bluePointInitIds[0] : 0;
                    $id2 = isset($bluePointInitIds[1]) ? $bluePointInitIds[1] : 0;
                    $id3 = isset($bluePointInitIds[2]) ? $bluePointInitIds[2] : 0;
                    $reinit = true;
                }


                // Переиницилизируем только цели красных и синих
                if($reinit) {
                    $result = $point->setup([
                        'Type' => Point::TYPE_6,
                        'id' => $id,
                        'TypeBomb' => $pointSetup['TypeBomb'],
                        'TimerBomb' => $missionConfig['TimerBomb'],
                        'PeriodBomb' => $missionConfig['PeriodBomb'],
                        'AnyId' => 0,
                        'id1' => $id1,
                        'id2' => $id2,
                        'id3' => $id3,
                    ]);

                    if (!$result) {
                        $point->setStatus(Point::STATUS_ERROR);
                    } else {
                        $point->setStatus(Point::STATUS_NEUTRAL);
                    }


                    // Status Not Play
                    if ($pointSetup['Active'] == 0) {
                        $point->setStatus(Point::STATUS_NOT_PLAY);
                    }
                }
            } else {
                // Отключаем точки, не учавствующие в миссии
                $point->off();
                $point->setStatus(Point::STATUS_NOT_PLAY);
            }

            $i++;
        }
    }

    public function removeBomb($id)
    {
        $this->_db->query("DELETE FROM `TagerBomb` WHERE `id`='{$id}'");
    }
    public function getPlayersWithBomb()
    {
        return $this->_db->getAll("SELECT * FROM `TagerBomb` ORDER BY `add_date` DESC");
    }



    public function flagBasePointToggle($pointBase, $colorId, $status = 0) {
        $id = $pointBase['id'];
        $pointBase = new Point($pointBase['Address'], $pointBase['Name'], $this->_db);
        $round = $this->getRound();

        $result = $pointBase->setup([
            'Type' => Point::TYPE_2,
            'id' => $id,
            'Team' => $colorId,
            'Status' => $status,
            'AnyId' => $round['anyId'],
            'id1' => $round['id1'],
            'id2' => $round['id2'],
            'id3' => $round['id3']
        ]);
        if(!$result) {
            $pointBase->setStatus(Point::STATUS_ERROR);
        }
    }
    public function flagAimPointToggle($pointAim, $colorId, $status = 0, $tagerId = 0) {
        $id = $pointAim['id'];
        $pointAim = new Point($pointAim['Address'], $pointAim['Name'], $this->_db);

        if(!$pointAim->canInit()) return;
        $result = $pointAim->setup([
            'Type' => Point::TYPE_3,
            'id' => $id,
            'Team' => $colorId,
            'Status' => $status,
            'AnyId' => 0,
            'id1' => $tagerId
        ]);
        if(!$result) {
            $pointAim->setStatus(Point::STATUS_ERROR);
        }
        if($tagerId!=0) {
            $pointAim->setCanInit(0);
        }
    }
    public function getPlayerWithFlagRed($redTimeTake=0)
    {
        $game = $this->getGame();
        $team = RED_TEAM;
        $player = $this->_db->getRow("SELECT * FROM `PlayersList` WHERE `hasFlag`='1' AND `Color`='{$team}' AND `Game`='{$game['id']}' ORDER BY `actionTime` DESC");

        if($player) {
            $player['PlayerData'] = $this->_db->getRow("SELECT * FROM `PlayerNameList` WHERE `id`='{$player['Name']}'");
            $player['PlayerStat'] = $this->_db->getRow("SELECT * FROM `PlayerStat` WHERE `PlayerId`='{$player['Name']}'");
            $player['flagLostTime'] = ($redTimeTake + strtotime($player['actionTime'])) - time();

            if($player['flagLostTime']<0) {
                $player['flagLostTime'] = 0;
            }
        }

        return $player;
    }
    public function getPlayerWithFlagBlue($blueTimeTake=0)
    {
        $game = $this->getGame();
        $team = BLUE_TEAM;
        $player = $this->_db->getRow("SELECT * FROM `PlayersList` WHERE `hasFlag`='1' AND `Color`='{$team}' AND `Game`='{$game['id']}' ORDER BY `actionTime` DESC");
        if($player) {
            $player['PlayerData'] = $this->_db->getRow("SELECT * FROM `PlayerNameList` WHERE `id`='{$player['Name']}'");
            $player['PlayerStat'] = $this->_db->getRow("SELECT * FROM `PlayerStat` WHERE `PlayerId`='{$player['Name']}'");
            $player['flagLostTime'] = ($blueTimeTake + strtotime($player['actionTime'])) - time();

            if($player['flagLostTime']<0) {
                $player['flagLostTime'] = 0;
            }
        }

        return $player;
    }
    public function teamHasFlag($colorId)
    {
        $game = $this->getGame();
        $flags = $this->_db->getAll("SELECT * FROM `PlayersList` WHERE `Color`='{$colorId}' AND `hasFlag`='1' AND `Game`='{$game['id']}'");
        if(count($flags)>=1) return true;
        return false;
    }

    private function initPoints()
    {
        $checkpoints = $this->_db->getAll("SELECT * FROM `PointList`");

        if(!$this->_points) {
            foreach ($checkpoints as $item) {
                $this->_points[$item['id']] = new Point($item['Address'], $item['Name'], $this->_db);
            }
        }

        return $this->_points;
    }

    private function initBases()
    {
        $bases = $this->_db->getAll("SELECT * FROM `BaseList`");

        foreach ($bases as $item) {
            $this->_bases[$item['id']] = new Base($item['Address'], $item['Name'], $this->_db);
        }
    }

    public function init()
    {
        $this->_inited = true;
    }
}