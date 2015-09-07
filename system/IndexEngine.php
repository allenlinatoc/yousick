<?php

/**
 * Description of IndexEngine
 */
class IndexEngine {

    const DEFAULT_PAGE = 'home';
    const PAGE_PARAM_INDEX = 0;
    const ACTION_PARAM_INDEX = 1;


    public function __construct()
    {
        /**
         * Startup processes
         */

        // all initialization takes place
        error_reporting(E_ALL | E_STRICT);
        // setting the internal character encoding
        mb_internal_encoding('UTF-8');
        // autoload
        spl_autoload_register(array($this, 'autoload'));
        // initialize constants
        $this->initializeConstants();
    }

    public function autoload($classname)
    {
        $path = 'system/libraries/'.$classname.'.php';
        $path = str_replace('\\', '/', $path);

        require_once $path;
    }

    public function getPage($page=self::DEFAULT_PAGE)
    {
        for ( $index=0,reset($_GET); $index<count($_GET); next($_GET),$index++ )
        {
            $key = key($_GET);
            if ( $index === self::PAGE_PARAM_INDEX )
            {
                $page = $key;
                BREAK;
            }
        }
        RETURN $page;
    }



    public function getBackboneFile($page=self::DEFAULT_PAGE)
    {
        $pagesJson = Utilities\Config::ReadJSON(ROOT_PATH . 'config/pages.json');
        $backbonePage = isset($pagesJson[$page]) ? $pagesJson[$page] : array($page);
        if ( !isset($backbonePage['file']) )
        {
            $backbonePage['file'] = '404';
        }
        RETURN ROOT_PATH.'ui/Backbones/'.$backbonePage['file'].'.php';
    }



    public function getTemplateFile($page=self::DEFAULT_PAGE)
    {
        $pagesJson = Utilities\Config::ReadJSON(ROOT_PATH . 'config/pages.json');
        $defaultTemplate = Utilities\Config::get('main_template', ROOT_PATH.'config/application.ini');
        $template = $defaultTemplate;
        $page = !isset($pagesJson[$page]) ? '404' : $page;
        if ( isset($pagesJson[$page]['template']) )
        {
            $template = $pagesJson[$page]['template'];
        }

        RETURN ROOT_PATH . 'ui/Templates/' . $template . '.template.phtml';
    }



    public function getViewFile($page=self::DEFAULT_PAGE)
    {
        $pagesJson = Utilities\Config::ReadJSON(ROOT_PATH . 'config/pages.json');
        $backbonePage = isset($pagesJson[$page]) ? $pagesJson[$page] : array($page);
        if ( !isset($backbonePage['file']) )
        {
            $backbonePage['file'] = '404';
            return false;
        }
        RETURN ROOT_PATH.'ui/Views/'.$backbonePage['file'].'.phtml';
    }



    public function initializeConstants()
    {
        define( 'BASE_URL',
                Utilities\System::GetBaseURL()
        );

        define( 'CONFIG_PATH',
                ROOT_PATH . 'config/'
        );
    }



    public function run()
    {
        $page = $this->getPage();
        REQUIRE_ONCE $this->getBackboneFile($page);

        ob_start();
        $view = $this->getViewFile($page);
        if ($view !== false)
            REQUIRE_ONCE $view;

        $_VIEW = ob_get_clean();

        REQUIRE_ONCE $this->getTemplateFile($page);
    }

}