<?php

class CJstats
{
    // Variables exclusive to this class
    protected $html = "";
    protected $domParser = null;
    protected $xpath = null;

    // The first things that's executed when we create a new instance of this class
    function __construct($url)
    {
        $this->html = file_get_contents($url);
        $this->parseDOM();
    }

    /**
     * Get the headline of the article defined by .asset-headline
     */
    public function getHeadline()
    {
        $node = $this->getElementsByClass('asset-headline');
        if ($node->length > 0) {
            return $node->item(0)->textContent;
        } else {
            return null;
        }
    }

    /**
     * Get all h2 tags from the article body defined by .presto-h2
     */
    public function getSubHeads()
    {
        $node = $this->getElementsByClass('presto-h2');
        if ($node->length > 0) {
            $nodes = [];
            for ($i = 0; $i < $node->length; $i++) {
                $nodes[] = $node->item($i)->textContent;
            }
            return $nodes;
        } else {
            return [];
        }
    }

    /** 
     * Get all inline images from the article body defined by .image-asset
     */
    public function getInlinePhotos()
    {
        $node = $this->getElementsByClass('image-asset');
        if ($node->length > 0) {
            $nodes = [];
            for ($i = 0; $i < $node->length; $i++) {
                $img = $node->item($i)->getElementsByTagName('img');
                if ($img->length > 0) {
                    $nodes[] = $img->item(0)->getAttribute('src');
                } else {
                    $nodes[] = "Unknown image source";
                }
            }
            return $nodes;
        } else {
            return [];
        }
    }

    /**
     * Get all inline videos from the article body defined by .story-oembed-type-video
     */
    public function getInlineVideos()
    {
        $node = $this->getElementsByClass('story-oembed-type-video');
        if ($node->length > 0) {
            $nodes = [];
            for ($i = 0; $i < $node->length; $i++) {
                $iframe = $node->item($i)->getElementsByTagName('iframe');
                if ($iframe->length > 0) {
                    $nodes[] = $iframe->item(0)->getAttribute('src');
                } else {
                    $nodes[] = "Unknown video source";
                }
            }
            return $nodes;
        } else {
            return [];
        }
    }

    /**
     * Return an associative array of items pulled from the article
     */
    public function getStats()
    {
        return [
            "headline" => $this->getHeadline(),
            "subHeads" => $this->getSubHeads(),
            "inlinePhotos" => $this->getInlinePhotos(),
            "inlineVideos" => $this->getInlineVideos()
        ];
    }

    /**
     * Return a count of items pulled from the article
     */
    public function getCounts()
    {
        return [
            "subHeads" => count($this->getSubHeads()),
            "inlinePhotos" => count($this->getInlinePhotos()),
            "inlineVideos" => count($this->getInlineVideos())
        ];
    }

    /**
     * PRIVATE - parse the DOM tree using DOMDocument
     */
    private function parseDOM()
    {
        $this->domParser = new DOMDocument();
        libxml_use_internal_errors(true);
        $this->domParser->loadHTML(mb_convert_encoding($this->html, 'HTML-ENTITIES', 'UTF-8'));

        $this->xpath = new DomXPath($this->domParser);
    }

    /**
     * PROTECTED - query elements by classname using DomXPath query constructor
     */
    protected function getElementsByClass($classname)
    {
        return $this->xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    }
}
