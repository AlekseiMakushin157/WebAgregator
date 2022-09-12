<?php

namespace Core;

/**
 * Simple view class
 */
class View {

    /**
     * Template filename
     * @var string
     */
    private $templateName;

    /**
     * View data values
     * @var array
     */
    private $data;

    /**
     * View constructor
     * @param string $templateName Name of template file (with extension)
     */
    public function __construct($templateName) {

        $this->data = array();
        $this->setTemplateName($templateName);
    }

    /**
     * Set template filename value
     * @param string $templateName Name of template file (with extension)
     * @throws UnexpectedValueException
     */
    protected function setTemplateName($templateName) {
        $path = Config::get('VIEW_DIR') . '/' . $templateName;
        if (!file_exists($path)) {
            throw new UnexpectedValueException('Template file not found in view directory. Given: "' . $templateName . '"');
        }
        $this->templateName = $templateName;
    }

    /**
     * Add data to view
     * @param string $key Data identifier
     * @param mixed $value Data value
     */
    public function addData($key, $value) {

        $this->data[$key] = $value;
    }

    /**
     * Render view and return result as a string
     * @return string
     */
    public function render() {

        $path = Config::get('VIEW_DIR') . '/' . $this->templateName;

        ob_start();
        extract($this->data, EXTR_SKIP);
        include $path;
        return ob_get_clean();
    }

}
