<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Coa_model extends CI_Model {

  public function import_from_json($json_path) {
    $raw = file_get_contents($json_path);
    $rows = json_decode($raw, true);
    $this->db->trans_start();
    foreach ($rows as $r) {
      // Ambil field kunci dari file JSON
        $is_active = (isset($r['is_active']) ? $r['is_active'] : '1') === '1' ? 1 : 0;

        $data = array(
            'head_code'      => isset($r['head_code']) ? $r['head_code'] : '',
            'head_name'      => isset($r['head_name']) ? $r['head_name'] : '',
            'p_head_code'    => isset($r['p_head_code']) ? $r['p_head_code'] : '',
            'head_level'     => isset($r['head_level']) ? (int)$r['head_level'] : 0,
            'is_active'      => $is_active,
            'is_aktiva'      => isset($r['is_aktiva']) ? (int)$r['is_aktiva'] : 0,
            'is_hutang'      => isset($r['is_hutang']) ? (int)$r['is_hutang'] : 0,
            'is_modal'       => isset($r['is_modal']) ? (int)$r['is_modal'] : 0,
            'is_penghasilan' => isset($r['is_penghasilan']) ? (int)$r['is_penghasilan'] : 0,
            'is_biaya'       => isset($r['is_biaya']) ? (int)$r['is_biaya'] : 0,
        );

      // Upsert
      $exists = $this->db->get_where('coa_a', ['head_code' => $data['head_code']])->row();
      if ($exists) {
        $this->db->where('id', $exists->id)->update('coa_a', $data);
      } else {
        $this->db->insert('coa_a', $data);
      }
    }
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  /**
   * Saldo per akun pada periode (inklusif)
   */
  public function saldo_per_akun($date_from, $date_to) {
    $sql = "
      SELECT d.head_code,
             SUM(d.debit) AS debit,
             SUM(d.credit) AS credit
      FROM gl_detail d
      JOIN gl_header h ON h.id = d.header_id
      WHERE h.trx_date BETWEEN ? AND ?
      GROUP BY d.head_code
    ";
    $rows = $this->db->query($sql, [$date_from, $date_to])->result_array();
    $out = [];
    foreach ($rows as $r) {
      $out[$r['head_code']] = ['debit' => (float)$r['debit'], 'credit' => (float)$r['credit']];
    }
    return $out;
  }

  /**
   * Laporan generic berdasarkan coa_report_map
   * $report: 'NERACA' atau 'LABARUGI'
   */
  public function laporan_grouped($report, $date_from, $date_to) {
    $sql = "
      SELECT m.report, m.section, m.report_line, m.side, m.sort_order,
             c.head_code, c.head_name,
             SUM(d.debit) AS debit, SUM(d.credit) AS credit
      FROM coa_a c
      JOIN coa_report_map m
        ON c.head_code LIKE m.code_pattern
       AND m.report = ?
      LEFT JOIN gl_detail d ON d.head_code = c.head_code
      LEFT JOIN gl_header h ON h.id = d.header_id
                           AND h.trx_date BETWEEN ? AND ?
      GROUP BY m.report, m.section, m.report_line, m.side, m.sort_order, c.head_code, c.head_name
      ORDER BY m.sort_order, c.head_code
    ";
    $q = $this->db->query($sql, [$report, $date_from, $date_to])->result_array();

    // agregasi per baris
    $lines = [];
    foreach ($q as $r) {
      $key = $r['report_line'];
      $side = $r['side'];
      $amount = ($side === 'D')
        ? ((float)$r['debit'] - (float)$r['credit'])
        : ((float)$r['credit'] - (float)$r['debit']);
      if (!isset($lines[$key])) {
        $lines[$key] = [
          'section' => $r['section'],
          'sort'    => (int)$r['sort_order'],
          'amount'  => 0.0,
          'details' => []
        ];
      }
      $lines[$key]['amount'] += $amount;
      $lines[$key]['details'][] = [
        'head_code' => $r['head_code'],
        'head_name' => $r['head_name'],
        'amount'    => $amount
      ];
    }
    // urutkan
      uasort($lines, function($a, $b) {
          return ($a['sort'] < $b['sort']) ? -1 : (($a['sort'] > $b['sort']) ? 1 : 0);
      });

      return $lines;
  }
}
