<?php

namespace Quill;

class View {

    private $templateDirectory;
    private static $share = array();

    function __construct($directoryPath = '') {

        $this->templateDirectory = $directoryPath ? $directoryPath : __DIR__ . '/../../app/views';
    }

    public function setTemplateDirectory($directoryPath) {

        $this->templateDirectory = $directoryPath;
    }

    public function getPart($path) {

        return $this->getTemplateDirectory() . $path;
        
    }
    public function getTemplateDirectory() {

        if (!empty($this->templateDirectory)) {

            return $this->templateDirectory;
        } else {

            throw new \RuntimeException('Template directory not set');
        }
    }

    public function getTemplatePath($template) {

        return $this->getTemplateDirectory() . DIRECTORY_SEPARATOR . $template;
    }

   
    public function make($template, $data = array(), $print = true) {       

        $templatePathName = $this->getTemplatePath($template);

        if (!is_file($templatePathName)) {

            throw new \RuntimeException("Can't make view `$templatePathName` because the template does not exist");
        }
        if(!empty(self::$share)) {

            $data = array_merge(self::$share, $data);
        }
        
        extract($data);

        ob_start();

        require $templatePathName;

        if ($print) {

            echo ob_get_clean();
        } else {

            return ob_get_clean();
        }
    }
    
    static public function share($data) {
        
        self::$share = array_merge(self::$share, $data);
    }

}
