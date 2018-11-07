<?php
/**
 * Created by https://github.com/Wheiss
 * Date: 05.11.2018
 * Time: 16:12
 */

namespace AppBundle\Services\News;


class NewsManagerStaticFactory
{
    public static function createNewsManager($doctrine, $paginator, $router)
    {
        $manager = new Manager($doctrine, $paginator, $router);
        return $manager;
    }
}