<?php
namespace entities;

class BaseResources {

    const TYPE_RESSURECTION = 102;
    const TYPE_AMMO = 103;


    private $ressurections;
    private $ammo;
    public $achivments = [];

    public function __construct($json = null)
    {
        if($json) {
            $json = json_decode($json, true);
            $this->ressurections = $json['ressurections'];
            $this->ammo = $json['ammo'];
            $this->achivments = $json['achivments'];
        }
    }

    public function updateRessurections($count, $type = 'plus')
    {
        if($type=='plus') {
            $this->ressurections += $count;
        } else {
            if($this->ressurections!=0) {
                $this->ressurections -= $count;
            }
        }
    }
    public function updateAmmo($count, $type = 'plus')
    {
        if($type=='plus') {
            $this->ammo += $count;
        } else {
            if($this->ammo!=0) {
                $this->ammo -= $count;
            }
        }
    }
    public function setResourcesDefault($ressurections, $ammo) {
        $this->ressurections = $ressurections;
        $this->ammo = $ammo;
    }
    public function clearAchivements()
    {
        $this->achivments = [];
    }
    public function getRessurections()
    {
        return $this->ressurections;
    }
    public function getAmmo()
    {
        return $this->ammo;
    }
    public function getAchivments()
    {
        return $this->achivments;
    }

    public function getJson()
    {
        return json_encode([
            'ressurections' => $this->ressurections,
            'ammo' => $this->ammo,
            'achivments' => $this->achivments,
        ], JSON_UNESCAPED_UNICODE);
    }
}