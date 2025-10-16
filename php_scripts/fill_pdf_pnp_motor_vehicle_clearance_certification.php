<?php
require 'vendor/autoload.php';
use setasign\Fpdi\Fpdi;

// Function: Get suffix only
function getOrdinalSuffix($number) {
    if (!in_array(($number % 100), [11, 12, 13])) {
        switch ($number % 10) {
            case 1: return "ST";
            case 2: return "ND";
            case 3: return "RD";
        }
    }
    return "TH";
}

// Create new PDF
$pdf = new Fpdi();
$pdf->AddPage();

// Import PDF template
$pdf->setSourceFile('PNP_MOTOR_VEHICLE_CLEARANCE_CERTIFICATION_CLASS_B_TEMPLATE_V3.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 215.9, 330.2); // long bond size (8.5 x 13 in)

// Font setup
$pdf->SetFont('Times', '', 12);

// --- Hardcoded values ---
$operator_name   = "JUAN DELA CRUZ";
$barangay        = "MAYABON";
$make            = "HONDA";
$motor_no        = "MTR-123456";
$chassis_no      = "CHS-78910";
$plate_no        = "XYZ-321";
$color        = "RED";
$date_registered = "OCTOBER 1, 2025";
$date_pay = $date_registered;
$original_receipt = "12345678";
$lto_original_receipt = "1244678";
$lto_certificate_registration = "1244678";
$mv_file_no = "1244678";
$amount = "740";


// --- Write text on template ---
$pdf->SetXY(90, 111);
$pdf->Write(10, $operator_name);


$pdf->SetXY(67, 115);
$pdf->Write(11, $barangay);

$pdf->SetXY(73, 93);
$pdf->Write(10, $make);

$pdf->SetXY(73, 98);
$pdf->Write(10, $motor_no);

$pdf->SetXY(75, 121);
$pdf->Write(9, $chassis_no);

$pdf->SetXY(73, 103);
$pdf->Write(9, $plate_no);

$pdf->SetXY(136, 61);
$pdf->Write(11, $date_registered);

$pdf->SetXY(73, 107);
$pdf->Write(10, $color);


$pdf->SetXY(36, 133);
$pdf->Write(12, $lto_original_receipt);

$pdf->SetXY(85, 133);
$pdf->Write(12, $lto_certificate_registration);

$pdf->SetXY(155, 133);
$pdf->Write(12, $mv_file_no);

$pdf->SetXY(40, 246);
$pdf->Write(12, $original_receipt);

$pdf->SetXY(44, 251);
$pdf->Write(11, $date_pay);

$pdf->SetXY(55, 260);
$pdf->Write(11, $amount);

// Output PDF
$pdf->Output('I', 'Filled_Application.pdf');
?>
