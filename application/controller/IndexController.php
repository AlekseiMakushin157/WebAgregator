<?php

use Core\Controller;
use Core\View;
use Core\Config;
use NewsAgregator\NewsAgregator;

/**
 * Logic for Index controller
 */
class IndexController extends Controller {

    /**
     * Base action
     */
    public function IndexAction() {

        $view = new View('index.php');
        $view->addData('sources', NewsAgregator::getSources());
        $view->addData('title', Config::get('SITE_TITLE'));

        $elementsOnPage = array();
        $elementsOnPage[5] = 5;
        $elementsOnPage[10] = 10;
        $elementsOnPage[25] = 25;
        $view->addData('number', $elementsOnPage);

        echo $view->render();
    }

    /**
     * Load data action
     * @param array $args
     */
    public function loadAction($args = array()) {

        if (!isset($args['source']) || !preg_match('/[^A-Za-z0-9]*/', $args['source'])) {
            http_response_code(500);
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(array('message' => 'invalid source value')));
        }

        $source = (string) $args['source'];
        $limit = isset($args['limit']) ? (int) $args['limit'] : 10;
        $offset = isset($args['offset']) ? (int) $args['offset'] : 0;

        $data = NewsAgregator::getData($source, $limit, $offset);

        /**
         * Set values for empty data
         */
        foreach ($data as $key => $row) {
            if (!$row['author']) {
                $data[$key]['author'] = 'Автор не указан';
            }
            if (!$row['publicationDate']) {
                $data[$key]['publicationDate'] = 'Дата публикации не указана';
            }
            if (!$row['copyright']) {
                $data[$key]['copyright'] = 'Владелец не указан';
            }
        }
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Load data starting with given record + 1
     * @param array $args
     */
    public function loadfromAction($args = array()) {

        if (!isset($args['source']) || !preg_match('/[^A-Za-z0-9]*/', $args['source'])) {
            $error = 'invalid or empty "source" value';
        }

        if (!isset($args['last_guid'])) {
            $error = 'invalid or empty "last_guid" value';
        }

        if (isset($error)) {
            http_response_code(500);
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(array('message' => $error)));
        }

        $source = (string) $args['source'];
        $lastGuid = htmlspecialchars($args['last_guid']);
        $limit = isset($args['limit']) ? (int) $args['limit'] : 10;

        $data = NewsAgregator::getDataFrom($source, $lastGuid, $limit);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

}
