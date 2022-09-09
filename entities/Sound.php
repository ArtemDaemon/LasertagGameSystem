<?php
namespace entities;

use classes\Logger;

class Sound
{
    const CHANNEL_COMMON = 1;
    const CHANNEL_BASE_1 = 2;
    const CHANNEL_BASE_2 = 3;

    public $config;
    public $mainPreset;
    public $server;
    private $_db;
    private $_sounds = ['round' => null, 'point' => null, 'base' => null, 'times' => null, 'surv' => null, 'bombplant' => null, 'takeflag' => null];


    public function __construct($db)
    {
        $this->_db = $db;
    }

    public function setServer($address)
    {
        $this->server = $address;
    }

    public function setup($config)
    {
        $this->config = $config;
    }

    public function playSoundTime($timeLeft)
    {
        $soundTimes = $this->getSoundTimes();

        foreach($soundTimes as $item) {
            if($item['TimeToEnd']>=$timeLeft) {
                $soundTime = $this->_db->getRow("SELECT * FROM `SoundTime` WHERE `id`='{$item['id']}' AND `isPlayed`='0'");
                $this->_db->query("UPDATE `SoundTime` SET `isPlayed`='1' WHERE `id`='{$item['id']}'");

                if($soundTime!=null) {
                    $this->playSound($soundTime['File']);
                }
                break;
            }
        }
    }
    public function playTeamReady($teamName)
    {
        $roundSounds = $this->getSoundRound();
        $columnName = $teamName.'Ready';
        $this->playSound($roundSounds[$columnName]);
    }
    public function playRound10()
    {
        $roundSounds = $this->getSoundRound();
        $this->playSound($roundSounds['BackSound10']);
    }
    public function playRoundStartSound()
    {
        $roundSounds = $this->getSoundRound();
        $this->playSound($roundSounds['RoundStart']);
    }
    public function playRoundStopSound()
    {
        $roundSounds = $this->getSoundRound();
        $this->playSound($roundSounds['RoundStop']);
    }
    public function playForceRoundStopSound()
    {
        $roundSounds = $this->getSoundRound();
        $this->playSound($roundSounds['RoundComplete']);
    }
    public function playStartBg()
    {
        $roundSounds = $this->getSoundRound();
        $this->playSound($roundSounds['BackSound']);
    }
    public function playRoundDrawSound()
    {
        $roundSounds = $this->getSoundRound();
        $this->playSound($roundSounds['Draw']);
    }
    public function playRoundTeamWinSound($teamName)
    {
        $roundSounds = $this->getSoundRound();
        $columnName = $teamName.'Win';
        $this->playSound($roundSounds[$columnName]);
    }
    public function playSoundByPoint($tableName, $namePresetId, $soundColumn, $pointId)
    {
        $this->playSound(
            $this->getSoundPoint($tableName, $namePresetId, $soundColumn, $pointId)
        );
    }

    public function playFlagAimReady($teamName)
    {
        $flag = $this->getSoundTakeFlag();


        if($flag) {
            if($flag[$teamName.'ReadySetupPlayed']==1) return;

            $this->_db->getRow("UPDATE `SoundTakeFlag` SET `{$teamName}ReadySetupPlayed`='1' WHERE `id`='{$flag['id']}'");
            $this->playSound($flag[$teamName.'ReadySetup']);
        }
    }


    public function playAlarm()
    {
        $this->playSound($this->getSoundSurv());
    }


    public function playTakeBomb($teamName)
    {
        $bombplantSounds = $this->getSoundBombPlant();
        $columnName = $teamName.'TakeBomb';
        $this->playSound($bombplantSounds[$columnName]);
    }
    public function playMakeBomb($teamName)
    {
        $bombplantSounds = $this->getSoundBombPlant();
        $columnName = $teamName.'MakeBomb';
        $this->playSound($bombplantSounds[$columnName]);
    }
    public function playLostBomb($teamName)
    {
        $bombplantSounds = $this->getSoundBombPlant();
        $columnName = $teamName.'LostBomb';
        $this->playSound($bombplantSounds[$columnName]);
    }
    public function playSetupBomb($teamName)
    {
        $bombplantSounds = $this->getSoundBombPlant();
        $columnName = $teamName.'SetupBomb';
        $this->playSound($bombplantSounds[$columnName]);
    }
    public function playDefuseBomb($teamName)
    {
        $bombplantSounds = $this->getSoundBombPlant();
        $columnName = $teamName.'Defuse';
        $this->playSound($bombplantSounds[$columnName]);
    }
    public function playBombExplosion()
    {
        $bombplantSounds = $this->getSoundBombPlant();
        $this->playSound($bombplantSounds['Explosion']);
    }
    public function playBombBeep()
    {
        $bombplantSounds = $this->getSoundBombPlant();
        $this->playSound($bombplantSounds['Beep']);
    }

    public function playFlagTake($teamName)
    {
        $takeFlagSounds = $this->getSoundTakeFlag();
        $this->playSound($takeFlagSounds[$teamName . 'TakeFlag']);
    }
    public function playFlagMiss($teamName)
    {
        $takeFlagSounds = $this->getSoundTakeFlag();
        $this->playSound($takeFlagSounds[$teamName . 'MissFlag']);
    }
    public function playFlagComplete($teamName)
    {
        $takeFlagSounds = $this->getSoundTakeFlag();
        $this->playSound($takeFlagSounds[$teamName . 'Complete']);
    }
    public function playTakePoint($teamName)
    {
        $takeFlagSounds = $this->getSoundTakeFlag();
        $this->playSound($takeFlagSounds[$teamName . 'TakePoint']);
    }
    public function playBaseResourceTake($id, $soundBaseId, $baseId) {
        $sound = $this->getSoundForResource($id, $soundBaseId);
        if($baseId==RED_TEAM) {
            $channel = self::CHANNEL_BASE_1;
        } else {
            $channel = self::CHANNEL_BASE_2;
        }
        $this->playSound($sound['File'], $channel);
    }
    public function playLead($teamName, $presetId)
    {
        $sounds = $this->getSoundPoint2($presetId);

        // Update LastPeriod
        $period = $sounds['Period'];
        $nextPeriod = ($sounds['lastPeriod']!=NULL) ? ($sounds['lastPeriod'] + $period) : ($period+$period);
        $this->_db->query("UPDATE `SoundPoint2` SET `lastPeriod`='{$nextPeriod}' WHERE `id`='{$sounds['id']}'");

        $this->playSound($sounds[$teamName . 'Lead']);
    }
    public function playLeadInTime($teamName, $namePresetId)
    {
        $namePreset = $this->pointNamePesetById('SoundPoint1', $namePresetId);
        $sounds = $this->getSoundPoint2ByPresetName($namePreset);

        // Update LastPeriod
        $period = $sounds['Period'];
        $nextPeriod = ($sounds['lastPeriod']!=NULL) ? ($sounds['lastPeriod'] + $period) : ($period+$period);
        $this->_db->query("UPDATE `SoundPoint2` SET `lastPeriod`='{$nextPeriod}' WHERE `id`='{$sounds['id']}'");

        if($sounds) {
            $this->playSound($sounds[$teamName . 'Lead']);
        }
    }
    public function getLeadPeriod($presetId)
    {
        $sounds = $this->getSoundPoint2($presetId);

        $period = ($sounds['lastPeriod']!=NULL) ? $sounds['lastPeriod'] : $sounds['Period'];

        return $period * 60;
    }
    public function getLeadPeriodInTime($namePresetId)
    {
        $namePreset = $this->pointNamePesetById('SoundPoint1', $namePresetId);
        $sounds = $this->getSoundPoint2ByPresetName($namePreset);

        $period = ($sounds['lastPeriod']!=NULL) ? $sounds['lastPeriod'] : $sounds['Period'];

        return $period * 60;
    }


    public function playSound($sound, $channel = self::CHANNEL_COMMON)
    {
        $this->curlContents($this->server . '/Sound.php', 'GET', ['Name' => $sound, 'channel' => $channel]);
    }
    public function stopSound()
    {
        $this->playSound('stop_bg.mp3');
        //$this->curlContents($this->server . '/stop_bg.mp3', 'GET', ['channel' => self::CHANNEL_COMMON]);
    }


    public function getSoundPoint2ByPresetName($namePreset)
    {
        return $this->_db->getRow("SELECT * FROM `SoundPoint2` WHERE `NamePreset`='{$namePreset}'");
    }
    public function getSoundPoint2($id)
    {
        return $this->_db->getRow("SELECT * FROM `SoundPoint2` WHERE `id`='{$id}'");
    }
    public function getSoundTakeFlag()
    {
        if(!$this->_sounds['takeflag']) {
            $this->_sounds['takeflag'] = $this->_db->getRow("SELECT * FROM `SoundTakeFlag`");
        }
        return $this->_sounds['takeflag'];
    }
    public function getSoundBombPlant()
    {
        if(!$this->_sounds['bombplant']) {
            $this->_sounds['bombplant'] = $this->_db->getRow("SELECT * FROM `SoundSetupBomb` WHERE `id`='{$this->config['surv']}'");
        }
        return $this->_sounds['bombplant'];
    }
    public function getSoundSurv()
    {
        if(!$this->_sounds['surv']) {
            $this->_sounds['surv'] = $this->_db->getRow("SELECT * FROM `SoundSurv` WHERE `id`='{$this->config['surv']}'");
        }
        return $this->_sounds['surv']['File'];
    }
    public function getSoundRound()
    {
        if(!$this->_sounds['round']) {
            $this->_sounds['round'] = $this->_db->getRow("SELECT * FROM `SoundRound` WHERE `id`='{$this->config['round']}'");
        }
        return $this->_sounds['round'];
    }
    public function getSoundForResource($id, $soundBaseId)
    {
        return $this->_db->getRow("SELECT * FROM `SoundResourseCome` WHERE `ResourseId`='{$id}' AND `IdSoundBase`='{$soundBaseId}'");
    }
    public function getSoundTimes()
    {
        $soundRound = $this->getSoundRound();
        if(!$this->_sounds['times']) {
            $this->_sounds['times'] = $this->_db->getAll("SELECT * FROM `SoundTime` WHERE `NamePresetId`='{$soundRound['id']}'");
        }
        return $this->_sounds['times'];
    }
    public function resetSoundTimes()
    {
        $this->_db->query("UPDATE `SoundTime` SET `isPlayed`='0'");
    }
    public function getSoundPoint($tableName, $namePresetId, $soundColumn, $pointId)
    {
        $namePreset = $this->pointNamePesetById($tableName, $namePresetId);

        $soundPoint = $this->_db->getRow("SELECT * FROM `{$tableName}` WHERE `NamePreset`='{$namePreset}' AND `pointId`='{$pointId}'");
        return $soundPoint[$soundColumn];
    }
    public function pointNamePesetById($tableName, $id)
    {
        $preset = $this->_db->getRow("SELECT * FROM `{$tableName}` WHERE `id`='{$id}'");
        return $preset['NamePreset'];
    }

    private function curlContents($url, $method = 'GET', $data = false, $headers = false, $returnInfo = false)
    {
        if(!$this->server) {
            throw new \DomainException('Звуковой сервер не установлен');
        }
        if(!$this->config) {
            throw new \DomainException('config не установлен');
        }

        $ch = curl_init();

        if($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            if($data !== false) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        } else {
            if($data !== false) {
                if(is_array($data)) {
                    $dataTokens = [];
                    foreach($data as $key => $value) {
                        array_push($dataTokens, urlencode($key).'='.urlencode($value));
                    }
                    $data = implode('&', $dataTokens);
                }
                curl_setopt($ch, CURLOPT_URL, $url.'?'.$data);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 50);
        if($headers !== false) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $contents = curl_exec($ch);

        if($returnInfo) {
            $info = curl_getinfo($ch);
        }

        curl_close($ch);

        Logger::getLogger(LOG_NAME)->log('Запрос на звук: URL: '.$url.'| DATA: '.$data);

        if($returnInfo) {
            return ['contents' => $contents, 'info' => $info];
        } else {
            return false;
        }
    }
}