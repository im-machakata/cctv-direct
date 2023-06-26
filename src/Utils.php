<?php
namespace CctvDirect;

use simplehtmldom\HtmlNode;

class Utils {
    /**
     * @param string $value
     * @return string
     */
    static function toPrice($value) {
        $value = str_replace(
            [
                'R '
            ],
            [
                ''
            ],
            self::cleanValue($value)
        );
        return trim($value);
    }
    /**
     * @param string $value
     * @return string
     */
    static function cleanValue($value) {
        $value = str_replace(
            [
                '                              ',
                "\n",
                // 'Sale price',
            ],
            [
                ' ',
                '',
                // '',
            ],
            $value
        );
        return trim($value);
    }

    /**
     * Lazy way to remove double quotes from a url.
     */
    static function cleanUrl($url, $prefix = null) {
        $url = str_replace(['//'], ['/'], $url);
        return ($prefix ? $prefix : '') . $url;
    }

    /**
     * Returns an array pre-filled with image urls per size.
     * @param HtmlNode|null $product
     * @return array
     */
    static function getImage($product) {
        $results = array();
        if(!$product){
            return $results;
        }
        $widths  = explode(',', str_replace(['[', ']'], '', $product->getAttribute('data-widths')));
        foreach ($widths as $width) {
            $results[$width] = str_replace('{width}', $width, $product->getAttribute('data-src'));
        }
        return $results;
    }
}