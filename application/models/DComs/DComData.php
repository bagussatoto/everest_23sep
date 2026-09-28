<?php


class DComData extends MdlMother
{

    protected $filters = array();
    private $tableName;
    private $tableName_mutasi;
    private $tableName_fifoAvg;
    private $tableName_master = array();
    private $inParams = array( //===inputan dari transaksi

    );
    private $outParams = array( //===output ke tabel

    );
    private $outFields = array( // dari tabel cache
        "id",
        "jenis",
        "target_jenis",
        "reference_jenis",
        "transaksi_id",
        "extern_id",
        "extern_nama",
        "nomer",
        "label",
        "tagihan",
        "terbayar",
        "sisa",
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
        "awal_pinjaman",
    );
    private $koloms = array(
        "id",
        "jenis",
        "target_jenis",
        "reference_jenis",
        "transaksi_id",
        "extern_id",
        "extern_nama",
        "nomer",
        "label",
        "tagihan",
        "terbayar",
        "sisa",
        "tagihan_valas",
        "terbayar_valas",
        "sisa_valas",
        "cabang_id",
        "cabang_nama",
        "oleh_id",
        "oleh_nama",
        "dtime",
        "fulldate",
    );

    public function __construct()
    {

    }

    public function pair($inParams)
    {
        $this->inParams = $inParams;
        if (sizeof($this->inParams) > 0) {
            $lCounter = 0;
            foreach ($this->inParams as $cnt => $inSpec) {
                $mdlName = $inSpec["MdlName"];
                $this->load->model("Mdls/" . $mdlName);
                $d = new $mdlName();
                $selectField = $d->getOutFields();
                if (isset($inSpec['static']) && sizeof($inSpec['static']) > 0) {
                    $lCounter++;
                    $prev = $this->cekPreValue($inSpec['static']['transaksi_id'], $mdlName);
                    if (isset($prev['id'])) {
                        $writeMode = "update";
                        $data = array(
                            "extern_value_2" => $inSpec['static']['extern_value_2'],
                            "transaksi_id" => $inSpec['static']['transaksi_id'],
                            "nomer" => $inSpec['static']['nomer'],
                            "extern_value" => $prev['extern_value'] + $inSpec['static']['extern_value'],
                            "awal_pinjaman" => $prev['awal_pinjaman'],
                            "jatuh_tempo" => $prev['jatuh_tempo'],
                            );
                        $where = array(
                            "extern_id" => $inSpec['static']['extern_id'],
                        );
                    }
                    else {
                        $writeMode = "new";
                        foreach ($selectField as $ix => $fields) {
                            if (isset($inSpec['static'][$fields])) {
                                    $data[$fields] = $inSpec['static'][$fields];
                                }
                            }
                        }

                    switch ($writeMode) {
                        case "new" :
//                            $awal_pinjaman = isset($data['awal_pinjaman']) ? $data['awal_pinjaman'] : date('Y-m-d');
//                            $data['awal_pinjaman'] = $awal_pinjaman;
                            $data['extern_jenis'] = "main";
                            $data['repeat'] = "1";
                            $insertIDs[] = $d->addData($data);
                            cekLime($this->db->last_query());

                            $extern_id = isset($data['extern_id']) ? $data['extern_id'] : "";
                            $extern_nama = isset($data['extern_nama']) ? $data['extern_nama'] : "";
                            $nomer = isset($data['nomer']) ? $data['nomer'] : "";
                            $awal_pinjaman = isset($data['awal_pinjaman']) ? $data['awal_pinjaman'] : date('Y-m-d');
                            $jatuh_tempo = isset($data['jatuh_tempo']) ? $data['jatuh_tempo'] : date('Y-m-d');
                            $nilai_pinjaman = isset($data['extern_value']) ? $data['extern_value'] : 0;
                            $rate_bunga = isset($data['extern_value_2']) ? $data['extern_value_2'] : 0;

                            $npwp="";
                            $pph_nilai = strlen($npwp)>10 && $pph_nilai==15 ? 15 : 15; //dipaksa 15% untuk pemegang saham
                            $valid_bunga = ($nilai_pinjaman/12);
                            $nilai_bunga = ($valid_bunga * $rate_bunga) / 100 ;
                            $nilai_pph23 = ($nilai_bunga * $pph_nilai) / 100;

                            $period = new DatePeriod(
                                new DateTime($awal_pinjaman),
                                new DateInterval('P1D'),
                                new DateTime($jatuh_tempo)
                            );

                            $periodNow = new DatePeriod(
                                new DateTime($awal_pinjaman),
                                new DateInterval('P1D'),
                                new DateTime(date('Y-m-d'))
                            );

                            $arrBulan=array();
                            $arrBulanNow=array();
                            $arrHarian=array();
                            $arrWaktu=array();

                            foreach ($period as $key => $value) {
                                if(!isset($arrBulan[$value->format('Y-m')])){
                                    $arrBulan[$value->format('Y-m')]=array();
                                }
                                $arrBulan[$value->format('Y-m')][]=$value->format('Y-m-d');
                            }

                            foreach ($periodNow as $key => $value) {
                                if(!isset($arrBulanNow[$value->format('Y-m')])){
                                    $arrBulanNow[$value->format('Y-m')]=array();
                                }
                                $arrBulanNow[$value->format('Y-m')][]=$value->format('Y-m-d');
                            }

                            $hariPadaBulanJatuhTempo = count($arrBulan[date('Y-m', strtotime($jatuh_tempo))]);
                            $arrBulan[date('Y-m', strtotime($jatuh_tempo))][$hariPadaBulanJatuhTempo] = date('Y-m-d', strtotime($jatuh_tempo));

                            $arrCons=array();
                            $total_hari=0;
                            $total_bulan=0;
                            foreach($arrBulan as $thnbln => $thblntgl){
                                $tmp=array(
                                    "periode" => $thnbln,
                                    "jml_hari_dbln" => count($arrBulan[$thnbln]),
                                    "nomer" => $nomer,
                                    "extern_id" => $extern_id,
                                    "extern_jenis" => "detail",
                                    "extern_value_2" => $rate_bunga,
                                    "awal_pinjaman" => $awal_pinjaman,
                                    "jatuh_tempo" => $jatuh_tempo,
                                    "extern_nama" => $extern_nama,
                                    "extern_value" => $nilai_pinjaman,
                                    "valid_bunga" => $valid_bunga*(count($arrBulan[$thnbln])/30),
                                    "nilai_bunga" => $nilai_bunga*(count($arrBulan[$thnbln])/30),
                                    "nilai_pph23" => $nilai_pph23*(count($arrBulan[$thnbln])/30),
                                    "nett_bunga" => $nilai_bunga*(count($arrBulan[$thnbln])/30)-($nilai_pph23*(count($arrBulan[$thnbln])/30)),
                                    "silangan" => isset($arrBulanNow[$thnbln]) ? ($thnbln != date('Y-m') ? "hijau" : "berjalan") : "merah",
                                    "total_bulan" => $total_bulan+1,
                                );
                                if(!isset($arrCons[$thnbln])){
                                    $arrCons[$thnbln]=array();
                                }
                                $arrCons[$thnbln] = $tmp;
                                $total_bulan++;
                                $total_hari+=count($arrBulan[$thnbln]);
                            }

                            foreach($arrCons as $period => $data2){
                                $insertIDDetails[] = $d->addData($data2);
                                cekMerah($this->db->last_query());
                            }

                            Break;
                        case "update":
                            $insID = $d->updateData($where, $data) or die("can not update paymentSrc");
                            $insertIDs[] = $insID;
                            cekLime($this->db->last_query());
                            break;
                        default :
                            matiHere("method undefined yet!!" . __LINE__ . "func" . __FUNCTION__);
                            break;
                    }
                }
            }
            if (sizeof($insertIDs) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }


    private function cekPreValue($id, $mdlName)
    {
        $this->load->model($mdlName);
        $tr = new $mdlName();
        $tr->setFilters(array());
        $tr->addFilter("extern_id='$id'");
        $tmpR = $tr->lookUpAll()->result();
        if (sizeof($tmpR) > 0) {
            foreach ($tmpR as $row) {
                $result = array(
                    "id" => $row->id,
                    "extern_value" => $row->extern_value,
                    "extern_value_2" => $row->extern_value_2,
                );
            }
        } else {
            $result = null;
        }

        return $result;
    }

    public function addFilter($f)
    {
        $this->filters[] = $f;
    }

    public function exec()
    {
        return true;

    }


}