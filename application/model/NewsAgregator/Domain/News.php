<?php

namespace NewsAgregator\Domain;

use UnexpectedValueException;
use DateTime;

/**
 * News
 */
class News {

    /**
     * Identifier
     * @var string
     */
    private $guid;

    /**
     * News Title
     * @var string
     */
    private $title;

    /**
     * Url path
     * @var string
     */
    private $url;

    /**
     * Description (news content)
     * @var string
     */
    private $desc;

    /**
     * Publication date
     * @var DateTime|null
     */
    private $publicationDate;

    /**
     * Copyright
     * @var string|null
     */
    private $copyright;

    /**
     * News author
     * @var string|null
     */
    private $author;

    /**
     * News constructor
     * @param string $title
     * @param string $url
     * @param string $desc
     */
    public function __construct($title, $url, $desc) {

        $this->setTitle($title);
        $this->setUrl($url);
        $this->setDescription($desc);

        $this->guid = (string) $url;
    }

    /**
     * Set news title
     * @param string $title
     */
    protected function setTitle($title) {
        $this->title = (string) $title;
    }

    /**
     * Set news url
     * @param string $url Url to news page
     * @throws UnexpectedValueException
     */
    protected function setUrl($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new UnexpectedValueException('Invalid page url. Given: "' . $url . '"');
        }
        $this->url = (string) $url;
    }

    /**
     * Set news description
     * @param string $desc
     */
    protected function setDescription($desc) {
        $this->desc = (string) $desc;
    }

    /**
     * Set author name
     * @param string $author Author name
     */
    public function setAuthor($author) {
        $this->author = (string) $author;
    }

    /**
     * Set new publication date
     * @param string $date Publication date in string format
     */
    public function setPublicationDate($date) {
        $this->publicationDate = new DateTime($date);
    }

    /**
     * Set source copyright
     * @param string $copyright Copyright info
     */
    public function setCopyright($copyright) {
        $this->copyright = (string) $copyright;
    }

    /**
     * Convert news to array
     * @param News $news News object for convertion
     * @return array
     */
    public static function toArray(News $news) {
        $arr = array();
        $arr['guid'] = $news->guid;
        $arr['title'] = $news->title;
        $arr['url'] = $news->url;
        $arr['desc'] = $news->desc;
        $arr['copyright'] = $news->copyright;
        $arr['author'] = $news->author;
        $arr['publicationDate'] = $news->publicationDate->format('Y-m-d H:i:s');
        return $arr;
    }

}
