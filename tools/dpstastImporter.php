<?php
if(!isset($argv[1])){
  throw new Exception("Journal id belirtmelisiniz.");
}
$journalId = $argv[1];
if(!isset($argv[2])){
  throw new Exception("Genel veritabanı bilgileri eksik. (örn: kullanici:sifre@sunucu/veritabanı)");
}
$database = parseConnectionString($argv[2]);
if(!isset($argv[3])){
  throw new Exception("İstatistik veritabanı bilgileri eksik. (örn: kullanici:sifre@sunucu/veritabanı)");
}
$statsDatabase = parseConnectionString($argv[3]);

if(!isset($argv[4])){
  throw new Exception("Mongo veritabanı adı eksik.");
}
$mongoDbName = $argv[4];
if(!isset($argv[5])){
  throw new Exception("Mongo sunucu adresi eksik.");
}
$mongoServer = $argv[5];
$mongoConnection = new MongoClient("mongodb://$mongoServer:27017");
$mongoDb = $mongoConnection->{$mongoDbName};
$transferredRecords = $mongoDb->transferred_records;
$totalObjectDownload= $mongoDb->analytics_download_object_sum;
$singleObjectDownloads = $mongoDb->analytics_downloads_object;
$totalObjectView = $mongoDb->analytics_view_object_sum;
$singleObjectViews = $mongoDb->analytics_views_object;

//new database connection
$connection = new mysqli($database['host'],$database['user'],$database['password'],$database['dbname']);

$statsConnection = new mysqli($statsDatabase['host'],$statsDatabase['user'],$statsDatabase['password'],$statsDatabase['dbname']);

$record = $transferredRecords->find(['old_id'=>(int)$journalId,'entity'=>'Ojs\\JournalBundle\\Entity\\Journal']);
if(!$record->count()){
  echo "$journalId id'li dergi için hiç taşınma kaydı yok.";
  exit;
}
$result = iterator_to_array($record);
if(!$result){
  echo "$journalId id dergi için sorun var içerik yok.";
  exit;
}
$new_id = end($result)['new_id'];
if(!$new_id){
  echo "{$journalId} id'li dergi için hiç taşınma kaydı verisi yok.";
  exit;
}

if(mysqli_errno($connection)){
  throw new Exception(mysqli_error($connection));
}

$articles = $connection->query("SELECT id FROM article WHERE journal_id=$new_id");


if(!$articles){
  throw new Exception("Sonuçsuz");
}
echo "Toplam makale sayısı: ".$articles->num_rows."\n";
while($article = $articles->fetch_array()){
  // connect mongo and get record change / oldid
  $record = $transferredRecords->find(['new_id'=>(int)$article['id'],'entity'=>'Ojs\\JournalBundle\\Entity\\Article']);
  if(!$record){
    echo "{$article['id']} id'li makale için hiç taşınma kaydı yok.";
    continue;
  }
  $result = iterator_to_array($record);
  if(!$result)
    continue;
  $old_id = end($result)['old_id'];
  if(!$old_id){
    echo "{$article['id']} id'li makale için hiç taşınma kaydı verisi yok.";
    continue;
  }

  $totalDownload = single($statsConnection->query("select total from article_total_download_stats where article_id=$old_id"));
  $totalView = single($statsConnection->query("select total from article_total_view_stats where article_id=$old_id"));

  $singleViews = $statsConnection->query("select * from article_view_stats where article_id=$old_id");
  $singleDownloads = $statsConnection->query("select * from article_download_stats where article_id=$old_id");

  $totalDownloadCount = single($totalObjectDownload->find(['objectId'=>$article['id'],'entity'=>'article']));
  $totalViewCount = single($totalObjectView->find(["objectId"=>$article['id'],'entity'=>'article']));
  if(!$totalDownloadCount && $totalDownload['total']){
    $totalDownloadDocument = [
      'entity'=>'article',
      'objectId'=>$article['id'],
      'total'=>$totalDownload['total']
    ];
    $totalObjectDownload->insert($totalDownloadDocument);
    echo "id: {$article['id']} , download: {$totalDownload['total']} \n";
    $singleDownloadsArray = iterator_to_array($singleDownloads);
    echo "\t Total single downloads: ".count($singleDownloadsArray)."\n";
    foreach($singleDownloadsArray as $singleDownload){
      $singleDownloadDocument = [
          'entity'=>'article',
          'objectId'=>$article['id'],
          'logDate'=>(new \DateTime($singleDownload['download_time']))
      ];
      $singleObjectDownloads->insert($singleDownloadDocument);
    }
  }else{
    echo "{$article['id']} id'li makale için hiç indirme istatistiği yok.\n";
    echo "for debug: \n\t article_id: {$article['id']} | $old_id \n\t total_download_count: $totalDownloadCount\n\ttotal_download: {$totalDownload['total']} \n";
  }

  if(!$totalViewCount && $totalView['total']){
    $totalViewDocument = [
      'entity'=>'article',
      'objectId'=>$article['id'],
      'total'=>$totalView['total']
    ];
    $totalObjectView->insert($totalViewDocument);
    echo "id: {$article['id']} , view: {$totalView['total']} \n";
    $singleViewsArray = iterator_to_array($singleViews);
    echo "\t Total single views: ".count($singleViewsArray)."\n";
    foreach($singleViewsArray as $singleView){
      $singleViewDocument = [
          'entity'=>'article',
          'objectId'=>$article['id'],
          'logDate'=>(new \DateTime($singleView['view_time']))
      ];
      $singleObjectViews->insert($singleViewDocument);
    }
  }else{
    echo "{$article['id']} id'li makale için hiç görüntüleme istatistiği yok.\n";
    echo "for debug: \n\t article_id: {$article['id']} | $old_id \n\t total_download_count: $totalViewCount\n\ttotal_download: {$totalView['total']} \n";

  }
}







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

function single($result){
  $result = iterator_to_array($result);
  if(!$result)
    return false;
  if(!is_array($result))
    return false;
  if(count($result)<1)
    return false;
  return end($result);
}
