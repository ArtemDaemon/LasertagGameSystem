<?php

class AutoLoader
{
    const debug = 1;
    public function __construct(){}

    public static function autoload($file)
    {
        $file = str_replace('\\', '/', $file);
        $path = $_SERVER['DOCUMENT_ROOT'];
        $filepath = $_SERVER['DOCUMENT_ROOT'] . '/classes/' . $file . '.php';

        if (file_exists($filepath))
        {
            require_once($filepath);
        }
        else
        {
            $flag = true;
            AutoLoader::recursive_autoload($file, $path, $flag);
        }
    }

    public static function recursive_autoload($file, $path, $flag)
    {
        if (FALSE !== ($handle = opendir($path)) && $flag)
        {
            while (FAlSE !== ($dir = readdir($handle)) && $flag)
            {

                if (strpos($dir, '.') === FALSE)
                {
                    $filepath = $path . '/' . $file . '.php';

                    if (file_exists($filepath))
                    {
                        $flag = FALSE;
                        require_once($filepath);
                        break;
                    }
                    //AutoLoader::recursive_autoload($file, $path, $flag);
                }
            }
            closedir($handle);
        }
    }
}
\spl_autoload_register('AutoLoader::autoload');