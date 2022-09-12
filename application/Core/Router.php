<?php

namespace Core;

use UnexpectedValueException;

/**
 * Request router
 */
class Router {

    /**
     * Default controller name
     * @var string
     */
    private $defaultController;

    /**
     * Default action name
     * @var string
     */
    private $defaultAction;

    /**
     * Controllers search direcory
     * @var type
     */
    private $controllerPath;

    /**
     * Router constructor
     */
    public function __construct() {
        $this->defaultController = Config::get('DEFAULT_CONTROLLER') ? Config::get('DEFAULT_CONTROLLER') : 'index';
        $this->defaultAction = Config::get('DEFAULT_ACTION') ? Config::get('DEFAULT_CONTROLLER') : 'index';

        $this->controllerPath = trim(Config::get('CONTROLLER_DIR'), '/') . '/';
    }

    /**
     * Resolve current URL and redirect request to specific controller
     * @param string $url Site URL
     */
    public function resolve($url) {

        $filteredUrl = filter_var($url, FILTER_SANITIZE_URL);
        $urlPath = trim(parse_url($filteredUrl, PHP_URL_PATH), '/');
        $routes = empty($urlPath) ? array() : explode('/', $urlPath);

        $controllerName = ucwords(isset($routes[0]) ? $routes[0] : $this->defaultController);
        $actionName = ucwords(isset($routes[1]) ? $routes[1] : $this->defaultAction);

        $queryString = parse_url($filteredUrl, PHP_URL_QUERY);
        $params = array();
        parse_str($queryString, $params);

        $this->route($controllerName, $actionName, $params);
    }

    /**
     * Redirect request to specific controller
     * @param string $controller Controller name
     * @param string $action Action name
     * @param array $params Array of requested parameters. Default - empty array
     * @throws UnexpectedValueException
     */
    public function route($controller, $action, $params = array()) {

        $controllerName = $controller . 'Controller';
        $controllerPath = $this->controllerPath . $controllerName . '.php';

        $actionName = $action . 'Action';
        if (file_exists($controllerPath)) {

            include $controllerPath;
            if (method_exists($controllerName, $actionName)) {
                $controller = new $controllerName($controllerName, $actionName);
                call_user_func(array($controller, $actionName), $params);
            } else {

                throw new UnexpectedValueException('Action "' . $actionName . '" does not exist in controller "' . $controllerName . '"');
            }
        } else {
            throw new UnexpectedValueException('Controller "' . $controllerName . '" does not exist');
        }
    }

    /**
     * Redirect request to default controller and action
     */
    public function redirectToDefaultPage() {
        $host = ($_SERVER['HTTPS'] ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';
        header('Location: ' . $host . $this->defaultController . '/' . $this->defaultAction);
        die();
    }

    /**
     * Show 404 page
     */
    public function page404() {
        http_response_code(404);
        die();
    }

}
