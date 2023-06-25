# CCTV Direct Data Miner

Fetches data from [cctv-direct.co.za](http://cctv-direct.co.za) into a custom file.

## Installation

Clone or Download the code and install the dependencies using Composer
```composer install```

## Usage

Include the autoload file and setup and headers.

```php
use CctvDirect\Scrapper;

include __DIR__ . "/vendor/autoload.php";

header('Content-Type: application/json');
```

## # localhost scrapping

The following scrapes data from the file in the bin folder

```php
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
// ? print results
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

## # live scrapping

In this example, we get the collections from the file in the bin, then scrap products from the live site.

```php
$collections = $scrapper->collection->getCollections();

foreach ($collections as $collectionUrl) {
    $scrapper->scrape($collectionUrl);
    $results[$scrapper->collection->getCategorySlug()] = array(
        'title' => $scrapper->collection->getTitle(),
        'products' => $scrapper->collection->getProducts()
    );
}
```
