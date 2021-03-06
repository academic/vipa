<?php

namespace Vipa\CoreBundle\Helper;

class FileHelper
{
    /**
     * Generates a $n level folder tree for given filename by exploding the filename
     * @param  string  $fileName
     * @param  bool    $createDir
     * @param  string  $uploadRootPath upload root path
     * @param  integer $level
     * @return string
     */
    public function generatePath($fileName, $createDir = false, $uploadRootPath = './', $level = 3)
    {
        $level = $level > 6 ? 6 : $level;
        $array = str_split(md5($fileName), 4);
        $path = '';
        $arraySliced = array_slice($array, 0, $level);
        foreach ($arraySliced as $item) {
            $path .= $item.'/';
            $createDir && !file_exists($uploadRootPath.$path) && mkdir($uploadRootPath.$path);
        }

        return $path;
    }

    /**
     * Generates a $n level folder tree with random integer by exploding the filename
     *
     * @param  bool    $createDir
     * @param  string  $uploadRootPath upload root path
     * @param  integer $level
     * @return string
     */
    public function generateRandomPath($createDir = false, $uploadRootPath = './', $level = 3)
    {
        $level = $level > 6 ? 6 : $level;
        $array = str_split(md5(mt_rand()), 4);
        $path = '';
        $arraySliced = array_slice($array, 0, $level);
        foreach ($arraySliced as $item) {
            $path .= $item.'/';
            $createDir && !file_exists($uploadRootPath.$path) && mkdir($uploadRootPath.$path);
        }

        return $path;
    }
}
