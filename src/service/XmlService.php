<?php
/**
 * (c) Chaim <gc@dtapp.net>
 */


namespace DtApp\ThinkLibrary\service;


use DtApp\ThinkLibrary\Xmls;
use think\Service;

class XmlService extends Service
{
    public function register()
    {
        $this->app->bind('xmld', Xmls::class);
    }
}
