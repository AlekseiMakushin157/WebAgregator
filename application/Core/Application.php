<?php

namespace Core;

use UnexpectedValueException;
use Exception;
use RuntimeException;

/**
 * Simple web application
 */
class Application {

    /**
     * Is aplication initialized?
     * @var boolean
     */
    private $initialized;
    
    /**
     * Real path to application config file
     * @var string
     */
    private $confPath;

    /**
     * Page router
     * @var Router|null
     */
    private $router;

    /**
     * Application constructor
     * @param string $confPath Path to config file
     */
    public function __construct($confPath) {
        
        $this->initialized = false;
        $this->setConfPath($confPath);
    }

    /**
     * Set config file path
     * @param path $confPath Path to config file
     * @throws UnexpectedValueException
     */
    protected function setConfPath($confPath) {
        $path = realpath($confPath);

        if ($path == false) {
            throw new UnexpectedValueException("Can't find config file. Given path: " . $path);
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext != 'ini') {
            throw new UnexpectedValueException("Config file must be 'ini' extension, given '" . $ext . "'");
        }

        $this->confPath = $path;
    }

    /**
     * Initialize application
     */
    public function bootstrap() {
        
        Config::init($this->confPath);
        $this->router = new Router();
        
        $this->initialized = true;
    }

    /**
     * Start web application
     */
    public function run() {

        if(!$this->initialized) {
            throw new RuntimeException("Application hasn't been initiated. call the bootstrap method first.");
        }
        
        try {
            $url = $_SERVER['REQUEST_URI'];
            $this->router->resolve($url);
        } catch (Exception $ex) {
            
            /**
             * @todo Save exception to log file
             */
            $this->router->page404();
        }
    }

}
