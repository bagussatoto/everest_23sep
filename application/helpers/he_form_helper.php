<?php

class MyForm
{

    private $object;
    private $fieldSpec = array(); //==form-method,action,target,enctype
    private $content;
    private $validationResults = array();
    private $mode;

    //region gs
    public function getObject()
    {
        return $this->object;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function getFieldSpec()
    {
        return $this->fieldSpec;
    }

    public function setFieldSpec($fieldSpec)
    {
        $this->fieldSpec = $fieldSpec;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getValidationResults()
    {
        return $this->validationResults;
    }

    public function setValidationResults($validationResults)
    {
        $this->validationResults = $validationResults;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function __construct($object, $mode, $fieldSpec = array())
    {
        if (!is_array($fieldSpec)) {
            die("Form needs an array as a specification!");
        }
        $this->object = $object;
        $this->specs = $fieldSpec;
        $this->mode = $mode;
    }

    public function openForm($action)
    {
        // $this->content .= "<form"; // method='".$this->specs['method']."' action='".$this->specs['action']."' target='".$this->specs['target']."'>";
        // foreach ($this->specs as $key => $val) {
        //     $this->content .= " $key='$val' ";
        // }
        // $this->content .= ">";

        $this->content .= form_open($action, $this->specs);
    }

    public function closeForm()
    {
        $this->content .= "</form>";
    }

    public function fillForm($className, $o = null, $pf = null)
    {
        // cekHitam($className);
        $className_main = $className;

        $preffix = $pf != null ? "div#$pf " : "";
        $ci =& get_instance();
        if (is_object($this->object)) {

            $sc_botton = "<script>
                  top.$(document).on('ready', function() {
                      top.$('#input-1a').fileinput({
                          showUpload: false,
                          maxFileCount: 3,
                          mainClass: 'input-group-lg'
                      });
                  });
            </script>";

            if (count($this->object->getFields()) > 0) {

                $customAccessFields = array();
                $limitedMode = false;

                if (isset(config_item("heDataBehaviour")[$className]['customAccessFields'])) {
                    $customAccessFields = config_item("heDataBehaviour")[$className]['customAccessFields'];
                    $limitedMode = true;
                }
                else {
                    //die("NO customAcces detected!");
                }

                if (isset(config_item("heDataBehaviour")[$className]['customBlockFields'])) {
                    $customBlockFields = config_item("heDataBehaviour")[$className]['customBlockFields'];
                    foreach ($customBlockFields as $stateField => $arrField) {
                        $arrFieldState = array();
                        foreach ($arrField as $fieldName) {
                            $arrFieldState[$fieldName] = $stateField;
                        }
                    }
                }

                $dataTools = isset(config_item('dataTool')[$className]) ? config_item('dataTool')[$className] : array();

                $t = new Table();
                $t->openTable(array("class='table table-condensed no-padding no-border'"));
                $validCounter = count($this->object->getValidationRules());
                $validXorPairs = count($this->object->getXorPairs());

                $getMasterFields = method_exists($this->object, 'getMasterFields') ? $this->object->getMasterFields() : array();
                $getMasterSubs = method_exists($this->object, 'getMasterSubs') ? $this->object->getMasterSubs() : array();

                if (count($getMasterSubs)) {
                    $selectedFields = array();
                    $subFields = array();
                    foreach ($getMasterSubs as $ky => $subRow) {
                        $subFields[$subRow['sub_kategori_id']] = $subRow;
                    }
                    $tmpAnakan = $subFields[$o[0]->sub_kategori_id]['anakan'];
                    $oriFields = $this->object->getFields();
                    foreach ($tmpAnakan as $ky => $dts) {
                        if (isset($oriFields[$dts])) {
                            $selectedFields[$dts] = $oriFields[$dts];
                        }
                    }

                }
                else {
                    $selectedFields = $this->object->getFields();
                }

                /** ---------------------------------------------------------------------
                 * cek stok dan matikan fiel2 yang tidak boleh diedit
                 * ---------------------------------------------------------------------*/
                // arrPrint($o);
                if (method_exists($this->object, "validateStok")) {
                    $selectedID = $o[0]->id;
                    $dataBehavior = $ci->config->item('heDataBehaviour');
                    $paramMutasi = isset($dataBehavior[$className]['rel_deleters']) ? $dataBehavior[$className]['rel_deleters'] : array();
                    if (sizeof($paramMutasi) > 0) {
                        $relDir = $paramMutasi['dirModel'];
                        $relMdl = $paramMutasi['baseModel'];
                        $relCondites = $paramMutasi['condites'];
                        $relGrouping = $paramMutasi['grouping'];
                        $relSelected = $paramMutasi['selecteds'];
                        $relStrukture = $paramMutasi['data_strukture'];

                        $relCondites['extern_id'] = $selectedID;
                        $ci->load->model($relDir . $relMdl);
                        $pc = new $relMdl();
                        $ci->db->select($relSelected);
                        $ci->db->group_by($relGrouping);
                        $data_cache = $pc->lookupByCondition($relCondites)->result();
                        // showLast_query("merah");
                        // arrPrintPink($data_cache);

                        $data_aktif_cache = $data_cache[0]->sum_qty_debet;
                        // $data_aktif = 5;
                    }
                    // $readonlyStok = "readonly";
                    // $readonlyStok = $data_aktif > 0 ? "readonly" : "";

                    if (isset($paramMutasi['baseModelLocker'])) {
                        $relDirLocker = $paramMutasi['dirModelLocker'];
                        $relMdlLocker = $paramMutasi['baseModelLocker'];
                        $relConditesLocker = $paramMutasi['conditesLocker'];
                        $relGroupingLocker = $paramMutasi['groupingLocker'];
                        $relSelectedLocker = $paramMutasi['selectedsLocker'];
                        $relStruktureLocker = $paramMutasi['data_strukture_locker'];
                        $ci->load->model($relDirLocker . $relMdlLocker);
                        $pc = new $relMdlLocker();
                        $relConditesLocker['produk_id'] = $selectedID;
                        $ci->db->select($relSelectedLocker);
                        $ci->db->group_by($relGroupingLocker);
                        $data_cache_locker = $pc->lookupByCondition($relConditesLocker)->result();
                        // showLast_query("merah");
                        // arrPrintPink($data_cache_locker);

                        $data_aktif_locker_filter = $data_cache_locker[0]->sum_jumlah;
                    }
                    // $data_aktif = $data_aktif_cache > 0 || $data_aktif_locker_filter > 0 ? 1 : 0;
                    // arrPrintCyan($data_aktif);
                    // matiHere(__LINE__);
                }

                /** ----------------------------------------------
                 * logika masih ada stok yang
                 * ----------------------------------------------*/
                if ($data_aktif_cache > 0) {
                    $data_aktif = $data_aktif_cache;
                } elseif ($data_aktif_locker_filter > 0) {
                    $data_aktif = $data_aktif_locker_filter;
                } else {
                    $data_aktif = 0;
                }
                //-----------------------------------------------------------stok aktif

                if ($validXorPairs > 0) {
                    $xorPairs = $this->object->getXorPairs()[0];
                    $xorPaired = array();
                    foreach ($selectedFields as $colName => $colSpec) {
                        if (in_array($colName, $xorPairs)) {
                            $xorPaired["xorPairs"][$colName] = $colSpec;
                        }
                        else {
                            $xorPaired[$colName] = $colSpec;
                        }
                    }
                }
                else {
                    $xorPaired = $selectedFields;
                }

                if (sizeof($xorPaired) > 0) {
                    $validRules = $this->object->getValidationRules();
                    foreach ($xorPaired as $fieldName => $fieldSpec) {
                        if (isset($fieldSpec['editable']) && ($fieldSpec['editable'] === false) && ($o != null) && isset($fieldSpec['kolom_nama'])) {
                            $fName = isset($fieldSpec['kolom_nama']) ? $fieldSpec['kolom_nama'] : $fieldName;
                        }
                        else {
                            $fName = isset($fieldSpec['kolom']) ? $fieldSpec['kolom'] : $fieldName;
                        }
                        if (isset($fieldSpec['editable']) && ($fieldSpec['editable'] === false) && ($o != null)) {
//                            $defaultValue = isset($o[$className][0]->$defaultKolom) ? $o[$className][0]->$defaultKolom : "";
//                            arrPrint($pf);
//                            arrPrint($o);
//                            arrPrintWebs($fieldSpec);
//                            arrPrintWebs($className);

                            $className = $fieldSpec['reference'];

                            if (isset($o[$className])) {
                                $inputHidden_disabled = "<input type='hidden' className=$className name='" . $fieldSpec['kolom'] . "' value='" . (isset($o[$className][0]->$fieldSpec['kolom']) ? $o[$className][0]->$fieldSpec['kolom'] : '') . "'>";
                            }
                            else {
                                $inputHidden_disabled = "<input type='hidden' className=$className name='" . $fieldSpec['kolom'] . "' value='" . (isset($o[0]->$fieldSpec['kolom']) ? $o[0]->$fieldSpec['kolom'] : '') . "'>";
                            }

                            switch ($className) {
                                case "MdlSetupDepresiasiAssetsSales":
//                                case "MdlSetupDepresiasiAssetsProduction":
                                    $inputHidden_disabled = "<input mode='selain_edit' type='hidden' name='" . $fieldSpec['kolom'] . "' value='" . (isset($o[0]->$fieldSpec['kolom']) ? $o[0]->$fieldSpec['kolom'] : (isset($fieldSpec['defaultValue']) ? $fieldSpec['defaultValue'] : '')) . "'>";
                                    break;
                                default:
                                    $inputHidden_disabled = "<input mode='selain_edit' type='hidden' name='" . $fieldSpec['kolom'] . "' value='" . (isset($o[0]->$fieldSpec['kolom']) ? $o[0]->$fieldSpec['kolom'] : (isset($fieldSpec['defaultValue']) ? $fieldSpec['defaultValue'] : '')) . "'>";
                                    break;
                            }
                        }
                        else {
                            $inputHidden_disabled = "";
                        }
                        //region periksa existing values
                        if (is_array($o) && sizeof($o) > 0) {//===modeedit nih
                            $edit = "edit";
                            if ($fieldName == "xorPairs") {
                                $xorDefaultVal = array();
                                foreach ($fieldSpec as $xorKol => $xorDef) {
                                    $xorVal = isset($o[0]->$xorKol) ? $o[0]->$xorKol : "";
                                    $xorDefaultVal[$xorKol] = $xorVal;
                                }
                            }
                            else {
                                if (isset($fieldSpec['inputType'])) {// && $fieldSpec['inputType'] == "checkbox") {
                                    switch ($fieldSpec['inputType']) {
                                        case "checkbox":
                                            $fieldData = isset($o[0]->$fName) ? unserialize(base64_decode($o[0]->$fName)) : array();
                                            break;
                                        case "texts":
                                            $fieldData = isset($o[0]->$fName) ? unserialize(base64_decode($o[0]->$fName)) : array();
                                            foreach ($fieldSpec['dataParams'] as $param) {
                                                $defaultValues[$param] = isset($fieldData[$param]) ? $fieldData[$param] : "";
                                            }
                                            break;
                                        case "text":
                                            $defaultValue = isset($o[0]->$fName) ? (is_numeric($o[0]->$fName) ? ($o[0]->$fName * 1) : $o[0]->$fName) : "";
                                            break;
                                        case "password":
                                            //                                            $defaultValue = createDefaultPassword();
                                            $defaultValue = (isset($o[0]->$fName) && (strlen($o[0]->$fName) > 24)) ? $o[0]->$fName : createDefaultPassword();
                                            break;
                                        case "file":
                                            $defaultValue = isset($o[0]->$fName) ? $o[0]->$fName : "";
                                            break;
                                        case "image":
                                            $defaultValue = isset($o[0]->$fName) ? $o[0]->$fName : "";
                                            break;
                                        default:
                                            $defaultValue = isset($o[0]->$fName) ? (is_numeric($o[0]->$fName) ? ($o[0]->$fName * 1) : $o[0]->$fName) : "";
                                            $defaultClassStyle = "form-control";
                                            break;
                                    }
                                }
                                else {
                                    $defaultValue = isset($o[0]->$fName) ? $o[0]->$fName : "";
                                    $defaultClassStyle = "form-control";
                                }
                            }
                        }
                        else {
                            $edit = false;
                            if ($validCounter > 0) {
                                if (isset($fieldSpec['inputType'])) {
                                    switch ($fieldSpec['inputType']) {
                                        case "checkbox":
                                            $fieldData = $this->object->input->post($fName);
                                            $defaultClassStyle = "form-control";
                                            break;
                                        case "textarea":
                                            $fieldData = $this->object->input->post($fName);
                                            $defaultClassStyle = "form-control";
                                            break;
                                        case "password":
                                            $fieldData = md5($this->object->input->post($fName));
                                            $defaultClassStyle = "form-control";
                                            $defaultValue = createDefaultPassword();
                                            break;
                                        case "texts":
                                            if (isset($fieldSpec['dataParams'])) {
                                                foreach ($fieldSpec['dataParams'] as $param) {
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
                        //endregion

                        //region custom field access
                        $readonlyStr = "";
                        if ($limitedMode) {
                            $readonlyStr = "readonly";
                            $ci = &get_instance();
                            $loginType = $ci->session->login['membership'];
                            foreach ($loginType as $gID) {

                            }
                        }
                        //endregion

                        // arrPrintPink($defaultValue);

                        if ($fieldName == "xorPairs") {
                            $xorData = "";
                            foreach ($fieldSpec as $fieldXor => $fieldXorData) {
                                if (isset($fieldXorData['inputType'])) {
                                    switch ($fieldXorData['inputType']) {
                                        case "text":
                                            $defaultVal = isset($xorDefaultVal[$fieldXor]) ? $xorDefaultVal[$fieldXor] : "";
                                            $checked = $defaultVal > 0 ? "checked" : "";
                                            $settingHidden = $defaultVal > 0 ? "" : "hidden";
                                            $xorLabel = $fieldXorData["label"];
                                            $xorPlaceHolder = isset($fieldXorData["placeholder"]) ? $fieldXorData["placeholder"] : $xorLabel;
                                            $xorCollom = $fieldXorData["kolom"];
                                            $xorData .= "<div class='col-xs-12 col-lg-12 col-sm-12 no-padding'>";
                                            $xorData .= "<div class='col-xs-6 col-lg-6 col-sm-6' style='padding-right: 0px;width: 215px'>";
                                            $xorData .= "<label><input type=radio  ids='$xorCollom'  names='diskon' name='$fName' value='' onclick='show_input_$xorCollom(this)' $checked>" . $xorLabel . "</label>&nbsp;";
                                            $xorData .= "</div>";
                                            $xorData .= "<div class='col-xs-6 col-lg-6 col-sm-6' style='padding-left: 0px;display: nogne;' >";
                                            $xorData .= "<input $readonlyStr type='" . "text" . "' maxlength='" . $length . "' name='$xorCollom' id='_$xorCollom' ids='$xorCollom' placeholder='" . $xorPlaceHolder . "' value='" . $defaultVal . "' class='$settingHidden form-control' autocomplete='off'  onfocus=\"this.select()\" $eventKeyup>";
                                            $xorData .= "</div>&nbsp;<br>";
                                            $xorData .= "</div>";
                                            $xorData .= "<script>
                                                            function show_input_$xorCollom(z) {
                                                                var x = document.getElementById(_$xorCollom);
                                                                var arrXorData =  $('input[names=diskon]');
                                                                    jQuery.each(arrXorData,function(i,b) {
                                                                        var ids = $(arrXorData[i]).attr('ids');
                                                                        if( $(arrXorData[i]).is(':checked') ){
                                                                        $('input[name='+ids+']').removeClass('hidden');
                                                                        $('input[name='+ids+']').prop('disabled', false);
                                                                    }
                                                                    else{
                                                                        $('input[name='+ids+']').addClass('hidden');
                                                                        $('input[name='+ids+']').prop('disabled', true);
                                                                    }
                                                                });
                                                            }
                                                        </script>";
                                            break;
                                        default:
                                            break;
                                    }
                                }
                                else {
                                    $xorData = "";
                                }

                            }
                            $fieldRow = (array(
                                ucwords($fName),
                                "<div class='selectInput'>" . //"<select type='" . $fieldSpec['type'] . "' name='$fName' id='_$fName' >" .
                                $xorData . //"</select>" .
                                "</div>",
                            ));
                        }
                        else {
                            if (isset($fieldSpec['inputType'])) {
                                //                                cekMerah($fieldSpec['label'] . " || " . $fieldSpec['inputType']);
                                switch ($fieldSpec['inputType']) {
                                    case "combo-multiple":
                                        $isSelected = "";
                                        $txtSelection = "";
                                        $isReadonly = "";
                                        //region kiriman relasi
                                        $readFromQueryString = false;
                                        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
                                            if ($fName == $_GET['reqField']) {
                                                $readFromQueryString = true;
                                            }
                                        }
                                        //endregion

                                        //==kalau model sudah punya data source bawaan, bukan relasi
                                        if (isset($fieldSpec['dataSource']) && is_array($fieldSpec['dataSource'])) {
                                            // cekHere("ono");
                                            foreach ($fieldSpec['dataSource'] as $key => $val) {
                                                if (isset($fieldSpec['defaultValue'])) {
                                                    $isSelected = strcmp($key, $fieldSpec['defaultValue']) == 0 ? "selected" : "";
                                                }
                                                else {
                                                    $isSelected = isset($fieldSpec['defaultValue']) && $key == $fieldSpec['defaultValue'] ? "selected" : "";
                                                }

                                                if ($readFromQueryString && ($key == $_GET['reqVal'])) {
                                                    $isSelected = "selected";
                                                    $isReadonly = "readonly";
                                                }
                                                $txtSelection .= "<option value='" . $key . "' $isSelected>" . $val . "</option>";
                                            }
                                        }
                                        //===kalau berrelasi
                                        if (isset($fieldSpec['reference'])) {
                                            // cekMerah("detecting references for $fieldName");
                                            $className = $fieldSpec['reference'];
                                            $name2 = isset($fieldSpec['name2']) ? $fieldSpec['name2'] : "";
                                            $this->object->load->model("Mdls/" . $className);
                                            $o2 = new $className;
                                            if (isset($fieldSpec['referenceFilter']) && sizeof($fieldSpec['referenceFilter']) > 0) {
                                                $aFilter = $fieldSpec['referenceFilter'];
                                                $o2 = makeFilter($aFilter, $ci->session->login, $o2);
                                            }
                                            $dataSource = $o2->lookupAll()->result();
                                            // cekkuning($ci->db->last_query());
                                            foreach ($dataSource as $key => $dsSpec) {
                                                // $txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                                $relLabel2 = isset($fieldSpec['name2']) ? $dsSpec->$name2 : "";
                                                $relLabel = isset($dsSpec->name) ? $dsSpec->name : $dsSpec->nama;
                                                $relLabelFix = strlen($relLabel2) > 0 ? $relLabel2 : $relLabel;
                                                $relLabelCode = isset($dsSpec->kode) ? $dsSpec->kode : "";
                                                $relLabelMdl = isset($dsSpec->mdl_name) ? $dsSpec->mdl_name : "";
                                                $idField = $o2->getIndexFields() != null ? $o2->getIndexFields() : "id";

                                                if (isset($defaultValue)) {
                                                    $isSelected = $dsSpec->$idField == $defaultValue ? "selected" : "";
                                                }
                                                else {
                                                    $isSelected = isset($fieldSpec['defaultValue']) && $dsSpec->id == $fieldSpec['defaultValue'] ? "selected" : "";
                                                }
                                                if ($readFromQueryString && ($dsSpec->$idField == $_GET['reqVal'])) {
                                                    $isSelected = "selected";
                                                    $isReadonly = "readonly";
                                                }
                                                $ext_mdl = "";
                                                if ($relLabelMdl != "") {
                                                    $ext_mdl = "mdl_name='" . $relLabelMdl . "'";
                                                }
                                                $txtSelection .= "<option $ext_mdl value='" . $dsSpec->$idField . "' $isSelected>" . $relLabelCode . " " . $relLabelFix . "</option>";
                                            }
                                        }
                                        else {
                                            // cekMerah("not having references for $fieldName");
                                        }
                                        if ($readonlyStr == "disabled") {
                                            $inputHidden = "<input type='hidden' name='$fName' value='$defaultValue'>";
                                        }
                                        else {
                                            $inputHidden = "";
                                        }
                                        if (isset($fieldSpec['editable']) && ($fieldSpec['editable'] === false) && ($o != null)) {
                                            $fieldRow = (array(
                                                ucwords($fieldSpec['label']),
                                                "<fieldset>" . "<div class='selectInput'>
                                                <input type='text' class='form-control xxx' disabled value='$defaultValue'>" . " $inputHidden $inputHidden_disabled" . //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                                "</div>" . "</fieldset>",
                                            ));
                                        }
                                        else {
                                            $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                            $fieldRow = (array(
                                                ucwords($fieldSpec['label'] . " ") . (isset($fieldSpec['subLabel']) ? " " . $fieldSpec['subLabel'] . " " : ""),
                                                "<fieldset>" . "<div class='selectInput X1'>" . "<select $readonlyStr data-style='btn-primary' data-live-search='true' title='" . $xorPlaceHolder . "' data-headers='Ketik Nama/Kode/Folder/Barcode' data-size='10' data-container='body' multiple data-selected-text-format='count > 7' type='" . $fieldSpec['type'] . "' name='" . $fName . "[]' id='_$fName' class='_$fName selectpicker form-controls sini select2 show-tick' $isReadonly>" . "<option value='' $isSelected>--none--</option>" . $txtSelection . "</select> $inputHidden" . //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                                "</div>" . "</fieldset>",
                                            ));
                                            $sc_botton .= "
                                                <script>
                                                    $(document).ready( function() {
                                                        setTimeout(
                                                        function(){
                                                            var selectobject;
                                                            $('._$fName.select2').selectpicker({ dropdownParent: $('body') })
                                                            .selectpicker('val', [$defaultValue])
                                                                selectobject = document.getElementById(\"_$fName\").getElementsByTagName(\"option\");
                                                                var list = '$defaultValue';
                                                                var disabled = list.split(',');
                                                                jQuery.each(disabled, function(i, v){
                                                                    selectobject[v].disabled = true;
                                                                })
                                                            $('._$fName.select2').selectpicker('refresh')
                                                            console.log('_$fName yang no #1');
                                                        }, 200 );
                                                     });
                                                 </script>";
                                        }
                                        break;
                                    case "combo-hidden":
                                    case "combo-blank":
                                    case "combo":
                                        // cekHitam("999");
                                        $isSelected = "";
                                        $txtSelection = "";
                                        $isReadonly = "";
                                        // region kiriman relasi
                                        $readFromQueryString = false;
                                        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
                                            if ($fName == $_GET['reqField']) {
                                                $readFromQueryString = true;
                                            }
                                        }
                                        // endregion
                                        //==kalau model sudah punya data source bawaan, bukan relasi
                                        if (isset($fieldSpec['dataSource']) && is_array($fieldSpec['dataSource'])) {
                                            // cekHere("ono");
                                            foreach ($fieldSpec['dataSource'] as $key => $val) {
                                                if (isset($fieldSpec['defaultValue'])) {
                                                    $isSelected = strcmp($key, $fieldSpec['defaultValue']) == 0 ? "selected" : "";
                                                }
                                                else {
                                                    $isSelected = isset($fieldSpec['defaultValue']) && $key == $fieldSpec['defaultValue'] ? "selected" : "";
                                                }
                                                // $txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                                if ($readFromQueryString && ($key == $_GET['reqVal'])) {
                                                    $isSelected = "selected";
                                                    $isReadonly = "readonly";
                                                }
                                                $txtSelection .= "<option value='" . $key . "' $isSelected>" . $val . "</option>";
                                            }
                                        }
                                        // arrPrintPink($fieldSpec);
                                        //===kalau berrelasi
                                        $idFieldKeterangan = "";
                                        if (isset($fieldSpec['reference'])) {
                                            // cekMerah("detecting references for $fieldName");
                                            $reference_src_rel = isset($fieldSpec['reference_src_rel']) ? $fieldSpec['reference_src_rel'] : array();
                                            $className = $fieldSpec['reference'];
                                            $name2 = isset($fieldSpec['name2']) ? $fieldSpec['name2'] : "";
                                            $relName = isset($reference_src_rel["key_nama"]) ? $reference_src_rel["key_nama"] : "";
                                            $this->object->load->model("Mdls/" . $className);
                                            $o2 = new $className;
                                            if (isset($fieldSpec['referenceFilter']) && sizeof($fieldSpec['referenceFilter']) > 0) {

                                                $aFilter = $fieldSpec['referenceFilter'];
                                                $sessDatas = array();
                                                /* -----------------------------------------------
                                                 * penmabahan data optional relasi baru saat add produk
                                                 * -----------------------------------------------*/
                                                if (isset($_SESSION['data'])) {
                                                    $sessDatas = $_SESSION['data'];
                                                    foreach ($aFilter as $itemKy => $params) {
                                                        foreach ($params as $jenis_param => $param) {
                                                            if ($jenis_param == 'var') {
                                                                $data_filter[$itemKy] = $sessDatas[$param];
                                                                $fieldSpec['defaultValue'] = $sessDatas[$param];
                                                            }
                                                            else {
                                                                $data_filter[$itemKy] = $param;
                                                            }
                                                        }
                                                    }
                                                    // $ci->db->where($data_filter);
                                                }
                                                else {
                                                    $o2 = makeFilter($aFilter, $ci->session->login, $o2);
                                                }
                                                // arrPrintKuning($aFilter);
                                                // arrPrintKuning($data_filter);
                                            }
                                            // $this->db->order("id","desc");
                                            $o2->setSortBy(array("kolom" => "id", "mode" => "desc"));
                                            $dataSource = $o2->lookupAll()->result();
                                            // showLast_query("hijau");
                                            //                                             arrPrint($o[$className]);
                                            // cekHijau("$className");
                                            if (array_key_exists($className, $o)) {
                                                // bukan yg bener2 ngedit data
                                            }
                                            else {
                                                if ($edit == "edit") {
                                                    unset($fieldSpec['defaultValue']);
                                                }
                                            }


                                            // arrPrint($fieldSpec);
                                            //                                             arrPrint($reference_src_rel);
                                            // arrPrintHijau($className);
                                            if (isset($fieldSpec['defaultValue'])) {
                                                // cekPink(__LINE__);
                                                $defaultKolom = isset($fieldSpec['defaultValue']) ? $fieldSpec['defaultValue'] : "";

                                                // cekKuning($defaultKolom);
                                                $defaultValue = isset($o[$className][0]->$defaultKolom) ? $o[$className][0]->$defaultKolom : "";
                                            }
                                            else {

                                            }
                                            // cekHere("$defaultKolom || $defaultValue || $edit");

                                            // cekkuning($ci->db->last_query());
                                            foreach ($dataSource as $key => $dsSpec) {
                                                // arrPrintPink($dsSpec->keterangan);
                                                $relketerangan = isset($dsSpec->keterangan) ? $dsSpec->keterangan : "";
                                                //$txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                                $relLabel2 = isset($fieldSpec['name2']) ? $dsSpec->$name2 : "";
                                                $relLabel = isset($dsSpec->name) ? $dsSpec->name : $dsSpec->nama;
                                                $relLabelFix = strlen($relLabel2) > 0 ? $relLabel2 : $relLabel;
                                                $relLabel2 = isset($reference_src_rel["key_nama"]) ? $dsSpec->$relName : "";
                                                $relLabelFix2 = strlen($relLabel2) > 2 ? $relLabel2 : $relLabel;
                                                $relLabelCode = isset($dsSpec->kode) && $dsSpec->kode * 1 > 0 ? $dsSpec->kode : "";

                                                $relLabelMdl = isset($dsSpec->mdl_name) ? $dsSpec->mdl_name : "";
                                                $idField = $o2->getIndexFields() != null ? $o2->getIndexFields() : "id";
                                                $idField2 = isset($reference_src_rel["key_id"]) ? $reference_src_rel["key_id"] : $idField;
                                                $idFieldKeterangan = isset($fieldSpec["keterangan"]) ? "<span id='keterangan_1'></span>" : "";

                                                // cekMerah("$idField == $defaultValue");
                                                if (isset($defaultValue)) {
                                                    $isSelected = $dsSpec->$idField == $defaultValue ? "selected" : "";
                                                }
                                                else {
                                                    $isSelected = isset($fieldSpec['defaultValue']) && $dsSpec->id == $fieldSpec['defaultValue'] ? "selected" : "";
                                                }
                                                if ($readFromQueryString && ($dsSpec->$idField == $_GET['reqVal'])) {
                                                    $isSelected = "selected";
                                                    $isReadonly = "readonly";
                                                }
                                                $ext_mdl = "";
                                                if ($relLabelMdl != "") {
                                                    $ext_mdl = "mdl_name='" . $relLabelMdl . "'";
                                                }

                                                $txtSelection .= "<option $ext_mdl value='" . $dsSpec->$idField2 . "' $isSelected>" . $relLabelCode . " " . $relLabelFix2 . "</option>";

                                            }
                                        }
                                        else {
                                            // cekMerah("not having references for $fieldName");
                                        }
                                        if ($readonlyStr == "disabled") {
                                            $inputHidden = "<input type='hidden' name='$fName' value='$defaultValue'>";
                                        }
                                        else {
                                            $inputHidden = "";
                                        }
                                        if (isset($fieldSpec['editable']) && ($fieldSpec['editable'] === false) && ($o != null)) {
                                            $fieldRow = (array(
                                                ucwords($fieldSpec['label']),
                                                "<fieldset>" . "<div class='selectInput'>
                                                <input type='text' class='form-control xxxx' name='$fName' readonly value='$defaultValue'>" . " $inputHidden $inputHidden_disabled" . //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                                "</div>" . "</fieldset>",
                                            ));
                                        }
                                        else {
                                            $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];

                                            $jsdefaultValue = $defaultValue != "" ? $defaultValue : (isset($fieldSpec['defaultValue']) && $fieldSpec['defaultValue'] != "" ? $fieldSpec['defaultValue'] : "");

                                            $reference_label = $fieldSpec['label'] ? strtoupper($fieldSpec['label']) : "";
                                            $reference = isset($fieldSpec['reference']) && $fieldSpec['reference'] != '' ? substr($fieldSpec['reference'], 3) : "";
                                            $link_add = base_url() . "statik/Data/add/$reference?main=$className_main&pfid=l2";
                                            $link_add_act = modalDialogBtn("New $reference_label", $link_add, 0, 'l2');
                                            $btn_add = isset($fieldSpec['add_btn']) ? "<button type='button' class='btn btn-warning' onclick=\"$link_add_act\"><i class='fa fa-plus'></i></button>" : "";

                                            if (isset($fieldSpec["eventonchange"])) {
                                                $dataStyle = $jsdefaultValue == 1 ? "btn-success" : "btn-danger";
                                                $fieldRow = (array(
                                                    ucwords($fieldSpec['label'] . " ") . (isset($fieldSpec['subLabel']) ? " " . $fieldSpec['subLabel'] . " " : ""),
                                                    "<fieldset>" . "<div class='selectInput X2'>" . "<select senera $readonlyStr data-style='$dataStyle' data-live-search='true' title='" . $xorPlaceHolder . "' data-headers='Ketik Nama/Kode/Folder/Barcode' data-size='10' data-container='body' type='" . $fieldSpec['type'] . "' name='$fName' id='_$fName' class='_$fName selectpicker form-controls sini select2 show-tick' $isReadonly>" . "<option value='' $isSelected>--none--</option>" . $txtSelection . "</select> $inputHidden" . //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                                    "</div>" . "</fieldset>",
                                                ));
                                            }
                                            else {
                                                $fieldRow = (array(
                                                    ucwords($fieldSpec['label'] . " ") . (isset($fieldSpec['subLabel']) ? " " . $fieldSpec['subLabel'] . " " : ""),
                                                    "<fieldset>" . "<div class='selectInput X3'>" . "<select $readonlyStr data-style='btn-primary' data-live-search='true' title='" . $xorPlaceHolder . "' data-headers='Ketik Nama/Kode/Folder/Barcode' data-size='10' data-container='body' type='" . $fieldSpec['type'] . "' name='$fName' id='_$fName' class='_$fName selectpicker form-controls sini select2 show-tick' $isReadonly onchange=\"top.jml_serial(this.value);\">" . "<option value='' $isSelected>--none--</option>" . $txtSelection . "</select> $btn_add $idFieldKeterangan $inputHidden" . //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                                    "</div>" . "</fieldset>",
                                                ));
                                            }

                                            $sc_botton .= "\n<script>";

                                            if (isset($fieldSpec["eventonchange"])) {

                                                $sc_botton .= "
                                                        setTimeout(
                                                        function(){
                                                        $('#_$fName').on('change', function(){
                                                            switch(this.value){
                                                                case '0':
                                                                    $('[data-dismiss=\"modal\"]').click();
                                                                        var azz = $('input[name=extern_id]').val();
                                                                    BootstrapDialog.show(
                                                                       {
                                                                            title:'Setup data depresiasi',
                                                                            message: top.$('<div></div>').load('" . base_url() . "SetupDepresiasi/add/Assets_cln_/'+azz+'?val=" . $_GET['val'] . "'),
                                                                            size: BootstrapDialog.SIZE_WIDE,
                                                                            draggable:false,
                                                                            cache: false,
                                                                            closable:true,
                                                                       }
                                                                    );
                                                                break;
                                                                case '1':
                                                                    $('[data-dismiss=\"modal\"]').click()
                                                                    var azz = $('input[name=extern_id]').val();

                                                                    BootstrapDialog.show(
                                                                       {
                                                                            title:'Setup data depresiasi',
                                                                            message: top.$('<div></div>').load('" . base_url() . "SetupDepresiasi/add/Assets/'+azz+'?val=" . $_GET['val'] . "'),
                                                                            size: BootstrapDialog.SIZE_WIDE,
                                                                            draggable:false,
                                                                            cache: false,
                                                                            closable:true,
                                                                       }
                                                                    );
                                                                break;
                                                            }
                                                        });
                                                        }, 1000 );
                                                    ";

                                            }

                                            $sc_botton .= "\n
                                                     setTimeout( function(){
                                                        top.$('$preffix._$fName.select2').selectpicker({ dropdownParent: $('body') })
                                                        .selectpicker('val', ['$jsdefaultValue']);
                                                     }, 300 );
                                                     function jml_serial(x){
                                                        if(top.$('#lbl_jml_serial1').length > 0){
                                                            if(x==1){
                                                                top.$('#lbl_jml_serial1').prop('checked', true).prop('disabled', false);
                                                                top.$('input[type=\"radio\"]:not(#lbl_jml_serial1)').prop('checked', false).prop('disabled', true);
                                                            }
                                                            else{
                                                                top.$('#lbl_jml_serial1').prop('disabled', true).prop('checked', false);
                                                                top.$('input[type=\"radio\"]:not(#lbl_jml_serial1)').prop('checked', false).prop('disabled', false);
                                                            }
                                                        }
                                                    \n};";

                                            $sc_botton .= "</script>";


                                        }
                                        break;
                                    case "radio":
                                        $isSelected = "";
                                        $isSelectedForJs = "";
                                        $isReadonly = "";
                                        //region kiriman relasi
                                        $readFromQueryString = false;
                                        if (isset($_GET['reqField']) && isset($_GET['reqVal'])) {
                                            if ($fName == $_GET['reqField']) {
                                                $readFromQueryString = true;
                                            }
                                        }
                                        //endregion
                                        //==kalau model sudah punya data source bawaan, bukan relasi
                                        // arrPrintWebs($o[0]->$fName);
                                        if ($edit) {

                                            $fieldSpec["defaultValue"] = $o[0]->$fName;
                                        }
                                        // cekBiru(__LINE__);
                                        if (isset($fieldSpec['dataSource']) && is_array($fieldSpec['dataSource'])) {
                                            $txtSelection = "";
                                            $x = 0;
                                            foreach ($fieldSpec['dataSource'] as $key => $val) {
                                                $x++;
                                                if (isset($fieldSpec['defaultValue'])) {
                                                    $isSelected = $key == $fieldSpec['defaultValue'] ? "checked" : "";
                                                }
                                                else {
                                                    $isSelected = isset($fieldSpec['defaultValue']) && $key == $fieldSpec['defaultValue'] ? "checked" : "";
                                                }

                                                if ($readFromQueryString && ($key == $_GET['reqVal'])) {
                                                    $isSelected = "checked";
                                                    $isReadonly = "readonly";
                                                }
                                                $tData = 12 / sizeof($fieldSpec['dataSource']);
                                                if ($isSelected == 'checked') {
                                                    $isSelectedForJs = "checkedClass: 'checked',";
                                                }
                                                $txtSelection .= "<span class=' no-padding' > <input id='lbl_$fName$x' type=radio name=$fName value='" . $key . "' $isSelected> <label class='text-capitalize' for='lbl_$fName$x'> " . $val . " </label></span>";

                                            }
                                        }
                                        else {
                                            $txtSelection = "";
                                        }
                                        //===kalau berrelasi
                                        if (isset($fieldSpec['reference'])) {
                                            $className = $fieldSpec['reference'];
                                            $this->object->load->model("Mdls/" . $className);
                                            $o2 = new $className;
                                            if (isset($fieldSpec['referenceFilter']) && sizeof($fieldSpec['referenceFilter']) > 0) {
                                                $aFilter = $fieldSpec['referenceFilter'];
                                                $o2 = makeFilter($aFilter, $ci->session->login, $o2);
                                            }
                                            $dataSource = $o2->lookupAll()->result();
                                            $x = 0;
                                            foreach ($dataSource as $key => $dsSpec) {
                                                $x++;
                                                $relLabelMdl = isset($dsSpec->mdl_name) ? $dsSpec->mdl_name : "";
                                                if (isset($fieldSpec['defaultValue'])) {
                                                    $isSelected = $dsSpec->id == $fieldSpec['defaultValue'] ? "checked" : "";
                                                }
                                                else {
                                                    $isSelected = isset($fieldSpec['defaultValue']) && $dsSpec->id == $fieldSpec['defaultValue'] ? "checked" : "";
                                                }
                                                $colName = isset($dsSpec->name) ? $dsSpec->name : $dsSpec->nama;
                                                if ($readFromQueryString && ($dsSpec->id == $_GET['reqVal'])) {
                                                    $isSelected = "checked";
                                                    $isReadonly = "readonly";
                                                }
                                                $ext_mdl = "";
                                                if ($relLabelMdl != "") {
                                                    $ext_mdl = "mdl_name='" . $relLabelMdl . "'";
                                                }
                                                $tData = 12 / sizeof($dataSource);
                                                if ($isSelected == 'checked') {
                                                    $isSelectedForJs = "checkedClass: 'checked',";
                                                }
                                                $txtSelection .= "<span class='col-sm-3 no-padding'><input $ext_mdl id='lbl_$fName$x' type=radio $readonlyStr name=$fName value='" . $dsSpec->id . "' $isSelected $isReadonly> <label class='text-capitalize' for='lbl_$fName$x'>" . $colName . "</label></span>";
                                            }
                                        }

                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            "<fieldSet style=\"border: 1px solid #cccccc;padding:5px;border-radius:2.5px;\">" . "<div id='si_$fName' class='selectInput'>" . $txtSelection . //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            "</div>" . "</fieldSet>",
                                        ));

                                        $sc_botton .= "<script>";
                                        $sc_botton .= "
                                                        setTimeout(
                                                        function(){
                                                            top.$('input[name=$fName]').iCheck({
                                                                checkboxClass: 'icheckbox_square-red',
                                                                radioClass: 'iradio_square-red',
                                                                increaseArea: '20%', // optional
                                                                $isSelectedForJs
                                                            });
                                                        }, 300 );

                                                    ";
                                        $sc_botton .= "</script>";
                                        break;
                                    case "checkbox":
                                        //cekMerah("evaluating chekboxes");
                                        //==kalau model sudah punya data source bawaan, bukan relasi
                                        $txtSelection = "<div class='panel-body'>";
                                        $txtSelection .= "<fieldSet>";
                                        if (isset($fieldSpec['dataSource']) && is_array($fieldSpec['dataSource'])) {
                                            //arrPrintPink($fieldSpec['dataSource']);
                                            foreach ($fieldSpec['dataSource'] as $key => $val) {
                                                $title = "";
                                                if (isset($fieldSpec['groupTransaksiLabel'][$key])) {
                                                    foreach ($fieldSpec['groupTransaksiLabel'][$key] as $gSpec) {
                                                        $title .= ucwords($gSpec['label']) . ", ";
                                                    }
                                                }
                                                $txtSelection .= "<div class='col-md-4'>";
                                                if (isset($fieldData)) {
                                                    $isSelected = in_array($key, $fieldData) ? "checked" : "";
                                                }
                                                else {
                                                    $isSelected = "";
                                                }
                                                $strLabel = $fName . "_" . $key;
                                                $txtSelection .= "<input type=checkbox $readonlyStr id=$strLabel name=$fName" . "[]" . " value='" . $key . "' $isSelected>";
                                                $txtSelection .= "&nbsp;<label for=$strLabel title='' name='qtips'
                                                    data-toggle=\"tooltip\" data-original-title=\"$title\">" . $val . "</label>";
                                                $txtSelection .= "</div>";
                                            }
                                        }
                                        else {//==kalau datasource berupa relasi
                                            if (isset($fieldSpec['reference'])) {
                                                //cekMerah("reference found");
                                                $className = $fieldSpec['reference'];
                                                $this->object->load->model("Mdls/" . $className);
                                                $o2 = new $className;
                                                if (isset($fieldSpec['referenceFilter']) && sizeof($fieldSpec['referenceFilter']) > 0) {
                                                    $aFilter = $fieldSpec['referenceFilter'];
                                                    $o2 = makeFilter($aFilter, $ci->session->login, $o2);
                                                }
                                                $dataSource = $o2->lookupAll()->result();
                                                $txtSelection = "";
                                                foreach ($dataSource as $key => $dsSpec) {
                                                    if (isset($defaultValue)) {
                                                        $isSelected = $dsSpec->id == $defaultValue ? "selected" : "";
                                                    }
                                                    else {
                                                        $isSelected = isset($fieldSpec['defaultValue']) && $dsSpec->id == $fieldSpec['defaultValue'] ? "selected" : "";
                                                    }
                                                    //$txtSelection .= "<option value='$key' $isSelected>$value</option>";
                                                    $strLabel = $fName . "_" . $key;
                                                    //$txtSelection .= "<label for=$strLabel><input type=checkbox id=$strLabel name=$fName" . "[]" . "  value='" . $dsSpec->id . "' $isSelected>" . $dsSpec->name . "</label><br/>";
                                                    $txtSelection .= "<input type=checkbox id=$strLabel name=$fName" . "[]" . "  value='" . $dsSpec->id . "' $isSelected><label for=$strLabel>" . $dsSpec->name . "</label><br/>";
                                                }
                                            }
                                        }
                                        $txtSelection .= "</fieldSet>";
                                        $txtSelection .= "</div>";
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            "<div class='selectInput'>" . //"<select type='" . $fieldSpec['type'] . "' name='$fName' id='_$fName' >" .
                                            $txtSelection . //"</select>" .
                                            //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            "</div>",
                                        ));
                                        break;
                                    case "texts":
                                        //==kalau model sudah punya data source bawaan, bukan relasi
                                        if (isset($fieldSpec['dataParams']) && is_array($fieldSpec['dataParams'])) {
                                            $txtSelection = "<div class='panel-body'>";
                                            $txtSelection .= "<fieldSet>";
                                            foreach ($fieldSpec['dataParams'] as $param) {
                                                $defaultValue = isset($fieldData[$param]) ? $fieldData[$param] : "";
                                                $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                                $txtSelection .= "<a data-toggle='tooltip' data-placement='left' title='" . $fieldSpec['label'] . " " . $param . "'>";
                                                $txtSelection .= "<input $readonlyStr type='" . "text" . "' autocomplete=off id=$param name=" . $fName . "_" . $param . " placeholder='" . $xorPlaceHolder . " $param' value='" . $defaultValue . "' class='form-control fc-modal' autocomplete='off' onfocus=\"this.select()\"><br/>";
                                                $txtSelection .= "</a>";
                                            }
                                            $txtSelection .= "</fieldSet>";
                                            $txtSelection .= "</div>";
                                        }
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            "<div class='selectInput'>" . //"<select type='" . $fieldSpec['type'] . "' name='$fName' id='_$fName' >" .
                                            $txtSelection . //"</select>" .
                                            //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            "</div>",
                                        ));
                                        break;
                                    case "hidden":
                                        //                                        cekMerah($fName);
                                        $fieldRow = (array(
                                            " ",
                                            "<input type='hidden' name='$fName' placeholder='" . $fieldSpec['label'] . "' value='$defaultValue'>",
                                        ));
                                        break;
                                    case "text":
                                        // arrPrint($fieldSpec);
                                        if($data_aktif > 0){
                                            $readonlyStok = isset($fieldSpec['stokValidate']) && $fieldSpec['stokValidate'] == 1 ? "readonly" : "";
                                            $msg = isset($fieldSpec['stokValidate']) && $fieldSpec['stokValidate'] == 1 ? "stok ada $data_aktif, maka data tidak bisa diganti" : "";
                                        }
                                        else{
                                            $readonlyStok = "";
                                            $msg = "";
                                        }
                                        if (isset($fieldSpec['editable']) && ($fieldSpec['editable'] === false) && ($o != null)) {
                                            $disabled = "disabled";
                                        }
                                        else {
                                            $disabled = "";
                                        }

                                        if (isset($fieldSpec["eventonchange"]) && $fName == 'used') {
                                            $defaultValue = 48;
                                        }

                                        if (isset($fieldSpec["eventonchange"]) && $fName == 'value_used') {
                                            $defaultValue = ($o[0]->harga_perolehan * 1) > 0 ? ($o[0]->harga_perolehan * 1) - 1 : 0;
                                        }

                                        $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                        $eventKeyup = isset($fieldSpec['eventTrigger']) ? $fieldSpec['eventTrigger'] : "";
                                        $extInput = "";
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            "<input $readonlyStr $readonlyStok $disabled  type='" . "text" . "' maxlength='" . $length . "' name='$fName' id='_$fName' placeholder='" . $xorPlaceHolder . "' value='" . $defaultValue . "' class='form-control fc-modal' autocomplete='off'  onfocus=\"this.select()\" $eventKeyup> $inputHidden_disabled
                                            <div style='width: 300px;' id='notif_$fName' class='meta'>$msg</div>",
                                        ));
                                        break;
                                    case "file":
                                        if (isset($defaultValue) && $defaultValue !== '') {
                                            $img_scr = " src='$defaultValue'";
                                            $hiden_val = "<input type='hidden' name='$fName' value='$defaultValue'>";
                                        }
                                        else {
                                            $imageAvail = base_url() . "public/images/img_blank.gif?v=edan";
                                            $img_scr = "src='$imageAvail'";
                                            $hiden_val = "";
                                        }
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                        $images_str = "<div class='thumbnail'>";
                                        $images_str .= "<img $img_scr class='img-responsive' width='150px'>";
                                        $images_str .= "<div class='caption'>";
                                        $images_str .= "<input id='input-1a' type='" . "file" . "' $readonlyStr maxlength='" . $length . "' name='$fName' id='_$fName' placeholder='" . $xorPlaceHolder . "'  class='form-control fc-modal' autocomplete='off' data-show-preview='TRUE'  multiple data-show-upload='false'>";
                                        $images_str .= "$hiden_val";
                                        $images_str .= "</div>";
                                        $images_str .= "</div>";
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            $images_str
                                            //"<div class='$defaultClassStyle'>" .
                                            //"<input type='" . "file" . "' $readonlyStr maxlength='" . $length . "' name='$fName' id='_$fName' placeholder='" . $fieldSpec['label'] . "' value='" . $defaultValue . "' class='form-control fc-modal' autocomplete='off' data-show-preview='TRUE'  multiple data-show-upload='false'>"
                                            //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            //"</div>")
                                        ));
                                        break;
                                    case "image":

                                        $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $keyLabel = array(
                                            "key" => $fName,
                                            "label" => ucwords($fieldSpec['label']),
                                        );

                                        if ($defaultValue != "") {
                                            $images_val = "";
                                            $img_list = "";

                                            //                                            $deleteLink = base_url() . "" . $this->uri->segment(1) . "/delete/" . $id;

                                            $deleteLink = "alert";
                                            $img_scr = "src='$defaultValue'";
                                            $images_del = "<a href='javascript:void(0)' class='btn btn-link' onclick=\"top.confirm_alert_result('Hapus Foto?','foto untuk produk ini akan di hapus','$deleteLink')\" title='klik untuk hapus gambar produk'><i class='fa fa-trash-o'></i></a>";

                                            $img_list .= "<div style='border: 1px solid lightgray;' class='box box-warning'>";
                                            $img_list .= "<div class='box-body'>";
                                            $img_list .= "<div class='col-xs-6 col-sm-4 col-lg-3 no-padding'>";
                                            $img_list .= "<div class='thumbnail'>";
                                            $img_list .= "<img $img_scr class='img-responsive' width='130px'>";

                                            //                                            $img_list .= "<div class='text-center caption'>";
                                            //                                            $img_list .= "$images_del";
                                            //                                            $img_list .= "</div>";

                                            $img_list .= "</div>"; //thumbnail
                                            $img_list .= "</div>"; //col-col 3
                                            $img_list .= "<div class='col-xs-6 col-sm-12 col-lg-12 no-padding'>";
                                            $img_list .= "<div class='text-right'><span class='btn btn-sm btn-info' onclick=\"tutorialQrCode('$fName', '" . ucwords($fieldSpec['label']) . "')\">Upload " . ucwords($fieldSpec['label']) . " From Smartphone</span></div>"; //box-body
                                            $img_list .= "<div class='clearfix'>&nbsp;</div>"; //box-body
                                            $img_list .= "<input type='file' maxlength='" . $length . "' name='$fName' id='_$fName' $readonlyStr placeholder='" . $xorPlaceHolder . "' value='" . $defaultValue . "' class='form-control fc-modal' autocomplete='off' >"; //col-col
                                            $img_list .= "</div>"; //col-col 12
                                            $img_list .= "</div>"; //box-body

                                            //                                            $img_list .= "<div>". json_encode($o) ."</div>"; //box-body
                                            $img_list .= "</div>"; //box-warning

                                            $images_val .= $img_list;

                                            $sc_botton .= " \n<script>
                                                                top.$('#_$fName').fileinput({
                                                                    showUpload: false,
                                                                    maxFileCount: 3,
                                                                    mainClass: 'input-group-lg'
                                                                });
                                                            </script>
                                                ";

                                            $fieldRow = (array(
                                                ucwords($fieldSpec['label']),
                                                "
                                                $images_val
                                                <input type='text' class='hidden' name='tmp_$fName' id='tmp_$fName' value='" . $defaultValue . "'>
                                                "
                                            ));

                                        }
                                        else {

                                            $images_val = "";
                                            $img_list = "";
                                            $imageAvail = base_url() . "public/images/img_blank.gif?v=edan";
                                            $deleteLink = "alert";
                                            $img_scr = "src='$imageAvail'";
                                            $images_del = "<a href='javascript:void(0)' class='btn btn-link' onclick=\"top.confirm_alert_result('Hapus Foto?','foto untuk produk ini akan di hapus','$deleteLink')\" title='klik untuk hapus gambar produk'><i class='fa fa-trash-o'></i></a>";

                                            $img_list .= "<div style='border: 1px solid lightgray;' class='box box-warning'>";
                                            $img_list .= "<div class='box-body'>";
                                            $img_list .= "<div class='col-xs-6 col-sm-4 col-lg-3 no-padding'>";
                                            $img_list .= "<div class='thumbnail'>";
                                            $img_list .= "<img $img_scr class='img-responsive' width='130px'>";

                                            $img_list .= "</div>"; //thumbnail
                                            $img_list .= "</div>"; //col-col 3
                                            $img_list .= "<div class='col-xs-6 col-sm-12 col-lg-12 no-padding'>";
                                            $img_list .= "<div class='text-right'><span class='btn btn-sm btn-info' onclick=\"tutorialQrCode('$fName', '" . ucwords($fieldSpec['label']) . "')\">Upload " . ucwords($fieldSpec['label']) . " From Smartphone</span></div>"; //box-body
                                            $img_list .= "<div class='clearfix'>&nbsp;</div>"; //box-body
                                            $img_list .= "<input type='file' maxlength='" . $length . "' name='$fName' id='_$fName' $readonlyStr placeholder='" . $xorPlaceHolder . "' value='" . $defaultValue . "' class='form-control fc-modal' autocomplete='off' >"; //col-col
                                            $img_list .= "</div>"; //col-col 12
                                            $img_list .= "</div>"; //box-body
                                            //                                            $img_list .= "<div>". json_encode($o) ."</div>"; //box-body

                                            $img_list .= "</div>"; //box-warning

                                            $images_val .= $img_list;

                                            $sc_botton .= " \n<script>
                                                                top.$('#_$fName').fileinput({
                                                                    showUpload: false,
                                                                    maxFileCount: 3,
                                                                    mainClass: 'input-group-lg'
                                                                });
                                                            </script>
                                                ";

                                            $fieldRow = (array(
                                                ucwords($fieldSpec['label']),
                                                "$images_val"
                                            ));

                                        }

                                        break;
                                    case "textarea":
                                        $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            //"<div class='$defaultClassStyle'>" .
                                            "<textarea cols=20 rows='" . $length . "' name='$fName' id='_$fName' $readonlyStr placeholder='" . $xorPlaceHolder . "' class='form-control fc-modal' autocomplete='off'  onfocus=\"this.select()\">" . $defaultValue . "</textarea>"
                                            //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            //"</div>")
                                        ));
                                        break;

                                    case "format-numbering":

                                        $tmpNumb = $defaultValue != '' ? json_decode(base64_decode($defaultValue), 1) : array();

                                        $tmpNumbNew = array();
                                        if (!empty($tmpNumb)) {
                                            foreach ($tmpNumb as $ky => $arrNum) {
                                                $tmpNumbNew[$arrNum['key']] = $arrNum;
                                            }
                                        }

                                        $arrKeyLabel = array(
                                            "dtime" => "Format Tgl & Waktu",
                                            "cabangID" => "ID Cabang",
                                            "olehID" => "ID pelaku",
                                            "pihakID" => "ID pihak ke-3",
                                            "stepCode" => "kode transaksi",
                                            "_company" => "count global number",
                                            "_company_stepCode" => "count jenis transaksi",
                                            "_company_fulldate" => "count Tgl & Waktu",
                                            "_company_cabangID_cabangID" => "count cabang",
                                            "_company_cabangID_pihakID" => "count pihak ke-3",
                                            "_company_cabangID_olehID" => "count pelaku",
                                        );

                                        $digits = 3;

                                        $arrKeySample = array(
                                            "dtime" => date("Ymd"),
                                            "cabangID" => my_cabang_id(),
                                            "olehID" => my_id(),
                                            "pihakID" => "33",
                                            "stepCode" => "466",
                                            "_company" => str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT),
                                            "_company_stepCode" => str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT),
                                            "_company_fulldate" => str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT),
                                            "_company_cabangID_cabangID" => str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT),
                                            "_company_cabangID_pihakID" => str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT),
                                            "_company_cabangID_olehID" => str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT),
                                        );

                                        $cBox = "";
                                        $cBox .= "<div class='box box-warning box-solid'>";
                                        $cBox .= "<div class='box-header'><span style='font-size: 16px;'>Format Numbering / Invoice (SAMPLE FORMAT)</span></div>";
                                        $cBox .= "<div class='box-body no-padding'>";
                                        $cBox .= "<div class='rows'>";
                                        $cBox .= "<div class='container-fluid'>";

                                        $cBox .= "<table id='table_editor_number' style='font-family: monospace;font-size: x-small;' class='table dataTable compact table-borderedx displayx'>";
                                        $cBox .= "<thead>";
                                        $cBox .= "<tr>";

                                        //                                        foreach($arrKeyLabel as $ky => $label){
                                        //
                                        //                                        }
                                        foreach ($tmpNumbNew as $ky => $arrNumb) {
                                            $cBox .= "<th colspans=2 style='cursor:move;' class='text-center' keys='$ky'><br>" . $arrKeyLabel[$ky] . "</th>";
                                            $cBox .= "<th class='text-center' keys='separator'></th>";
                                        }

                                        $cBox .= "</tr>";
                                        $cBox .= "</thead>";

                                        $cBox .= "<tbody>";
                                        $cBox .= "<tr>";

                                        $last = count($arrKeyLabel);

                                        if (!empty($tmpNumb)) {
                                            $u = 0;
                                            foreach ($tmpNumbNew as $ky => $arrNumb) {
                                                $u++;
                                                $keys = $ky;
                                                $separator = isset($arrNumb['separator']) && $arrNumb['separator'] != "" ? $arrNumb['separator'] : "";
                                                $show = isset($arrNumb['show']) && $arrNumb['show'] * 1 == 1 ? "_show" : "_hidden";
                                                $ticBox = isset($arrNumb['show']) && $arrNumb['show'] * 1 == 1 ? "<div class='text-center'><label for='show_tic'>show: </label> <input keys='$keys' id='show_tic_$keys' class='show_tic' type='checkbox' checked></div>" : "<div class='text-center'><label for='show_tic'>show: </label> <input keys='$keys' id='show_tic_$keys' class='show_tic' type='checkbox'></div>";
                                                $cBox .= "<td last='$last' key='$u' class='no-padding'>$ticBox<input name='$keys' value='" . $arrKeySample[$keys] . "' size='1' class='form-control text-center $show urut_numbering' readonly></td>";
                                                if (($u) != $last) {
                                                    $cBox .= "<td width='2%' class='no-padding'><br><br><input name='separator_$keys' onclick='select()' value='$separator' size='1' class='form-control no-padding text-center $show urut_numbering'></td>";
                                                }
                                                else {
                                                    $cBox .= "<td width='2%' class='no-padding'><br><br><input name='separator_$keys' onclick='select()' value='' size='1' class='form-control no-padding text-center $show urut_numbering' readonly></td>";
                                                }
                                            }
                                        }
                                        else {
                                            $noAdd = 0;
                                            foreach ($arrKeyLabel as $ky => $lab) {
                                                $noAdd++;
                                                $keys = $ky;
                                                $separator = ".";
                                                $ticBox = "<div class='text-center'><label for='show_tic'>show: </label> <input keys='$keys' id='show_tic_$keys' class='show_tic ' type='checkbox' checked></div>";
                                                $cBox .= "<td last='$last' key='$noAdd' class='no-padding'>$ticBox<input name='$keys' value='" . $arrKeySample[$keys] . "' size='1' class='form-control text-center _show urut_numbering' readonly></td>";
                                                if (($noAdd) != $last) {
                                                    $cBox .= "<td width='2%' class='no-padding'><br><br><input name='separator_$keys' onclick='select()' value='$separator' size='1' class='form-control no-padding text-center _show urut_numbering'></td>";
                                                }
                                                else {
                                                    $cBox .= "<td width='2%' class='no-padding'><br><br><input name='separator_$keys' onclick='select()' value='' size='1' class='form-control no-padding text-center _hidden urut_numbering' readonly></td>";
                                                }
                                            }
                                        }

                                        $cBox .= "</tr>";
                                        $cBox .= "</tbody>";
                                        $cBox .= "</table>";

                                        $cBox .= "</div>";
                                        $cBox .= "</div>";
                                        $cBox .= "</div>";
                                        $cBox .= "</div>";

                                        $cBox .= "<div class='text-center text-bold text-red alert alert-warning error_hasil_notif hidden'><i class='fa fa-warning'></i> </div>";
                                        $cBox .= "<div class='text-center text-bold hasil_sample_numbering' title='sample number menurut setingan anda'><h3>4666546542466543455664134556643</h3></div>";
                                        $cBox .= "<div style='margin-bottom: 42px;'></div>";
                                        $cBox .= "<textarea class='hidden' type='text' name='$fName' id='_$fName'></textarea>";

                                        $cBox .= "
                                        <script>

                                            top.$('.show_tic').off();
                                            top.$('.show_tic').on('change', function(){
                                                var ticKey = $(this).attr('keys');
                                                setTimeout(function(){
                                                    console.log('ticKey: ' + ticKey + ' isCheck?: ' + $('#show_tic_'+ticKey).is(':checked') );
                                                    if($('#show_tic_'+ticKey).is(':checked')){
                                                        $('input[name='+ticKey+']').removeClass('_show').removeClass('_hidden').addClass('_show');
                                                    }
                                                    else{
                                                        $('input[name='+ticKey+']').addClass('_hidden').removeClass('_show');
                                                    }
                                                    setTimeout(function(){
                                                        showNumbering();
                                                    }, 250);
                                                }, 250);

                                            })

                                            function inArray(needle, haystack) {
                                                var length = haystack.length;
                                                for(var i = 0; i < length; i++) {
                                                    if(haystack[i] == needle) return true;
                                                }
                                                return false;
                                            }

                                            var table = top.$('#table_editor_number').DataTable({
                                                info: false,
                                                paging: false,
                                                searching: false,
                                                ordering: false,
                                                bAutoWidth: true,
                                                colReorder: true
                                            });
                                            $( table.table().container() ).removeClass( 'form-inline' );
                                            table.on( 'column-reorder', delay_v2( function ( e, settings, details ) {
                                                showNumbering();
                                            }, 2500) );
                                            top.$('.urut_numbering').off();
                                            top.$('.urut_numbering').on('keyup', function(){
                                                showNumbering();
                                            })

                                            var listNumb = [
                                                'dtime',
                                                'cabangID',
                                                'olehID',
                                                'pihakID',
                                                'stepCode',
                                                '_company',
                                                '_company_stepCode',
                                                '_company_fulldate',
                                                '_company_cabangID_cabangID',
                                                '_company_cabangID_pihakID',
                                                '_company_cabangID_olehID',
                                            ]

                                            function showNumbering(){
                                                var dtime       = $(\"input[name='show_dtime']:checked\").val();
                                                var format_dtime     = $(\"input[name='format_dtime']:checked\").val();
                                                var arrUrutNumber = $('.urut_numbering');
                                                var hasil_number = '';
                                                var hasil_new_array = {}
                                                top.jQuery.each(arrUrutNumber, function(a, b){
                                                    var show = $(b).hasClass('_show') ? 1 : 0;
                                                    var key = $(b).attr('name');
                                                    if(inArray(key, listNumb)){
                                                        var separator = $('input[name=separator_'+key+']').val();
                                                        hasil_new_array[a] = {
                                                            'key': key,
                                                            'separator': separator,
                                                            'show': show,
                                                            'value': '',
                                                        }
                                                        if( show ){
                                                            hasil_number += $(b).val();
                                                            hasil_number += separator;
                                                        }
                                                    }
                                                })

                                                $('.hasil_sample_numbering h3').html(hasil_number).css('font-size',35).css('text-decoration','underline')
                                                console.log(hasil_number);
                                                console.log(hasil_new_array);
                                                var ganjil=0;
                                                jQuery.each(hasil_new_array, function(aa,bb){
                                                    //console.error(aa);
                                                    if(aa%2!=0){
                                                        console.log(aa%2);
                                                        ganjil++;
                                                    }
                                                })

                                                if(ganjil){
                                                    $('.hasil_sample_numbering').addClass('text-red').removeClass('text-green')
                                                    $('#btnSave').addClass('hidden')
//                                                    .removeAttr('onclick')
                                                }
                                                else{
                                                    $('#_$fName').val(btoa(JSON.stringify(hasil_new_array)));
                                                    $('input[name=json]').val(JSON.stringify(hasil_new_array));
                                                    $('.hasil_sample_numbering').removeClass('text-red').addClass('text-green')
                                                    $('#btnSave').removeClass('hidden')
//                                                    .removeAttr('onclick')
                                                }
                                                console.error('ganjil: ' + ganjil);
                                            }

                                            showNumbering();
                                        </script>



                                        ";

                                        $fieldRow = array(
                                            array(
                                                $cBox,
                                                "colspan='2'",
                                            )
                                        );
                                        break;

                                    case "date":
                                        //                                        $onFocus = isset($fieldSpec['onchange']) ? "onfocus=\"" . $fieldSpec['onchange'] . "\"" : "";
                                        //                                        $onBlur = isset($fieldSpec['onblur']) ? "onblur=\"" . $fieldSpec['onblur'] . ";document.getElementById('notif_$fName').innerHTML=''\"" : "onblur=\"document.getElementById('notif_$fName').innerHTML=''\"";
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : "YYYY-MM-DD";
                                        $onChange = isset($fieldSpec['onchange']) ? "onchange=\"" . $fieldSpec['onchange'] . "\"" : "";
                                        $onBlur = isset($fieldSpec['onblur']) ? "onblur=\"\"" : "onblur=\"\"";

                                        $arr = array(
                                            "dtime_perolehan", "dtime_start"
                                        );

                                        if (isset($fieldSpec["eventonchange"]) && in_array($fName, $arr)) {
                                            $defaultValue = date('Y-m-d');
                                        }

                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            "<input type='date' $onChange $onBlur name='$fName' id='_$fName' $readonlyStr placeholder='" . $xorPlaceHolder . "' required pattern=\"(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])/(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])/(?:30))|(?:(?:0[13578]|1[02])-31))\" title='Enter a date in this format YYYY-MM-DD' value='" . $defaultValue . "' class='form-control fc-modal' autocomplete='off' max='2045-12-31' min='1950-01-01'>
                                            <div style='width: 300px;' id='notif_$fName' class='meta'></div>"
                                        ));
                                        break;
                                    case "number":
                                        $length = isset($fieldSpec['length']) ? $fieldSpec['length'] : "8";
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            //"<div class='$defaultClassStyle'>" .
                                            "<input type='" . "number" . "' maxlength='" . $length . "' name='$fName' $readonlyStr id='_$fName' placeholder='" . $xorPlaceHolder . "' value='" . $defaultValue . "' class='form-control fc-modal' autocomplete='off'  onfocus=\"this.select()\" >"
                                            //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            //"</div>")
                                        ));
                                        break;
                                    case "password":
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label'] . ""),
                                            // "<div class='input-group'>" .
                                            "<input type='" . "password" . "' autocomplete='off' $readonlyStr
                                        maxlength='" . $fieldSpec['length'] . "' name='$fName' id='_$fName' 
                                        placeholder='" . $xorPlaceHolder . "'  class='form-control fc-modal'>"
                                            //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            //     "<span class='input-group-btn'>
                                            //     <button type='button' onclick=\"confirm_alert_result('beneran','ok deh','kkk');\" class='btn btn-warning'><i class='fa fa-bolt'></i></button>
                                            // </span>" .
                                            //     "</div>"
                                        ));
                                        break;
                                }
                            }
                            else {
                                switch ($fieldSpec['type']) {
                                    case "varchar":
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            //"<div class='$defaultClassStyle'>" .
                                            "<a data-toggle='tooltip' data-placement='left' title='" . $fieldSpec['label'] . "'>" . "<input type='" . $fieldSpec['type'] . "' $readonlyStr autocomplete='off' maxlength='" . $fieldSpec['length'] . "' name='$fName' id='_$fName' placeholder='" . $xorPlaceHolder . "' value='$defaultValue' class='form-control fc-modal'>" . //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            "</a>"
                                            //"</div>")
                                        ));
                                        break;
                                    case "decimal":
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            //"<div class='$defaultClassStyle'>" .
                                            "<input type='" . $fieldSpec['type'] . "' $readonlyStr autocomplete='off' maxlength='" . floor($fieldSpec['length'] - 2) . "' name='$fName' id='_$fName' placeholder='" . $xorPlaceHolder . "' value='$defaultValue' class='form-control fc-modal'>"
                                            //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            // "</div>")
                                        ));
                                        break;
                                    default:
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label']),
                                            //"<div class='$defaultClassStyle'>" .
                                            "<a data-toggle='tooltip' data-placement='left' title='" . $fieldSpec['label'] . "'>" . "<input type='" . $fieldSpec['type'] . "' $readonlyStr autocomplete='off' maxlength='" . $fieldSpec['length'] . "' name='$fName' id='_$fName' placeholder='" . $xorPlaceHolder . "' value='$defaultValue' class='form-control fc-modal'>" . //"<label for='_$fName'>".$fieldSpec['label']."</label>".
                                            "</a>"
                                            //"</div>")
                                        ));
                                        break;
                                }
                            }
                        }

                        //region generate validation error-messages
                        if ($validCounter > 0) {
                            if (isset($validRules[$fName]) && in_array("required", $validRules[$fName])) {
                                $fieldRow[0] = "<span style='border-bottom:2px #992200 solid;'><b>" . $fieldRow[0] . "</b><span class='text-red'>*</span></span>";
                            }
                        }
                        //endregion

                        // region config tool $fName
                        if (sizeof($dataTools) > 0) {

                            foreach ($dataTools as $fTool => $paramTool) {

                                if ($paramTool['kolom'] == $fName) {
                                    $arrDataTool = $paramTool;

                                    $statusStr = $arrDataTool['status'];
                                    $label = $arrDataTool['label'];
                                    $fa_icon = isset($arrDataTool['icon']) ? $arrDataTool['icon'] : "fa-circle";
                                    $msg = isset($arrDataTool['message']) ? $arrDataTool['message'] : "";
                                    $msg_f = str_replace('{def_password}', createDefaultPassword(), $msg);

                                    if ($statusStr == $edit) {
                                        $aidi = isset($o[0]->id) ? $o[0]->id : "";
                                        $target_link = base_url() . $arrDataTool['target'] . "/" . $aidi;
                                        $xorPlaceHolder = isset($fieldSpec["placeholder"]) ? $fieldSpec["placeholder"] : $fieldSpec['label'];
                                        // cekHijau("$fName");
                                        $fieldRow = (array(
                                            ucwords($fieldSpec['label'] . ""),
                                            "<div class='input-group'>" . "<input type='" . "password" . "' autocomplete='off' $readonlyStr
                                            maxlength='" . $fieldSpec['length'] . "' name='$fName' id='_$fName'
                                            placeholder='" . $xorPlaceHolder . "' value='$defaultValue' class='form-control fc-modal'>" .
                                            "<span class='input-group-btn'>
                                            <button type='button'" . "onclick=\"confirm_alert_result('WARNING !!!','$msg_f','$target_link','oke');\"" . // "onclick=\"btn_result('$target_link');\"" .
                                            "title='$label' data-toggle='tooltip' class='btn btn-warning'><i class='fa $fa_icon'></i></button>
                                            </span>" . "</div>",
                                        ));

                                    }
                                }
                            }
                        }
                        // endregion config tool

                        $t->addRow($fieldRow);
                    }
                    $yesBtnLabel = "Save";

                    $btnCancel = ("<button class ='btn btn-default' data-dismiss ='modal'><span class='glyphicon glyphicon-arrow-left'></span> Cancel</button>");

                    if ($validCounter > 0) {
                        $validRules = $this->object->getValidationRules();
                        if (isset($validRules[$fieldName]) && in_array("required", $validRules[$fieldName])) {
                            $fieldRow[0] = "<span style='border-bottom:2px #992200 solid;'><b>" . $fieldRow[0] . "</b><span class='text-red'>*</span></span>";
                        }
                    }

                    if (isset($this->specs['id'])) { //===form has an ID, then use button with validation instead of submit
                        $t->addRow(array(
                            $btnCancel,
                            "<button id='btnSave' type=button class='btn btn-success pull-right' onClick=\"this.disabled=true;document.getElementById('" . $this->specs['id'] . "').submit();\" '><span class='glyphicon glyphicon-ok'> $yesBtnLabel</button>",
                        ));
                    }

                    else { //===then use a submit button
                        $t->addRow(array(
                            $btnCancel,
                            "<button id='btnSave' type=submit class='btn btn-success pull-right' value='Save'><span class='glyphicon glyphicon-ok'> $yesBtnLabel</button>",
                        ));
                    }

                    $t->closeTable();

                    if ($limitedMode) {
                        $this->content .= "<div class='alert alert-default-dot text-center'>";
                        $this->content .= "Limited mode activated. Only certain fields match your access-right can be modified.";
                        $this->content .= "</div class='alert alert-warning text-center'>";
                    }

                    $this->content .= "<div class='table-responsive'>";
                    $this->content .= $t->getContent(); //==grab table into form object
                    $this->content .= "</div class='table-responsive'>";
                    $this->content .= $sc_botton;
                    $this->content .= "<script> if(typeof wysihtml5 != 'undefined'){ $('textarea').wysihtml5(); };</script>";
                }
                // arrPrint($selectedFields);
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
        $valUnion = 0;
        if (count($this->object->getValidationRules()) > 0) {
            //==do some validation
            foreach ($this->object->getFields() as $fieldName => $spec) {
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                if (array_key_exists($fName, $this->object->getValidationRules())) {
                    //echo "$fName to be validated.<br>";
                    // <editor-fold defaultstate="collapsed" desc="validasi kolom wajib/required">
                    if (in_array("required", $this->object->getValidationRules()[$fName])) {
                        if (isset($spec['dataParams'])) {
                            foreach ($spec['dataParams'] as $param) {
                                if (strlen($this->object->input->post($fName . "_" . $param)) < 1) {
                                    //echo "$fName can not be empty!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName . "_" . $param] = array(
                                        "fieldName" => $fName . "_" . $param,
                                        "fieldLabel" => $spec['label'] . " " . $param,
                                        "errMsg" => $spec['label'] . " " . $param . " wajib diisi (dilengkapi)!",
                                    );
                                }
                            }
                        }
                        else {
                            if (strlen($this->object->input->post($fName)) < 1) {
                                $unionValid = 0;
                                if (in_array($fName, $this->object->getUnionPairs())) {

                                }
                                else {
                                    echo "$fName wajib diisi (dilengkapi)!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => $spec['label'] . " wajib diisi (dilengkapi)!",
                                    );
                                }


                            }
                        }
                    }

                    if (in_array("numberOnly", $this->object->getValidationRules()[$fName])) {
                        if (isset($spec['dataParams'])) {
                            foreach ($spec['dataParams'] as $param) {
                                if (!is_numeric($this->object->input->post($fName . "_" . $param))) {
                                    //echo "$fName wajib diisi (dilengkapi)!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName . "_" . $param] = array(
                                        "fieldName" => $fName . "_" . $param,
                                        "fieldLabel" => $spec['label'] . " " . $param,
                                        "errMsg" => $spec['label'] . " " . $param . " only accept numbers!",
                                    );
                                }
                            }
                        }
                        else {
                            if (!is_numeric($this->object->input->post($fName))) {
                                if (in_array($fName, $this->object->getUnionPairs())) {

                                }
                                else {
                                    echo "$fName wajib diisi (dilengkapi)!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => $spec['label'] . " only accept numbers!",
                                    );
                                }


                            }
                        }
                    }

                    if (in_array("unique", $this->object->getValidationRules()[$fName])) {
                        //$tmpEvalQuery = $this->object->getByCondition(array($fName => $this->object->input->post($fName)))->result();
                        if (in_array($fName, $this->object->getUnionPairs())) {
                        }
                        else {
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
                                        "errMsg" => " $fName with value " . $this->object->input->post($fName) . " already exist!",
                                    );
                                }
                            }
                        }

                    }


                    if (in_array("alphanumeric", $this->object->getValidationRules()[$fName])) {
                        if (isset($spec['dataParams'])) {
                            foreach ($spec['dataParams'] as $param) {
                                if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $this->object->input->post($fName . "_" . $param))) {
                                    //echo "$fName wajib diisi (dilengkapi)!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName . "_" . $param] = array(
                                        "fieldName" => $fName . "_" . $param,
                                        "fieldLabel" => $spec['label'] . " " . $param,
                                        "errMsg" => $spec['label'] . " " . $param . " only alphanumeric accepted and must be started with letter!",
                                    );
                                }
                            }
                        }
                        else {
                            if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $this->object->input->post($fName))) {
                                //echo "$fName wajib diisi (dilengkapi)!<br>";
                                $invalidCounter++;
                                $this->validationResults[$fName] = array(
                                    "fieldName" => $fName,
                                    "fieldLabel" => $spec['label'],
                                    "errMsg" => $spec['label'] . " only alphanumeric accepted and must be started with letter!",
                                );
                            }
                        }
                    }

                    //tambahan image
                    if (in_array("image", $this->object->getValidationRules()[$fName])) {
                        //                        arrPrint($spec['dataParams']);
                        //                        if (isset($spec['dataParams'])) {
                        //untuk validasi sini
                        if (isset($spec['dataParams'])) {
                            foreach ($spec['dataParams'] as $param) {
                                if (strlen($this->object->input->post($fName . "_" . $param)) < 1) {
                                    //echo "$fName wajib diisi (dilengkapi)!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName . "_" . $param] = array(
                                        "fieldName" => $fName . "_" . $param,
                                        "fieldLabel" => $spec['label'] . " " . $param,
                                        "errMsg" => $spec['label'] . " " . $param . " wajib diisi (dilengkapi)!",
                                    );
                                }
                            }
                        }
                        else {
                            if (!empty($_FILES[$fName]['name'])) {
                                $isImage = $_FILES[$fieldName]['type'];
                                $maxSize = imageSizeAllow();
                                if (substr($isImage, '0', '5') == "image") {
                                    if ($_FILES[$fieldName]['size'] > $maxSize) {
                                        $invalidCounter++;
                                        $this->validationResults[$fName] = array(
                                            "fieldName" => $fName,
                                            "fieldLabel" => $spec['label'],
                                            "errMsg" => "image size more than 10MB NOT ALLOWED!",
                                        );
                                    }

                                }
                                else {
                                    $invalidCounter++;
                                    $this->validationResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => " only image allowed!",
                                    );
                                }
                            }
                            else {
                                //no image to add
                            }

                        }

                        //

                        //                        matiHEre($invalidCounter);
                        //                            foreach ($spec['dataParams'] as $param) {
                        //                                if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $this->object->input->post($fName . "_" . $param))) {
                        //                                    //echo "$fName wajib diisi (dilengkapi)!<br>";
                        //                                    $invalidCounter++;
                        //                                    $this->validationResults[$fName . "_" . $param] = array(
                        //                                        "fieldName"  => $fName . "_" . $param,
                        //                                        "fieldLabel" => $spec['label'] . " " . $param,
                        //                                        "errMsg"     => $spec['label'] . " " . $param . " only alphanumeric accepted and must be started with letter!",
                        //                                    );
                        //                                }
                        //                            }
                        //                        }
                        //                        else {
                        //                            arrPrint($_FILES);
                        //                           matiHEre("xxx $fieldName");
                        //                        }
                    }

                }
            }
            //            die("hoop");

        }
        if ($invalidCounter > 0) {//==ada yang tidak valid===
            return false;

        }
        else {
            //die("Nothing to validate");
            return true;
        }
    }

    public function isUnionValid()
    {
        $pairedField = array();
        foreach ($this->object->getUnionPairs() as $colPair) {
            if (array_key_exists($colPair, $this->object->input->post())) {
                $pairedField[$colPair] = $this->object->input->post($colPair);
            }
        }
        //region cek field required not union
        $invalidCounter = 0;
        if (count($this->object->getValidationRules()) > 0) {
            //==do some validation
            $requiredFields = array();
            foreach ($this->object->getFields() as $fieldName => $spec) {
                //                arrPrint($spec);
                $fName = isset($spec['kolom']) ? $spec['kolom'] : $fieldName;
                $requiredFields[$fName] = $spec["label"];
                if (array_key_exists($fName, $pairedField)) {
                    if (sizeof(array_filter($pairedField)) < 1) {
                        if (in_array("required", $this->object->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (strlen($this->object->input->post($fName . "_" . $param)) < 1) {
                                        //echo "$fName wajib diisi (dilengkapi)!<br>";
                                        $invalidCounter++;
                                        $this->validationResults[$fName . "_" . $param] = array(
                                            "fieldName" => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg" => $spec['label'] . " " . $param . " wajib diisi (dilengkapi)!",
                                        );
                                    }
                                }
                            }
                        }

                    }
                    else {
                        if (in_array("numberOnly", $this->object->getValidationRules()[$fName])) {
                            cekHijau($fName . "**");
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    cekBiru("ndak isset $fName ||* $param");
                                    if (strlen($this->object->input->post($fName . "_" . $param)) < 1) {

                                    }
                                    else {
                                        cekHijau(" isset $fName");
                                        if (!is_numeric($this->object->input->post($fName . "_" . $param))) {
                                            //echo "$fName wajib diisi (dilengkapi)!<br>";
                                            $invalidCounter++;
                                            $this->validationResults[$fName . "_" . $param] = array(
                                                "fieldName" => $fName . "_" . $param,
                                                "fieldLabel" => $spec['label'] . " " . $param,
                                                "errMsg" => $spec['label'] . " " . $param . " only accept numbers!",
                                            );
                                        }
                                    }

                                }
                            }
                            else {
                                if (strlen($this->object->input->post($fName)) > 1) {
                                    if (!is_numeric($this->object->input->post($fName))) {
                                        echo "$fName wajib diisi (dilengkapi)!<br>";
                                        $invalidCounter++;
                                        $this->validationResults[$fName] = array(
                                            "fieldName" => $fName,
                                            "fieldLabel" => $spec['label'],
                                            "errMsg" => $spec['label'] . " only accept numbers!",
                                        );


                                    }
                                }

                            }
                        }
                        if (in_array("unique", $this->object->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (strlen($this->object->input->post($fName . "_" . $param)) > 0) {
                                        //echo "$fName wajib diisi (dilengkapi)!<br>";
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
                                                    "errMsg" => " $fName with value " . $this->object->input->post($fName) . " already exist!",
                                                );
                                            }
                                        }
                                    }
                                }
                            }
                            else {
                                if (strlen($this->object->input->post($fName)) > 0) {
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
                                                "errMsg" => " $fName with value " . $this->object->input->post($fName) . " already exist!",
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
                else {
                    if (array_key_exists($fName, $this->object->getValidationRules())) {
                        if (in_array("required", $this->object->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (strlen($this->object->input->post($fName . "_" . $param)) < 1) {
                                        //echo "$fName wajib diisi (dilengkapi)!<br>";
                                        $invalidCounter++;
                                        $this->validationResults[$fName . "_" . $param] = array(
                                            "fieldName" => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg" => $spec['label'] . " " . $param . " wajib diisi (dilengkapi)!",
                                        );
                                    }

                                }
                            }
                            else {
                                if (strlen($this->object->input->post($fName)) < 1) {
                                    echo "$fName wajib diisi (dilengkapi)!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => $spec['label'] . " wajib diisi (dilengkapi)!",
                                    );

                                }
                            }
                        }

                        if (in_array("numberOnly", $this->object->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {

                                    if (!is_numeric($this->object->input->post($fName . "_" . $param))) {
                                        //echo "$fName wajib diisi (dilengkapi)!<br>";
                                        $invalidCounter++;
                                        $this->validationResults[$fName . "_" . $param] = array(
                                            "fieldName" => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg" => $spec['label'] . " " . $param . " only accept numbers!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (!is_numeric($this->object->input->post($fName))) {
                                    echo "$fName wajib diisi (dilengkapi)!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => $spec['label'] . " only accept numbers!",
                                    );

                                }
                            }
                        }

                        if (in_array("unique", $this->object->getValidationRules()[$fName])) {
                            //$tmpEvalQuery = $this->object->getByCondition(array($fName => $this->object->input->post($fName)))->result();
                            $this->object->addFilter($fName . "='" . $this->object->input->post($fName) . "'");
                            $tmpEvalQuery = $this->object->lookupAll()->result();
                            //                            cekBiru($this->object->last_query);


                            //==validasi unique hanya dikenakan pada penambahan data
                            if ($this->mode == "addProcess") {
                                //if ($tmpEvalQuery > 0) {
                                if (sizeof($tmpEvalQuery) > 0) {

                                    //echo "entri sudah ada <br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => " $fName with value " . $this->object->input->post($fName) . " already exist!",
                                    );
                                }
                            }
                        }
                        if (in_array("alphanumeric", $this->object->getValidationRules()[$fName])) {
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $this->object->input->post($fName . "_" . $param))) {
                                        //echo "$fName wajib diisi (dilengkapi)!<br>";
                                        $invalidCounter++;
                                        $this->validationResults[$fName . "_" . $param] = array(
                                            "fieldName" => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg" => $spec['label'] . " " . $param . " only alphanumeric accepted and must be started with letter!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (!preg_match("/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/", $this->object->input->post($fName))) {
                                    //echo "$fName wajib diisi (dilengkapi)!<br>";
                                    $invalidCounter++;
                                    $this->validationResults[$fName] = array(
                                        "fieldName" => $fName,
                                        "fieldLabel" => $spec['label'],
                                        "errMsg" => $spec['label'] . " only alphanumeric accepted and must be started with letter!",
                                    );
                                }
                            }
                        }

                        //tambahan image
                        if (in_array("image", $this->object->getValidationRules()[$fName])) {
                            //                        arrPrint($spec['dataParams']);
                            //                        if (isset($spec['dataParams'])) {
                            //untuk validasi sini
                            if (isset($spec['dataParams'])) {
                                foreach ($spec['dataParams'] as $param) {
                                    if (strlen($this->object->input->post($fName . "_" . $param)) < 1) {
                                        //echo "$fName wajib diisi (dilengkapi)!<br>";
                                        $invalidCounter++;
                                        $this->validationResults[$fName . "_" . $param] = array(
                                            "fieldName" => $fName . "_" . $param,
                                            "fieldLabel" => $spec['label'] . " " . $param,
                                            "errMsg" => $spec['label'] . " " . $param . " wajib diisi (dilengkapi)!",
                                        );
                                    }
                                }
                            }
                            else {
                                if (!empty($_FILES[$fName]['name'])) {
                                    $isImage = $_FILES[$fieldName]['type'];
                                    $maxSize = imageSizeAllow();
                                    if (substr($isImage, '0', '5') == "image") {
                                        if ($_FILES[$fieldName]['size'] > $maxSize) {
                                            $invalidCounter++;
                                            $this->validationResults[$fName] = array(
                                                "fieldName" => $fName,
                                                "fieldLabel" => $spec['label'],
                                                "errMsg" => "image size more than 10MB NOT ALLOWED!",
                                            );
                                        }

                                    }
                                    else {
                                        $invalidCounter++;
                                        $this->validationResults[$fName] = array(
                                            "fieldName" => $fName,
                                            "fieldLabel" => $spec['label'],
                                            "errMsg" => " only image allowed!",
                                        );
                                    }
                                }
                                else {
                                    //no image to add
                                }

                            }

                        }
                    }

                }
            }

        }


        //endregion
        if ($invalidCounter > 0) {
            return false;
        }
        else {
            if (sizeof(array_filter($pairedField)) > 0 && $invalidCounter == 0) {
                //lolos validasi
                return true;
            }
            else {
                $labelField = "";
                foreach ($pairedField as $paireName => $pairVal) {
                    $label = isset($requiredFields[$paireName]) ? $requiredFields[$paireName] : $paireName;
                    $labelField .= "$label/";
                }
                $labelField = rtrim($labelField, "/");
                $this->validationResults[$labelField] = array(
                    "fieldName" => $labelField,
                    "fieldLabel" => $labelField,
                    "errMsg" => $labelField . " wajib diisi (dilengkapi)!",
                );

                //                    die("kowk");
                return false;
            }

        }

    }

}

?>