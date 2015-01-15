<?php
/** 
 * Date: 15.01.15
 * Time: 15:29
 */
$extension = new \ReflectionExtension('memcached');
echo $extension->getVersion();