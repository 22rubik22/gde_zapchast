<?php

namespace App\Helpers;

class UrlHelper
{
    public static function generateUrlWithCity($route, $id = null, $city = null)
    {
        $url = route($route, $id);
        
        if ($city) {
            $url .= '?city=' . urlencode($city);
        }
        
        return $url;
    }

}