<?php

if (!function_exists('am_get_default_request_rules')) {
    function am_get_default_request_rules()
    {
        return array(
            "id" => array("type" => "int"),
            "iid" => array("type" => "int"),
            "pid" => array("type" => "int"),
            "trID" => array("type" => "int"),
            "topID" => array("type" => "int"),
            "stID" => array("type" => "string", "max_length" => 100),
            "sID" => array("type" => "string", "max_length" => 100),
            "status" => array("type" => "int"),
            "step" => array("type" => "int"),
            "y" => array("type" => "int", "min" => 2000, "max" => 2100),
            "year" => array("type" => "int", "min" => 2000, "max" => 2100),
            "m" => array("type" => "int", "min" => 1, "max" => 12),
            "month" => array("type" => "int", "min" => 1, "max" => 12),
            "d" => array("type" => "int", "min" => 1, "max" => 31),
            "page" => array("type" => "int", "min" => 1),
            "jml" => array("type" => "decimal"),
            "newQty" => array("type" => "decimal"),
            "qty_opname" => array("type" => "decimal"),
            "harga" => array("type" => "decimal"),
            "hargapluspajak" => array("type" => "decimal"),
            "dpp_persen_pengganti" => array("type" => "decimal"),
            "dpp_nilai_pengganti" => array("type" => "decimal"),
            // `date` di modul report dipakai campuran `Y-m` dan `Y-m-d`.
            // Validasi format spesifik tetap dilakukan di endpoint terkait.
            "date" => array("type" => "string", "max_length" => 10),
            "date1" => array("type" => "date"),
            "date2" => array("type" => "date"),
            "viewMode" => array("type" => "enum", "values" => array("list", "thumbnail")),
            "force" => array("type" => "int", "min" => 0, "max" => 1),
            "stop1" => array("type" => "int", "min" => 0, "max" => 1),
            "debug" => array("type" => "int", "min" => 0, "max" => 1),
            "json" => array("type" => "int", "min" => 0, "max" => 1),
            "search" => array("type" => "string", "max_length" => 255),
            "rawPrev" => array("type" => "string", "max_length" => 2048),
            "rawBuilderURL" => array("type" => "string", "max_length" => 2048),
            "addParams" => array("type" => "string", "max_length" => 10000),
            "param" => array("type" => "string", "max_length" => 20000),
            "params" => array("type" => "string", "max_length" => 20000),
            "singleRefID" => array("type" => "int"),
            "byexternid" => array("type" => "string", "max_length" => 2000),
            "byrekmainid" => array("type" => "string", "max_length" => 2000),
            "type" => array("type" => "string", "max_length" => 50),
            "mode" => array("type" => "string", "max_length" => 50),
            "mobMode" => array("type" => "string", "max_length" => 50),
            "key_source" => array("type" => "string", "max_length" => 50),
            "selector" => array("type" => "string", "max_length" => 100),
            "f" => array("type" => "string", "max_length" => 100),
            "o" => array("type" => "string", "max_length" => 100),
            "u" => array("type" => "string", "max_length" => 2048),
            "x" => array("type" => "string", "max_length" => 100),
            "mb" => array("type" => "string", "max_length" => 20),
            "test" => array("type" => "string", "max_length" => 100),
            "key" => array("type" => "string", "max_length" => 100),
            "valCol" => array("type" => "string", "max_length" => 100),
            "val" => array("type" => "string", "max_length" => 4000),
            "valValue" => array("type" => "string", "max_length" => 4000),
            "note1" => array("type" => "string", "max_length" => 1000),
            "note2" => array("type" => "string", "max_length" => 1000),
        );
    }
}

if (!function_exists('am_normalize_request_scalar')) {
    function am_normalize_request_scalar($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        $value = trim($value);
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value);

        return $value;
    }
}

if (!function_exists('am_normalize_request_array')) {
    function am_normalize_request_array($data)
    {
        if (!is_array($data)) {
            return am_normalize_request_scalar($data);
        }

        $result = array();
        foreach ($data as $key => $value) {
            $result[$key] = am_normalize_request_array($value);
        }

        return $result;
    }
}

if (!function_exists('am_validate_request_value')) {
    function am_validate_request_value($rawValue, $spec, &$normalizedValue, &$errorMessage)
    {
        $normalizedValue = null;
        $errorMessage = "";

        if (is_array($rawValue) || is_object($rawValue)) {
            $errorMessage = "Format input tidak valid.";
            return false;
        }

        $type = "string";
        $min = null;
        $max = null;
        $enumValues = array();
        $maxLength = 255;

        if (is_string($spec)) {
            $type = $spec;
        }
        elseif (is_array($spec)) {
            $type = isset($spec['type']) ? $spec['type'] : "string";
            $min = array_key_exists('min', $spec) ? $spec['min'] : null;
            $max = array_key_exists('max', $spec) ? $spec['max'] : null;
            $enumValues = isset($spec['values']) && is_array($spec['values']) ? $spec['values'] : array();
            $maxLength = isset($spec['max_length']) ? (int) $spec['max_length'] : 255;
        }

        $rawValue = am_normalize_request_scalar($rawValue);

        switch ($type) {
            case "int":
            case "integer":
                if (!preg_match('/^-?[0-9]+$/', (string) $rawValue)) {
                    $errorMessage = "Input harus berupa angka bulat.";
                    return false;
                }
                $normalizedValue = (int) $rawValue;
                if ($min !== null && $normalizedValue < (int) $min) {
                    $errorMessage = "Input lebih kecil dari batas minimum.";
                    return false;
                }
                if ($max !== null && $normalizedValue > (int) $max) {
                    $errorMessage = "Input melebihi batas maksimum.";
                    return false;
                }
                return true;

            case "decimal":
            case "float":
                if (!is_numeric($rawValue)) {
                    $errorMessage = "Input harus berupa angka desimal.";
                    return false;
                }
                $normalizedValue = (float) $rawValue;
                if ($min !== null && $normalizedValue < (float) $min) {
                    $errorMessage = "Input lebih kecil dari batas minimum.";
                    return false;
                }
                if ($max !== null && $normalizedValue > (float) $max) {
                    $errorMessage = "Input melebihi batas maksimum.";
                    return false;
                }
                return true;

            case "enum":
                if (sizeof($enumValues) < 1) {
                    $errorMessage = "Konfigurasi enum tidak valid.";
                    return false;
                }
                if (!in_array((string) $rawValue, array_map('strval', $enumValues), true)) {
                    $errorMessage = "Nilai input tidak diperbolehkan.";
                    return false;
                }
                $normalizedValue = (string) $rawValue;
                return true;

            case "date":
                if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', (string) $rawValue)) {
                    $errorMessage = "Format tanggal harus YYYY-MM-DD.";
                    return false;
                }
                $year = (int) substr($rawValue, 0, 4);
                $month = (int) substr($rawValue, 5, 2);
                $day = (int) substr($rawValue, 8, 2);
                if (!checkdate($month, $day, $year)) {
                    $errorMessage = "Tanggal tidak valid.";
                    return false;
                }
                $normalizedValue = $rawValue;
                return true;

            case "string":
            default:
                $normalizedValue = (string) $rawValue;
                if ($maxLength > 0 && strlen($normalizedValue) > $maxLength) {
                    $errorMessage = "Panjang input melebihi batas maksimum.";
                    return false;
                }
                return true;
        }
    }
}

if (!function_exists('am_reject_invalid_input')) {
    function am_reject_invalid_input($ci, $fieldName, $errorMessage, $statusCode)
    {
        $message = "Input tidak valid pada field <strong>" . htmlspecialchars($fieldName, ENT_QUOTES, 'UTF-8') . "</strong>. " . $errorMessage;
        if (is_object($ci) && isset($ci->output)) {
            $ci->output->set_status_header((int) $statusCode);
        }

        if (function_exists('lgShowAlert')) {
            echo lgShowAlert($message);
        }
        else {
            echo $message;
        }

        return false;
    }
}

if (!function_exists('am_get_request_value_by_source')) {
    function am_get_request_value_by_source($ci, $fieldName, $source)
    {
        switch ($source) {
            case "get":
                return $ci->input->get($fieldName, true);
            case "post":
                return $ci->input->post($fieldName, true);
            case "get_post":
            default:
                return $ci->input->get_post($fieldName, true);
        }
    }
}

if (!function_exists('am_apply_request_guard')) {
    function am_apply_request_guard($ci, $rules, $options = array())
    {
        if (!is_object($ci) || !isset($ci->input)) {
            return true;
        }
        if (PHP_SAPI === "cli") {
            return true;
        }

        $statusCode = isset($options['status_code']) ? (int) $options['status_code'] : 400;
        $sanitizeBags = isset($options['sanitize_bags']) ? (bool) $options['sanitize_bags'] : true;

        if ($sanitizeBags) {
            $safeGet = $ci->input->get(null, true);
            $safePost = $ci->input->post(null, true);
            $_GET = is_array($safeGet) ? am_normalize_request_array($safeGet) : array();
            $_POST = is_array($safePost) ? am_normalize_request_array($safePost) : array();
        }

        if (!is_array($rules) || sizeof($rules) < 1) {
            return true;
        }

        foreach ($rules as $fieldName => $spec) {
            $source = "get_post";
            $required = false;
            if (is_array($spec)) {
                $source = isset($spec['source']) ? $spec['source'] : "get_post";
                $required = isset($spec['required']) ? (bool) $spec['required'] : false;
            }

            $rawValue = am_get_request_value_by_source($ci, $fieldName, $source);
            if ($rawValue === null || $rawValue === "") {
                if ($required) {
                    return am_reject_invalid_input($ci, $fieldName, "Input wajib diisi.", $statusCode);
                }
                continue;
            }

            $normalizedValue = null;
            $errorMessage = "";
            if (!am_validate_request_value($rawValue, $spec, $normalizedValue, $errorMessage)) {
                return am_reject_invalid_input($ci, $fieldName, $errorMessage, $statusCode);
            }

            if ($source === "post") {
                $_POST[$fieldName] = $normalizedValue;
            }
            elseif ($source === "get") {
                $_GET[$fieldName] = $normalizedValue;
            }
            else {
                if (isset($_POST[$fieldName])) {
                    $_POST[$fieldName] = $normalizedValue;
                }
                if (isset($_GET[$fieldName])) {
                    $_GET[$fieldName] = $normalizedValue;
                }
                if (!isset($_GET[$fieldName]) && !isset($_POST[$fieldName])) {
                    $_GET[$fieldName] = $normalizedValue;
                }
            }
        }

        return true;
    }
}
