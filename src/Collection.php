<?php
namespace CctvDirect;

use CctvDirect\Utils;
use simplehtmldom\HtmlDocument;

class Collection {

    /**
     * @var HtmlDocument|null
     */
    private $doc;
    private $url;
    public function __construct() {
        $this->doc = null;
    }

    /**
     * Add a source to scrape data from
     * @param HtmlDocument $doc
     * @param string $uri
     */
    public function source($doc, $uri) {
        $this->url = $uri;
        $this->doc = $doc;
        return $this;
    }

    /**
     * Returns the title of the current collection
     * @return string|null
     */
    public function getTitle() {
        return !$this->doc ? null : $this->doc->find('.collection__title .heading.h1', 0)->plaintext;
    }

    /**
     * Returns a list of products
     * @return array
     */
    public function getProducts() {
        $results  = array();
        $products = $this->doc->find('.product-item--vertical');

        foreach ($products as $product) {

            // remove the span tag to get the price only
            $product->find('.price span', 0)->remove();

            $results[] = [
                'title' => Utils::cleanValue($product->find('.product-item__title.text--strong.link', 0)->innertext),
                'price' => Utils::toPrice($product->find('.price', 0)->innertext),
                'images' => Utils::getImage($product->find('.product-item__primary-image', 0)),
                'image_alt' => $product->find('.product-item__primary-image', 0)->alt,
            ];
        }
        return $results;
    }

    /**
     * Returns the current category slug url
     */
    public function getCategorySlug() {
        $uri = explode('/', $this->url);
        return str_replace(['.htm', '.html'], '', end($uri));
    }

    /**
     * Returns a list of all collections excluding the all collections collection
     * @return array
     */
    public function getCollections() {
        $results    = array();
        $menu_items = $this->doc->find('.mobile-menu__panel.is-nested .mobile-menu__nav');

        foreach ($menu_items as $menu) {
            foreach ($menu->find('a') as $a) {
                if ($a->href == "/collections/all") {
                    continue;
                }
                $results[] = Utils::cleanUrl($a->href, $this->getBaseUrl());
            }
        }

        return $results;
    }

    /**
     * Returns the base url where the content has been scrapped from eg. cctvscrap.tld
     * @return string
     */
    public function getBaseUrl() {
        $url = explode('/', $this->url);
        return $url[0] . '//' . $url[2];
    }
}