<?php
/**
 * Created by PhpStorm.
 * User: emreyilmaz
 * Date: 24.06.15
 * Time: 09:24
 */

namespace Ojs\Common\Helper;


/**
 * Class StringHelper
 * @package Ojs\Common\Helper
 */
class StringHelper {
    /**
     * Generate randomized , 64 char sized keys.
     * @return string
     */
    public static function generateKey()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $apikey = '';
        for ($i = 0; $i < 64; $i++) {
            $apikey .= $characters[rand(0, strlen($characters) - 1)];
        }
        $apikey = base64_encode(sha1(uniqid('ue'.rand(rand(), rand())).$apikey));
        return $apikey;
    }
} 