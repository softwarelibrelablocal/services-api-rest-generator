<?php



abstract class Singleton {
    
    private static $_instances = array();

    /**
     * @return self
     */
    public static function getInstance() {
        $class = get_called_class();
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }


}