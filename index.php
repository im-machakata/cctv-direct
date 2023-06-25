<?php
use CctvDirect\Scrapper;

include __DIR__ . "/vendor/autoload.php";
include __DIR__ . "/src/Scrapper.php";

header('Content-Type: application/json');

$results  = array();
$scrapper = new Scrapper();
$scrapper->scrape('http://cctvscrap.test/bin/dahua-analog-hd-cctv-cameras.htm');

// ? localhost demo scrapping 
$collection = $scrapper->collection;
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