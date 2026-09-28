<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//===helper buatan kita diberi prefix "he_" untuk mempermudah pengelolaan yaa...
//===config  buatan kita diberi prefix "he" untuk mempermudah pengelolaan yaa...

$autoload['libraries'] = array(
    'database',
    'session',
    'table',
    'pagination',
    'LoginForm',
    'user_agent',
    'Layout',
    'Excel',
    "ViewTemplate",
//    "Mongo_db",
);
$autoload['drivers'] = array();
$autoload['helper'] = array(
    'url',
    'cookie',
    'form',
    'he_url',
    // 'he_angka',
    'he_misc',
    'he_setting',
    'he_accounting',
    'he_date_time_helper',
    'he_format',
    'he_menu',
    'he_lang_eng',
    'he_navigation',
    'he_form',
    'he_table',
    'he_prices',
    'he_value_maker',
    'he_element',
    "he_cart",
);
$autoload['config'] = array(
    'heDataBehaviour',
    'heSettingAdmin',
    'heAccounting',
    'heTransaksi_ui',
    "heTransaksi_core",
    "heTransaksi_layout",
//    "heReceipt_layout",
    "heTransaksi_report",
    "heTransaksi_misc",
    "heTransaksi_settlement",
    "heComponents",
    "heNavigation",
    "hePrices",
    "heMenu",
    "heVideos",
    "usergroup",
    "heOpname",
    "mongo_db", // library mati ini kok masih hidup? 30/11/22
);

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/
$autoload['language'] = array();

$autoload['model'] = array('Mdls/MdlMother', 'Mdls/MdlMother_static', 'Mdls/MdlEmployee', 'Mdls/MdlActivityLog');
