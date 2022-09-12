<?php

namespace NewsAgregator\Domain;

/**
 * Interface for data source objects
 */
interface DataSourceInterface {

    /**
     * Get news from source
     * @param int $limit Maximum number of records (minimum 0, default 10)
     * @param int $offset Offset from the begining (minimum 0, default 0)
     * @return News
     */
    public function getData($limit = 10, $offset = 0);

    /**
     * Get news from source start from specific record
     * @param string $lastGuid Identifier of the last available record
     * @param int $limit Maximum number of records (minimum 0, default 10)
     * @return News
     */
    public function getDataFrom($lastGuid, $limit = 10);
}
