<?php
/**
 * Created by PhpStorm.
 * User: widi
 * Date: 10/22/2018
 * Time: 4:34 PM
 */
//arrPrint ($style);
//cekHere($mode);
switch ($mode) {

    case "view":

        $p = New Layout("$title", "$subTitle", "application/template/default.html");
        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
//                                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
//                "menu_sub" => callSubMEnu(),
                "content" => "",
                "profile_name" => $this->session->login['nama'],
            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }

        $contents = isset($contens) ? $contens : "";
        $contents .= isset($scriptLoad) ? $scriptLoad : "";

        $p->setContent($contents);
        $p->render();
        break;
    case "index" :
//        arrPrint($content);
//        die();
//        $p = New Layout("", "", "application/template/pages2.html");
        $p = New Layout("", "", "application/template/default.html");
        $template = array(
            'table_open' => '<table id="table" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="" style="text-align: center;">',
        );
        $this->table->set_template($template);
        $content = "";
        if (sizeof($arrayHeader) > 0) {
            $header_f = array();
//            $header_f[] = array('data' => "No", 'class' => 'text-center text-muted');
            foreach ($arrayHeader as $kolom => $label) {
                $header_f[] = array('data' => $label, 'class' => 'text-center text-muted');
            }
            $this->table->set_heading($header_f);
            if (sizeof($items) > 0) {
//arrPrint($items);
                foreach ($items as $key => $data) {
//                    arrPrint($data);
                    $isi = array();
                    foreach ($arrayHeader as $kolom => $label) {
                        $value = $data[$kolom];
                        $isi[] = array('data' => $value);
                    }
//                    arrPRint($isi);
                    $this->table->add_row($isi);

                }
//                die();

            }
            else {
                $this->table->add_row(array(
                    'data' => "no category found for ",
                    'colspan' => count($arrayHeader) + 2,
                    'class' => 'text-center',
                ));
            }

            $content .= ($this->table->generate());
        }
//        $content .= "<br><a href='javascript:void(0);' class='btn btn-success' onclick='$btnClick'>Opname</a>";
        $content .= "<br><a href='javascript:void(0);' class='btn btn-success' onclick=\"$btnClick\">Opname</a>";

        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
//                                "trans_menu" => callTransMenu(),
                "float_menu_atas" => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar" => callMenuTaskbar(),
                "btn_back" => callBackNav(),
//                "menu_sub"     => callSubMenu(),
                "content" => $content,
                "profile_name" => $this->session->login['nama'],
            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }

//        $p->setContent($contens);
        $p->render();

        break;

    case "doPrint":
//        arrPrint($fixedElements);
        $title_x = urldecode($this->uri->segment(4));
        $p = New Layout("", "", "application/template/opname.html");
        $p->addTags(
            array(
                "content" => $content,
                "title" => "stok opname $title_x",
                "companyProfile" => $companyProfile['companyProfile']['contents'][0],
//                "fixedElements" => $fixedElements,

            )
        );

        //  endregion menu left
        if (isset($lebar_modal)) {
            $p->setLebarModal($lebar_modal);
        }
        $p->render();
        break;

}