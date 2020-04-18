<?php
/**
 * (c) Chaim <gc@dtapp.net>
 */


namespace DtApp\ThinkLibrary\service;


use DtApp\ThinkLibrary\helper\Time;
use think\Service;

class TimeService extends Service
{
    public function register()
    {
        $this->app->bind('timed', Time::class);
    }
}
