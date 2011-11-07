<?php
/**
 * CompleXml_Application class
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Application
    {

    private static $_current_controller = null;

    public static function runController(CompleXml_Router $RouterObject)
    {
        $settings = CompleXml_Config::readComponentSettings(__CLASS__);
        $controller = sprintf($settings['controller_template'], $RouterObject->Request->getString('controller', $settings['default_controller']));
        if (!file_exists($settings['controllers'].DIRECTORY_SEPARATOR.$controller.'.php')){
            throw new CompleXml_Application_ControllerNotFoundException( $controller.'('.$settings['controllers'].DIRECTORY_SEPARATOR.$controller.'.php'.') not found' );
        }
        if (!class_exists($controller)){
            require_once $settings['controllers'].DIRECTORY_SEPARATOR.$controller.'.php';
        }
        $ref_controller = new CompleXml_Reflection_Class($controller);
        $action = sprintf($settings['action_template'], $RouterObject->Request->getString('action', $settings['default_action']));
        if (!$ref_controller->hasMethod($action)){
            throw new CompleXml_Application_ActionNotFoundException('Action '.$action.' not found at '.$controller);
        }
        $ControllerObject = new $controller($RouterObject);
        $ControllerObject->View->setTemplate($RouterObject->Request->getString('controller', $settings['default_controller']).'/'.$RouterObject->Request->getString('action', $settings['default_action']));
        $ControllerObject->View->setLocale($RouterObject->Request->getString('locale', $settings['default_locale']));
        $ControllerObject->init();
        $listeners = array();
        $listeners_name = (array) $settings['listeners'];
        foreach ($listeners_name as $listener) {
            $ListenerObject = new $listener($ControllerObject);
            $ListenerObject->beforeAction($action);
            $listeners[] = $ListenerObject;
        }
        $ControllerObject->$action();
        foreach ($listeners as $ListenerObject) {
            $ListenerObject->afterAction($action);
        }
        self::$_current_controller = $ControllerObject;
    }

    public static function run()
    {
        $settings = CompleXml_Config::readComponentSettings(__CLASS__);
        $cache = array();
        $cache_hash = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['REQUEST_METHOD'];
        if ($settings['router_cache']){
            $CacheObject = new CompleXml_Cache($settings['cache_driver']);
            $cache = (array) $CacheObject->get($cache_hash);
        }
        if (isset($cache[$cache_hash])){
            $RouterObject = $cache[$cache_hash]['router'];
            $route = $cache[$cache_hash]['route'];
            $route_args = $cache[$cache_hash]['route_args'];
            $RouterObject->init();
            $route_result = (array) call_user_func_array(array($RouterObject, $route), $route_args);
            $RouterObject->Request->setValues($route_result, CompleXml_Dataset::UPDATE_SOURCE);
            try{
                CompleXml_Application::runController($RouterObject);
            }catch (CompleXml_Exception $e){
                $RouterObject->Request->setValue('controller', 'error');
                $RouterObject->Request->setValue('action', 'notfound');
                $RouterObject->Request->setValue('error', $e->getMessage());
                $RouterObject->Request->setValue('exception', $e);
                try{
                    CompleXml_Application::runController($RouterObject);
                }catch (Exception $e){
                    echo '<h1>Error 500</h1>';
                    die();
                }
            }
        }else{
            $workspace_reflection = new CompleXml_Reflection_Workspace();
            $router = $workspace_reflection->getClass('CompleXml_Router', array('domain'=>$_SERVER['HTTP_HOST']));
            if (is_null($router)){
                $router = new CompleXml_Reflection_Class($settings['default_router']);
            }
            $route = $router->getMethodByAnnotation(array('uri'=>strtolower($_SERVER['REQUEST_URI'])));
            $router_name = $router->getName();
            $RouterObject = new $router_name;
            $router_params = CompleXml_Reflection_Annotations::getParametrs($router->getAnnotations(), array('domain'=>$_SERVER['HTTP_HOST']));
            $RouterObject->Request->setValues($router_params, CompleXml_Dataset::UPDATE_SOURCE);
            $RouterObject->init();
            if (!is_null($route)){
                $route_params = CompleXml_Reflection_Annotations::getParametrs($route->getAnnotations(), array('uri'=>strtolower($_SERVER['REQUEST_URI'])));
                $args = $route->getParameters();
                $route_args = array();
                foreach ($args as $param){
                    $route_args[] = @$route_params[$param->getName()];
                }
                $RouterObject->Request->setValues($route_params, CompleXml_Dataset::UPDATE_SOURCE);
                $route_result = (array) call_user_func_array(array($RouterObject, $route->getName()), $route_args);
                $RouterObject->Request->setValues($route_result, CompleXml_Dataset::UPDATE_SOURCE);
            }
            try{
                CompleXml_Application::runController($RouterObject);
                if ($settings['router_cache']){
                    $cache[$cache_hash]['router']
                            = $RouterObject;
                    $cache[$cache_hash]['route']
                            = $route->getName();
                    $cache[$cache_hash]['route_args']
                            = $route_args;
                    $CacheObject->set($cache_hash, $cache, false, array('Application', 'Router'));
                }
            }catch (CompleXml_Exception $e){
                $RouterObject->Request->setValue('controller', 'error');
                $RouterObject->Request->setValue('action', 'notfound');
                $RouterObject->Request->setValue('error', $e->getMessage());
                $RouterObject->Request->setValue('exception', $e);

                try{
                    CompleXml_Application::runController($RouterObject);
                }catch (Exception $e){
                    echo '<h1>Error 500</h1>';
                    die();
                }
            }
        }

        if (!is_null(self::$_current_controller)){
            $settings = CompleXml_Config::readComponentSettings(__CLASS__);
            $outputs = (array) $settings['outputs'];
            foreach ($outputs as $output){
                $is_out = call_user_func(array($output, 'execute'), self::$_current_controller);
                if ($is_out){
                    break;
                }
            }
        }
    }
}