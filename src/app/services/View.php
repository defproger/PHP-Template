<?php

namespace app\services;

class View
{
    /**
     * Method for rendering a view.
     *
     * @param string $template Name of the template file (without the .php extension)
     * @param array $data Array of data to pass to the template
     * @param callable|null $onMissingTemplate Function to call if the template is not found
     */
    public function render($template, $data = [], $onMissingTemplate = null)
    {
        $templatePath = __DIR__ . '/../../views/' . $template . '.php';

        if (!file_exists($templatePath)) {
            if (is_callable($onMissingTemplate)) {
                return $onMissingTemplate($template, $data);
            } else {
                throw new \Exception("Template '{$template}' not found.");
            }
        }

        extract($data);

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }

    /**
     * Method for directly displaying a template with data output.
     *
     * @param string $template Name of the template file
     * @param array $data Data to pass to the template
     * @param callable|null $onMissingTemplate Function to call if the template is not found
     */
    public function display($template, $data = [], $onMissing = null)
    {
        echo $this->render($template, $data, $onMissing);
    }
}