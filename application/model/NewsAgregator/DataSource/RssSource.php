<?php

namespace NewsAgregator\DataSource;

use NewsAgregator\Domain\DataSourceInterface;
use NewsAgregator\Domain\News;
use UnexpectedValueException;

/**
 * Class for getting data from rss feeds
 */
class RssSource implements DataSourceInterface {

    /**
     * Url to rss feed
     * @var string
     */
    private $url;

    /**
     * Constructor for rss source object
     * @param string $url Path to rss feed
     */
    public function __construct($url) {
        $this->setUrl($url);
    }

    /**
     * Get news from source
     * @param int $limit Maximum number of records (minimum 0, default 10)
     * @param int $offset Offset from the begining (minimum 0, default 0)
     * @return News
     * @throws UnexpectedValueException
     */
    public function getData($limit = 10, $offset = 0) {

        if ($limit < 0) {
            throw new UnexpectedValueException('Limit must be greater or equal 0');
        }

        if ($offset < 0) {
            throw new UnexpectedValueException('Offset must be greater or equal 0');
        }

        $xml = simplexml_load_file($this->url);

        $srcCopyright = $xml->channel->copyright ? (string) $xml->channel->copyright : $xml->channel->link;

        $items = $xml->channel->item;

        $newsArray = array();

        for ($i = $offset; $i < min(($offset + $limit), count($items)); ++$i) {

            $news = new News($items[$i]->title, $items[$i]->link, $items[$i]->description);
            $news->setCopyright($srcCopyright);
            if (isset($items[$i]->author)) {
                $news->setAuthor((string) $items[$i]->author);
            }
            if (isset($items[$i]->pubDate)) {
                $news->setPublicationDate((string) $items[$i]->pubDate);
            }
            $newsArray[] = $news;
        }

        return $newsArray;
    }

    /**
     * Get news from source start from specific record
     * @param string $lastGuid Identifier of the last available record
     * @param int $limit Maximum number of records (minimum 0, default 10)
     * @return News
     * @throws UnexpectedValueException
     */
    public function getDataFrom($lastGuid, $limit = 10) {

        if ($limit < 0) {
            throw new UnexpectedValueException('Limit must be greater or equal 0');
        }

        $xml = simplexml_load_file($this->url);

        $srcCopyright = $xml->channel->copyright ? (string) $xml->channel->copyright : $xml->channel->link;

        $items = $xml->channel->item;

        $newsArray = array();

        $offset = 0;
        foreach ($items as $item) {
            ++$offset;
            /**
             * Use link as guid because this field is unique and requered for rss
             */
            if ($item->link == $lastGuid) {
                break;
            }
        }

        if ($offset == count($items) && $items[$offset - 1]->link != $lastGuid) {
            throw new UnexpectedValueException('Element with given Guid not found in source. Guid: "' . $lastGuid . '"');
        }

        for ($i = $offset; $i < min(($offset + $limit), count($items)); ++$i) {

            $news = new News($items[$i]->title, $items[$i]->link, $items[$i]->description);
            $news->setCopyright($srcCopyright);
            if (isset($items[$i]->author)) {
                $news->setAuthor((string) $items[$i]->author);
            }
            if (isset($items[$i]->pubDate)) {
                $news->setPublicationDate((string) $items[$i]->pubDate);
            }
            $newsArray[] = $news;
        }

        return $newsArray;
    }

    /**
     * Set url to rss feed
     * @param string $url Path to rss feed
     * @throws UnexpectedValueException
     */
    protected function setUrl($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new UnexpectedValueException('Invalid url. Given: ' . $url);
        }

        $this->url = $url;
    }

}
