<?php

class Router {
    
    private $routes; 
    
    public function __construct() {
        $routesPath = ROOT.'/config/routes.php';
        $this->routes = include ($routesPath);
    }
    
    /*
     * Returns request string
     * @return string
     */
    private function getURI() {
        
        if (!empty($_SERVER['REQUEST_URI'])){
            return trim($_SERVER['REQUEST_URI'], '/'); 
        }
        
    }

    public function run(){
        //print_r($this->routes);
        //echo '<br/>'.'Class Router, method run';
        //echo "test"; 
        
        // Получить строку запроса
        $uri = $this->getURI(); 
        //echo $uri;
        
        // Проверить наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path){
            //echo "<br>$uriPattern -> $path";
        
            // Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)){
                
                //echo '+';
                //echo $path;
                
                // Определить какой контроллер и 
                // action обрабатывает запрос. 
                $segments = explode('/', $path); 
                //echo '<pre>';
                //print_r($segments); 
                //echo '</pre>';
                
                $controllerName = array_shift($segments).'Controller'; 
                $controllerName = ucfirst($controllerName); 
                
                $actionName = 'action'.ucfirst(array_shift($segments)); 
                
                //echo $actionName;
                
                //echo '<br>Класс: '.$controllerName; 
                //echo '<br>Метод: '.$actionName;
                
                
                // Подключить файл класса-контроллера. 
                $controllerFile = ROOT.'/controllers/'.
                        $controllerName.'.php';
                
                if(file_exists($controllerFile)){
                    include_once($controllerFile);
                }
                
                // Создать объект, вызвать метод (т.е. action) 
                $controllerObject = new $controllerName; 
                $result = $controllerObject->$actionName(); 
                
                if ($result != null) {
                    break;
                }
                
                
            } 
            
        }
        // Если есть совпадение, определить какой контроллер
        // и action обрабатывают запрос. 
        
        // Создать объект, вызвать метод (т.е. action) 
        
    }
    
}
