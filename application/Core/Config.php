<?php

namespace Core;

use UnexpectedValueException;
use BadMethodCallException;

/**
 * Object for storing and working with configuration values
 */
class Config {

    /**
     * Array of values from config file
     * @var array
     */
    private static $values;

    /**
     * Initialization
     * @param string $confPath Path to config
     * @throws UnexpectedValueException
     */
    public static function init($confPath) {

        $config = parse_ini_file($confPath);
        if (!$confPath) {
            throw new UnexpectedValueException("Can't read configuration file. Given: " . $confPath);
        }

        self::$values = $config;
    }

    /**
     * Get value from config file
     * @param string $valueName Value name from config file
     * @return mixed
     */
    public static function get($valueName) {
        if (!self::$values) {
            throw new BadMethodCallException("Configuration not initialized. Use Config::Init() before calling this method.");
        }
        return isset(self::$values[$valueName]) ? self::$values[$valueName] : false;
    }

}
