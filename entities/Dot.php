<?php

namespace entities;


use classes\Logger;

class Dot
{
    public $address;
    public $name;

    public $_db;
    public function __construct($address, $name, $db = null)
    {
        $this->address = $address;
        $this->name = $name;
        $this->_db = $db;
    }


    public function off()
    {
        if(DEBUG_GLOBAL) {
            //echo 'Отключение точки '.$this->name.'<br />';
            return;
        }
        return $this->curlContents($this->address . '/off.php');
    }

    public function getStatus()
    {
        if(DEBUG_GLOBAL) {
            //echo 'Получение статуса точки '.$this->name.'<br />';
            return;
        }
        return $this->curlContents($this->address . '/status.php');
    }


    public function curlContents($url, $method = 'GET', $data = false, $headers = false, $returnInfo = false) {
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

        //$contents = curl_exec($ch);

        $contents = NULL;
        $CountCurl = 0;

        do {
            $contents = curl_exec($ch);
            Logger::getLogger(LOG_NAME)->log('Отправлен запрос N '.$CountCurl.' на точку:'.$this->address.'. URL: '.$url.'?'.$data);
            Logger::getLogger(LOG_NAME)->log('Точка ответила:'.json_encode($contents));
            $CountCurl++;
            if ($CountCurl>5) break;
        } while ($contents===FALSE);

        if($returnInfo) {
            $info = curl_getinfo($ch);
        }

        curl_close($ch);

        //Logger::getLogger(LOG_NAME)->log('Отправлен запрос на точку:'.$this->address.'. URL: '.$url.'?'.$data);
        //Logger::getLogger(LOG_NAME)->log('Точка ответила:'.$contents);

        if($returnInfo) {
            return ['contents' => $contents, 'info' => $info];
        } else {
            return false;
        }
    }
}