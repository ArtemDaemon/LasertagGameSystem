<?php

namespace classes;



class RoundHelper
{
    public static function teamList()
    {
        return [
            RED_TEAM => 'Красные',
            BLUE_TEAM => 'Синие',
        ];
    }

    public static function drawTeam($id)
    {
        $teams = self::teamList();
        return (isset($teams[$id])) ? $teams[$id] : false;
    }

    public static function drawPlayerName()
    {

    }
}