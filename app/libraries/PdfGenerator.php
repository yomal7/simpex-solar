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



    public function generateQuotationPDF($quotation, $equipment)
    {

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
        foreach ($equipment as $item) {
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
}
