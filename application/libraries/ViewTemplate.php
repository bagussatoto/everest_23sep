<?php

/**
 * Created by JetBrains PhpStorm.
 * User: azes
 * Date: 5/9/12
 * Time: 11:56 AM
 * To change this template use File | Settings | File Templates.
 */
class ViewTemplate
{

    var $TAGS = array();
    var $THEME;
    var $CONTENT;

    function defineTag($tagName, $varName)
    {
        $this->TAGS[$tagName] = $varName;
    }

    function defineTheme($themeName)
    {
        $this->THEME = $themeName;
    }

    function parse()
    {
        $this->CONTENT = file($this->THEME);
        $this->CONTENT = implode("", $this->CONTENT);
        while (list($key, $val) = each($this->TAGS)) {
            $this->CONTENT = str_replace($key, $val, $this->CONTENT);
        }
    }

    function printTheme()
    {
        //echo $this->CONTENT;
        $tmpArr = explode(" ", $this->CONTENT);

        foreach ($tmpArr as $tmp) {
            echo $tmp . " ";
            flush();
            ob_flush();
        }
    }

    function replaceFile($file)
    {
        //if (file_exists($file))
        //{
        $fd = fopen($file, "w+") or die("Error Accessing File........");
        $num = fwrite($fd, "$this->CONTENT") or die("Error replacing........");;
        fclose($fd);
        //}
    }

}
