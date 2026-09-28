<?php

class MyForm
{

    private $object;
    private $specs = array(); //==form-method,action,target,enctype
    private $content;
    private $validationResults = array();
    private $mode;

    function get_mysqli()
    {
        $db = (array)get_instance()->db;
        return mysqli_connect($db['hostname'], $db['username'], $db['password'], $db['database']);
    }

    //<editor-fold defaultstate="COLLAPSED" desc="getter-setter">
    //region getter-setter=====
    public function getMode()
    {
        return $this->mode;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * @param array $specs
     */
    public function setSpecs($specs)
    {
        $this->specs = $specs;
    }

    /**
     * @return array
     */
    public function getSpecs()
    {
        return $this->specs;
    }

    /**
     * @param array $validationResults
     */
    public function setValidationResults($validationResults)
    {
        $this->validationResults = $validationResults;
    }

    /**
     * @return array
     */
    public function getValidationResults()
    {
        return $this->validationResults;
    }

    /**
     * @param mixed $content
     */
    public function addContent($content)
    {
        $this->content .= $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    //endregion getter-setter=====
    //</editor-fold>

    public function __construct($object, $mode, $specs = array())
    {
        if (!is_array($specs)) {
            die("Form needs an array as a specification!");
        }
        $this->object = $object;
        $this->specs = $specs;
        $this->mode = $mode;
    }

    public function openForm()
    {
        $this->content .= "<form"; // method='".$this->specs['method']."' action='".$this->specs['action']."' target='".$this->specs['target']."'>";
        foreach ($this->specs as $key => $val) {
            $this->content .= " $key='$val' ";
        }
        $this->content .= ">";
    }

    public function closeForm()
    {
        $this->content .= "</form>";
    }

    public function fillForm($o = null)
    {

        if (is_object($this->object)) {
            if (count($this->object->getFields()) > 0) {

                //region ===making table contains inputs from data fields==
                $t = new Table();
                //$t->openTable(array("align=center", "class='table table-bordered'"));
                $t->openTable(array("align=center", "border=0", "width=600"));
                $validCounter = count($this->object->getValidationRules());
                foreach ($this->object->getFields() as $fieldName => $specs) {
                    $fName = isset($specs['fieldName']) ? $specs['fieldName'] : $fieldName;
                    //echo "$fieldName<br>";
                    if (is_array($o) && sizeof($o) > 0) {//===modeedit nih
                        if (isset($specs['inputType'])) {// && $specs['inputType'] == "checkbox") {
                            switch ($specs['inputType']) {
                                case "checkbox":
                                    $fieldData = isset($o[0]->$fName) ? unserialize(base64_decode($o[0]->$fName)) : array();
                                    break;
                                case "texts":
                                    $fieldData = isset($o[0]->$fName) ? unserialize(base64_decode($o[0]->$fName)) : array();
                                    foreach ($specs['dataParams'] as $param) {
                                        $defaultValues[$param] = isset($fieldData[$param]) ? $fieldData[$param] : "";
                                    }
                                    break;
                                case "text":
                                    $defaultValue = isset($o[0]->$fName) ? $o[0]->$fName : "";
                                    break;
                                default:
                                    $defaultValue = isset($o[0]->$fName) ? $o[0]->$fName : "";
                                    $defaultClassStyle = "form-control";
                                    break;

                            }
                        }
                        else {
                            $defaultValue = isset($o[0]->$fName) ? $o[0]->$fName : "";
                            $defaultClassStyle = "form-control";
                        }
                    }
                    else {//==new data, bukan edit
                        //echo "NEW ENTRY<br>";
                        if ($validCounter > 0) {
                            if (isset($specs['inputType'])) {// && $specs['inputType'] == "checkbox") {
                                switch ($specs['inputType']) {
                                    case "checkbox":
                                        $fieldData = $this->object->input->post($fName);
                                        $defaultClassStyle = "form-control";
                                        break;
                                    case "textarea":
                                        //$fieldData=$_POST["$fName"];                                        
                                        $fieldData = $this->object->input->post($fName);
                                        $defaultClassStyle = "form-control";
                                        break;
                                    case "texts":
                                        //$fieldData = $this->object->input->post($fName);
                                        if (isset($specs['dataParams'])) {
                                            foreach ($specs['dataParams'] as $param) {
                                                $fieldData[$param] = $this->object->input->post($fName . "_" . $param);
                                            }
                                        }
                                        $defaultClassStyle = "form-control";
                                        break;

                                    default:
                                        $defaultValue = $this->object->input->post($fName);
                                        $defaultClassStyle = "form-control";
                                        $fieldData = null;
                                        break;
                                }
                            }
                            else {
                                $defaultValue = $this->object->input->post($fName);

                                if (array_key_exists($fName, $this->validationResults)) {
                                    $defaultClassStyle = "form-control";
                                }
                                else {
                                    $defaultClassStyle = "form-control";
                                }
                            }
                        }
                        else {
                            $defaultClassStyle = "form-control";
                            $defaultValue = $this->object->input->post($fName);
                        }
                    }


                    if (isset($specs['inputType'])) {
                        switch ($specs['inputType']) {
                            case "combo":
                                //==kalau model sudah punya data source bawaan, bukan relasi
                                if (isset($specs['dataSource']) && is_array($specs['dataSource'])) {
                                    $txtSelection = "";

                                    foreach ($specs['dataSource'] as $key => $val) {
                                        if (isset($defaultValue)) {
                                            $isSelected = strcmp($key, $defaultValue) == 0 ? "selected" : "";
                                        }
                                        else {
                                            $isSelected = isset($specs['defaultValue']) && $key == $specs['defaultValue'] ? "selected" : "";
                                        }
                                        //$txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                        $txtSelection .= "<option value='" . $key . "' $isSelected>" . $val . "</option>";
                                    }
                                }
                                else {
                                    $txtSelection = "";
                                }

                                //===kalau berrelasi
                                if (isset($specs['reference'])) {
                                    $className = $specs['reference'];
                                    $this->object->load->model($className);
                                    $o2 = new $className;

                                    $dataSource = $o2->lookupAll()->result();

                                    foreach ($dataSource as $key => $dsSpec) {
                                        //$txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                        $relLabel = isset($dsSpec->name) ? $dsSpec->name : $dsSpec->nama;
                                        $idField = $o2->getIndexFieldName();
                                        if (isset($defaultValue)) {
                                            $isSelected = $dsSpec->$idField == $defaultValue ? "selected" : "";
                                        }
                                        else {
                                            $isSelected = isset($specs['defaultValue']) && $dsSpec->id == $specs['defaultValue'] ? "selected" : "";
                                        }
                                        $txtSelection .= "<option value='" . $dsSpec->$idField . "' $isSelected>" . $relLabel . "</option>";
                                    }
                                }


                                $fieldRow = (
                                    array(
                                    ucwords($specs['label']),
                                    "<fieldSet>" .
                                    "<div class='selectInput'>" .
                                    "<select type='" . $specs['type'] . "' name='$fName' id='_$fName' >" .
                                    "<option value='' $isSelected>--pilih--</option>" .
                                    $txtSelection .
                                    "</select>" .
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    "</div>" .
                                    "</fieldSet>"
                                )
                                );
                                break;
                            case "radio":
                                //==kalau model sudah punya data source bawaan, bukan relasi
                                if (isset($specs['dataSource']) && is_array($specs['dataSource'])) {
                                    $txtSelection = "";
                                    foreach ($specs['dataSource'] as $key => $val) {
                                        if (isset($defaultValue)) {
                                            $isSelected = $key == $defaultValue ? "checked" : "";
                                        }
                                        else {
                                            $isSelected = isset($specs['defaultValue']) && $key == $specs['defaultValue'] ? "checked" : "";
                                        }
                                        //$txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                        $txtSelection .= "<label><input type=radio name=$fName value='" . $key . "' $isSelected>" . $val . "</label>&nbsp;<br>";
                                    }
                                }
                                else {
                                    $txtSelection = "";
                                }

                                //===kalau berrelasi
                                if (isset($specs['reference'])) {
                                    $className = $specs['reference'];
                                    $this->object->load->model($className);
                                    $o2 = new $className;

                                    $dataSource = $o2->lookupAll()->result();

                                    foreach ($dataSource as $key => $dsSpec) {
                                        if (isset($defaultValue)) {
                                            $isSelected = $dsSpec->id == $defaultValue ? "checked" : "";
                                        }
                                        else {
                                            $isSelected = isset($specs['defaultValue']) && $dsSpec->id == $specs['defaultValue'] ? "checked" : "";
                                        }

                                        //$txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                        $colName = isset($dsSpec->name) ? $dsSpec->name : $dsSpec->nama;
                                        $txtSelection .= "<label><input type=radio name=$fName value='" . $dsSpec->id . "' $isSelected>" . $colName . "</label>&nbsp;";
                                    }
                                }


                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    "<fieldSet>" .
                                    "<div class='selectInput'>" .
                                    $txtSelection .
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    "</div>" .
                                    "</fieldSet>"
                                )
                                );
                                break;
                            case "checkbox":
                                //==kalau model sudah punya data source bawaan, bukan relasi
                                $txtSelection = "<div class='panel-body'>";
                                $txtSelection .= "<fieldSet>";

                                if (isset($specs['dataSource']) && is_array($specs['dataSource'])) {
                                    foreach ($specs['dataSource'] as $key => $val) {
                                        if (isset($fieldData)) {
                                            $isSelected = in_array($key, $fieldData) ? "checked" : "";
                                        }
                                        else {
                                            $isSelected = "";
                                        }

                                        //$txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                        $strLabel = $fName . "_" . $key;
                                        $txtSelection .= "<label for=$strLabel><input type=checkbox id=$strLabel name=$fName" . "[]" . " value='" . $key . "' $isSelected>" . $val . "</label><br/>";
                                    }
                                }
                                else {//==kalau datasource berupa relasi
                                    if (isset($specs['reference'])) {
                                        $className = $specs['reference'];
                                        $this->object->load->model($className);
                                        $o2 = new $className;

                                        $dataSource = $o2->lookupAll()->result();
                                        $txtSelection = "";

                                        foreach ($dataSource as $key => $dsSpec) {
                                            if (isset($defaultValue)) {
                                                $isSelected = $dsSpec->id == $defaultValue ? "selected" : "";
                                            }
                                            else {
                                                $isSelected = isset($specs['defaultValue']) && $dsSpec->id == $specs['defaultValue'] ? "selected" : "";
                                            }

                                            //$txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                            $strLabel = $fName . "_" . $key;
                                            $txtSelection .= "<label for=$strLabel><input type=checkbox id=$strLabel name=$fName" . "[]" . "  value='" . $dsSpec->id . "' $isSelected>" . $dsSpec->name . "</label><br/>";
                                        }
                                    }
                                }
                                $txtSelection .= "</fieldSet>";
                                $txtSelection .= "</div>";


                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    "<div class='selectInput'>" .
                                    //"<select type='" . $specs['type'] . "' name='$fName' id='_$fName' >" .
                                    $txtSelection .
                                    //"</select>" .
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    "</div>")
                                );
                                break;
                            case "texts":
                                //==kalau model sudah punya data source bawaan, bukan relasi
                                if (isset($specs['dataParams']) && is_array($specs['dataParams'])) {

                                    $txtSelection = "<div class='panel-body'>";
                                    $txtSelection .= "<fieldSet>";
                                    foreach ($specs['dataParams'] as $param) {
                                        $defaultValue = isset($fieldData[$param]) ? $fieldData[$param] : "";
                                        $txtSelection .= "<a data-toggle='tooltip' data-placement='left' title='" . $specs['label'] . " " . $param . "'>";
                                        $txtSelection .= "<input type='" . "text" . "' autocomplete=off id=$param name=" . $fName . "_" . $param . " placeholder='" . $specs['label'] . " $param' value='" . $defaultValue . "' class='form-control' autocomplete='off'><br/>";
                                        $txtSelection .= "</a>";
                                    }
                                    $txtSelection .= "</fieldSet>";
                                    $txtSelection .= "</div>";
                                }

                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    "<div class='selectInput'>" .
                                    //"<select type='" . $specs['type'] . "' name='$fName' id='_$fName' >" .
                                    $txtSelection .
                                    //"</select>" .
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    "</div>")
                                );
                                break;
                            case "hidden":
                                $fieldRow = (array(" ", "<input type='hidden' name='$fName' placeholder='" . $specs['label'] . "' value='$defaultValue'>"));
                                break;
                            case "text":
                                $length = isset($specs['length']) ? $specs['length'] : "8";
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<input type='" . "text" . "' autocomplete=off maxlength='" . $length . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' value='" . $defaultValue . "' class='form-control' autocomplete='off' >"
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    //"</div>")
                                )
                                );
                                break;


                            case "file":
                                $length = isset($specs['length']) ? $specs['length'] : "8";
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<input type='" . "file" . "' maxlength='" . $length . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' value='" . $defaultValue . "' class='form-control' autocomplete='off' >"
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    //"</div>")
                                )
                                );
                                break;
                            case "image":
                                $length = isset($specs['length']) ? $specs['length'] : "8";
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<input type='" . "file" . "' maxlength='" . $length . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' value='" . $defaultValue . "' class='form-control' autocomplete='off' >"
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    //"</div>")
                                )
                                );
                                break;
                            case "textarea":
                                $length = isset($specs['length']) ? $specs['length'] : "8";
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<textarea cols=50 rows='" . $length . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' class='form-control' autocomplete='off' >" .
                                    $defaultValue . "</textarea>"
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    //"</div>")
                                )
                                );

                                break;
                            case "date":


                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<input  name='$fName' id='_$fName' placeholder='YYYY-MM-DD' required pattern=\"(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])/(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])/(?:30))|(?:(?:0[13578]|1[02])-31))\" title='Enter a date in this format YYYY-MM-DD' value='" . $defaultValue . "' class='form-control' autocomplete='off' max='2022-12-31' min='" . date("Y-m-d") . "'>"
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    //"</div>")
                                )
                                );
                                break;
                            case "number":
                                $length = isset($specs['length']) ? $specs['length'] : "8";
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<input type='" . "number" . "' maxlength='" . $length . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' value='" . $defaultValue . "' class='form-control' autocomplete='off' >"
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    //"</div>")
                                )
                                );
                                break;
                            case "password":
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<input type='" . "password" . "' autocomplete='off' maxlength='" . $specs['length'] . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' value='' class='form-control'>"
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    // "</div>")
                                )
                                );
                                break;
                        }
                    }
                    else {
                        switch ($specs['type']) {
                            case "varchar":
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<a data-toggle='tooltip' data-placement='left' title='" . $specs['label'] . "'>" .
                                    "<input type='" . $specs['type'] . "' autocomplete='off' maxlength='" . $specs['length'] . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' value='$defaultValue' class='form-control'>" .
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    "</a>"
                                    //"</div>")
                                )
                                );
                                break;
                            case "decimal":
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<input type='" . $specs['type'] . "' autocomplete='off' maxlength='" . floor($specs['length'] - 2) . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' value='$defaultValue' class='form-control'>"
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    // "</div>")
                                )
                                );
                                break;
                            default:
                                $fieldRow = (
                                array(
                                    ucwords($specs['label']),
                                    //"<div class='$defaultClassStyle'>" .
                                    "<a data-toggle='tooltip' data-placement='left' title='" . $specs['label'] . "'>" .
                                    "<input type='" . $specs['type'] . "' autocomplete='off' maxlength='" . $specs['length'] . "' name='$fName' id='_$fName' placeholder='" .
                                    $specs['label'] . "' value='$defaultValue' class='form-control'>" .
                                    //"<label for='_$fName'>".$specs['label']."</label>".
                                    "</a>"
                                    //"</div>")
                                )
                                );
                                break;
                        }
                    }
                    if ($validCounter > 0) {
                        $validRules = $this->object->getValidationRules();

                        if (isset($validRules[$fieldName]) && in_array("required", $validRules[$fieldName])) {
                            $fieldRow[0] = "<span style='border-bottom:2px #992200 solid;'><b>" . $fieldRow[0] . "</b><span class='text-red'>*</span></span>";
                        }
                    }
                    $t->addRow($fieldRow);
                }
                if ((null != $this->object->getCustomLink()) && is_array($this->object->getCustomLink())) {
                    $yesBtnLabel = "Lanjut";
                }
                else {
                    $yesBtnLabel = "Simpan";

                }
                $btnCancel = "<button type=button class='btn btn-warning' onClick=\"location.href='" . base_url() . str_replace("Mdl", "", get_class($this->object)) . "'\"><span class='glyphicon glyphicon-arrow-left'> Batal</button>";
                if (isset($this->specs['id'])) { //===form has an ID, then use button with validation instead of submit
                    $t->addRow(array($btnCancel, "<button type=button class='btn btn-primary' onClick=\"this.disabled=true;document.getElementById('" . $this->specs['id'] . "').submit();\" '><span class='glyphicon glyphicon-ok'> $yesBtnLabel</button>"));
                }
                else { //===then use a submit button
                    $t->addRow(array($btnCancel, "<button type=submit class='btn btn-primary' value='Simpan'><span class='glyphicon glyphicon-ok'> $yesBtnLabel</button>"));
                }

                $t->closeTable();
                //endregion ===making table contains inputs from data fields==

                $this->content .= $t->getContent(); //==grab table into form object
            }
            else {
                die("Form needs an object with fields!");
            }
        }
        else {
            die("Form needs an object!");
        }
    }

    public function isInputValid()
    {
        $invalidCounter = 0;
        if (count($this->object->getValidationRules()) > 0) {
            //==do some validation
            foreach ($this->object->getFields() as $fieldName => $spec) {
                $fName = isset($spec['fieldName']) ? $spec['fieldName'] : $fieldName;
                if (array_key_exists($fieldName, $this->object->getValidationRules())) {
                    //echo "$fName to be validated.<br>";
                    // <editor-fold defaultstate="collapsed" desc="validasi kolom wajib/required">
                    if (in_array("required", $this->object->getValidationRules()[$fieldName])) {
                        if (isset($spec['dataParams'])) {
                            foreach ($spec['dataParams'] as $param) {
                                if (strlen($this->object->input->post($fName . "_" . $param)) < 1) {
                                    //echo "$fName harus diisi!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName . "_" . $param] = array(
                                        "fieldName" => $fName . "_" . $param,
                                        "fieldLabel" => $spec['label'] . " " . $param,
                                        "errMsg" => $spec['label'] . " " . $param . " harus diisi!"
                                    );
                                }
                            }
                        }
                        else {
                            if (strlen($this->object->input->post($fName)) < 1) {
                                //echo "$fName harus diisi!<br>";
                                $invalidCounter++;
                                $this->validationResults[$fName] = array(
                                    "fieldName" => $fName,
                                    "fieldLabel" => $spec['label'],
                                    "errMsg" => $spec['label'] . " harus diisi!"
                                );
                            }
                        }
                    }
                    // </editor-fold>
                    // <editor-fold defaultstate="collapsed" desc="validasi numbers only">
                    if (in_array("numberOnly", $this->object->getValidationRules()[$fieldName])) {
                        if (isset($spec['dataParams'])) {
                            foreach ($spec['dataParams'] as $param) {
                                if (!is_numeric($this->object->input->post($fName . "_" . $param))) {
                                    //echo "$fName harus diisi!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName . "_" . $param] = array(
                                        "fieldName" => $fName . "_" . $param,
                                        "fieldLabel" => $spec['label'] . " " . $param,
                                        "errMsg" => $spec['label'] . " " . $param . " hanya boleh diisi angka!"
                                    );
                                }
                            }
                        }
                        else {
                            if (!is_numeric($this->object->input->post($fName))) {
                                //echo "$fName harus diisi!<br>";
                                $invalidCounter++;
                                $this->validationResults[$fName] = array(
                                    "fieldName" => $fName,
                                    "fieldLabel" => $spec['label'],
                                    "errMsg" => $spec['label'] . " hanya boleh diisi angka!"
                                );
                            }
                        }
                    }
                    // </editor-fold>
                    // <editor-fold defaultstate="collapsed" desc="validasi unique">
                    if (in_array("unique", $this->object->getValidationRules()[$fieldName])) {

                        //$tmpEvalQuery = $this->object->getByCondition(array($fName => $this->object->input->post($fName)))->result();
                        $this->object->addFilter($fName . "='" . $this->object->input->post($fName) . "'");
                        $tmpEvalQuery = $this->object->lookupAll()->result();
                        //==validasi unique hanya dikenakan pada penambahan data


                        if ($this->mode == "addProcess") {
                            //if ($tmpEvalQuery > 0) {
                            if (sizeof($tmpEvalQuery) > 0) {

                                //echo "entri sudah ada <br>";
                                $invalidCounter++;
                                $this->validationResults[$fName] = array(
                                    "fieldName" => $fName,
                                    "fieldLabel" => $spec['label'],
                                    "errMsg" => " sudah ada entri dengan kolom $fName berisi " . $this->object->input->post($fName) . "!"
                                );
                            }
                        }
                    }// </editor-fold>


                    if (in_array("alphanumeric", $this->object->getValidationRules()[$fieldName])) {
                        if (isset($spec['dataParams'])) {
                            foreach ($spec['dataParams'] as $param) {
                                if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $this->object->input->post($fName . "_" . $param))) {
                                    //echo "$fName harus diisi!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName . "_" . $param] = array(
                                        "fieldName" => $fName . "_" . $param,
                                        "fieldLabel" => $spec['label'] . " " . $param,
                                        "errMsg" => $spec['label'] . " " . $param . " hanya boleh berisi abjad/nomor dan harus diawali abjad!"
                                    );
                                }
                            }
                        }
                        else {
                            if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $this->object->input->post($fName))) {
                                //echo "$fName harus diisi!<br>";
                                $invalidCounter++;
                                $this->validationResults[$fName] = array(
                                    "fieldName" => $fName,
                                    "fieldLabel" => $spec['label'],
                                    "errMsg" => $spec['label'] . " hanya boleh berisi abjad/nomor dan harus diawali abjad!"
                                );
                            }
                        }
                    }
                    //preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $str);
                }
            }
            if ($invalidCounter > 0) {//==ada yang tidak valid===
                return false;
            }
            else {
                return true;
            }
        }
        else {
            //die("Nothing to validate");
            return true;
        }
    }

}

?>