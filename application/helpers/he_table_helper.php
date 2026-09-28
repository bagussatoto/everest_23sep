<?php

/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 09/07/17
 * Time: 19:58
 */
class Table
{

    private $content;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    public function openTable($params = array())
    {
        $tmp = "<table";
        if (count($params) > 0) {
            foreach ($params as $k => $v) {
                $tmp .= " $v ";
            }
        }
        $tmp .= ">\n";
        $this->content .= $tmp;
        return $tmp;
    }

    public function addHeaderRow($rowContent = array())
    {
        if (count($rowContent) > 0) {
            $tmp = "<tr bgcolor=#c5c5c5>";
            foreach ($rowContent as $rc) {
                if (is_array($rc)) {
                    $colCounter = 0;
                    foreach ($rc as $col) {

                        if ($colCounter == 0) {
                            $tmp .= "<th style='border-bottom:1px #c0c0c0 solid;' ";
                            $colContent = $rc[$colCounter];
                        }
                        else {
                            $tmp .= $rc[$colCounter] . " ";
                        }
                        $colCounter++;
                    }
                    $tmp .= ">$colContent</th>";
                }
                else {
                    $tmp .= "<th style='border-bottom:1px #c0c0c0 solid;'>";
                    $tmp .= "$rc";
                    $tmp .= "</th>";
                }
            }
            $tmp .= "</tr>\n";
        }
        $this->content .= $tmp;
        return $tmp;
    }

    public function addRow($rowContent = array())
    {
        $tmp = "";
        if (sizeof($rowContent) > 0) {
            $tmp .= "<tr>";
            foreach ($rowContent as $rc) {
                if (is_array($rc)) {
                    $colCounter = 0;
                    foreach ($rc as $col) {

                        if ($colCounter == 0) {
                            $tmp .= "<td ";
                            $colContent = $rc[$colCounter];
                        }
                        else {
                            $tmp .= $rc[$colCounter] . " ";
                        }
                        $colCounter++;

                    }
                    $defaultAlign = is_numeric($colContent) ? " align=right" : " align=left";
                    $tmp .= " $defaultAlign>$colContent</td>";
                }
                else {
                    $defaultAlign = is_numeric($rc) ? " align=right" : " align=left";
                    $tmp .= "<td $defaultAlign>";
                    $tmp .= "$rc";
                    $tmp .= "</td>";
                }
            }
            $tmp .= "</tr>\n";
        }
        $this->content .= $tmp;
        return $tmp;
    }

    public function addSpanRow($rowContent = array(), $spanSize = 2, $align = "left")
    {
        $tmp = "";
        if (count($rowContent) > 0) {
            $tmp .= "<tr>";
            foreach ($rowContent as $rc) {
                if (is_array($rc)) {
                    foreach ($rc as $col) {
                        $tmp .= "<td align=$align colspan=$spanSize>";
                        $tmp .= "$col";
                        $tmp .= "</td>";
                    }
                }
                else {
                    $tmp .= "<td align=$align colspan=$spanSize>";
                    $tmp .= "$rc";
                    $tmp .= "</th>";
                }
            }
            $tmp .= "</tr>\n";
        }
        $this->content .= $tmp;
        return $tmp;
    }

    public function closeTable()
    {
        $tmp = "</table>\n";
        $this->content .= $tmp;
        return $tmp;
    }

}
