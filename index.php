<?php
use CctvDirect\Scrapper;

include __DIR__ . "/vendor/autoload.php";

header('Content-Type: application/json');

$results  = array();
$scrapper = new Scrapper();

// ? use this on when not connected to the internet
// $host     = sprintf('http://%s/bin/dahua-analog-hd-cctv-cameras.htm', $_SERVER['HTTP_HOST']);

// ? scrap from the server directly
$host = sprintf('https://cctv-direct.co.za/collections/dahua-analog-hd-cctv-cameras.html');
$scrapper->scrape($host);

// ? localhost demo scrapping 
$collection = $scrapper->collection;

if (!$collection) {
    die("Failed to connect to " . $host);
}
$results = array(
    'title' => $collection->getTitle(),
    'category_slug' => $collection->getCategorySlug(),
    'products' => $collection->getProducts(),

    // 'collections' => $collection->getCollections(),
);

// ? scrapping all collections from official website
$collections = $scrapper->collection->getCollections();

foreach ($collections as $collectionUrl) {
    $scrapper->scrape($collectionUrl);

    if (!$scrapper->collection) {
        continue;
    }
    $results[$scrapper->collection->getCategorySlug()] = array(
        'title' => $scrapper->collection->getTitle(),
        'products' => $scrapper->collection->getProducts()
    );
}

// ? print results
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);