<?php

namespace NewsAgregator;

use NewsAgregator\Domain\DataSourceInterface;
use NewsAgregator\DataSource\RssSource;
use UnexpectedValueException;

/**
 * Interface for working with NewsAgregator module
 */
class NewsAgregator {

    /**
     * Mapping of various sources by access methods and parameters
     * (in format [ident=>[name=>.., method=>.., url=>...]])
     * @var array|null
     */
    private static $map;

    /**
     * Get mapping array in format [ident=>[name=>.., method=>.., url=>...]]
     * @return array
     */
    protected static function getMapping() {

        if (empty(self::$map)) {
            $xml = simplexml_load_file(__DIR__ . '/Mapping.xml');

            self::$map = array();

            foreach ($xml->dataSource as $dataSource) {
                $data = (array) $dataSource;
                $ident = $data['ident'];
                unset($data['ident']);
                self::$map[$ident] = $data;
            }
        }

        return self::$map;
    }

    /**
     * Get data source by name
     * @param string $sourceName Name of data source
     * @return DataSourceInterface
     */
    protected static function getDataSource($sourceName) {

        $map = self::getMapping();

        switch ($map[$sourceName]['method']) {
            case 'rss':
                $source = new RssSource($map[$sourceName]['url']);
                break;
            default:
                throw new UnexpectedValueException('Unsupported source read method. Given: ' . $map[$sourceName]['method']);
        }

        return $source;
    }

    /**
     * Get list of available sources
     * @return array Associative array of sources in format
     * ['source identificator'=>'source name']
     */
    public static function getSources() {

        $map = self::getMapping();

        $sourceList = array();

        foreach ($map as $ident => $source) {
            $sourceList[$ident] = $source['name'];
        }

        return $sourceList;
    }

    /**
     * Get data from specific source
     * @param string $sourceName Source name
     * @param int $limit Maximum number of records (minimum 0, default 10)
     * @param int $offset Offset from the begining (minimum 0, default 0)
     */
    public static function getData($sourceName, $limit = 10, $offset = 0) {

        $source = self::getDataSource($sourceName);
        $newsArray = $source->getData($limit, $offset);

        $newsData = array();

        foreach ($newsArray as $news) {
            $newsData[] = Domain\News::toArray($news);
        }

        return $newsData;
    }

    /**
     * Get data from specific source by last record guid
     * @param string $sourceName Source name
     * @param string $lastGuid Identifier of the last available record
     * @param int $limit Maximum number of records (minimum 0, default 10)
     */
    public static function getDataFrom($sourceName, $lastGuid, $limit) {

        $source = self::getDataSource($sourceName);
        $newsArray = $source->getDataFrom($lastGuid, $limit);

        $newsData = array();

        foreach ($newsArray as $news) {
            $newsData[] = Domain\News::toArray($news);
        }

        return $newsData;
    }

}
