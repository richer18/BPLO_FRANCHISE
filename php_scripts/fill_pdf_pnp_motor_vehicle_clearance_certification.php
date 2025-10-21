<?php
require __DIR__ . '/../backend/vendor/autoload.php';
use setasign\Fpdi\Fpdi;


// --- Database connection (same as your .env in Laravel) ---
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "business_permit_license"; // ⚠️ change to your actual DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// --- Get record ID from URL ---
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid record ID.");
}

// --- Fetch record from database ---
$sql = "SELECT * FROM bplo_records WHERE ID = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Record not found.");
}

$data = $result->fetch_assoc();

// --- FPDI setup ---
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

$pdf = new Fpdi();
$pdf->AddPage();
$pdf->setSourceFile(__DIR__ . '/../template/PNP_MOTOR_VEHICLE_CLEARANCE_CERTIFICATION_CLASS_B_TEMPLATE_V3.pdf');
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 215.9, 330.2); // long bond size

$pdf->SetFont('Times', '', 12);


// --- Hardcoded values ---
$operator_name   = strtoupper(trim($data['FNAME'] . " " . $data['MNAME'] . " " . $data['LNAME']));
$barangay        = strtoupper($data['BARANGAY']);
$make            = strtoupper($data['MAKE']);
$motor_no        = strtoupper($data['MOTOR_NO']);
$chassis_no      = strtoupper($data['CHASSIS_NO']);
$plate_no        = strtoupper($data['PLATE']);
$color           = strtoupper($data['COLOR']);
$date_registered = date("F j, Y", strtotime($data['DATE'] ?? date("Y-m-d")));
$date_pay = $date_registered;
$original_receipt = strtoupper($data['ORIGINAL_RECEIPT_PAYMENT']);
$lto_original_receipt =strtoupper($data['LTO_ORIGINAL_RECEIPT']);
$lto_certificate_registration = strtoupper($data['LTO_CERTIFICATE_REGISTRATION']);
$mv_file_no = strtoupper($data['LTO_MV_FILE_NO']);
$amount =strtoupper($data['AMOUNT']);


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
