<?php
/**
 * Circulation Receipt PDF Generator
 * Copyright (C) 2019  Drajat Hasan 2019 (drajathasan20@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */
define('INDEX_AUTH', '1');

require '../../../sysconfig.inc.php';
require SB.'admin/default/session.inc.php';
require SB.'admin/default/session_check.inc.php';
require SIMBIO.'simbio_UTILS/simbio_date.inc.php';
require LIB.'fpdf/fpdfpp.php';
require MDLBS.'membership/member_base_lib.inc.php';

/* Checking */
if (!isset($_GET['member_id'])) {
    die('No data to processing!');
}

/* Prepare Query */
$memberID = $dbs->escape_string($_GET['member_id']);
$loan_list_query = $dbs->query(sprintf("SELECT L.loan_id, b.title, ct.coll_type_name,
        i.item_code, L.loan_date, L.due_date, L.return_date, L.renewed,
        IF(lr.reborrow_limit IS NULL, IF(L.renewed>=mt.reborrow_limit, 1, 0), IF(L.renewed>=lr.reborrow_limit, 1, 0)) AS extend
        FROM loan AS L
        LEFT JOIN item AS i ON L.item_code=i.item_code
        LEFT JOIN mst_coll_type AS ct ON i.coll_type_id=ct.coll_type_id
        LEFT JOIN member AS m ON L.member_id=m.member_id
        LEFT JOIN mst_member_type AS mt ON m.member_type_id=mt.member_type_id
        LEFT JOIN mst_loan_rules AS lr ON mt.member_type_id=lr.member_type_id AND i.coll_type_id = lr.coll_type_id
        LEFT JOIN biblio AS b ON i.biblio_id=b.biblio_id
        WHERE L.is_lent=1 AND L.is_return=0 AND L.member_id='%s'", $memberID));

$data = array();

while ($loan_list_data = $loan_list_query->fetch_assoc()) {
    if ($loan_list_data['item_code']) {
        $data[] = $loan_list_data;
    }
}

/* Call membership APi */
$u = new member($dbs, $memberID);
// echo '<pre>';
// var_dump($data);
// echo '</pre>';
// exit();
$id_month_name = array('1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
                            '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Juli',
                            '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            );

$pdf = new PDFPP('L','mm',array(220,180));

$pdf->AddPage();

/* Header */
// $pdf->Image(LIB.'fpdf/arpus.png', 9, 5,-190);
// $pdf->Ln(25);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,4, '',0,1,'C');
$pdf->Ln(15);
$pdf->Cell(215,4, 'SURAT TANDA TERIMA PEMINJAMAN BUKU',0,1,'C');
$pdf->Cell(190,4, '',0,1,'C');
// $pdf->Ln(10);
$pdf->SetFont('Arial','',14);
$pdf->Cell(35,5,'',0);
$pdf->Cell(30,5,'No ID', 0, '', 'L');
$pdf->Cell(5,5,':',0, '', 'C');
$pdf->Cell(120,5,$u->member_id, 0, '', 'L');
$pdf->Ln();
$pdf->Cell(35,5,'',0);
$pdf->Cell(30,5,'Nama', 0, '', 'L');
$pdf->Cell(5,5,':',0, '', 'C');
$pdf->Cell(120,5,$u->member_name, 0, '', 'L');
$pdf->Ln();
$pdf->Cell(35,5,'',0);
$pdf->Cell(30,5,'Tipe', 0, '', 'L');
$pdf->Cell(5,5,':',0, '', 'C');
$pdf->Cell(120,5,$u->member_type_name, 0, '', 'L');
$pdf->Ln();
$pdf->Cell(35,5,'',0);
$pdf->Cell(30,5,'Alamat', 0, '', 'L');
$pdf->Cell(5,5,':',0, '', 'C');
$pdf->Cell(120,5,$u->member_address, 0, '', 'L');
$pdf->Ln();
$pdf->Cell(35,5,'',0);
$pdf->Cell(30,5,'No.Telp', 0, '', 'L');
$pdf->Cell(5,5,':',0, '', 'C');
$pdf->Cell(120,5,$u->member_phone, 0, '', 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial','B',12);
//$pdf->Cell(25,7,'', 0, '', 'C');
$pdf->Cell(10,7,'No.', 1, '', 'C');
$pdf->Cell(90,7,'Judul Buku',1, '', 'C');
$pdf->Cell(35,7, 'Tanggal pinjam', 1, '', 'C');
$pdf->Cell(35,7, 'Tanggal Kembali', 1, '', 'C');
$pdf->Cell(35,7, 'Keterangan', 1, '', 'C');
$pdf->Ln();
$pdf->tblContent($data);
$pdf->Ln(2);
//$pdf->Cell(24,7,'',0);
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(200,4,'Perhatian : Melalui surat tanda terima pinjam buku ini, kami harap saudara agar tepat waktu untuk mengembalikan buku dan membawa bukti surat ini ketika mengembalikan buku.',0);
$pdf->Cell(160,4, '',0,1,'L');
// $pdf->Ln(1);
$pdf->SetFont('Arial','',12);
$pdf->Cell(25,7,'',0);
$pdf->Cell(109,7,'Pemustaka', 0, '', 'L');
$pdf->Cell(40,7, 'Indramayu, '.date('j').' '.$id_month_name[date('n')].' '.date('Y'), 0, '', 'L');
$pdf->Cell(20,7,'',0);
$pdf->Ln(8);
$pdf->Cell(8,7,'',0);
$pdf->Cell(126,7,'', 0, '', 'L');
$pdf->Cell(40,7, 'Pustakawan', 0, '', 'L');
$pdf->Cell(20,7,'',0);
$pdf->Ln(23);
$pdf->Cell(25,4,'',0);
$pdf->Cell(109,4,$u->member_name, 0, '', 'L');
$pdf->Cell(40,4, '..............................', 0, '', 'L');
$pdf->Cell(20,4,'',0);
// $pdf->AutoPrint();
$pdf->Output();
exit();