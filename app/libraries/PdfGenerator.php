<?php
require_once APPROOT . '/libraries/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGenerator
{
    private $dompdf;

    public function __construct()
    {
        // Initialize DOMPDF options
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Enable remote resources
        $options->set('isHtml5ParserEnabled', true);


        // Create DOMPDF instance with the options
        $this->dompdf = new Dompdf($options);
    }


    public function generateQuotationPDF($quotation, $equipment) {
        $logoPath = URLROOT . '/public/assets/simpex-logo.png';
        
        // Ensure the equipment and total price values are correct
        $subtotal = 0;
        foreach ($equipment as $item) {
            // Make sure the unit_price and total_price are correct
            // Sometimes values in the database might be stored differently than display needs
            $item->unit_price = floatval($item->unit_price);
            
            // Recalculate the total price for this item to be sure it's correct
            $item->total_price = $item->unit_price * $item->quantity;
            
            // Add to subtotal
            $subtotal += $item->total_price;
        }
        
        // Ensure service charge is a proper float
        $serviceCharge = floatval($quotation->service_charge);
        
        // Calculate the final total
        $totalPrice = $subtotal + $serviceCharge;
        
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

        foreach ($equipment as $item) {
            $html .= '
                    <tr>
                        <td>' . $item->item_name . '</td>
                        <td style="text-align: center">' . $item->quantity . '</td>
                        <td style="text-align: right">Rs. ' . number_format($item->unit_price, 2) . '</td>
                        <td style="text-align: right">Rs. ' . number_format($item->total_price, 2) . '</td>
                    </tr>';
        }

        $html .= '
                </table>
        
                <div class="total-section">
                    <div class="total-row">Subtotal: Rs. ' . number_format($subtotal, 2) . '</div>
                    <div class="total-row">Service Charge: Rs. ' . number_format($serviceCharge, 2) . '</div>
                    <div class="final-total">Total: Rs. ' . number_format($totalPrice, 2) . '</div>
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
    


    public function generateAgreementPDF($agreement, $equipment) {
        $logoPath = URLROOT . '/public/assets/simpex-logo.png';
        
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Solar System Installation Agreement</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 40px;
                    color: #333;
                    line-height: 1.6;
                }
                
                .header {
                    margin-bottom: 30px;
                    border-bottom: 2px solid #2e7d32;
                    padding-bottom: 20px;
                }
                
                .company-info {
                    float: left;
                    width: 50%;
                }
                
                .company-info h1 {
                    color: #2e7d32;
                    font-size: 24px;
                    margin: 0 0 10px 0;
                }
                
                .agreement-info {
                    float: right;
                    width: 40%;
                    text-align: right;
                }
                
                .agreement-number {
                    color: #2e7d32;
                    font-size: 18px;
                    margin: 0 0 10px 0;
                }
                
                .clearfix::after {
                    content: "";
                    clear: both;
                    display: table;
                }
                
                .title {
                    text-align: center;
                    color: #2e7d32;
                    font-size: 22px;
                    margin: 30px 0;
                    text-transform: uppercase;
                }
                
                .section {
                    margin-bottom: 30px;
                }
                
                .section-title {
                    color: #2e7d32;
                    border-bottom: 1px solid #2e7d32;
                    padding-bottom: 5px;
                    margin-bottom: 15px;
                }
                
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                
                th {
                    background-color: #2e7d32;
                    color: white;
                    text-align: left;
                    padding: 10px;
                }
                
                td {
                    padding: 10px;
                    border-bottom: 1px solid #ddd;
                }
                
                tr:nth-child(even) {
                    background-color: #f8f8f8;
                }
                
                .price-summary {
                    width: 60%;
                    float: right;
                    margin-top: 20px;
                }
                
                .price-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 0;
                    border-bottom: 1px solid #eee;
                }
                
                .price-row.total {
                    font-weight: bold;
                    border-top: 2px solid #2e7d32;
                    border-bottom: 2px solid #2e7d32;
                    margin-top: 10px;
                    color: #2e7d32;
                }
                
                .terms {
                    margin-top: 40px;
                    font-size: 12px;
                }
                
                .signature-block {
                    margin-top: 50px;
                    page-break-inside: avoid;
                }
                
                .signature {
                    display: inline-block;
                    width: 45%;
                    margin-right: 5%;
                    vertical-align: top;
                }
                
                .signature-line {
                    border-top: 1px solid #333;
                    margin-top: 50px;
                    padding-top: 10px;
                }
                
                .signature img {
                    max-width: 200px;
                    max-height: 80px;
                    margin-bottom: 10px;
                }
                
                .date {
                    font-style: italic;
                    color: #666;
                }
                
                .footer {
                    margin-top: 50px;
                    text-align: center;
                    font-size: 10px;
                    color: #666;
                    border-top: 1px solid #eee;
                    padding-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="header clearfix">
                <div class="company-info">
                    <h1>SimplEx Solar Solutions</h1>
                    <p>123 Energy Street<br>
                    Green City, GC 12345<br>
                    Tel: (555) 123-4567<br>
                    Email: info@simplex.com</p>
                </div>
                <div class="agreement-info">
                    <h2 class="agreement-number">Agreement #AG' . str_pad($agreement->agreement_id, 5, '0', STR_PAD_LEFT) . '</h2>
                    <p>Date: ' . date('F d, Y', strtotime($agreement->created_at)) . '<br>
                    Status: ' . ucfirst($agreement->status) . '</p>
                </div>
            </div>
            
            <div class="title">Solar System Installation Agreement</div>
            
            <div class="section">
                <h3 class="section-title">Client Information</h3>
                <p>
                    <strong>Client Name:</strong> ' . $agreement->customer_name . '<br>
                    <strong>Phone:</strong> ' . $agreement->phone . '<br>
                </p>
            </div>
            
            <div class="section">
                <h3 class="section-title">System Specifications</h3>
                <p>
                    <strong>System Capacity:</strong> ' . $agreement->system_capacity . ' kW<br>
                    <strong>Estimated Annual Generation:</strong> ' . $agreement->estimated_generation . ' kWh/year<br>
                </p>
            </div>
            
            <div class="section">
                <h3 class="section-title">Equipment List</h3>
                <table>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>';
        
        $subtotal = 0;
        foreach($equipment as $item) {
            $subtotal += $item->total_price;
            $html .= '
                    <tr>
                        <td>' . $item->item_name . '</td>
                        <td>' . $item->quantity . '</td>
                        <td>Rs. ' . number_format($item->unit_price, 2) . '</td>
                        <td>Rs. ' . number_format($item->total_price, 2) . '</td>
                    </tr>';
        }
        
        $html .= '
                </table>
                
                <div class="price-summary">
                    <div class="price-row">
                        <span>Subtotal:</span>
                        <span>Rs. ' . number_format($subtotal, 2) . '</span>
                    </div>
                    <div class="price-row">
                        <span>Service Charge:</span>
                        <span>Rs. ' . number_format($agreement->service_charge, 2) . '</span>
                    </div>
                    <div class="price-row total">
                        <span>Total Price:</span>
                        <span>Rs. ' . number_format($agreement->total_price, 2) . '</span>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
            
            <div class="section">
                <h3 class="section-title">Notes</h3>
                <p>' . nl2br(htmlspecialchars($agreement->notes)) . '</p>
            </div>
            
            <div class="section">
                <h3 class="section-title">Payment Terms</h3>
                <p>The total price of this agreement is payable in two installments:</p>
                <ol>
                    <li><strong>First Payment (50%):</strong> Rs. ' . number_format($agreement->total_price * 0.5, 2) . ' - Due upon signing this agreement</li>
                    <li><strong>Final Payment (50%):</strong> Rs. ' . number_format($agreement->total_price * 0.5, 2) . ' - Due upon completion of installation</li>
                </ol>
            </div>
            
            <div class="terms">
                <h3 class="section-title">Terms and Conditions</h3>
                <ol>
                    <li><strong>Scope of Work:</strong> SimplEx Solar Solutions agrees to install the solar system as specified in this agreement at the client\'s property.</li>
                    
                    <li><strong>Payment Schedule:</strong> The client agrees to make payments according to the payment terms outlined in this agreement.</li>
                    
                    <li><strong>Cancellation Policy:</strong> After signing this agreement, the project cannot be cancelled. If the first payment has been made, it is non-refundable.</li>
                    
                    <li><strong>Installation Timeline:</strong> Installation will commence within 30 days of the first payment, subject to permit approvals and weather conditions.</li>
                    
                    <li><strong>Warranty:</strong> All equipment is covered by manufacturer warranties. SimplEx Solar Solutions provides a 2-year workmanship warranty on the installation.</li>
                    
                    <li><strong>Access to Property:</strong> The client agrees to provide SimplEx Solar Solutions with reasonable access to the property for installation and maintenance purposes.</li>
                    
                    <li><strong>Permits and Approvals:</strong> SimplEx Solar Solutions will obtain all necessary permits and approvals for the installation. The client agrees to provide any required documentation promptly.</li>
                    
                    <li><strong>System Performance:</strong> The estimated annual generation is based on historical weather data and system specifications. Actual generation may vary based on weather patterns and other factors.</li>
                    
                    <li><strong>Changes to Agreement:</strong> Any changes to this agreement must be made in writing and agreed upon by both parties.</li>
                    
                    <li><strong>Legal Jurisdiction:</strong> This agreement is governed by the laws of Sri Lanka. Any disputes arising from this agreement shall be resolved in the courts of Sri Lanka.</li>
                </ol>
            </div>
            
            <div class="signature-block">
                <h3 class="section-title">Signatures</h3>
                <p>By signing below, both parties acknowledge that they have read, understood, and agree to the terms and conditions of this agreement.</p>
                
                <div class="signature">
                    <strong>SimplEx Solar Solutions:</strong>';
        
        if ($agreement->signature_image) {
            $coordinatorSignaturePath = APPROOT . '/../public/uploads/signatures/' . $agreement->signature_image;
            if (file_exists($coordinatorSignaturePath)) {
                // Convert image to base64 for embedding in PDF
                $type = pathinfo($coordinatorSignaturePath, PATHINFO_EXTENSION);
                $data = file_get_contents($coordinatorSignaturePath);
                $coordinatorSigBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $html .= '<img src="' . $coordinatorSigBase64 . '" alt="Coordinator Signature">';
            }
        }
        
        $html .= '
                    <div class="signature-line">
                        <strong>Authorized Representative</strong><br>
                        <span class="date">Date: ' . date('F d, Y', strtotime($agreement->created_at)) . '</span>
                    </div>
                </div>
                
                <div class="signature">
                    <strong>Client:</strong>';
        
        if ($agreement->customer_signature) {
            $customerSignaturePath = APPROOT . '/../public/uploads/signatures/customers/' . $agreement->customer_signature;
            if (file_exists($customerSignaturePath)) {
                // Convert image to base64 for embedding in PDF
                $type = pathinfo($customerSignaturePath, PATHINFO_EXTENSION);
                $data = file_get_contents($customerSignaturePath);
                $customerSigBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $html .= '<img src="' . $customerSigBase64 . '" alt="Customer Signature">';
            }
        }
        
        $html .= '
                    <div class="signature-line">
                        <strong>' . $agreement->customer_name . '</strong><br>
                        <span class="date">Date: ' . date('F d, Y', strtotime($agreement->updated_at)) . '</span>
                    </div>
                </div>
            </div>
            
            <div class="footer">
                <p>Agreement #AG' . str_pad($agreement->agreement_id, 5, '0', STR_PAD_LEFT) . ' | Page 1 of 1</p>
                <p>&copy; ' . date('Y') . ' SimplEx Solar Solutions. All rights reserved.</p>
            </div>
        </body>
        </html>';

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    

    public function generateBankDepositSlip($data)
    {
        $html = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    color: #333;
                }
                
                .bank-slip {
                    width: 100%;
                    max-width: 800px;
                    margin: 0 auto;
                    border: 2px solid #2e7d32;
                    padding: 20px;
                }
                
                .header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 2px solid #2e7d32;
                    padding-bottom: 10px;
                    margin-bottom: 20px;
                }
                
                .logo {
                    width: 150px;
                }
                
                .slip-title {
                    font-size: 24px;
                    font-weight: bold;
                    color: #2e7d32;
                    text-align: center;
                    margin: 10px 0 20px;
                }
                
                .bank-info, .payment-info, .customer-info {
                    margin-bottom: 20px;
                }
                
                .section-title {
                    font-size: 16px;
                    font-weight: bold;
                    color: #2e7d32;
                    margin-bottom: 10px;
                    border-bottom: 1px solid #ddd;
                    padding-bottom: 5px;
                }
                
                .info-row {
                    display: flex;
                    margin-bottom: 5px;
                }
                
                .label {
                    width: 150px;
                    font-weight: bold;
                }
                
                .value {
                    flex: 1;
                }
                
                .amount {
                    font-size: 18px;
                    font-weight: bold;
                    color: #2e7d32;
                    text-align: right;
                    margin: 20px 0;
                }
                
                .amount-words {
                    margin-bottom: 20px;
                    font-style: italic;
                }
                
                .signatures {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 50px;
                }
                
                .signature-box {
                    width: 45%;
                    border-top: 1px solid #333;
                    padding-top: 5px;
                    text-align: center;
                }
                
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
                
                .instructions {
                    margin-top: 20px;
                    font-size: 12px;
                    border: 1px dashed #ccc;
                    padding: 10px;
                    background-color: #f9f9f9;
                }
            </style>
        </head>
        <body>
            <div class="bank-slip">
                <div class="header">
                    <div class="company-info">
                        <h2 style="margin: 0; color: #2e7d32;">SimplEx Solar Solutions</h2>
                        <p style="margin: 5px 0;">' . address . '</p>
                    </div>
                    <div>
                        <p style="text-align: right;">Reference: ' . $data['reference'] . '</p>
                        <p style="text-align: right;">Date: ' . date('Y-m-d') . '</p>
                    </div>
                </div>
                
                <h1 class="slip-title">BANK DEPOSIT SLIP</h1>
                
                <div class="bank-info">
                    <h3 class="section-title">Bank Details</h3>
                    <div class="info-row">
                        <span class="label">Bank Name:</span>
                        <span class="value">' . $data['bank_account']['bank_name'] . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Name:</span>
                        <span class="value">' . $data['bank_account']['account_name'] . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Number:</span>
                        <span class="value">' . $data['bank_account']['account_number'] . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Branch:</span>
                        <span class="value">' . $data['bank_account']['branch'] . ' (' . $data['bank_account']['branch_code'] . ')</span>
                    </div>
                </div>
                
                <div class="customer-info">
                    <h3 class="section-title">Customer Details</h3>
                    <div class="info-row">
                        <span class="label">Customer Name:</span>
                        <span class="value">' . htmlspecialchars($data['customer_name']) . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Customer ID:</span>
                        <span class="value">' . $data['customer_id'] . '</span>
                    </div>
                </div>
                
                <div class="payment-info">
                    <h3 class="section-title">Payment Details</h3>
                    <div class="info-row">
                        <span class="label">Project ID:</span>
                        <span class="value">' . $data['project_id'] . '</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Payment Type:</span>
                        <span class="value">' . $data['payment_type'] . '</span>
                    </div>
                </div>
                
                <div class="amount">
                    Amount: Rs. ' . number_format($data['amount'], 2) . '
                </div>
                
                <div class="amount-words">
                    Amount in words: ' . $this->numberToWords($data['amount']) . ' rupees only
                </div>
                
                <div class="signatures">
                    <div class="signature-box">
                        Customer Signature
                    </div>
                    <div class="signature-box">
                        Bank Officer Signature & Stamp
                    </div>
                </div>
                
                <div class="instructions">
                    <strong>Instructions:</strong>
                    <ol>
                        <li>Print this deposit slip and take it to any branch of ' . $data['bank_account']['bank_name'] . '.</li>
                        <li>Make sure the bank officer signs and stamps the slip after depositing the amount.</li>
                        <li>Upload a scanned copy or clear photo of the stamped deposit slip to your project dashboard.</li>
                        <li>Keep the original receipt for your records.</li>
                    </ol>
                </div>
                
                <div class="footer">
                    <p>This is an official payment document for SimplEx Solar Solutions.</p>
                    <p>For any queries please contact: finance@simpex.com | Phone: 011-2345678</p>
                </div>
            </div>
        </body>
        </html>';

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    /**
     * Convert a number to words
     * 
     * @param float $number Number to convert
     * @return string Number in words
     */
    private function numberToWords($number)
    {
        $ones = array(
            0 => "Zero",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );

        $tens = array(
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety"
        );

        // For Sri Lankan currency format (Rupees)
        $thousands = array(
            "",
            "Thousand",
            "Million",
            "Billion",
            "Trillion"
        );

        $number = number_format($number, 2, '.', '');

        $number_array = explode('.', $number);
        $wholeNumber = $number_array[0];
        $decimalNumber = $number_array[1];

        $result = "";

        // Process whole number
        $wholeNumber = (int)$wholeNumber;
        if ($wholeNumber == 0) {
            $result = "Zero";
        } else {
            // Handle millions
            $millions = floor($wholeNumber / 1000000);
            if ($millions > 0) {
                $result .= $this->convertLessThanThousand($millions) . " Million ";
                $wholeNumber %= 1000000;
            }

            // Handle thousands
            $thousands = floor($wholeNumber / 1000);
            if ($thousands > 0) {
                $result .= $this->convertLessThanThousand($thousands) . " Thousand ";
                $wholeNumber %= 1000;
            }

            // Handle hundreds and remaining
            if ($wholeNumber > 0) {
                $result .= $this->convertLessThanThousand($wholeNumber);
            }
        }

        // Process decimal part
        if ($decimalNumber > 0) {
            $result .= " and ";

            if ((int)$decimalNumber < 20) {
                $result .= $ones[(int)$decimalNumber];
            } else {
                $tensVal = floor($decimalNumber / 10);
                $onesVal = $decimalNumber % 10;

                $result .= $tens[$tensVal];
                if ($onesVal > 0) {
                    $result .= " " . $ones[$onesVal];
                }
            }

            $result .= " Cents";
        }

        return $result;
    }

    /**
     * Convert a number less than 1000 to words
     * 
     * @param int $number Number to convert
     * @return string Number in words
     */
    private function convertLessThanThousand($number)
    {
        $ones = array(
            0 => "",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );

        $tens = array(
            0 => "",
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety"
        );

        $result = "";

        // Handle hundreds
        $hundreds = floor($number / 100);
        if ($hundreds > 0) {
            $result .= $ones[$hundreds] . " Hundred";
            $number %= 100;

            if ($number > 0) {
                $result .= " and ";
            }
        }

        // Handle tens and ones
        if ($number < 20) {
            $result .= $ones[$number];
        } else {
            $tensVal = floor($number / 10);
            $onesVal = $number % 10;

            $result .= $tens[$tensVal];
            if ($onesVal > 0) {
                $result .= " " . $ones[$onesVal];
            }
        }

        return $result;
    }

    public function generateBankDepositSlipOrders($data)
    {
        $order = $data['order'];
        $bank = $data['bank'];
        $user = $data['user'];
        $logoPath = URLROOT . '/public/assets/simpex-logo.png';

        $html = '
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                color: #333;
                line-height: 1.5;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
                box-sizing: border-box;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
                padding-bottom: 10px;
                border-bottom: 2px solid #4caf50;
            }
            .logo {
                max-width: 200px;
                margin-bottom: 10px;
            }
            h1 {
                color: #4caf50;
                font-size: 24px;
                margin: 0 0 10px;
            }
            .slip-title {
                font-size: 18px;
                margin-bottom: 5px;
                color: #666;
            }
            .section {
                margin-bottom: 30px;
            }
            .section-title {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 10px;
                color: #4caf50;
                border-bottom: 1px solid #eee;
                padding-bottom: 5px;
            }
            .row {
                display: block;
                margin-bottom: 8px;
            }
            .label {
                font-weight: bold;
                color: #666;
                display: inline-block;
                width: 150px;
            }
            .value {
                display: inline-block;
            }
            .bank-info {
                padding: 15px;
                background: #f9f9f9;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 20px;
            }
            .amount {
                font-size: 20px;
                font-weight: bold;
                color: #4caf50;
                margin: 15px 0;
            }
            .customer-field {
                padding: 10px;
                border: 1px dashed #999;
                margin: 15px 0;
                background: #f9f9f9;
            }
            .customer-field p {
                margin: 0 0 10px;
                color: #666;
                font-style: italic;
            }
            .footer {
                border-top: 1px solid #eee;
                padding-top: 10px;
                margin-top: 30px;
                font-size: 12px;
                color: #999;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="' . $logoPath . '" alt="SimplEx Solar Logo" class="logo">
                <h1>SimplEx Solar Solutions</h1>
                <div class="slip-title">Bank Deposit Slip</div>
            </div>
            
            <div class="section">
                <div class="section-title">Order Information</div>
                <div class="row">
                    <span class="label">Order Number:</span>
                    <span class="value">' . $order->order_number . '</span>
                </div>
                <div class="row">
                    <span class="label">Date:</span>
                    <span class="value">' . date('F j, Y') . '</span>
                </div>
                <div class="row">
                    <span class="label">Customer Name:</span>
                    <span class="value">' . htmlspecialchars($user->name) . '</span>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Bank Account Details</div>
                <div class="bank-info">
                    <div class="row">
                        <span class="label">Bank:</span>
                        <span class="value">' . htmlspecialchars($bank['bank_name']) . '</span>
                    </div>
                    <div class="row">
                        <span class="label">Account Name:</span>
                        <span class="value">' . htmlspecialchars($bank['account_name']) . '</span>
                    </div>
                    <div class="row">
                        <span class="label">Account Number:</span>
                        <span class="value">' . htmlspecialchars($bank['account_number']) . '</span>
                    </div>
                    <div class="row">
                        <span class="label">Branch:</span>
                        <span class="value">' . htmlspecialchars($bank['branch']) . '</span>
                    </div>
                    ' . (isset($bank['branch_code']) ? '
                    <div class="row">
                        <span class="label">Branch Code:</span>
                        <span class="value">' . htmlspecialchars($bank['branch_code']) . '</span>
                    </div>
                    ' : '') . '
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Payment Details</div>
                <div class="amount">
                    Amount to Pay: Rs. ' . number_format($order->total_amount, 2) . '
                </div>
                <div class="row">
                    <span class="label">Amount in Words:</span>
                    <span class="value">' . $this->numberToWords($order->total_amount) . ' Rupees Only</span>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Depositor Information (To be filled by customer)</div>
                <div class="customer-field">
                    <p>Depositor\'s Name: ____________________________________</p>
                    <p>Depositor\'s Phone: ___________________________________</p>
                    <p>Date of Deposit: ______________________________________</p>
                    <p>Signature: ____________________________________________</p>
                </div>
            </div>
            
            <div class="footer">
                <p>Please bring this slip when making your deposit. Upload a copy of the completed slip to confirm your payment.</p>
                <p>If you have any questions, please contact us at: support@simplexsolar.com</p>
            </div>
        </div>
    </body>
    </html>';

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        return $this->dompdf->output();
    }

    public function generateDeliveryReport($data)
    {
        $order = $data['order'];
        $orderItems = $data['orderItems'];
        $deliveryPerson = $data['deliveryPerson'];
        $logoPath = URLROOT . '/public/assets/simpex-logo.png';

        $html = '
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                color: #333;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .header img {
                max-width: 200px;
                margin-bottom: 10px;
            }
            .header h2 {
                margin: 5px 0;
                color: #4CAF50;
            }
            .section {
                margin-bottom: 25px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }
            .section h3 {
                margin-top: 0;
                color: #4CAF50;
                border-bottom: 1px solid #eee;
                padding-bottom: 5px;
            }
            .details-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
            .detail-item {
                margin-bottom: 8px;
            }
            .detail-item label {
                font-weight: bold;
                display: inline-block;
                width: 150px;
            }
            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            .items-table th, .items-table td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            .items-table th {
                background-color: #f5f5f5;
            }
            .items-table tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .total-row td {
                font-weight: bold;
            }
            .signature-section {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-top: 50px;
            }
            .signature-box {
                margin-top: 10px;
                border-top: 1px solid #333;
                padding-top: 5px;
                text-align: center;
                font-weight: bold;
            }
            .qr-section {
                text-align: center;
                margin-top: 30px;
            }
            .qr-code {
                width: 100px;
                height: 100px;
                background-color: #f5f5f5;
                margin: 0 auto;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <img src="' . $logoPath . '" alt="Simpex Solar Logo">
            <h2>Delivery Report</h2>
            <p>Order #' . $order->order_number . '</p>
        </div>
        
        <div class="section">
            <h3>Customer Information</h3>
            <div class="details-grid">
                <div class="detail-item">
                    <label>Customer Name:</label>
                    <span>' . htmlspecialchars($order->customer_name) . '</span>
                </div>
                <div class="detail-item">
                    <label>Contact Phone:</label>
                    <span>' . htmlspecialchars($order->contact_phone) . '</span>
                </div>
            </div>
        </div>
        
        <div class="section">
            <h3>Shipping Details</h3>
            <div class="detail-item">
                <label>Shipping Address:</label>
                <span>' . nl2br(htmlspecialchars($order->shipping_address)) . '</span>
            </div>
            <div class="detail-item">
                <label>Delivery Date:</label>
                <span>' . date('F j, Y') . '</span>
            </div>
            <div class="detail-item">
                <label>Delivery Person:</label>
                <span>' . htmlspecialchars($deliveryPerson) . '</span>
            </div>
        </div>
        
        <div class="section">
            <h3>Order Items</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>';

        $subtotal = 0;
        foreach ($orderItems as $item) {
            $itemTotal = $item->price_at_time * $item->quantity;
            $subtotal += $itemTotal;

            $html .= '
                    <tr>
                        <td>' . htmlspecialchars($item->name) . '</td>
                        <td>' . $item->quantity . '</td>
                        <td>Rs. ' . number_format($item->price_at_time, 2) . '</td>
                        <td>Rs. ' . number_format($itemTotal, 2) . '</td>
                    </tr>';
        }

        $html .= '
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">Total:</td>
                        <td>Rs. ' . number_format($order->total_amount, 2) . '</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="section">
            <h3>Payment Information</h3>
            <div class="detail-item">
                <label>Payment Method:</label>
                <span>' . ucfirst(str_replace('_', ' ', $order->payment_method)) . '</span>
            </div>';

        if ($order->payment_method == 'cash') {
            $html .= '
            <div class="detail-item">
                <label>Cash Amount:</label>
                <span>Rs. ' . number_format($order->total_amount, 2) . '</span>
            </div>
            <div class="detail-item" style="margin-top: 15px;">
                <input type="checkbox" name="payment_received" style="width: 15px; height: 15px;"> 
                <strong>I confirm that I have received the payment in full.</strong>
            </div>';
        }

        $html .= '
        </div>
        
        <div class="signature-section">
            <div>
                <p>Delivered By:</p>
                <div class="signature-box">
                    ' . htmlspecialchars($deliveryPerson) . '
                </div>
            </div>
            <div>
                <p>Received By:</p>
                <div class="signature-box">
                    Customer Signature
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Thank you for shopping with Simpex Solar!</p>
            <p>For any questions or concerns regarding your delivery, please contact our customer service at support@simpexsolar.com</p>
        </div>
    </body>
    </html>';

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();
        return $this->dompdf->output();
    }
}
