<?php
/**
 * Created by thomas Maya Graha Kencana.
 * Date: 29/06/18
 * Time: 15:34
 * ------------------------
 * form dibuat didalam tempalte login.html
 * ------------------------
 */
//print_r($mode);

switch ($mode) {

    case "index":
        $mytitle = "Indeks Data";
        $hasil = "";

        //$myId = my_id();
        //$myCabangId = my_cabang_id();

        $p = New Layout("$mytitle", "sub judul", "application/template/pages.html");


        if (sizeof($availMenus) > 0) {
            $hasil .= "<ul class='list-group'>";
            foreach ($availMenus as $jenis => $jenisName) {
                $hasil .= "<li class='list-group-item'>";
                if (array_key_exists($jenis, $availNewMenus)) {
                    $hasil .= "<a href='" . base_url() . "DataView/add/$jenisName'><span class='glyphicon glyphicon-plus'></span></a> ";
                }
                $hasil .= "<a href='" . base_url() . "DataView/view/$jenisName/active/1'>$jenisName</a>";
                $hasil .= "</li class='list-group-item'>";
            }
            $hasil .= "</ul class='list-group'>";
        }


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "btn_back" => callBackNav(),
//                "content"=>$hasil,
                "stop_time" => "",
                "profile_name" => $this->session->login['nama'],
            )
        );

        $p->render();
        break;
    case "viewData_":
        $p = New Layout("$mytitle", "sub judul", "application/template/pages.html");


        $template = array(
            'table_open' => '<table border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info text-uppercase" style="text-align: center;">',
        );
        $this->table->set_template($template);

        //  region segment-segment
        $segment_uri_array = $this->uri->segment_array();
        $segment_total = $this->uri->total_segments();
        $segment_page = $segment_total;
        $segment_url = "";
        for ($x = 1; $x <= ($segment_page - 1); $x++) {
            $segment_url .= $this->uri->segment($x) . "/";
        }
        $segment_url = rtrim($segment_url, "/");
        $segment_url .= "";

        $segment_url_right = "";
        for ($x = 1; $x <= ($segment_page - 2); $x++) {
            $segment_url_right .= $this->uri->segment($x) . "/";
        }
        $segment_url_right = rtrim($segment_url_right, "/");
        $segment_url_right .= "";

        $base_url = base_url();
        //  endregion segment-segment


        //  region bagian header tabel
        if (isset($header)) {
            if ((sizeof($header) > 0) && (is_array($header))) {
                $header_f[] = "No.";
                foreach ($header as $header_value) {
                    $header_result = isset($fields[$header_value]['label']) ? $fields[$header_value]['label'] : $header_value;
                    $header_result_f = array('data' => $header_result, 'class' => 'text-center');

                    $header_f[] = $header_result_f;
                }
                if (isset($action)) {
                    if ((sizeof($action) > 0) && (is_array($action))) {
                        $header_f[] = "Action";
                    }
                }

                $this->table->set_heading($header_f);
            }
        }
        //  endregion bagian header tabel

        //  region bagian isi tabel
        if (isset($data)) {
            if ((sizeof($data) > 0) && (is_array($data))) {
                $cf_button = $this->config->item('button');
                $no = $position;
                foreach ($data as $data_result) {

                    $no++;
                    $isi = array();
                    $isi[] = $no;
                    if (isset($header)) {
                        if ((sizeof($header) > 0) && (is_array($header))) {
                            foreach ($header as $header_value) {
//cekHere(":: $header_value ::");
                                $kolom = isset($fields[$header_value]['kolom']) ? $fields[$header_value]['kolom'] : "";
                                $type = isset($fields[$header_value]['inputType']) ? $fields[$header_value]['inputType'] : "";
                                $input_name = isset($fields[$header_value]['inputName']) ? $fields[$header_value]['inputName'] : "";
                                $label = isset($fields[$header_value]['label']) ? $fields[$header_value]['label'] : "";
//                                $input_value = sizeof($data) > 0 ? $data[0]->$kolom : "";
                                if (sizeof($data) > 0) {
                                    $input_value = isset($data[0]->$kolom) ? $data[0]->$kolom : "";
                                }
                                else {
                                    $input_value = "";
                                }


                                $data_result_f = "";
                                switch ($type) {
                                    case "number":
                                        $data_result_f = isset($data_result->$kolom) ? number_format($data_result->$kolom, 0, '', '.') : 0;
                                        break;
                                    case "dropdown":
                                        $str_dropdown = "";
                                        //  region array dropdown
                                        if ((isset($kategori)) && (sizeof($kategori[$input_name])) && (is_array($kategori[$input_name]))) {
                                            if ($this->uri->segment(2) == "view") {
                                                $dropdown_disabled = "disabled";
                                            }
                                            else {
                                                $dropdown_disabled = "";
                                            }
                                            $anu = base_url() . $this->uri->segment(1) . "/settingProses/$data_result->id?j=$input_name&v=";
                                            $str_dropdown = "<select name='$kolom' $dropdown_disabled class='form-control btn btn-default btn-block' style='width:100%;'
                                    onchange=\"getData('$anu'+this.value, 'hasil_nya')\">";


                                            $str_dropdown .= "<option value='0'>pilih disini</option>";
                                            foreach ($kategori[$input_name] as $kategori_data) {
                                                $selected_dropdown = $data_result->$kolom == $kategori_data->id ? "selected" : "";
                                                $str_dropdown .= "<option value='" . $kategori_data->id . "' $selected_dropdown>$kategori_data->nama</option>";
                                            }
                                            $str_dropdown .= "</select>";

                                        }
                                        else {
                                            $str_dropdown .= $data_result->$kolom;
                                        }
                                        $data_result_f .= $str_dropdown;
                                        //  endregion array dropdown
                                        //                                $data_result_f .= form_dropdown($kolom, $options, $data_result->$kolom, $attributes_dropdown);
                                        break;
                                    case "button":

                                        $button_attribute = isset($this->config->item('button')[$label]['attribute']) ? $this->config->item('button')[$label]['attribute'] : "";

                                        switch ($button_attribute) {

                                            case "alert":

                                                $link_alert = isset($action_status[$data_result->id][$label]) ? $action_status[$data_result->id][$label]['link'] : "";
                                                $label_f = isset($action_status[$data_result->id][$label]) ? $action_status[$data_result->id][$label]['label'] : "";
//                                                cekHere(":: $link_alert");
                                                $item_val = isset($this->config->item('button')[$label]['title']) ? $this->config->item('button')[$label]['title'] : "";
                                                $item_val_f = $item_val . " " . strtoupper($data_result->nama);
                                                $link_button_setting = "#";
                                                $anchor_attribute = array(
                                                    "title" => "Klik untuk melakukan pengaturan $label",
                                                    "class" => "btn btn-primary btn-block",
                                                    "onclick" => "confirm_alert('Peringatan', '$item_val_f', '$link_alert/" . $data_result->id . "')",
                                                );
                                                if ($data_result->cabang_id == 0) {
                                                    $anchor_attribute["disabled"] = "";
                                                }
                                                $data_result_f = form_button($link_button_setting, $label_f, $anchor_attribute);
                                                break;
                                            default:
                                            case "modal":
                                                $anchor_attribute = array(
                                                    "title" => "Klik untuk melakukan pengaturan $label",
                                                    "class" => "btn btn-primary btn-block",
                                                    "data-target" => "#myModal",
                                                    "data-toggle" => "modal",
                                                );
                                                if ($data_result->cabang_id == 0) {
                                                    $link_button_setting = "#";
                                                    $anchor_attribute["disabled"] = "";
                                                    $data_result_f = form_button($link_button_setting, $label, $anchor_attribute);
                                                }
                                                else {
                                                    $link_button_setting = base_url() . $this->uri->segment(1) . "/$label/$data_result->id";
                                                    $data_result_f = anchor($link_button_setting, $label, $anchor_attribute);
                                                }
                                                break;
                                        }


//                                        cekHere(":: $link_button_setting :: $label ::");
                                        break;
                                    default:
//                                        if((is_array($data_result))&&(sizeof($data_result)>0)){
//                                            $data_result_f = "";
//                                        }
//                                        else{

                                        if (array_key_exists($data_result->jenis, $this->config->item('view'))) {
                                            if (in_array($kolom, $this->config->item('view')[$data_result->jenis])) {
                                                $link_x = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "";
                                                $data_result_f = "<a href='$link_x/item/" . $data_result->id . "/1'>" . $data_result->$kolom . " </a>";
                                            }
                                            else {
                                                $data_result_f = isset($data_result->$kolom) ? $data_result->$kolom : "";
                                            }
                                        }
                                        else {

                                            if (isset($data_result->$kolom)) {
                                                if ((is_array($data_result->$kolom)) && (sizeof($data_result->$kolom))) {

                                                    $data_result_f = "";
                                                    foreach ($data_result->$kolom as $result_kolom) {
                                                        if (isset($fields_komposisi)) {
                                                            if (is_array($fields_komposisi) && (sizeof($fields_komposisi) > 0)) {
                                                                foreach ($fields_komposisi as $fields_komposisi_data) {

                                                                    $kolom_add = isset($fields[$fields_komposisi_data]['kolom']) ? $fields[$fields_komposisi_data]['kolom'] : "";

                                                                    if ($data_result_f == "") {
                                                                        $data_result_f = "<span class='pull-left'>" . $result_kolom->$kolom_add . " &nbsp;</span>";
                                                                    }
                                                                    else {
                                                                        $data_result_f = "$data_result_f <span class='pull-left'>" . $result_kolom->$kolom_add . " &nbsp;</span>";
                                                                    }
                                                                }
                                                                $data_result_f .= "<br>";
                                                            }
//                                                            if(isset($link_komposisi)){
////                                                                cekHere(":: $link_komposisi / $data_result->id ::");
//                                                                $data_result_f = "<a href='$link_komposisi/$data_result->id'> $data_result_f </a>";
//                                                            }
                                                        }
                                                        else {

                                                            if ($data_result_f == "") {
                                                                $data_result_f = "<span class='pull-left'>$result_kolom->produk_dasar_nama</span><span class='pull-right'>$result_kolom->jml</span>";
                                                            }
                                                            else {
                                                                $data_result_f = "$data_result_f<br><span class='pull-left'>$result_kolom->produk_dasar_nama</span><span class='pull-right'>$result_kolom->jml</span>";
                                                            }
                                                        }
                                                    }
                                                }
                                                else {
//                                                        cekHere(":: HEHEEH ::");
                                                    $data_result_f = $data_result->$kolom;
                                                }
                                            }
                                            else {
//                                                    cekHere(":: WKWKWKW ::");
                                                if (isset($data_result->$header_value)) {
                                                    $data_result_f = $data_result->$header_value;
                                                }
                                                else {
                                                    $data_result_f = "";
                                                }
                                            }
                                        }
//                                        }

                                        break;
                                }
//                                arrPrint($data_result_f);

                                $isi[] = array("data" => $data_result_f);
                            }
                        }
                    }

                    //  region action button
                    if (isset($action)) {
                        if ((sizeof($action) > 0) && (is_array($action))) {
                            $button = "";
                            $attributes = "";
                            // arrPrint($action);
                            foreach ($action as $label => $link) {
                                $label_f = $cf_button[$label]['label'];
                                $icon_f = $cf_button[$label]['icon'];
                                $attributes = $cf_button[$label]['attribute'];

                                if (in_array($label, $this->config->item('button_data_exception'))) {
                                    if (in_array('item', $segment_uri_array)) {
                                        $page_default = '/item/1';
                                    }
                                    else {
                                        $page_default = '/1';
                                    }
                                }
                                else {
                                    $page_default = '';
                                }

                                $attributes_f = "";
                                $href = "";
                                if (is_array($attributes)) {

                                    $attributes_1 = array_keys($attributes)[0];
                                    $item_val = array_values($attributes)[0];

                                    switch ($attributes_1) {
                                        case "modal":
                                            $attributes_f = "$item_val";
                                            $href = $link . "/" . $data_result->id . $page_default;
                                            break;
                                        case "alert":

                                            $attributes_f = "onclick=\"confirm_alert_result('Peringatan','$item_val','$link/" . $data_result->id . "');\"";
                                            $href = "javascript:void(0);";

                                            break;
                                        default:
                                            break;
                                    }

                                }
                                else {
                                    switch ($attributes) {
                                        case"modal":
                                            $attributes_f = "data-toggle='modal' data-target='#myModal'";
                                            $href = $link . "/" . $data_result->id . $page_default;
                                            break;
                                        case "alert":

                                            $attributes_f = "onclick=\"confirm_alert_result('Peringatan','Penonaktifan Data','$link/" . $data_result->id . "');\"";
                                            $href = "javascript:void(0);";

                                            // $dd = "<a onclick=\"confirm_alert('iii','pesan',$link)\"></a>";
                                            break;
                                        default:
                                            break;
                                    }
                                }
                                $button .= "<a href='$href' class='" . $this->config->item('button')[$label]['background'] . "' $attributes_f'>$icon_f</a>&nbsp;";
                            }
                            // $button .= form_button("testing", "test","class='btn btn-danger btn-sm' onclick=\"confirm_alert_result('Peringatan','Penonaktifan Data','anu.php')\"");
                            $isi[] = $button;
                        }
                    }
                    //  endregion action button

                    $this->table->add_row($isi);
                }

            }
            else {
                $this->table->add_row(array(
                    'data' => 'tidak ada data',
                    'colspan' => count($header) + 2,
                    'class' => 'text-center'
                ));
            }
        }
        //  endregion bagian isi tabel


        //  region searching data
        $arrAtribut = array(
            "name" => "resultt",
            "id" => "resultt",
            'method' => 'post',
        );
        $action_link = $base_url . "$segment_url/1";
        $form_searching = "<div style='padding: 0 0 10px 0;'>";
        //button back
        $form_searching_button_back = array(
            'type' => 'button', // submit / button
            'content' => $this->config->item('button')['back']['icon'], // tulisan dalam button
            'class' => $this->config->item('button')['back']['background'],
            'style' => 'margin:0 0 1px 15px;',
        );
        $form_searching_w = form_button($form_searching_button_back);
        //button add data
        $form_searching_button_add = array(
            'type' => 'button', // submit / button
            'content' => $this->config->item('button')['add']['icon'], // tulisan dalam button
            'class' => $this->config->item('button')['add']['background'],
            // 'style' => 'margin:0 0 1px 15px;',
        );
        $form_searching_z = form_button($form_searching_button_add);
        //searching isian dan button
        $form_searching = form_open($action_link, $arrAtribut);
        $form_searching_input = array(
            'type' => 'text',
            'name' => 'search',
            'value' => isset($search) ? $search : '',
            'placeholder' => 'ketikkan nama ',
            'class' => 'btn btn-default btn-sm text-left',
            'style' => 'width:80%;',
        );
        $form_searching_button = array(
            'type' => 'submit', // submit / button
            'content' => $this->config->item('button')['search']['icon'], // tulisan dalam button
            'class' => $this->config->item('button')['search']['background'],
        );
        $form_searching_x = form_input($form_searching_input);
        $form_searching_y = form_button($form_searching_button);

        //tombol tambahan
        if (sizeof($this->config->item('button_data')) > 0) {
            $form_searching_button_right = "";
            foreach ($this->config->item('button_data') as $key => $val) {

                $status_button_right = $val['status']; // active, non_active, add

                if (in_array($status_button_right, $segment_uri_array)) {
                    $link_button = "$base_url$segment_url_right";
                    $disabled = "disabled";
                }
                else {
                    $link_button = "$base_url$segment_url_right/$status_button_right";
                    $disabled = '';
                }

                $form_searching_button_data = array(
                    'type' => 'button', // submit / button
                    'content' => $val['icon'], // tulisan dalam button
                    'class' => $val['background'],
                    'title' => $val['title'],
                    $disabled => ''
                );

                $link_button_right_click = "onClick=\"location.href='$link_button/1'\"";
                $form_searching_button_right .= form_button($form_searching_button_data, '', $link_button_right_click);
            }
            $form_searching_button_data_add = array(
                'type' => 'button', // submit / button
                //                'content' => $this->config->item('button')['add']['icon'], // tulisan dalam button
                'class' => $this->config->item('button')['add']['background'],
                'title' => $this->config->item('button')['add']['title'],
                'data-toggle' => 'modal',
                'data-target' => '#myModal',
            );
            $form_searching_button_right .= anchor("/" . $segment_uri_array[1] . "/add/" . $this->uri->segment(3), $this->config->item('button')['add']['icon'], $form_searching_button_data_add);

        }
        else {
            $form_searching_button_right = "";
        }
        $form_searching .= "<div style='padding-bottom:40px;'>";
        $form_searching .= "<span class='pull-left' style='margin-left:20px;width:50%;'>$form_searching_x $form_searching_y</span>";
        $form_searching .= "<span class='pull-right' style='margin-right:20px;'>$form_searching_button_right</span>";
        $form_searching .= "</div>";
        $form_searching .= form_close();
        $form_searching .= "</div>";
        //  endregion searching data

        $hasil = "";
        $hasil .= $form_searching;
        $hasil .= $this->table->generate();

        $config = array(
            'base_url' => base_url() . "$segment_url",
            'first_url' => 1,
            'total_rows' => isset($total_rows) ? $total_rows : '',
            'per_page' => isset($per_page) ? $per_page : '',
            "uri_segment" => $segment_page,
            'use_page_numbers' => TRUE,
            //            'page_query_string'   => FALSE,
            'full_tag_open' => '<div class="text-center">',
            'full_tag_close' => '</div>',

            'cur_tag_open' => '<span class="btn btn-primary disabled">',
            'cur_tag_close' => '</span>',

            'next_link' => "<span class='fa fa-angle-right'></span>",
            'next_tag_open' => '<span class="btn btn-default">',
            'next_tag_close' => '</span>',

            'prev_link' => "<span class='fa fa-angle-left'></span>",
            'prev_tag_open' => '<span class="btn btn-default">',
            'prev_tag_close' => '</span>',

            //            'last_link'          => "<span cclass='fa fa-gg'></span>",
            'last_tag_open' => '<span class="btn btn-warning">',
            'last_tag_close' => '</span>',

            //            'first_link'         => "<span class='fa fa-home'></span>",
            'first_tag_open' => '<span class="btn btn-warning">',
            'first_tag_close' => '</span>',

            'num_tag_open' => '<span class="btn btn-default">',
            'num_tag_close' => '</span>',
        );
        $this->pagination->initialize($config);
        $hasil .= $this->pagination->create_links();
        $hasil .= "<div id='hasil_nya' sstyle='border: 1px solid red;'></div>";

        $menu_utama = array();


//        $ci =& get_instance();


        $p->addTags(
            array(
                "menu_left" => callMenuLeft(),
                "btn_back" => callBackNav(),
                "content" => $hasil,
                "profile_name" => $this->session->login['nama'],
                "stop_time" => "",
            )
        );

        $p->render();
        break;
    case "formData":


        $p = New Pages("$mytitle", "sub judul", "application/template/pages.html");
        $arrAtribut = array(
            //            "target" => "result",
            "name" => "result",
            "id" => "result",
        );
        $form = form_open($action_link, $arrAtribut);

        $template = array(
            'table_open' => '<table border="2" cellpadding="1" cellspacing="1" class="table table-bordered tabled-condensed">',
            'thead_open' => '<thead class="bg-info" style="text-align: center;">',
        );
        $this->table->set_template($template);

        //  region isi form
        if (sizeof($header) > 0) {
            foreach ($header as $header_result) {
                $label_form = $fields[$header_result]['label'];
                $input_type = $fields[$header_result]['inputType'];
                $input_fields = $fields[$header_result]['kolom'];
                $input_name = $fields[$header_result]['inputName'];
                $input_value = sizeof($data) > 0 ? $data[0]->$fields[$header_result]['kolom'] : "";
                //echo ":: $label_form :: $input_name :: $input_value ::<br>";

                if (is_array($input_type) && (sizeof($input_type) > 0)) {
                    $attributes = array(
                        'class' => '',
                        'style' => 'color:#000000;margin:10px 0 0 20px;'
                    );
                    $form .= form_label($label_form, '', $attributes);
                    $form .= "<div></div>";

                    $input_form_arr = "";
                    foreach ($input_type as $key => $input_type_f) {
                        $input_type_label = $fields[$header_result]['inputTypeLabel'][$key];
                        $checked = $input_value == $key ? TRUE : FALSE;

                        $form_input_arr = array(
                            'name' => $input_fields,
                            'id' => $input_name,
                            'value' => $key,
                            'checked' => $checked,
                            'style' => 'margin:0 0 0 20px;'
                        );
                        $form .= form_radio($form_input_arr);
                        $form .= "<span>$input_type_label</span>";
                    }
                }
                else {
                    if (in_array($input_type, $this->config->item('angka'))) {
                        $input_value = $input_value > 0 ? number_format($input_value, 0, '', '') : 0;
                    }
                    $attributes_label = array(
                        'style' => 'color:#000000;margin:10px 0 0 20px;'
                    );
                    $form .= form_label($label_form, '', $attributes_label);

                    switch ($input_type) {
                        case "dropdown":
                            $attributes_dropdown = array(
                                'style' => 'width:50%;margin:0 0 0 20px',
                                'class' => 'form-control',
                            );

                            //  region array dropdown
                            //                            $options = array();
                            $options = array(
                                '0' => 'pilih disini',
                            );
                            if (sizeof($kategori[$input_name]) && (is_array($kategori[$input_name]))) {
                                foreach ($kategori[$input_name] as $kategori_data) {
                                    $options[$kategori_data->id] = $kategori_data->nama;
                                }
                            }

                            //  endregion array dropdown

                            $form .= form_dropdown($input_fields, $options, $input_value, $attributes_dropdown);
                            break;
                        case "text":
                        case "number":
                        default:
                            $form_input = array(
                                'type' => $input_type,
                                'name' => $input_fields,
                                'id' => $input_name,
                                'value' => $input_value,
                                'placeholder' => $label_form,
                                'class' => 'form-control',
                                'style' => 'width:50%;margin:0 0 0 20px;',
                            );
                            $form .= form_input($form_input);
                            break;
                    }
                }
            }
        }
        //  endregion isi form

        //  region button form
        if (sizeof($action) > 0) {
            $form .= "<div style='margin: 20px 0 0 20px;'>";
            $button = "";
            foreach ($action as $action_key => $action_value) {
                if (is_array($action_value)) {
                    foreach ($action_value as $k => $v) {
                        $button .= "<button class='" . $this->config->item('button')[$action_key]['background'] . "'
                            type='$action_key' onclick=\"location.href='$v'\">
                            $k</button>&nbsp;";
                    }
                }
                else {
                    $button .= "<button class='" . $this->config->item('button')[$action_key]['background'] . "'
                            type='$action_key' >
                            $action_value</button>&nbsp;";
                }

                //                $form_button = array(
                //                    'type'          => $action_key, // submit / button
                //                    'content'       => $action_value, // tulisan dalam button
                //                    'class'       => $this->config->item('button')[$action_key]['background'],
                //
                //                );
                //                $form .= form_button($form_button);
            }
            $form .= $button;
            $form .= "</div>";
        }
        //  endregion button form

        //  region hidden form
        if ((isset($hidden)) && (is_array($hidden))) {
            foreach ($hidden as $hidden_val) {
                $form_hidden = array(
                    $hidden_val => $data[0]->$hidden_val,
                );
                $form .= form_hidden($form_hidden);
            }
        }
        //  endregion hidden form

        $form .= form_close();

        $p->setContent($form);
        $p->setProfileName("thomas");
        //
        //        $p->setLogoLogin("anu");
        //        $p->setAttribute(false);
        //        $p->setActions("Login/execute");
        $p->render();

        break;

    case "modalForm":

        function bs_modal($label, $field)
        {
            $label_width = "col-sm-3";
            $forms_width = "col-sm-9";

            $var = "<div class='form-group overflow-h'>";
            $var .= "<label class='$label_width control-label'>$label</label>
                  <div class='$forms_width'>
                  <div class='input-group' style='width:100%;'>
                    $field
                    
                  </div>
                  </div>";
            $var .= "</div>";

            return $var;
        }


        $p = New Layout("$mytitle", "sub judul", "application/template/pages.html");
        $arrAtribut = array(
            "target" => "result",
            "name" => "myForm",
            "id" => "myForm",
        );
        // $action_link = "";
        $form = "";
        //  region isi form
        if (sizeof($header) > 0) {
            $form .= "<div class='overflow-h'>";
            $form_field = "";
            foreach ($header as $header_result) {
                $label_form = $fields[$header_result]['label'];
                $input_type = $fields[$header_result]['inputType'];
                $input_fields = $fields[$header_result]['kolom'];
                $input_name = $fields[$header_result]['inputName'];
                $input_value = sizeof($data) > 0 ? $data[0]->$fields[$header_result]['kolom'] : "";
                //echo ":: $label_form :: $input_name :: $input_value ::<br>";

                if (is_array($input_type) && (sizeof($input_type) > 0)) {
                    $attributes = array(
                        'class' => '',
                        'style' => 'color:#000000;margin:10px 0 0 20px;'
                    );

                    $input_form_arr = "";
                    foreach ($input_type as $key => $input_type_f) {
                        $input_type_label = $fields[$header_result]['inputTypeLabel'][$key];
                        $checked = $input_value == $key ? TRUE : FALSE;

                        $form_input_arr = array(
                            'name' => $input_fields,
                            'id' => $input_name,
                            'value' => $key,
                            'checked' => $checked,
                            'style' => 'margin:0 0 0 20px;'
                        );
                        // $form .= form_radio($form_input_arr);
                        // $form .= "<span>$input_type_label</span>";
                        // $checked_status = $checked;
                    }

                    $checked_status = $key == 1 ? "checked" : "";
                    $form .= bs_modal("$label_form", "<input id='toggle-one' type='checkbox' name='$input_fields' $checked_status value='$key'  data-width='100' data-on='ACTIVE'  data-off='Disable'>");


                }
                else {
                    if (in_array($input_type, $this->config->item('angka'))) {
                        $input_value = $input_value > 0 ? number_format($input_value, 0, '', '') : 0;
                    }
                    $attributes_label = array(
                        'style' => 'color:#000000;margin:10px 0 0 20px;'
                    );
                    // $form .= form_label($label_form, '', $attributes_label);

                    switch ($input_type) {
                        case "dropdown":
                            $attributes_dropdown = array(
                                'style' => 'widthh:50%;mmargin:0 0 0 20px',
                                'class' => 'form-control',
                            );

                            //  region array dropdown
                            //                            $options = array();
                            $options = array(
                                '0' => 'pilih disini',
                            );
                            if (sizeof($kategori[$input_name]) && (is_array($kategori[$input_name]))) {
                                foreach ($kategori[$input_name] as $kategori_data) {
                                    $options[$kategori_data->id] = $kategori_data->nama;
                                }
                            }

                            //  endregion array dropdown

                            $form_field = form_dropdown($input_fields, $options, $input_value, $attributes_dropdown);
                            break;
                        case "text":
                        case "number":
                        default:
                            $form_input = array(
                                'type' => $input_type,
                                'name' => $input_fields,
                                'id' => $input_name,
                                'value' => $input_value,
                                'placeholder' => $label_form,
                                'class' => 'form-control',
                                'style' => 'wwidth:50%;mmargin:0 0 0 20px;',
                            );
                            $form_field = form_input($form_input);
                            break;
                    }

                    $form .= bs_modal("$label_form", "$form_field");
                }
            }

            // $form .= bs_modal("status","<input id='toggle-one' type='checkbox' name='status' $checked_status value='1'  data-width='100' data-on='ACTIVE'  data-off='Disable'>");
            $form .= "<script>$(function() {
                $('#toggle-one').bootstrapToggle();
              })</script>";

            $form .= "</div>";
        }
        //  endregion isi form

        //  region button modal-footer
        if (sizeof($action) > 0) {

            $button = "";
            foreach ($action as $action_key => $action_value) {
                if (is_array($action_value)) {
                    foreach ($action_value as $k => $v) {
                        $button .= "<button class='" . $this->config->item('button')[$action_key]['background'] . "'
                            type='$action_key' onclick=\"location.href='$v'\">
                            $k</button>&nbsp;";
                    }
                }
                else {
                    $button .= "<button class='" . $this->config->item('button')[$action_key]['background'] . "'
                            type='$action_key' >
                            $action_value</button>&nbsp;";
                }
            }


        }
        $button .= form_button("tes", "<i class='fa fa-close'> Close</i>", "class='btn btn-default pull-left' data-dismiss='modal'");
        //  endregion button form

        //  region hidden form
        if ((isset($hidden)) && (is_array($hidden))) {
            foreach ($hidden as $hidden_val) {
                $form_hidden = array(
                    $hidden_val => $data[0]->$hidden_val,
                );
                $form .= form_hidden($form_hidden);
            }
        }
        //  endregion hidden form


        $p->setLayoutModalHeader($mytitle, true);
        $p->setLayoutModalBody($form);
        $p->setLayoutModalFooter($button);

        $modal = form_open($action_link, $arrAtribut);
        $modal .= $p->layout_modal();
        $modal .= form_close();

        echo($modal);
        die();

        echo $modal;
        break;
    case "modalTbl":
        $button = "";
        $form = "";
        // arrPrint($data);

        $template = array(
            'table_open' => '<table class="table table-bordered tabled-condensed table-hover">',
            'thead_open' => '<thead class="bg-info text-capitalize" style="text-align: center;">',
        );
        $this->table->set_template($template);
        //  region bagian header tabel
        if ((sizeof($header) > 0) && (is_array($header))) {
            $header_f[] = "No.";
            foreach ($header as $header_value) {
                $header_result = $fields[$header_value]['label'];
                $header_result_f = array('data' => $header_result, 'class' => 'text-center');

                $header_f[] = $header_result_f;
            }
            if ((isset($action) > 0) && (is_array($action))) {
                $header_f[] = "Action";
            }
            $this->table->set_heading($header_f);
        }
        //  endregion bagian header tabel

        //  region bagian isi tabel
        if ((sizeof($data) > 0) && (is_array($data))) {
            $cf_button = $this->config->item('button');
            // arrPrint($cf_button);
            $no = $position;
            foreach ($data as $data_result) {
                $no++;
                $isi = array();
                $isi[] = $no;
                if ((sizeof($header) > 0) && (is_array($header))) {
                    foreach ($header as $header_value) {

                        $kolom = $fields[$header_value]['kolom'];
                        $type = $fields[$header_value]['inputType'];
                        if (in_array($type, $this->config->item('angka'))) {
                            $data_result_f = number_format($data_result->$kolom, 0, '', '.');
                        }
                        elseif (array_key_exists($data_result->jenis, $this->config->item('view'))) {
                            if (in_array($kolom, $this->config->item('view')[$data_result->jenis])) {
                                $link_x = base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . "";
                                $data_result_f = "<a href='$link_x/item/" . $data_result->id . "/1'>" . $data_result->$kolom . "</a>";
                            }
                            else {
                                $data_result_f = $data_result->$kolom;
                            }
                        }
                        else {
                            $data_result_f = $data_result->$kolom;
                        }
                        $isi[] = array("data" => $data_result_f);
                    }
                }

                //  region action button
                if ((isset($action) > 0) && (is_array($action))) {
                    $button = "";
                    $attributes = "";
                    // arrPrint($action);
                    foreach ($action as $label => $link) {
                        $label_f = $cf_button[$label]['label'];
                        $icon_f = $cf_button[$label]['icon'];
                        $attributes = $cf_button[$label]['attribute'];

                        if (in_array($label, $this->config->item('button_data_exception'))) {
                            if (in_array('item', $segment_uri_array)) {
                                $page_default = '/item/1';
                            }
                            else {
                                $page_default = '/1';
                            }
                        }
                        else {
                            $page_default = '';
                        }

                        $attributes_f = "";
                        $href = "";
                        if (is_array($attributes)) {
                            // arrPrint($attributes);
                            // foreach ($attributes as $item => $item_val) {
                            //
                            // }
                            $attributes_1 = array_keys($attributes)[0];
                            $item_val = array_values($attributes)[0];
                            // $item_val = "";
                            // cekHijau("$attributes_1 $item_val");
                            switch ($attributes_1) {
                                case"modal":
                                    $attributes_f = "$item_val";
                                    $href = $link . "/" . $data_result->id . $page_default;
                                    break;
                                case "alert":

                                    $attributes_f = "onclick=\"confirm_alert_result('Peringatan','$item_val','$link/" . $data_result->id . "');\"";
                                    $href = "javascript:void(0);";

                                    break;
                                default:
                                    break;
                            }

                        }
                        else {
                            switch ($attributes) {
                                case"modal":
                                    $attributes_f = "data-toggle='modal' data-target='#myModal'";
                                    $href = $link . "/" . $data_result->id . $page_default;
                                    break;
                                case "alert":

                                    $attributes_f = "onclick=\"confirm_alert_result('Peringatan','Penonaktifan Data','$link/" . $data_result->id . "');\"";
                                    $href = "javascript:void(0);";

                                    // $dd = "<a onclick=\"confirm_alert('iii','pesan',$link)\"></a>";
                                    break;
                                default:
                                    break;
                            }
                        }
                        $button .= "<a href='$href' class='" . $this->config->item('button')[$label]['background'] . "' $attributes_f'>$icon_f</a>&nbsp;";
                    }
                    // $button .= form_button("testing", "test","class='btn btn-danger btn-sm' onclick=\"confirm_alert_result('Peringatan','Penonaktifan Data','anu.php')\"");
                    $isi[] = $button;
                }
                //  endregion action button

                $this->table->add_row($isi);
            }

        }
        else {
            $this->table->add_row(array(
                'data' => 'tidak ada data',
                'colspan' => count($header) + 2,
                'class' => 'text-center'
            ));
        }
        //  endregion bagian isi tabel
        // $this->table->add_row($isi);

        $form .= "<div class='panel panel-info table-responsive no-margin'>";
        $form .= $this->table->generate();
        $form .= "</div>";

        $button .= form_button("tes", "<i class='fa fa-close'> Close</i>", "class='btn btn-default pull-left' data-dismiss='modal'");


        $p = New Pages("$mytitle", "sub judul", "application/template/pages.html");
        // $arrAtribut = array(
        //     "target" => "result",
        //     "name" => "myForm",
        //     "id" => "myForm",
        // );


        $p->setLebarModal("modal-lg");
        $p->setLayoutModalHeader($mytitle, true);
        $p->setLayoutModalBody($form);
        $p->setLayoutModalFooter($button);

        // $modal = form_open($action_link, $arrAtribut);
        $modal = $p->layout_modal();
        // $modal .= form_close();

        echo $modal;
        break;

    case "viewAkses":
        $p = New Pages("$mytitle", "sub judul", "application/template/pages.html");

        if (isset($employee_id)) {
            $employee_selected_id = $employee_id;
        }
        else {
            $employee_selected_id = 0;
        }

        $cnt = 0;
        $modal = "";
        $str_menu = "";
        if (isset($data) && (sizeof($data) > 0) && (is_array($data))) {
            $str_menu .= "<table class='table table-borderless' sstyle='border: 1px solid black;'>";
            $str_menu .= "<tr>";

            foreach ($data as $main_menu => $arr_sub_menu) {
                $cnt++;

                $str_menu .= "<td>";
                $str_menu .= "<div class='panel panel-info'>";
                $str_menu .= "<div class='text-center text-bold panel-heading panel-info' style='vertical-align: top;'>$main_menu</div>";
                $str_menu .= "<div class='panel-body text-left'>";
                foreach ($arr_sub_menu as $sub_menu => $sub_link) {
                    if (isset($checked_menu_sub)) {
                        if ((sizeof($checked_menu_sub) > 0) && (is_array($checked_menu_sub))) {
                            $checked_input = isset($checked_menu_sub[$main_menu][$sub_menu]) ? $checked_menu_sub[$main_menu][$sub_menu] : "";
                        }
                        else {
                            $checked_input = "";
                        }
                    }
                    else {
                        $checked_input = "";
                    }


                    $anu = base_url() . $this->uri->segment(1) . "/$proses/$employee_selected_id?main=$main_menu&sub=$sub_menu&";
                    $str_menu .= "<label class='text-regular'>
                        <input type='" . $this->config->item('menu_setting')[$this->uri->segment(2)]['type'] . "' 
                            name='" . $this->config->item('menu_setting')[$this->uri->segment(2)]['name'] . "[$main_menu]'
                            $checked_input
                            onclick=\"getData('$anu'+this.value, 'menu_sub_click')\">&nbsp;
                        $sub_menu
                        </label> <br>";
                }
                $str_menu .= "</div>";
                $str_menu .= "</div>";
                $str_menu .= "</td>";

                if ($cnt % 3 == 0) {
                    $str_menu .= "</tr><tr>";
                }
            }

            $str_menu .= "</table>";
            $str_menu .= "<div id='menu_sub_click' style='border: 0px solid red;'></div>";
            $p->setLayoutModalHeader($mytitle, true);
            $p->setLayoutModalBody($str_menu);
            $p->setLayoutModalFooter('<button class="btn btn-default pull-left" data-dismiss="modal">Close</button>');
            $modal = $p->layout_modal();
        }
        else {
            $str_menu = "<div class='alert alert-danger text-center'>";
            $str_menu .= "Hak akses tidak dikenali.";
            $str_menu .= "</div>";
            $p->setLayoutModalHeader($mytitle, true);
            $p->setLayoutModalBody($str_menu);
            $p->setLayoutModalFooter('<button class="btn btn-default pull-left" data-dismiss="modal">Close</button>');
            $modal = $p->layout_modal();
        }

        echo $modal;
        break;

    default:
        cekHere();
        break;
}


?>