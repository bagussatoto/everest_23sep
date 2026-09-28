<?php
/**
 * Created by thomas Maya Graha Kencana.
 * Date: 29/06/18
 * Time: 15:34
 * ------------------------
 * form dibuat didalam tempalte login.html
 * ------------------------
 */
// include_once
switch ($mode) {
    case "forms":

        $tab = "";
        foreach($modul as $mod => $row){
            $label = $row['label'];
            $sub_label = $row['sub_label'];
            $tab .= "<li class='active'><a href='#tab_$mod' data-toggle='tab'>$label</a></li>";
        }

        $mainScriptButton = "";
        $subScriptButton = "";

        $tab_content = "";
        foreach($modul as $mod => $row){
            $label = $row['label'];
            $sub_label = $row['sub_label'];

            $tab_content .= "<div class='tab-pane active' id='tab_$mod'>";
            $tab_content .= "<p>Integrasi Whatsapp ke <a target='_blank' href='https://whapify.id/dashboard'>https://whapify.id</a></p>";
            $tab_content .= "<p>Jika belum memiliki Account <b>whapify.id</b> disarankan untuk mendaftar terlebih dahulu.</p>";

            if(isset($row['form']) && !empty($row['form']) ){
                $method = isset($row['form_method']) ? $row['form_method'] . "" : "GET";
                $action = isset($row['form_url']) ? $row['form_url'] . "" : "index";
//                $tab_content .= "<form role='form' method='$method' action='$action'>";
                foreach($row['form'] as $kk => $fRow){

                    switch($fRow['type']){
                        case "input":
                            $label = $fRow['label'];
                            $ids_ = $fRow['id'];
                            $inType = isset($fRow['input_type']) ? $fRow['input_type'] : "text";
                            $placeholder = isset($fRow['placeholder']) ? $fRow['placeholder'] : "Masukkan $label";

                            $disabled = "";
                            if(isset($fRow['disabled']) && $fRow['disabled'] == true){
                                $disabled = "disabled";
                            }

//                            $readonly = "";
//                            if(isset($fRow['readonly']) && $fRow['readonly'] == true){
//                                $readonly = "readonly";
//                            }

                            $defaultValue = isset($fRow['defaultValue']) && $fRow['defaultValue'] !== "" ? $fRow['defaultValue'] : "";
                            $defaultValue = isset($default_modul_value[$mod][$ids_]) ? $default_modul_value[$mod][$ids_] : $defaultValue;

                            if(isset($fRow['value_alias'])){
                                $defaultValue = $defaultValue==1 && isset($fRow['value_alias'][$defaultValue]) ? "<i class='fa fa-check-square text-success'></i>  " . $fRow['value_alias'][$defaultValue] : "<i class='fa fa-times-circle text-danger'></i>  " . $fRow['value_alias'][0];
                            }

//                            cekHijau( $defaultValue );
//                            cekMerah(isset($fRow['value_alias']));
//                            cekHere("=================================");
//                            cekHere(__LINE__);

                            if(isset($fRow['readonly']) && $fRow['readonly'] == true){
                                $tab_content .= "<div class='form-group'>";
                                $tab_content .= "<label for='$ids_'>$label</label>";
                                $tab_content .= "<span class='form-control' disabled id='$ids_'>$defaultValue</span>";
                                $tab_content .= "</div>";
                            }
                            else{
                                $tab_content .= "<div class='form-group'>";
                                $tab_content .= "<label for='$ids_'>$label</label>";
                                $tab_content .= "<input $readonly $disabled type='$inType' value='$defaultValue' class='form-control' id='$ids_' name='$ids_' placeholder='$placeholder'>";
                                $tab_content .= "</div>";
                            }

                            break;
                        case "button":
                            $btn_type = $fRow['btn_type'];
                            $label = $fRow['label'];
                            $ids_ = $fRow['id'];
                            $addIcon = $fRow['add_icon'];
                            $add_class = isset($fRow['add_class']) ? $fRow['add_class'] : "btn-primary";
                            $tab_content .= "<div class='form-group'>";
                            $tab_content .= "<button type='$btn_type' id='$ids_' class='btn $add_class'> $addIcon $label </button>";
                            $tab_content .= "</div>";

                            if(isset($fRow['sub_function_js']) && $fRow['sub_function_js'] !="" ){
                                $subScriptButton .= $fRow['sub_function_js'];
                            }
                            break;
                    }
                }
//                $tab_content .= "</form>";
            }
            $tab_content .= "</div>";
        }

        //matiHere( isset($fRow['value_alias'][$defaultValue]) );

        $str .= "<div class='nav-tabs-custom'>";
        $str .= "<ul class='nav nav-tabs'>";
        $str .= $tab;
        $str .= "</ul>"; //end-off nav-tabs

        $str .= "<div class='tab-content'>";
        $str .= $tab_content;
        $str .= "</div>"; //end-off tab-content

        $str .= "</div>"; //end-off nav-tabs-custom

        $p = New Layout("API Integration", "Intergrasi dengan API Pihak ke-3", MODUL_TEMPLATE_PATH . "template/setup.html");

        $p->addTags(array(
            "menu_left" => callMenuLeft(),
            "menu_taskbar" => callMenuTaskbar(),
            "float_menu_bawah" => callFloatMenu(),
            "content" => $str,
            "errMsg" => $errMsg,
            "stop_time" => "",
            "main_table_title" => "Integration Setup",
            "history_sender_table_title" => "History Sender",
            "history_setting_table_title" => "History Perubahan Setting",
            "history_activity_table_title" => "History Activity",
            "history_sender" => "",
            "history_setting" => "",
            "history_activity" => "",
            "mainScriptButton" => $mainScriptButton,
            "subScriptButton" => $subScriptButton,
        ));

        $p->render();

        break;
    default:
        cekHere();
        break;
}
?>