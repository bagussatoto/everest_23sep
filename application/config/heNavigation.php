<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/9/2018
 * Time: 4:35 PM
 */
// $config['lgNavigationSegments']=array(
//     "{segment3}"=>uri_segment(3),
// );
$config['navigation'] = array(
	"Transaksi"      => array(
		"index"                  => "Welcome/index/",
		"viewHistory"            => "Transaksi/index/{segment3}",
		"viewIncomplete"         => "Transaksi/index/{segment3}",
		"createForm"             => "Transaksi/index/{segment3}",
		"selectPaymentSrc"       => "Transaksi/selectPaymentExternSrc/{segment3}",
		"selectPaymentExternSrc" => "Transaksi/index/{segment3}",
	),
	"ActivityReport" => array(
		"viewMonthly" => "Transaksi/index/{segment3}",
		"viewDaily"   => "Transaksi/index/{segment3}",
	),
	"DataView"       => array(
        "index"                  => "Welcome/index/",
		"view"  => "DataView/index",
	),
	"Data"           => array(
        "index"                  => "Welcome/index/",
		"view"     => "DataIndex/index",
		"addMany"  => "Data/view/{segment3}",
		"editMany" => "Data/view/{segment3}/{segment4}",
	),
	"DataIndex"      => array(
        "index"                  => "Welcome/index/",
	),
	"Spread"         => array(
        "index"                  => "Welcome/index/",
		//        "view"         => "DataIndex/index",
	),
	"Ledger"         => array(
		"viewMoves_l1"    => "Neraca/viewBalanceSheet",
		"viewMoves_l2"    => "Ledger/viewBalances_l1/{segment3}/{segment4}",
		"viewBalances_l1" => "Neraca/viewBalanceSheet",
        "viewMoveDetails"    => "Ledger/viewBalances_l1/{segment3}/{segment4}",
		//        "view"         => "DataIndex/index",
	),

);


