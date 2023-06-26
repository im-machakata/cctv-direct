<?php
namespace CctvDirect;

use simplehtmldom\HtmlWeb;

/**
 * Scrapes data from cctv-direct.co.za
 * 
 * Code scrapes the entire collection page and creates a new endpoint with a title and a data array with the content. This can be converted to a CSV or printed as JSON
 * @author Isaac Machakata <isaac@caasi.co.zw>
 * @link https://github.com/im-machakata
 */
class Scrapper {
    /**
     * @var HtmlWeb
     */
    private $scrapper;
    /**
     * @var Collection|null
     */
    public $collection;
    /**
     * @var bool
     */
    public $wasScrapped;

    public function __construct() {
        $this->scrapper   = new HtmlWeb();
        $this->collection = new Collection();
    }

    /**
     * Basically fetches the html from the url.
     * The function then returns a collection class or null depending on the result.
     * @param string $url
     */
    public function scrape($url) {
        $scrapped         = $this->scrapper->load($url);
        $this->collection = !$scrapped ? null : $this->collection->source($scrapped, $url);
        unset($scrapped);
        return ($this->wasScrapped = ($this->collection !== null));
    }
}