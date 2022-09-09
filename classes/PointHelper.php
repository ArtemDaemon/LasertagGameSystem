<?php
namespace classes;

use entities\Mission;
use entities\Point;

class PointHelper
{
    public static function defaultStatusList()
    {
        return [
            Point::STATUS_OFF => 'Выключена',
            Point::STATUS_ERROR => 'Нет связи',
            Point::STATUS_NOT_PLAY => 'Не играет',
        ];
    }
    public static function additionalStatusList()
    {
        /*return [
            NULL => [0 => 'Не играет'],
            Point::TYPE_1 => [
                \entities\Point::STATUS_NEUTRAL => 'Нейтральная',
                \entities\Point::STATUS_BLUE => 'Синие',
                \entities\Point::STATUS_RED => 'Красные'
            ],
            Point::TYPE_2 => [
                \entities\Point::TYPE_RED_FLAG => 'Флаг красных',
                \entities\Point::TYPE_RED_AIM => 'Цель красных',
                \entities\Point::TYPE_BLUE_FLAG => 'Флаг синих',
                \entities\Point::TYPE_BLUE_AIM => 'Цель синих',
                \entities\Point::TYPE_POINT => 'Точка',
            ],
            Point::TYPE_4 => [
                \entities\Point::STATUS_BLUE => 'Синие',
                \entities\Point::STATUS_RED => 'Красные'
            ],
            Point::TYPE_5 => [
                \entities\Point::STATUS_DO_RAD => 'Излучает радиацию',
                \entities\Point::STATUS_WAITING => 'Ожидание',
                \entities\Point::STATUS_GIVES_AMMUNATION => 'Выдает патроны'
            ],
            Point::TYPE_6 => [
                \entities\Point::STATUS_BOMB_PRODUCTION => 'Производство',
                \entities\Point::STATUS_BOMB_READY=> 'Готова',
                \entities\Point::STATUS_BOMB_PLANTED => 'Установлена бомба',
                \entities\Point::STATUS_BOMB_EXPLODED => 'Взорвана'
            ],
        ];*/
        return [
            NULL => [0 => 'Не играет'],
            Mission::MISSION_TIME => [
                Point::TYPE_1 => [
                    \entities\Point::STATUS_NEUTRAL => 'Нейтральная',
                    \entities\Point::STATUS_BLUE => 'Синие',
                    \entities\Point::STATUS_RED => 'Красные'
                ],
            ],

            Mission::MISSION_SHOOTOUT_BY_TURNS => [
                Point::TYPE_2 => [
                    //Point::TYPE_CAPTURE_FLAG_STATUS_ON => 'В сети'
                ],
                Point::TYPE_1 => [
                    \entities\Point::STATUS_NEUTRAL => 'Нейтральная',
                    \entities\Point::STATUS_BLUE => 'Синие',
                    \entities\Point::STATUS_RED => 'Красные'
                ],
            ],
            Mission::MISSION_SEQUENTIAL_SEIZURE => [
                Point::TYPE_2 => [
                    //Point::TYPE_CAPTURE_FLAG_STATUS_ON => 'В сети'
                ],
                Point::TYPE_1 => [
                    \entities\Point::STATUS_NEUTRAL => 'Нейтральная',
                    \entities\Point::STATUS_BLUE => 'Синие',
                    \entities\Point::STATUS_RED => 'Красные'
                ],
            ],

            Mission::MISSION_CAPTURE_THE_FLAG => [
                Point::TYPE_1 => [
                    \entities\Point::STATUS_NEUTRAL => 'Нейтральная',
                    \entities\Point::STATUS_BLUE => 'Синие',
                    \entities\Point::STATUS_RED => 'Красные'
                ],
                Point::TYPE_2 => [
                    Point::TYPE_CAPTURE_FLAG_STATUS_FLAG_ON_POINT => 'Флаг на точке',
                    Point::TYPE_CAPTURE_FLAG_STATUS_NO_FLAG_ON_POINT => 'Флага нет'
                ],
                Point::TYPE_3 => [
                    Point::TYPE_CAPTURE_FLAG_STATUS_FLAG_ON_POINT => 'Флаг на точке',
                    Point::TYPE_CAPTURE_FLAG_STATUS_NO_FLAG_ON_POINT => 'Флага нет'
                ],
            ],

            Mission::MISSION_HOME_DESTROING => [
                Point::TYPE_4 => [
                    \entities\Point::STATUS_BLUE => 'Синие',
                    \entities\Point::STATUS_RED => 'Красные'
                ],
            ],
            Mission::MISSION_SURVIVAL => [
                Point::TYPE_5 => [
                    \entities\Point::STATUS_DO_RAD => 'Излучает радиацию',
                    \entities\Point::STATUS_WAITING => 'Ожидание',
                    \entities\Point::STATUS_GIVES_AMMUNATION => 'Выдает патроны'
                ],
            ],
            Mission::MISSION_BOMB_PLANT => [
                Point::TYPE_6 => [
                    \entities\Point::STATUS_BOMB_NO_PLANTED => 'Бомбы нет',
                    \entities\Point::STATUS_BOMB_PRODUCTION => 'Производство',
                    \entities\Point::STATUS_BOMB_READY => 'Готова',
                    \entities\Point::STATUS_BOMB_PLANTED => 'Установлена бомба',
                    \entities\Point::STATUS_BOMB_EXPLODED => 'Взорвана'
                ],
            ],
        ];
    }

    public static function statusName($id, $missionCatalogType=null, $typePoint=null)
    {
        //print_r($id);
        $statuses = self::defaultStatusList();
        if(isset($statuses[$id])) {
            return $statuses[$id];
        }

        $additional = self::statusNameByPointType($missionCatalogType, $typePoint);
        return $additional[$id];
    }


    public static function statusNameByPointType($missionCatalogType, $typePoint)
    {
        $additional = self::additionalStatusList();
        return $additional[$missionCatalogType][$typePoint];
    }

    public static function drawHealth($health, $maxHealth)
    {
        if(!$maxHealth) {
            return '';
        }

        return $health . ' / ' . $maxHealth;
    }

    public static function drawTakeTager($id)
    {
        if(!$id) return;
        return 'Захвачен id ' . $id;
    }

    public static function typeBomb($id)
    {
        $types = [
            Point::TYPE_BOMB_RED_TAKE => 'Выдача бомбы красным',
            Point::TYPE_BOMB_BLUE_TAKE => 'Выдача бомбы синим',
            Point::TYPE_BOMB_RED_AIM => 'Цель красных',
            Point::TYPE_BOMB_BLUE_AIM => 'Цель синих',
        ];

        return $types[$id];
    }

    public static function typeCaptureFlag($id)
    {
        $types = [
            Point::TYPE_CAPTURE_FLAG_RED_BASE => 'Флаг красных',
            Point::TYPE_CAPTURE_FLAG_RED_AIM => 'Цель красных',
            Point::TYPE_CAPTURE_FLAG_BLUE_BASE => 'Флаг синих',
            Point::TYPE_CAPTURE_FLAG_BLUE_AIM => 'Цель синих',
            Point::TYPE_CAPTURE_FLAG_POINT => 'Точка',
        ];

        return $types[$id];
    }


    public static function pointTypes()
    {
        return [
            Point::TYPE_1,
            Point::TYPE_2,
            Point::TYPE_3,
            Point::TYPE_4,
            Point::TYPE_5,
            Point::TYPE_6
        ];
    }
}