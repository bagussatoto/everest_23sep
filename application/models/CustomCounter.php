<?php

/**
 * Nama kelas: CustomCounter
 *
 * Kode ditulis oleh: azizisasi
 */
class CustomCounter extends CI_Model
{

    //kode kamu di bawah sini broh!
    protected $filters = array();
    protected $criteria = array();
    protected $criteria2 = array();
    private $type;
    private $row;
    private $params = array();
    private $paramValues = array();
    private $value;
    private $content;
    private $rules = array();
    private $tableNames = array(
        "number" => "counters_custom_number",
        "content" => "counters_custom_content",
        "rekening" => "counters_rekening_number",
        //---------
        "number2" => "counters_number",
    );
    private $modul;
    private $stepCode;


    public function getTableName()
    {
        return $this->tableNames;
    }

    // <editor-fold defaultstate="collapsed" desc=" getter-setter ">
    public function getModul()
    {
        return $this->modul;
    }

    public function setModul($modul)
    {

        $this->modul = $modul;
    }

    public function getStepCode()
    {
        return $this->stepCode;
    }

    public function setStepCode($stepCode)
    {
        $this->stepCode = $stepCode;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function __construct()
    {
        //parent::__construct();
//        $this->rules = array(
//            "334"  => array("jenis|oleh_id"),
//            "334r" => array("jenis|oleh_id"),
//            "466"  => array("jenis|oleh_id", "jenis|suppliers_id"),
//            "467"  => array("jenis|oleh_id", "jenis|suppliers_id"),
//            "488"  => array("jenis|oleh_id"),
//            "489"  => array("jenis|oleh_id"),
//            "582"  => array("jenis|oleh_id", "jenis|customers_id"),
//            "582r" => array("jenis|oleh_id", "jenis|customers_id"),
//            "583"  => array("jenis|oleh_id"),
//            "583r" => array("jenis|oleh_id"),
//            "663"  => array("jenis|oleh_id"),
//            "663r" => array("jenis|oleh_id"),
//            "671"  => array("jenis|oleh_id"),
//            "671r" => array("jenis|oleh_id"),
//            "749"  => array("jenis|oleh_id"),
//            "757"  => array("jenis|oleh_id"),
//            "759"  => array("jenis|oleh_id"),
//            "759r" => array("jenis|oleh_id"),
//            "769"  => array("jenis|oleh_id"),
//            "776"  => array("jenis|oleh_id"),
//            "776r" => array("jenis|oleh_id"),
//            "779"  => array("jenis|oleh_id"),
//            "967"  => array("jenis|oleh_id", "jenis|suppliers_id"),
//            "967r" => array("jenis|oleh_id", "jenis|suppliers_id"),
//            "982"  => array("jenis|oleh_id", "jenis|customers_id"),
//            "982r" => array("jenis|oleh_id", "jenis|customers_id"),
//            "983"  => array("jenis|oleh_id"),
//            "983r" => array("jenis|oleh_id"),
//        );
    }

    public function setTableName($tableNames)
    {
        $this->tableNames = $tableNames;
    }

    public function getRules()
    {
        return $this->rules;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getRow()
    {
        return $this->row;
    }

    public function setRow($row)
    {
        $this->row = $row;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParamValues()
    {
        return $this->paramValues;
    }

    public function setParamValues($paramValues)
    {
        $this->paramValues = $paramValues;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getContent()
    {
        return $this->content;
    }

// </editor-fold>

    public function setContent($content)
    {
        $this->content = $content;
    }

    function getNewCountOri($requiredParamKeys, $requiredParamValues)
    {
        $query = $this->db->get_where($this->tableNames['number'], array("type" => $this->type));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $ctr => $rows) {
                $ctrData[] = array(
                    "id" => $rows->id,
                    "paramKeys" => $rows->paramKeys,
                    "paramValues" => $rows->paramValues,
                    "value" => $rows->value,
                );
            }

            //return $data;
        }

        $paramString = "";
        foreach ($requiredParamValues as $v) {
            $paramString .= "$v.";
        }

        if ($query->num_rows() > 0) {
            foreach ($ctrData as $spec) {
                $paramKeys = unserialize(base64_decode($spec['paramKeys']));
                $paramValues = unserialize(base64_decode($spec['paramValues']));
                //$nextValue=0;
                if ($this->array_equal($paramKeys, $requiredParamKeys) && $this->array_equal($paramValues, $requiredParamValues)) {
                    $id = $spec['id'];
//                    cekHere("old value:" . $spec['value']);
                    $nextValue = ($spec['value'] + 1);
                    $mode = "update";
                    $id = $spec['id'];
                    $ketemu = TRUE;
                }
            }
            //echo $nextValue."<br>";
        }

        if (!isset($ketemu)) {

            $mode = "create";
            $id = 0;
            $nextValue = 1;
        }

        //echo "<hr>";

        $keys = base64_encode(serialize($requiredParamKeys));
        $values = base64_encode(serialize($requiredParamValues));
//        echo "AKAN DIRETURN oleh counter ";
//        print_r(array(
//            "id"          => $id,
//            "value"       => $nextValue,
//            "paramString" => $paramString . $nextValue,
//            "mode"        => $mode,
//        ));
        return array(
            "id" => $id,
            "value" => $nextValue,
            "paramString" => $paramString . $nextValue,
            "mode" => $mode,
        );
    }

    function array_equal($a, $b)
    {
//        $foo = serialize($a);
//        $bar = serialize($b);
        $foo = serialize(array_map('trim', $a));
        $bar = serialize(array_map('trim', $b));
        if ($foo == $bar) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

    function getNewCount($requiredParamKeys, $requiredParamValues)
    {
//        arrprintwebs($requiredParamKeys);
//        arrprintpink($requiredParamValues);
//        cekMErah($this->modul);
//        matiHEre();
        if (!isset($this->modul)) {
            matiHere("Gagal menulis counter modul LINE: " . __LINE__ . " || Class: " . __CLASS__ . ". Silahkan hubungi admin untuk melakukan pengecekan");
        }
        if (!isset($this->stepCode)) {
            matiHere("Gagal menulis counter kode LINE: " . __LINE__ . " || Class: " . __CLASS__ . ". Silahkan hubungi admin untuk melakukan pengecekan");
        }
        $query = $this->db->get_where($this->tableNames['number'], array("type" => $this->type, "modul" => $this->modul, "step_code" => $this->stepCode));
//        cekLime($this->db->last_query());
//        matiHere();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $ctr => $rows) {
                $ctrData[] = array(
                    "id" => $rows->id,
                    "paramKeys" => $rows->paramKeys,
                    "paramValues" => $rows->paramValues,
                    "value" => $rows->value,
                );
            }

            //return $data;
        }


        $paramString = "";
        foreach ($requiredParamValues as $v) {
            $paramString .= "$v.";
        }
//arrPrint($query->num_rows());
//        matiHEre();
        if ($query->num_rows() > 0) {
            foreach ($ctrData as $spec) {
                $paramKeys = unserialize(base64_decode($spec['paramKeys']));
                $paramValues = unserialize(base64_decode($spec['paramValues']));
//                arrPrint($paramValues);
//                arrPrintWebs($requiredParamValues);
                //$nextValue=0;
                if ($this->array_equal($paramKeys, $requiredParamKeys) && $this->array_equal($paramValues, $requiredParamValues)) {
                    $id = $spec['id'];
//                    cekMerah($id."__");
//                    cekHere("old value:" . $spec['value']);
                    $nextValue = ($spec['value'] + 1);
                    $mode = "update";
                    $id = $spec['id'];
                    $ketemu = TRUE;
                }

            }
            //echo $nextValue."<br>";
        }
//        matiHere(__LINE__);
//        matiHEre(__LINE__);
        if (!isset($ketemu)) {

            $mode = "create";
            $id = 0;
            $nextValue = 1;
        }

        //echo "<hr>";

        $keys = base64_encode(serialize($requiredParamKeys));
        $values = base64_encode(serialize($requiredParamValues));
//        echo "AKAN DIRETURN oleh counter ";
//        print_r(array(
//            "id"          => $id,
//            "value"       => $nextValue,
//            "paramString" => $paramString . $nextValue,
//            "mode"        => $mode,
//        ));
        return array(
            "id" => $id,
            "value" => $nextValue,
            "paramString" => $paramString . $nextValue,
            "mode" => $mode,
        );
    }

    function writeNewCountOri($requiredParamKeys, $requiredParamValues, $requiredParamKeys_raw, $requiredParamValues_raw)
    {
        $paramKeys = base64_encode(serialize($requiredParamKeys));
        $paramValues = base64_encode(serialize($requiredParamValues));
        $this->db->insert(
            $this->tableNames['number'],
            array(
                "type" => $this->type,
                "paramKeys" => $paramKeys,
                "paramValues" => $paramValues,
                "paramKeys_raw" => $requiredParamKeys_raw,
                "paramValues_raw" => $requiredParamValues_raw,
                "value" => "1")
        );
        return $this->db->insert_id();
    }

    function writeNewCount($requiredParamKeys, $requiredParamValues, $requiredParamKeys_raw, $requiredParamValues_raw, $modul = "")
    {
        $paramKeys = base64_encode(serialize($requiredParamKeys));
        $paramValues = base64_encode(serialize($requiredParamValues));
        if (!isset($this->modul)) {
            matiHere("Gagal menulis counter modul  " . __LINE__ . "" . __CLASS__ . " Silahkan hubungi admin untuk melakukan pengecekan");
        }
        if (!isset($this->stepCode)) {
            matiHere("Gagal menulis counter kode  " . __LINE__ . "" . __CLASS__ . " Silahkan hubungi admin untuk melakukan pengecekan");
        }
        $this->db->insert(
            $this->tableNames['number'],
            array(
                "modul" => $this->modul,
                "step_code" => $this->stepCode,
                "type" => $this->type,
                "paramKeys" => $paramKeys,
                "paramValues" => $paramValues,
                "paramKeys_raw" => $requiredParamKeys_raw,
                "paramValues_raw" => $requiredParamValues_raw,
                "value" => "1")
        );
        return $this->db->insert_id();
    }

    function updateCount($id, $value)
    {
//        $q = "update counters_custom_number set value='$value' where id='$id'";
//        cekHere($q);
//        $does = mysql_query($q);
//        //$query = $this->db->query($q);

        $this->db->where(array("id" => $id));
        $this->db->update($this->tableNames['number'], array("value" => $value));
        return $this->db->affected_rows();
    }

    function writeContent($type, $row, $content)
    {
//        $q = "insert into counters_custom_content (type,row,content) values ('$type','$row','$content')";
//        cekHere($q);
//        //$query = $this->db->query($q);
//        $does = mysql_query($q);
        $this->db->insert($this->tableNames['content'], array("type" => $type, "row" => $row, "content" => $content));
        return $this->db->insert_id();
    }

    function getByType()
    {

        $query = $this->db->get_where($this->tableNames['content'], array("type" => $this->type));

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $ctr => $rows) {
                $ctrData[] = array(
                    "id" => $rows[$ctr]->id,
                    "type" => $rows[$ctr]->type,
                    "row" => $rows[$ctr]->row,
                    "content" => $rows[$ctr]->content,
                );
            }
            return $ctrData;
        }
        else {
            return null;
        }

        //return $this->db->get();
    }


    public function lookupAllNumbers()
    {
        return $this->db->get($this->tableNames['number']);

    }

    public function lookupAllContents()
    {
        return $this->db->get($this->tableNames['content']);

    }

    //--------------------------------
    function writeNewCountRekening($rekening, $rekening_detail_id, $cabang_id, $position = "0")
    {
        $this->db->insert(
            $this->tableNames['rekening'],
            array(
                "position" => $position,
                "type" => "rekening",
                "rekening" => $rekening,
                "rekening_pembantu_id" => $rekening_detail_id,
                "cabang_id" => $cabang_id,
                "value" => "1"
            )
        );
        return $this->db->insert_id();
    }

    function updateCountRekening($id, $value)
    {

        $this->db->where(array("id" => $id));
        $this->db->update($this->tableNames['rekening'], array("value" => $value));
        showLast_query("ungu");
        return $this->db->affected_rows();
    }

    function getNewCountRekening($rekening, $rekening_detail_id, $cabang_id, $position = "0")
    {
        $query = $this->db->get_where(
            $this->tableNames['rekening'],
            array(
                "position" => $position,
                "type" => "rekening",
                "rekening" => $rekening,
                "rekening_pembantu_id" => $rekening_detail_id,
                "cabang_id" => $cabang_id,
            )
        )->result();
        showLast_query("pink");
        if (sizeof($query) > 0) {
            $mode = "update";
            $id = $query[0]->id;
            $nextValue = $query[0]->value + 1;
        }
        else {
            $mode = "create";
            $id = 0;
            $nextValue = 1;
        }

        return array(
            "id" => $id,
            "value" => $nextValue,
//            "paramString" => $paramString . $nextValue,
            "mode" => $mode,
        );
    }

    function setCounterRekening($rekening, $rekening_detail_id, $cabang_id, $position = "0")
    {
        $newCount = $this->getNewCountRekening($rekening, $rekening_detail_id, $cabang_id, $position);
        switch ($newCount['mode']) {
            case "update":
                $result = $this->updateCountRekening($newCount['id'], $newCount['value']);

                break;
            case "create":
                $result = $this->writeNewCountRekening($rekening, $rekening_detail_id, $cabang_id, $position);

                break;

        }
        return $newCount['value'];
        showLast_query("biru");
    }


    //--------------------------------
    function getNewCountNumber($params, $paramsValues, $counter_jenis, $counter_detail)
    {
        $pakai_ini = 0;
        if($pakai_ini){
            $query = $this->db->get_where(
                $this->tableNames['number2'],
                array(
                    "type" => "transaksi",
                    "p_keys" => "$params",
                    "p_values" => "$paramsValues",
                    "p_jenis" => "$counter_jenis",
                    "p_detail" => "$counter_detail",
                )
            )->result();
        }
        else{
            //region update dengan query builder
            $localFilters = array(
                "type" => "transaksi",
                "p_keys" => "$params",
                "p_values" => "$paramsValues",
                "p_jenis" => "$counter_jenis",
                "p_detail" => "$counter_detail",
            );
            $query_builder = $this->db->select()
                ->from($this->tableNames['number2'])
                ->where($localFilters)
                ->get_compiled_select();

            $query = $this->db->query("{$query_builder} FOR UPDATE")->result();
            //endregion
        }

        if (sizeof($query) > 0) {
            $mode = "update";
            $id = $query[0]->id;
            $nextValue = $query[0]->value + 1;
        }
        else {
            $mode = "create";
            $id = 0;
            $nextValue = 1;
        }

        return array(
            "id" => $id,
            "value" => $nextValue,
            "mode" => $mode,
        );
    }

    function updateCountNumber($id, $value)
    {
        $this->db->where(array("id" => $id));
        $this->db->update($this->tableNames['number2'], array("value" => $value));
        return $this->db->affected_rows();
    }

    function setCounterNumber($params, $paramsValues, $counter_jenis = "0", $counter_detail = "0")
    {
        $newCount = $this->getNewCountNumber($params, $paramsValues, $counter_jenis, $counter_detail);
        switch ($newCount['mode']) {
            case "update":
                $result = $this->updateCountNumber($newCount['id'], $newCount['value']);
//                showLast_query("orange");
                break;
            case "create":
                $result = $this->writeNewCountNumber($params, $paramsValues, $counter_jenis, $counter_detail);
//                showLast_query("kuning");
                break;

        }
        return $newCount['value'];

    }

    function writeNewCountNumber($params, $paramsValues, $counter_jenis, $counter_detail)
    {
        $this->db->insert(
            $this->tableNames['number2'],
            array(
                "p_jenis" => "$counter_jenis",
                "p_detail" => "$counter_detail",
                "p_keys" => "$params",
                "p_values" => "$paramsValues",
                "type" => "transaksi",
                "value" => "1"
            )
        );
        return $this->db->insert_id();
    }

    function getNewCountData($requiredParamKeys, $requiredParamValues, $ctrData)
    {
        $paramString = "";
        foreach ($requiredParamValues as $v) {
            $paramString .= "$v.";
        }

        if (count($ctrData) > 0) {
            foreach ($ctrData as $spec) {
                // $paramKeys = blobDecode($spec['paramKeys']);
                // $paramValues = blobDecode($spec['paramValues']);
                $paramKeys = $spec['paramKeys'];
                $paramValues = $spec['paramValues'];
                if ($this->array_equal($paramKeys, $requiredParamKeys) && $this->array_equal($paramValues, $requiredParamValues)) {
                    $id = $spec['id'];
                    $nextValue = ($spec['value'] + 1);
                    $mode = "update";
                    $id = $spec['id'];
                    $ketemu = TRUE;
                }
            }

        }

        if (!isset($ketemu)) {

            $mode = "create";
            $id = 0;
            $nextValue = 1;
        }
        $keys = base64_encode(serialize($requiredParamKeys));
        $values = base64_encode(serialize($requiredParamValues));

        return array(
            "id" => $id,
            "value" => $nextValue,
            "paramString" => $paramString . $nextValue,
            "mode" => $mode,
        );
    }

    function updateData($where, $data)
    {
        // arrprint($this->getTableName());
        // matiHEre($this->tableNames);

        $this->db->where($where);
        $this->db->update($this->tableNames, $data);
        // cekMErah($this->db->last_query());

    }

    function getNewCountDataNew($requiredParamKeys, $requiredParamValues, $srcParams, $srcValues)
    {
        $src_key = $srcParams . "|" . $srcValues;

        $query = $this->db->get_where($this->tableNames['number'], array("type" => $this->type, "src_key" => $src_key));
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $ctr => $rows) {
                $ctrData[] = array(
                    "id" => $rows->id,
                    "paramKeys" => $rows->paramKeys,
                    "paramValues" => $rows->paramValues,
                    "value" => $rows->value,
                );
            }


        }
// arrPrintWebs($ctrData);

        $paramString = "";
        foreach ($requiredParamValues as $v) {
            $paramString .= "$v.";
        }

        if ($query->num_rows() > 0) {
            foreach ($ctrData as $spec) {
                $paramKeys = unserialize(base64_decode($spec['paramKeys']));
                $paramValues = unserialize(base64_decode($spec['paramValues']));
                //$nextValue=0;
                if ($this->array_equal($paramKeys, $requiredParamKeys) && $this->array_equal($paramValues, $requiredParamValues)) {
                    $id = $spec['id'];

                    $nextValue = ($spec['value'] + 1);
                    $mode = "update";
                    $id = $spec['id'];
                    $ketemu = TRUE;
                }
            }
            //echo $nextValue."<br>";
        }

        if (!isset($ketemu)) {

            $mode = "create";
            $id = 0;
            $nextValue = 1;
        }

        return array(
            "id" => $id,
            "value" => $nextValue,
            "paramString" => $paramString . $nextValue,
            "mode" => $mode,
        );
    }

    function writeNewCountData($requiredParamKeys, $requiredParamValues, $requiredParamKeys_raw, $requiredParamValues_raw, $srcKey, $srcValue)
    {
        $paramKeys = base64_encode(serialize($requiredParamKeys));
        $paramValues = base64_encode(serialize($requiredParamValues));
        $this->db->insert(
            $this->tableNames['number'],
            array(
                "type" => $this->type,
                "paramKeys" => $paramKeys,
                "paramValues" => $paramValues,
                "paramKeys_raw" => $requiredParamKeys_raw,
                "paramValues_raw" => $requiredParamValues_raw,
                "p_keys" => $srcKey,
                "p_value" => $srcValue,
                "src_key" => "$srcKey|$srcValue",
                "value" => "1")
        );
        return $this->db->insert_id();
    }

    public function lookupAll()
    {
        $criteria = array();
        $criteria2 = "";
        if (sizeof($this->filters) > 0) {
            //            arrPrint($this->filters);
            $this->fetchCriteria();
            $criteria = $this->getCriteria();
            $criteria2 = $this->getCriteria2();
        }
        if (sizeof($criteria) > 0) {
            $this->db->where($criteria);
        }
        if ($criteria2 != "") {
            $this->db->where($criteria2);
        }
        if (isset($this->sortBy) && sizeof($this->sortBy) > 0) {
            $this->db->order_by($this->tableName . "." . $this->sortBy['kolom'], $this->sortBy['mode']);

        }

        $res = $this->db->get($this->tableNames['number']);

        return $res;

    }

}
