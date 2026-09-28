<?php
// START OF COMPLETE REPEATED LOGIC
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {

    case "coa":
        // $this->load->model(array(
        //     'Mdls/MdlAccounts',
        //     // 'Web_settings'
        // ));
        //
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH ."template/coa.html");
        $var = "<link rel='stylesheet' href='" . base_url() . "assets/custom/style.min.css' />";

        $var .= "<div class='box box-info'>";
        $var .= "<div class='box-body'>";
        $var .= "<div class='row'>";
        $var .= "<div class='col-md-5'>";
        $var .= "<div id='jstree1'>";
        $var .= "<ul>";

        $visit = array();
        for ($i = 0; $i < count($userList); $i++) {
            $visit[$i] = false;
        }

        // $var_data = $p->dfs('COA', '0', $userList, $visit, 0);
        $var_data = $p->dfs_code('COA', '0', $userList, $visit, 0);
        $var .= $var_data;

        $var .= "</ul>";

        $var .= "</div>"; // jstree
        $var .= "</div>"; // col-md-4

        // Vertical Floating Navigation for Expand/Collapse All
        $var .= "
        <div class='vertical-coa-nav' style='position: fixed; right: 25px; top: 50%; transform: translateY(-50%); z-index: 9999; display: flex; flex-direction: column; gap: 10px; background: rgba(255, 255, 255, 0.95); padding: 15px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); border: 1px solid rgba(0,0,0,0.08); width: 140px;'>
            <div style='text-align: center; font-weight: bold; font-size: 11px; color: #333; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 5px; text-transform: uppercase;'>Navigasi COA</div>
            <button class='btn btn-info btn-xs btn-flat' onclick=\"$('#jstree1').jstree('open_all');\" style='border-radius: 4px; padding: 6px; border: none; font-weight: bold; width: 100%; transition: all 0.2s;' onmouseover=\"this.style.transform='scale(1.05)'\" onmouseout=\"this.style.transform='scale(1)'\" title='Expand All COA'>
                <i class='fa fa-plus-square-o'></i> Expand All
            </button>
            <button class='btn btn-warning btn-xs btn-flat' onclick=\"$('#jstree1').jstree('close_all'); $('#jstree1').jstree('open_node', $('#jstree1 > ul > li:first'));\" style='border-radius: 4px; padding: 6px; border: none; font-weight: bold; width: 100%; margin-top: 5px; transition: all 0.2s;' onmouseover=\"this.style.transform='scale(1.05)'\" onmouseout=\"this.style.transform='scale(1)'\" title='Collapse All COA'>
                <i class='fa fa-minus-square-o'></i> Collapse All
            </button>
            
            <div style='text-align: center; font-weight: bold; font-size: 10px; color: #666; margin-top: 10px; border-top: 1px solid #eee; padding-top: 8px; margin-bottom: 6px; text-transform: uppercase;'>Toggle Level 1</div>
            <div style='display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;'>
                <button id='btn_group_1' class='btn btn-default btn-xs' onclick=\"expandGroup('1')\" title='Toggle 1 - Aktiva' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">1</button>
                <button id='btn_group_2' class='btn btn-default btn-xs' onclick=\"expandGroup('2')\" title='Toggle 2 - Kewajiban' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">2</button>
                <button id='btn_group_3' class='btn btn-default btn-xs' onclick=\"expandGroup('3')\" title='Toggle 3 - Modal' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">3</button>
                <button id='btn_group_4' class='btn btn-default btn-xs' onclick=\"expandGroup('4')\" title='Toggle 4 - Penjualan' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">4</button>
                <button id='btn_group_5' class='btn btn-default btn-xs' onclick=\"expandGroup('5')\" title='Toggle 5 - HPP' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">5</button>
                <button id='btn_group_6' class='btn btn-default btn-xs' onclick=\"expandGroup('6')\" title='Toggle 6 - Biaya' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">6</button>
                <button id='btn_group_7' class='btn btn-default btn-xs' onclick=\"expandGroup('7')\" title='Toggle 7 - Pendapatan/Biaya Lain' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">7</button>
                <button id='btn_group_8' class='btn btn-default btn-xs' onclick=\"expandGroup('8')\" title='Toggle 8 - Rekening Transisi' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">8</button>
                <button id='btn_group_9' class='btn btn-default btn-xs' onclick=\"expandGroup('9')\" title='Toggle 9 - Laba/Rugi' style='font-weight: bold; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; background-color: #f4f4f4; color: #444; border-color: #ddd;' onmouseover=\"this.style.transform='scale(1.15)';\" onmouseout=\"this.style.transform='scale(1)';\">9</button>
            </div>
        </div>
        
        <script>
        function expandGroup(code) {
            var jstree = $('#jstree1');
            var node = $('#node_' + code);
            var nodeId = null;
            
            if (node.length) {
                nodeId = node.attr('id');
            } else {
                var list = jstree.jstree('get_json', null, {flat: true});
                $.each(list, function(i, val) {
                    if (val.id == code || val.id == 'node_' + code) {
                        nodeId = val.id;
                        return false;
                    }
                });
                if (!nodeId) {
                    $.each(list, function(i, val) {
                        if (val.text && val.text.indexOf(code + ' - ') === 0) {
                            nodeId = val.id;
                            return false;
                        }
                    });
                }
            }
            
            if (nodeId) {
                if (jstree.jstree('is_open', nodeId)) {
                    jstree.jstree('close_all', nodeId);
                } else {
                    jstree.jstree('open_all', nodeId);
                }
            }
        }

        function updateButtonStyles() {
            var jstree = $('#jstree1');
            for (var i = 1; i <= 9; i++) {
                var btn = $('#btn_group_' + i);
                var node = $('#node_' + i);
                var nodeId = null;
                
                if (node.length) {
                    nodeId = node.attr('id');
                } else {
                    var list = jstree.jstree('get_json', null, {flat: true});
                    $.each(list, function(j, val) {
                        if (val.id == i || val.id == 'node_' + i || (val.text && val.text.indexOf(i + ' - ') === 0)) {
                            nodeId = val.id;
                            return false;
                        }
                    });
                }
                
                if (nodeId && jstree.jstree('is_open', nodeId)) {
                    btn.css({
                        'background-color': '#00c0ef',
                        'color': '#ffffff',
                        'border-color': '#00c0ef',
                        'box-shadow': 'inset 0 2px 4px rgba(0,0,0,0.15)'
                    });
                } else {
                    btn.css({
                        'background-color': '#f4f4f4',
                        'color': '#444444',
                        'border-color': '#dddddd',
                        'box-shadow': 'none'
                    });
                }
            }
        }

        $(document).ready(function () {
            // Bind JSTree events to update button states
            $('#jstree1').on('ready.jstree open_node.jstree close_node.jstree', function () {
                updateButtonStyles();
            });
        });
        </script>
        ";

        $var .= "<div class='col-md-7' style='position: fixed; border: 1px solid red;left: 46%;width: 750px;z-index:1000;background-color: #ffffff;padding:10px 0;' id='newform'></div>";

        // $var .= "</div>";
        $var .= "</div>"; // panel-body
        $var .= "</div>"; // panel
        $var .= "</div>"; // row

        $p->addTags(
            array(
                "menu_left"        => callMenuLeft(),
                "float_menu_atas"  => callFloatMenu('atas'),
                "float_menu_bawah" => callFloatMenu(),
                "menu_taskbar"     => callMenuTaskbar(),
                "btn_back"         => callBackNav(),
                "btn_top"          => "",
                "stop_time"        => "",
                "content"          => $var,
                // "script_bottom"    => $script_bottom,
                "profile_name"     => $this->session->login['nama'],
            )
        );

        // $p->setContent($contens);
        $p->render();
        break;
}
// END OF COMPLETE REPEATED LOGIC