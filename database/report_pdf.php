<?php
require('fpdf/fpdf.php');
include 'connection.php';

class IMSPDF extends FPDF
{
    function __construct()
    {
        // default to landscape orientation
        parent::__construct('L');
    }

    // Load data
    function LoadData($type)
    {
        $select_query = '';
        if ($type === 'products') {
            $select_query = "SELECT products.*,UserLoginInformation.first_name,UserLoginInformation.last_name FROM products INNER JOIN UserLoginInformation ON products.created_by=UserLoginInformation.id ORDER BY products.created_at DESC";
        } else if ($type === 'suppliers') {
            $select_query = "SELECT suppliers.*,UserLoginInformation.first_name,UserLoginInformation.last_name FROM suppliers INNER JOIN UserLoginInformation ON suppliers.created_by=UserLoginInformation.id ORDER BY suppliers.created_at DESC";
        } else if ($type === 'deliveries') {
            $select_query = "SELECT * FROM product_order_history ORDER BY created_at DESC";
        } else if ($type === 'orders') {
            $select_query = "SELECT * FROM product_order ORDER BY created_at DESC";
        }

        include 'connection.php';
        $stmt = $connection->prepare($select_query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
        // Read file lines
        // $lines = file($file);
        // $data = array();
        // foreach ($lines as $line)
        //     $data[] = explode(';', trim($line));
        // return $data;
    }

    function GetHeader($dataRows, $type)
    {
        if (count($dataRows) > 0) {
            $row_header = $dataRows[0];

            // Clean up the data
            if ($type === 'products' || $type === 'suppliers') {
                unset($row_header['first_name'], $row_header['last_name']);
                if ($type === 'products') {
                    unset($row_header['image']);
                }
            } else if ($type === 'deliveries') {

            } else if ($type === 'orders') {

            }
            return array_keys($row_header);
        }
        return [];
    }

    function GetData($dataRows, $type)
    {
        $data = [];
        if (count($dataRows) > 0) {
            foreach ($dataRows as $row) {
                // Clean up the data
                if ($type === 'products' || $type === 'suppliers') {
                    $row['created_by'] = $row['first_name'] . ' ' . $row['last_name'];
                    $row['updated_at'] = date('M d,Y H:i:s A', strtotime($row['updated_at']));
                    $row['created_at'] = date('M d,Y H:i:s A', strtotime($row['created_at']));
                    unset($row['first_name'], $row['last_name']);
                    if ($type === 'products') {
                        unset($row['image']);
                    }
                } else if ($type === 'deliveries') {

                } else if ($type === 'orders') {

                }

                // detect double-quotes and escape any values that contains them
                array_walk($row, function (&$str) {
                    $str = preg_replace("/\t/", "\\t", $str);
                    $str = preg_replace("/\r?\n/", "\\n", $str);
                    if (strstr($str, '"'))
                        $str = '"' . str_replace('"', '""', $str) . '"';
                });

                $data[] = array_values($row);
            }
        }
        return $data;
    }

    // Colored table
    function FancyTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(15, 40, 60, 20, 40, 50, 50);
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, number_format($row[0]), 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);

            $current_y = $this->GetY();
            $current_x = $this->GetX();
            $this->MultiCell($w[2], 6, $row[2], 'LR', 'L', $fill);
            $this->SetXY($current_x + $w[2], $current_y);

            // $this->Cell($w[3], 6, $row[3], 'LR', 0, 'C', $fill); // Image
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'C', $fill);
            $this->Cell($w[4], 6, $row[4], 'LR', 0, 'C', $fill);
            $this->Cell($w[5], 6, $row[5], 'LR', 0, 'L', $fill);
            $this->Cell($w[6], 6, $row[6], 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$type = $_GET['report'];
$mapping_filenames = [
    'products' => 'Products Report',
    'suppliers' => 'Suppliers Report',
    'deliveries' => 'Deliveries Report',
    'orders' => 'Product Orders Report',
];

$file_name = $mapping_filenames[$type] . ' ' . gmdate('Y-m-d H:i:s') . '.csv';

$pdf = new IMSPDF();
// Data loading
$dataRows = $pdf->LoadData($type);

if (count($dataRows) > 0) {
    // Get column headings
    $header = $pdf->GetHeader($dataRows, $type);

    // Get data
    $data = $pdf->GetData($dataRows, $type);
    
    $pdf->SetFont('Arial', '', 16);
    $pdf->AddPage();
    $pdf->Cell(110);
    $pdf->Cell(50, 10, $mapping_filenames[$type], 0, 0, 'C');
    // Line break
    $pdf->Ln(20);
    $pdf->SetFont('Arial', '', 11);
    $pdf->FancyTable($header, $data);
    $pdf->Output();
}
?>