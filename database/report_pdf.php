<?php
require('fpdf/fpdf.php');
include 'connection.php';

enum CellType
{
    case Cell;
    case Multi;
    case Image;
}

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
        } else if ($type === 'orders') {
            $select_query = "SELECT product_order.*,suppliers.supplier_name,products.product_name,UserLoginInformation.first_name,UserLoginInformation.last_name 
                FROM product_order 
                INNER JOIN products ON product_order.product=products.id 
                INNER JOIN suppliers ON product_order.supplier=suppliers.id 
                INNER JOIN UserLoginInformation ON product_order.created_by=UserLoginInformation.id 
                ORDER BY product_order.batch DESC";
        } else if ($type === 'deliveries') {
            $select_query = "SELECT product_order_history.date_received, product_order_history.quatity_received as quantity_received,product_order.batch,product_order.created_by,
                UserLoginInformation.first_name,UserLoginInformation.last_name, products.product_name, suppliers.supplier_name 
                FROM product_order_history,product_order,products,suppliers,UserLoginInformation 
                WHERE product_order_history.product_order_id=product_order.id 
                AND product_order.created_by=UserLoginInformation.id 
                AND product_order.supplier=suppliers.id 
                AND product_order.product=products.id 
                ORDER BY product_order.batch DESC";
        }

        include 'connection.php';
        $stmt = $connection->prepare($select_query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    function GetHeader($dataRows, $type)
    {
        if (count($dataRows) > 0) {
            $row_header = $dataRows[0];

            // Clean up the data
            if ($type === 'products' || $type === 'suppliers') {
                unset($row_header['first_name'], $row_header['last_name']);
            } else if ($type === 'orders') {
                unset($row_header['first_name'], $row_header['last_name'], $row_header['product'], $row_header['supplier'], $row_header['id'], $row_header['updated_at']);
            } else if ($type === 'deliveries') {
                unset($row_header['first_name'], $row_header['last_name']);
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
                    $row['id'] = number_format($row['id']);
                    $row['stock'] = number_format($row['stock']);
                    $row['created_by'] = $row['first_name'] . ' ' . $row['last_name'];
                    $row['updated_at'] = date('M d,Y H:i:s A', strtotime($row['updated_at']));
                    $row['created_at'] = date('M d,Y H:i:s A', strtotime($row['created_at']));
                    if ($type === 'products') {
                        if ($row['image']) {
                            $row['image'] = '.././uploads/products/' . $row['image'];
                        }
                    } else {
                        unset($row['stock']);
                    }
                    unset($row['first_name'], $row['last_name']);
                } else if ($type === 'orders') {
                    $row['created_by'] = $row['first_name'] . ' ' . $row['last_name'];
                    $row['batch'] = number_format($row['batch']);
                    $row['quantity_ordered'] = !$row['quantity_ordered'] ? 0 : number_format($row['quantity_ordered']);
                    $row['quantity_received'] = !$row['quantity_received'] ? 0 : number_format($row['quantity_received']);
                    $row['quantity_remaining'] = !$row['quantity_remaining'] ? 0 : number_format($row['quantity_remaining']);
                    $row['updated_at'] = date('M d,Y H:i:s A', strtotime($row['updated_at']));
                    $row['created_at'] = date('M d,Y H:i:s A', strtotime($row['created_at']));

                    unset($row['first_name'], $row['last_name'], $row['product'], $row['supplier'],$row['id'],$row['updated_at']);
                } else if ($type === 'deliveries') {
                    $row['batch'] = number_format($row['batch']);
                    $row['quantity_received'] = number_format($row['quantity_received']);
                    $row['created_by'] = $row['first_name'] . ' ' . $row['last_name'];
                    $row['date_received'] = date('M d,Y H:i:s A', strtotime($row['date_received']));
                    unset($row['first_name'], $row['last_name']);
                }

                // detect double-quotes and escape any values that contains them
                array_walk($row, function (&$str) {
                    $str = preg_replace("/\t/", "\\t", $str);
                    $str = preg_replace("/\r?\n/", "\\n", $str);
                    if (strstr($str, '"'))
                        $str = '"' . str_replace('"', '""', $str) . '"';
                });
                $data[] = $row;
            }
        }
        return $data;
    }

    // Returns each column's meta data
    private function GetColumnMetaData($type)
    {
        return [
            'id' => [
                'displayName' => 'ID',
                'width' => 15,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'product_name' => [
                'displayName' => 'Product',
                'width' => 35,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'supplier_name' => [
                'displayName' => 'Supplier',
                'width' => 35,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'supplier_location' => [
                'displayName' => 'Supplier Location',
                'width' => 35,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'email' => [
                'displayName' => 'Email',
                'width' => 45,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'description' => [
                'displayName' => 'Description',
                'width' => 55,
                'type' => CellType::Multi,
                'align' => 'L'
            ],
            'image' => [
                'displayName' => 'Image',
                'width' => 35,
                'type' => CellType::Image,
                'align' => 'C'
            ],
            'stock' => [
                'displayName' => 'Stock',
                'width' => 15,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'batch' => [
                'displayName' => 'Batch',
                'width' => 30,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'date_received' => [
                'displayName' => 'Date Received',
                'width' => 60,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'quantity_received' => [
                'displayName' => 'Qty Received',
                'width' => 25,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'quantity_remaining' => [
                'displayName' => 'Qty Remaining',
                'width' => 25,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'quantity_ordered' => [
                'displayName' => 'Qty Ordered',
                'width' => 25,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'status' => [
                'displayName' => 'Status',
                'width' => 30,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'created_by' => [
                'displayName' => 'Created By',
                'width' => 30,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'created_at' => [
                'displayName' => 'Created At',
                'width' => 45,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
            'updated_at' => [
                'displayName' => 'Updated At',
                'width' => 45,
                'type' => CellType::Cell,
                'align' => 'C'
            ],
        ];
    }

    // Add table
    function PopulateTable($header, $data, $type, $row_height)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Header
        // $w = array(12, 35, 55, 35, 15, 30, 45, 45);
        $columnInfo = $this->GetColumnMetaData($type);
        $totalWidth = 0;
        for ($i = 0; $i < count($header); $i++) {
            $width = $columnInfo[$header[$i]]['width'];
            $text = $columnInfo[$header[$i]]['displayName'];
            $totalWidth += $width;

            $this->Cell($width, 7, $text, 1, 0, 'C', true);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Data
        foreach ($data as $rowData) {

            // echo '<pre>';
            // var_dump($rowData);
            // echo '</pre>';

            foreach ($rowData as $key => $row) {
                $columnData = $columnInfo[$key];
                $cellType = $columnData['type'];
                $width = $columnData['width'];
                $align = $columnData['align'];

                if ($cellType === CellType::Multi) {
                    $current_y = $this->GetY();
                    $current_x = $this->GetX();
                    $this->MultiCell($width, 6, $row, 'LRT', $align);
                    $this->SetXY($current_x + $width, $current_y);
                } else if ($cellType === CellType::Image) {
                    $current_y = $this->GetY();
                    $current_x = $this->GetX();
                    $image_name = $row ? $this->Image($row, $current_x, $current_y, 30, 25) : 'No image filed';
                    $this->Cell($width, $row_height, $image_name, 'LRBT', 0, $align);
                } else {
                    $this->Cell($width, $row_height, $row, 'LRBT', 0, $align);
                }
            }
            $this->Ln();
        }
        // Closing line
        $this->Cell($totalWidth, 0, '', 'T');
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
    $row_height = $type === 'products' ? 30 : 20;

    $pdf->SetFont('Arial', '', 16);
    $pdf->AddPage();
    $pdf->Cell(110);
    $pdf->Cell(50, 10, $mapping_filenames[$type], 0, 0, 'C');
    // Line break
    $pdf->Ln(20);
    $pdf->SetFont('Arial', '', 10);
    $pdf->PopulateTable($header, $data, $type,$row_height);
    $pdf->Output();
}
?>