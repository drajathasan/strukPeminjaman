<?php
require 'fpdfpp.php';

$pdf = new PDFPP();

$pdf->AddPage();

/* Header */
$pdf->Image('arpus.png', 9, 10,-180);
$pdf->Ln(25);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,4, 'Surat Tanda Terima Peminjaman Buku',0,1,'C');
$pdf->Cell(190,4, 'Tanggal 12 Januari 2015',0,1,'C');
$pdf->Ln(10);
$pdf->SetFont('Arial','',12);
$pdf->Cell(7,7,'',0);
$pdf->Cell(30,7,'No ID', 0, '', 'L');
$pdf->Cell(5,7,':',0, '', 'C');
$pdf->Cell(120,7,'.........................', 0, '', 'L');
$pdf->Ln();
$pdf->Cell(7,7,'',0);
$pdf->Cell(30,7,'Nama', 0, '', 'L');
$pdf->Cell(5,7,':',0, '', 'C');
$pdf->Cell(120,7,'.........................', 0, '', 'L');
$pdf->Ln();
$pdf->Cell(7,7,'',0);
$pdf->Cell(30,7,'Tipe', 0, '', 'L');
$pdf->Cell(5,7,':',0, '', 'C');
$pdf->Cell(120,7,'.........................', 0, '', 'L');
$pdf->Ln();
$pdf->Cell(7,7,'',0);
$pdf->Cell(30,7,'Alamat', 0, '', 'L');
$pdf->Cell(5,7,':',0, '', 'C');
$pdf->Cell(120,7,'.........................', 0, '', 'L');
$pdf->Ln();
$pdf->Cell(7,7,'',0);
$pdf->Cell(30,7,'No.Telp', 0, '', 'L');
$pdf->Cell(5,7,':',0, '', 'C');
$pdf->Cell(120,7,'.........................', 0, '', 'L');
$pdf->Ln(10);
$pdf->Cell(8,7,'',0);
$pdf->Cell(10,7,'No.', 1, '', 'C');
$pdf->Cell(100,7,'Judul Buku',1, '', 'C');
$pdf->Cell(20,7, 'Jumlah', 1, '', 'C');
$pdf->Cell(40,7, 'Tanggal Kembali', 1, '', 'C');
$pdf->Ln();
$data = array(
			  array('title' => 'Wkwkwkwkwwkwkwkw Wkwkwkwkwwkwkwkw Wkwkwkwkwwkwkwkw')
             );
$pdf->tblContent($data);
$pdf->Ln(10);
$pdf->Cell(7,7,'',0);
$pdf->Cell(190,4, 'Perhatian :',0,1,'L');
$pdf->Ln(30);
$pdf->Cell(8,7,'',0);
$pdf->Cell(134,7,'Pemustaka', 0, '', 'L');
$pdf->Cell(40,7, 'Pustakawan', 0, '', 'L');
$pdf->Cell(20,7,'',0);
$pdf->Ln(30);
$pdf->Cell(8,7,'',0);
$pdf->Cell(134,7,'.........,', 0, '', 'L');
$pdf->Cell(40,7, '..........', 0, '', 'L');
$pdf->Cell(20,7,'',0);
$pdf->Output();