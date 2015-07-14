<?php

function parseConnectionString($connectionString)
{

    $database = [];
    preg_match_all("~([^\:]+)\:([^\@]+)?\@([^\/]+)\/(.*)~", $connectionString, $matches);
    if (isset($matches[1])) {
        $database['user'] = $matches[1][0];
    } else {
        throw new \Exception('Hatalı parametre.');
    }
    if (isset($matches[2])) {
        $database['password'] = empty($matches[2][0]) ? null : $matches[2][0];
    } else {
        throw new \Exception('Hatalı parametre.');
    }
    if (isset($matches[3])) {
        $database['host'] = $matches[3][0];
    } else {
        throw new \Exception('Hatalı parametre.');
    }
    if (isset($matches[4])) {
        $database['dbname'] = $matches[4][0];
    } else {
        throw new \Exception('Hatalı parametre.');
    }

    $database['charset'] = 'utf8';
    return $database;
}
function generatePath($fileName, $level = 3)
{
    $level = $level > 6 ? 6 : $level;
    $array = str_split(md5($fileName), 4);
    $path = '';
    $arraySliced = array_slice($array, 0, $level);
    foreach ($arraySliced as $item) {
        $path .= $item.'/';
    }

    return $path;
}
if(!isset($argv[1])){
    throw new Exception("Genel veritabanı bilgileri eksik. (örn: kullanici:sifre@sunucu/veritabanı)");
}
$database = parseConnectionString($argv[1]);
$connection = new mysqli($database['host'],$database['user'],$database['password'],$database['dbname']);

$journals = $connection->query("SELECT id,image FROM journal where image is not null and image<>''");
while($journal = $journals->fetch_array()){
    $path = generatePath($journal['image']);
    $journalDir = __DIR__."/web/uploads/journalfiles/";
    $issueDir = __DIR__."/web/uploads/issuefiles/";
    if(file_exists($journalDir.$path.$journal['image'])){
        echo $journal['id']." idli derginin görseli şuradadır: ".$journalDir.$path.$journal['image'];
        continue;
    }

    if(file_exists($issueDir.$path.$journal['image'])){
        $copy = @copy($issueDir.$path.$journal['image'],$journalDir.$path.$journal['image']);
        if(!$copy){
            echo $issueDir.$path.$journal['image']." dizinindeki dosya kopyalanamadı.\n";
            continue;
        }
        echo $issueDir.$path.$journal['image']." dizinindeki dosya ".$journalDir.$path.$journal['image']."kopyalandı.\n";
        continue;
    }
    echo $journal['id']." idli derginin görseli mevcut değil.";
}
echo "Tamamlandı.";