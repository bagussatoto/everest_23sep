<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//include_once "MdlMaster.php";

class MdlMailNotif_direct extends MdlMother
{

    //put your code here
    protected $fields = array();
    protected $indexFieldName;
    protected $fieldContents = array();
    protected $historyEnabled;
    protected $validationRules = array();
    protected $tableName;
    protected $unlistedFields = array();
    protected $listedFields = array();
    protected $relations = array(); //===isi array berupa data model
    protected $selfRelation = false;
    protected $selfCategorySpec = array();
    protected $filters = array();
    protected $child;
    protected $sortby;
    protected $customLink = array();
    private $recipientType;

    public function getRecipientType()
    {
        return $this->recipientType;
    }

    public function setRecipientType($recipientType)
    {
        $this->recipientType = $recipientType;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

// <editor-fold defaultstate="collapsed" desc=" getter-setter ">
    public function getCustomLink()
    {
        return $this->customLink;
    }

    public function setCustomLink($customLink)
    {
        $this->customLink = $customLink;
    }

    public function getIndexFieldName()
    {
        return $this->indexFieldName;
    }

    public function setIndexFieldName($indexFieldName)
    {
        $this->indexFieldName = $indexFieldName;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }

    public function getSelfRelation()
    {
        return $this->selfRelation;
    }

    public function getSelfCategorySpec()
    {
        return $this->selfCategorySpec;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getChild()
    {
        return $this->child;
    }

    public function getSortby()
    {
        return $this->sortby;
    }

    public function setSelfRelation($selfRelation)
    {
        $this->selfRelation = $selfRelation;
    }

    public function setSelfCategorySpec($selfCategorySpec)
    {
        $this->selfCategorySpec = $selfCategorySpec;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function setChild($child)
    {
        $this->child = $child;
    }

    public function setSortby($sortby)
    {
        $this->sortby = $sortby;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getFieldContents()
    {
        return $this->fieldContents;
    }

    public function getHistoryEnabled()
    {
        return $this->historyEnabled;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getUnlistedFields()
    {
        return $this->unlistedFields;
    }

    public function getRelations()
    {
        return $this->relations;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function setFieldContents($fieldContents)
    {
        $this->fieldContents = $fieldContents;
    }

    public function setHistoryEnabled($historyEnabled)
    {
        $this->historyEnabled = $historyEnabled;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setUnlistedFields($unlistedFields)
    {
        $this->unlistedFields = $unlistedFields;
    }

    public function setRelations($relations)
    {
        $this->relations = $relations;
    }

// </editor-fold>

    public function __construct()
    {
        parent::__construct();
        $this->tableName = "notif";
        $this->indexFieldName = "id";
        $this->filters = array();
        $this->sortBy = "name";
        $this->fields = array(
            "id"            =>
                array(
                    "type"      => "int",
                    "label"     => "id",
                    "fieldName" => "id",
                    "inputType" => "hidden",
                ),
            "type"          =>
                array(
                    "type"      => "varchar",
                    "label"     => "type",
                    "fieldName" => "type",
                    "length"    => "16",
                    "unique"    => true,
                ),
            "recipientType" =>
                array(
                    "type"      => "varchar",
                    "label"     => "type",
                    "fieldName" => "recipient_type",
                    "length"    => "16",
                    "unique"    => true,
                ),
            "title"         =>
                array(
                    "type"      => "varchar",
                    "label"     => "type",
                    "fieldName" => "type",
                    "length"    => "16",
                    "unique"    => true,
                ),
            "id_user"       =>
                array(
                    "type"      => "varchar",
                    "label"     => "nama",
                    "fieldName" => "id_user",
                    "length"    => "16",
                    "unique"    => true,
                ),
            "email"         =>
                array(
                    "type"      => "varchar",
                    "label"     => "email",
                    "fieldName" => "email",
                    "length"    => "32",
                ),
            "dtime"         =>
                array(
                    "type"      => "datetime",
                    "label"     => "written",
                    "fieldName" => "dtime",
                    "inputType" => "hidden",
                ),
            "read"          =>
                array(
                    "type"      => "char",
                    "label"     => "dibaca",
                    "fieldName" => "read",
                    "length"    => "1",
                ),
        );
        $this->validationRules = array(
            "title" => array("required"),
            "type"  => array("required"),
        );
        $this->listedFields = array("id_user", "title", "email", "dtime", "read");
    }

    public function send($params)
    {
        //Load email library
        $this->load->library('email');

//SMTP & mail configuration
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => 'mgk.mailer.daemon@gmail.com',
            'smtp_pass' => 'aslkaslk',
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
        );
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

//Email content
//         $htmlContent = "<h2>" . $params['title'] . "</h2>";
        $htmlContent = $params['body'];

        $htmlContent .="<br>";
        $htmlContent .="<div style='padding:4px;color:#003377;font-size:13px;background-image: linear-gradient(to right, rgba(0,0,0,0), rgba(80,170,255,1));color:#005689;border-top:1px #4488ab solid;'>";
        $htmlContent .= "Pemberitahuan ini dikirim otomatis pada jam ".date("H:i").", anda tak perlu memberi balasan.<br>";
        $htmlContent .= "<a href='" . base_url() . "' style='font-size:16px;padding:3px;background:#009900;color:#f0f0f0;border:1px #009a00 solid;border-radius:6px;text-decoration:none;'>Sentuh di sini</a> untuk menuju ke " . $this->config->item('appConfig')['appName'] . " menggunakan browser anda";
        $htmlContent .="</div>";


        $this->email->to($params['email']);
        //$this->email->from('sender@example.com', $this->config->item('appConfig')['appName']);
        $this->email->from('sender@example.com', $this->session->login['name']."@".$this->config->item('appConfig')['appName']);
        $this->email->subject($params['title']);
        $this->email->message($htmlContent);

//Send email
        $this->email->send();
    }

}
