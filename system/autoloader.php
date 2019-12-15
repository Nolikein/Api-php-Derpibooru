<?php

spl_autoload_register(
    function ($className) {
        $classpath = trim($classpath, '\\');
        $classpath = explode('\\', $className);

        if (count($classpath) > 1) {
            $className = $classpath[sizeof($classpath)-1];
            array_pop($classpath);
            $classpath = implode($classpath, '\\');

            if ($classpath == 'Nolikein\Api') {
                include(__DIR__.'/derpibooru/'.$className.'.class.php');
            } elseif ($classpath == 'Nolikein\Api\components') {
                include(__DIR__.'/derpibooru/components/'.$className.'.class.php');
            }
        }
    }
);
