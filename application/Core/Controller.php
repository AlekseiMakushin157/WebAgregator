<?php

namespace Core;

/**
 * Simple controller class
 */
class Controller {

    /**
     * Controller name
     * @var string
     */
    protected $controllerName;
    
    /**
     * Action name
     * @var string
     */
    protected $actionName;

    /**
     * Controller constructor
     * @param string $controllerName Controller name
     * @param string $actionName Action name
     */
    public function __construct($controllerName, $actionName) {

        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
    }

}
