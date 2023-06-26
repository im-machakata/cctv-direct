<?php
namespace CctvDirect;

use simplehtmldom\HtmlDocument;

/**
 * Scrapes data from cctv-direct.co.za
 * 
 * Code scrapes the entire collection page and creates a new endpoint with a title and a data array with the content. This can be converted to a CSV or printed as JSON
 * @author Isaac Machakata <isaac@caasi.co.zw>
 * @link https://github.com/im-machakata
 */
class Scrapper {
    /**
     * @var Collection|null
     */
    public $collection;
    /**
     * @var bool
     */
    public $wasScrapped;

    public function __construct() {
        $this->collection = new Collection();
    }

    /**
     * Basically fetches the html from the url.
     * The function then returns a collection class or null depending on the result.
     * @param string $url
     */
    public function scrape($url) {
        $html             = $this->get_url($url);
        $scrapped         = isset($html) ? new HtmlDocument($html) : null;
        $this->collection = !$scrapped ? null : $this->collection->source($scrapped, $url);

        // free memory
        unset($scrapped);

        return ($this->wasScrapped = ($this->collection !== null));
    }
    /**
     * Fetches the html contents of the url
     * @param string $url
     * @param int $javascript_loop 
     * @param int $timeout
     * @return string
     */
    private function get_url($url, $javascript_loop = 0, $timeout = 5) {
        $url = str_replace("&amp;", "&", urldecode(trim($url)));

        $cookie = tempnam("/tmp", "CURLCOOKIE");
        $ch     = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); # required for https urls
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $content  = curl_exec($ch);
        $response = curl_getinfo($ch);
        curl_close($ch);

        if ($response['http_code'] == 301 || $response['http_code'] == 302) {
            ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

            if ($headers = get_headers($response['url'])) {
                foreach ($headers as $value) {
                    if (substr(strtolower($value), 0, 9) == "location:")
                        return $this->get_url(trim(substr($value, 9, strlen($value))));
                }
            }
        }

        if ((preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value)) && $javascript_loop < 5) {
            return $this->get_url($value[1], $javascript_loop + 1);
        } else {
            return $content;
        }
    }
}