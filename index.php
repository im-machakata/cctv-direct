<?php
use CctvDirect\Scrapper;

include __DIR__ . "/vendor/autoload.php";

header('Content-Type: application/json');

$results  = array();
$host = $_SERVER['HTTP_HOST'];
$scrapper = new Scrapper();
$scrapper->scrape(sprintf('http://%s/bin/dahua-analog-hd-cctv-cameras.htm',$host));

// ? localhost demo scrapping 
$collection = $scrapper->collection;

if(!$collection){
    die("Failed to connect to ".$host);
}
$results    = array(
    'title' => $collection->getTitle(),
    'category_slug' => $collection->getCategorySlug(),
    'products' => $collection->getProducts(),

    // 'collections' => $collection->getCollections(),
);

// ? scrapping all collections from official website
// $collections = $scrapper->collection->getCollections();

// foreach ($collections as $collectionUrl) {
//     $scrapper->scrape($collectionUrl);
//     $results[$scrapper->collection->getCategorySlug()] = array(
//         'title' => $scrapper->collection->getTitle(),
//         'products' => $scrapper->collection->getProducts()
//     );
// }

// ? print results
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);