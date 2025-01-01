<?php
require_once APPROOT . '/libraries/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator {
    private $dompdf;
    
    public function __construct() {
        // Initialize DOMPDF options
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Enable remote resources
        $options->set('isHtml5ParserEnabled', true);

        
        // Create DOMPDF instance with the options
        $this->dompdf = new Dompdf($options);

    } 
    // public function generateQuotationPDF($quotation, $equipment) {
    //     $html = '
    //     <html>
    //     <head>
    //         <style>
    //             body {
    //                 font-family: Arial, sans-serif;
    //                 margin: 40px;
    //                 color: #333;
    //                 line-height: 1.6;
    //             }
                
    //             .header {
    //                 margin-bottom: 30px;
    //             }
                
    //             .company-info {
    //                 color: #1a5d1a;  /* Dark green for company name */
    //             }
                
    //             .company-info h1 {
    //                 color: #1a5d1a;
    //                 font-size: 24px;
    //                 margin: 0 0 10px 0;
    //             }
                
    //             .quote-info {
    //                 text-align: right;
    //                 color: #333;
    //             }
                
    //             .quote-number {
    //                 color: #1a5d1a;
    //                 font-size: 20px;
    //                 margin: 0;
    //             }
                
    //             .main-title {
    //                 color: #1a5d1a;
    //                 border-bottom: 2px solid #1a5d1a;
    //                 padding-bottom: 10px;
    //                 margin: 30px 0;
    //             }
                
    //             table {
    //                 width: 100%;
    //                 border-collapse: collapse;
    //                 margin: 20px 0;
    //             }
                
    //             th {
    //                 background-color: #1a5d1a;
    //                 color: white;
    //                 padding: 12px;
    //                 text-align: left;
    //             }
                
    //             td {
    //                 padding: 12px;
    //                 border-bottom: 1px solid #ddd;
    //             }
                
    //             tr:nth-child(even) {
    //                 background-color: #f8f8f8;
    //             }
                
    //             .total-section {
    //                 margin: 30px 0;
    //                 text-align: right;
    //             }
                
    //             .total-section .total-row {
    //                 margin: 8px 0;
    //             }
                
    //             .total-section .final-total {
    //                 color: #1a5d1a;
    //                 font-size: 18px;
    //                 font-weight: bold;
    //                 padding-top: 10px;
    //                 border-top: 2px solid #1a5d1a;
    //             }
                
    //             .terms-section {
    //                 margin-top: 40px;
    //                 border-top: 2px solid #1a5d1a;
    //                 padding-top: 20px;
    //             }
                
    //             .terms-section h3 {
    //                 color: #1a5d1a;
    //                 margin-bottom: 15px;
    //             }
                
    //             .terms-section ul {
    //                 list-style-type: none;
    //                 padding-left: 0;
    //             }
                
    //             .terms-section li {
    //                 padding: 5px 0;
    //                 padding-left: 20px;
    //                 position: relative;
    //             }
                
    //             .terms-section li:before {
    //                 content: "•";
    //                 color: #1a5d1a;
    //                 position: absolute;
    //                 left: 0;
    //             }
    //         </style>
    //     </head>
    //     <body>
    //         <div class="header">
    //             <div class="company-info">
    //                 <h1>SimplEx Solar Solutions</h1>
    //                 <p>123 Energy Street<br>
    //                 Green City, GC 12345<br>
    //                 Tel: (555) 123-4567<br>
    //                 Email: info@simplex.com</p>
    //             </div>
    //             <div class="quote-info">
    //                 <h2 class="quote-number">Quotation #QT' . str_pad($quotation->quotation_id, 5, '0', STR_PAD_LEFT) . '</h2>
    //                 <p>Date: ' . date('F d, Y') . '<br>
    //                 Valid until: ' . date('F d, Y', strtotime('+30 days')) . '</p>
    //             </div>
    //         </div>
    
    //         <h2 class="main-title">Solar System Installation Quotation</h2>
    
    //         <table>
    //             <tr>
    //                 <th>Description</th>
    //                 <th style="text-align: center">Quantity</th>
    //                 <th style="text-align: right">Unit Price</th>
    //                 <th style="text-align: right">Total</th>
    //             </tr>';
    
    //     $subtotal = 0;
    //     foreach($equipment as $item) {
    //         $html .= '
    //             <tr>
    //                 <td>' . $item->item_name . '</td>
    //                 <td style="text-align: center">' . $item->quantity . '</td>
    //                 <td style="text-align: right">Rs. ' . number_format($item->unit_price, 2) . '</td>
    //                 <td style="text-align: right">Rs. ' . number_format($item->total_price, 2) . '</td>
    //             </tr>';
    //         $subtotal += $item->total_price;
    //     }
    
    //     $html .= '
    //         </table>
    
    //         <div class="total-section">
    //             <div class="total-row">Subtotal: Rs. ' . number_format($subtotal, 2) . '</div>
    //             <div class="total-row">Service Charge: Rs. ' . number_format($quotation->service_charge, 2) . '</div>
    //             <div class="final-total">Total: Rs. ' . number_format($quotation->total_price, 2) . '</div>
    //         </div>
    
    //         <div class="terms-section">
    //             <h3>Terms and Conditions</h3>
    //             <ul>
    //                 <li>This quotation is valid for 30 days from the date of issue</li>
    //                 <li>Final pricing may be adjusted after site inspection</li>
    //                 <li>50% deposit required to commence work</li>
    //                 <li>Warranty: 25 years on panels, 10 years on inverter</li>
    //                 <li>Installation timeline: 2-3 weeks after deposit and permits</li>
    //             </ul>
    //         </div>
    //     </body>
    //     </html>';
    
    //     $this->dompdf->loadHtml($html);
    //     $this->dompdf->setPaper('A4', 'portrait');
    //     $this->dompdf->render();
    //     return $this->dompdf->output();
    // }

        
        public function generateQuotationPDF($quotation, $equipment) {

            $logoPath = URLROOT . '/public/assets/simpex-logo.png';
            $html = '
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 40px;
                        color: #2e7d32; /* Dark green theme */
                        line-height: 1.6;
                        background: url(' . $logoPath . ') no-repeat top right;
                        z-index: -1;
                        background-size: 200px;
                        background-opacity: 0.05;
                    }

                    .header {
                        margin-bottom: 30px;
                    }

                    .company-info {
                        color: #2e7d32;
                    }

                    .company-info h1 {
                        color: #2e7d32;
                        font-size: 24px;
                        margin: 0 0 10px 0;
                    }

                    .quote-info {
                        text-align: right;
                        color: #333;
                    }

                    .quote-number {
                        color: #2e7d32;
                        font-size: 20px;
                        margin: 0;
                    }

                    .main-title {
                        color: #2e7d32;
                        border-bottom: 2px solid #2e7d32;
                        padding-bottom: 10px;
                        margin: 30px 0;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                    }

                    th {
                        background-color: #2e7d32;
                        color: white;
                        padding: 12px;
                        text-align: left;
                    }

                    td {
                        padding: 12px;
                        border-bottom: 1px solid #ddd;
                    }

                    tr:nth-child(even) {
                        background-color: #f8f8f8;
                    }

                    .total-section {
                        margin: 30px 0;
                        text-align: right;
                    }

                    .total-section .total-row {
                        margin: 8px 0;
                    }

                    .total-section .final-total {
                        color: #2e7d32;
                        font-size: 18px;
                        font-weight: bold;
                        padding-top: 10px;
                        border-top: 2px solid #2e7d32;
                    }

                    .terms-section {
                        margin-top: 40px;
                        border-top: 2px solid #2e7d32;
                        padding-top: 20px;
                        font-size: 10px; /* Small font for terms */
                        color: #555; /* Muted color */
                    }

                    .terms-section h3 {
                        color: #2e7d32;
                        margin-bottom: 15px;
                    }

                    .terms-section ul {
                        list-style-type: none;
                        padding-left: 0;
                    }

                    .terms-section li {
                        padding: 5px 0;
                        padding-left: 20px;
                        position: relative;
                    }

                    .terms-section li:before {
                        content: "•";
                        color: #2e7d32;
                        position: absolute;
                        left: 0;
                    }

                    .copyright {
                        position: absolute;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        text-align: center;
                        font-size: 10px;
                        color: #555;
                        padding: 10px;
                        background-color: #f8f8f8;
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <div class="company-info">
                        <h1>SimplEx Solar Solutions</h1>
                        <p>123 Energy Street<br>
                        Green City, GC 12345<br>
                        Tel: (555) 123-4567<br>
                        Email: info@simplex.com</p>
                    </div>
                    <div class="quote-info">
                        <h2 class="quote-number">Quotation #QT' . str_pad($quotation->quotation_id, 5, '0', STR_PAD_LEFT) . '</h2>
                        <p>Date: ' . date('F d, Y') . '<br>
                        Valid until: ' . date('F d, Y', strtotime('+30 days')) . '</p>
                    </div>
                </div>
        
                <h2 class="main-title">Solar System Installation Quotation</h2>
        
                <table>
                    <tr>
                        <th>Description</th>
                        <th style="text-align: center">Quantity</th>
                        <th style="text-align: right">Unit Price</th>
                        <th style="text-align: right">Total</th>
                    </tr>';
        
            $subtotal = 0;
            foreach($equipment as $item) {
                $html .= '
                    <tr>
                        <td>' . $item->item_name . '</td>
                        <td style="text-align: center">' . $item->quantity . '</td>
                        <td style="text-align: right">Rs. ' . number_format($item->unit_price, 2) . '</td>
                        <td style="text-align: right">Rs. ' . number_format($item->total_price, 2) . '</td>
                    </tr>';
                $subtotal += $item->total_price;
            }
        
            $html .= '
                </table>
        
                <div class="total-section">
                    <div class="total-row">Subtotal: Rs. ' . number_format($subtotal, 2) . '</div>
                    <div class="total-row">Service Charge: Rs. ' . number_format($quotation->service_charge, 2) . '</div>
                    <div class="final-total">Total: Rs. ' . number_format($quotation->total_price, 2) . '</div>
                </div>
        
                <div class="terms-section">
                    <h3>Terms and Conditions</h3>
                    <ul>
                        <li>This quotation is valid for 30 days from the date of issue</li>
                        <li>Final pricing may be adjusted after site inspection</li>
                        <li>50% deposit required to commence work</li>
                        <li>Warranty: 25 years on panels, 10 years on inverter</li>
                        <li>Installation timeline: 2-3 weeks after deposit and permits</li>
                    </ul>
                </div>

                <div class="copyright">
                    <p>&copy; ' . date('Y') . ' SimplEx Solar Solutions. All rights reserved.</p>
                </div>
            </body>
            </html>';
        
            $this->dompdf->loadHtml($html);
            $this->dompdf->setPaper('A4', 'portrait');
            $this->dompdf->render();
            return $this->dompdf->output();
        }

    
}