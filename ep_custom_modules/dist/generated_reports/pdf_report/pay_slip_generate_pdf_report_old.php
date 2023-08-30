<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
session_start();
require_once('../../tcpdf/tcpdf.php');
require_once('../../handler/query.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Pay Slip');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
// $pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.

$abalc = TCPDF_FONTS::addTTFfont('../../fonts/abadi_mt_condensed_light/ABALC.ttf', 'TrueTypeUnicode', '', 96);
$calibri = TCPDF_FONTS::addTTFfont('../../fonts/calibri/Calibri.ttf', 'TrueTypeUnicode', '', 96);
$calibribold = TCPDF_FONTS::addTTFfont('../../fonts/calibri/CALIBRIB.TTF', 'TrueTypeUnicode', '', 96);


$pdf->SetFont($calibri, '', 14, '', true);
// $pdf->SetFont($fontname, '', 12, '', true);
// $pdf->SetFont('dejavusans', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage('L', 'A3');


//custom
//$pdf->addFormat("custom", 3, 2);
// $width = 175;  
// $height = 266; 
// $orientation = ($height>$width) ? 'P' : 'L';  
// $pdf->addFormat("custom", $width, $height);  
// $pdf->reFormat("custom", $orientation); 
//-custom


// set text shadow effect
// $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));





$defaultval = "0.00";
$defaultchar = "";

$employee_number = isset($_POST['employee_number'])?$_POST['employee_number']:$defaultchar;
$employee_name = isset($_POST['employee_name'])?$_POST['employee_name']:$defaultchar;
$department = isset($_POST['department'])?$_POST['department']:$defaultchar;
$pay_period = isset($_POST['description'])?$_POST['description']:$defaultchar;

$spn_regular_pay = isset($_POST['spn_regular_pay'])?$_POST['spn_regular_pay']:$defaultval;
$spn_less_absences_undertime = isset($_POST['spn_less_absences_undertime'])?$_POST['spn_less_absences_undertime']:$defaultval;
$spn_gross_pay = isset($_POST['spn_gross_pay'])?$_POST['spn_gross_pay']:$defaultval;
$spn_philhealth_premiums = isset($_POST['spn_philhealth_premiums'])?$_POST['spn_philhealth_premiums']:$defaultval;
$spn_with_tax = isset($_POST['spn_with_tax'])?$_POST['spn_with_tax']:$defaultval;
$spn_sss_premiums = isset($_POST['spn_sss_premiums'])?$_POST['spn_sss_premiums']:$defaultval;
$spn_net_pay = isset($_POST['spn_net_pay'])?$_POST['spn_net_pay']:$defaultval;
$spn_absences = isset($_POST['spn_absences'])?$_POST['spn_absences']:$defaultval;
$spn_undertime = isset($_POST['spn_undertime'])?$_POST['spn_undertime']:$defaultval;
$spn_ecola = isset($_POST['spn_ecola'])?$_POST['spn_ecola']:$defaultval;
$spn_overtime_night_diff = isset($_POST['spn_overtime_night_diff'])?$_POST['spn_overtime_night_diff']:$defaultval;
$spn_sl_vl_bl_sd_sdl = isset($_POST['spn_sl_vl_bl_sd_sdl'])?$_POST['spn_sl_vl_bl_sd_sdl']:$defaultval;
$spn_ctpa_prod_allow = isset($_POST['spn_ctpa_prod_allow'])?$_POST['spn_ctpa_prod_allow']:$defaultval;
$spn_socio_eco_allow = isset($_POST['spn_socio_eco_allow'])?$_POST['spn_socio_eco_allow']:$defaultval;
$spn_salary_adjustments = isset($_POST['spn_salary_adjustments'])?$_POST['spn_salary_adjustments']:$defaultval;
$spn_hipar = isset($_POST['spn_hipar'])?$_POST['spn_hipar']:$defaultval;
$spn_regular_ot = isset($_POST['spn_regular_ot'])?$_POST['spn_regular_ot']:$defaultval;
$spn_sunday_ot = isset($_POST['spn_sunday_ot'])?$_POST['spn_sunday_ot']:$defaultval;
$spn_sss_loan = isset($_POST['spn_sss_loan'])?$_POST['spn_sss_loan']:$defaultval;
$spn_sick_leave = isset($_POST['spn_sick_leave'])?$_POST['spn_sick_leave']:$defaultval;
$spn_vacation_leave = isset($_POST['spn_vacation_leave'])?$_POST['spn_vacation_leave']:$defaultval;
$spn_night_diff = isset($_POST['spn_night_diff'])?$_POST['spn_night_diff']:$defaultval;
$spn_sunday_ot_excess_of_8_hrs = isset($_POST['spn_sunday_ot_excess_of_8_hrs'])?$_POST['spn_sunday_ot_excess_of_8_hrs']:$defaultval;
$spn_advances_other_deductions = isset($_POST['spn_advances_other_deductions'])?$_POST['spn_advances_other_deductions']:$defaultval;
$spn_other_earnings = isset($_POST['spn_other_earnings'])?$_POST['spn_other_earnings']:$defaultval;
$spn_bonus_rice_subsidy = isset($_POST['spn_bonus_rice_subsidy'])?$_POST['spn_bonus_rice_subsidy']:$defaultval;
$spn_13th_month_pay = isset($_POST['spn_13th_month_pay'])?$_POST['spn_13th_month_pay']:$defaultval;
$spn_allowance = isset($_POST['spn_allowance'])?$_POST['spn_allowance']:$defaultval;

$spn_exemption_code = isset($_POST['spn_exemption_code'])?$_POST['spn_exemption_code']:$defaultchar;

$spn_pagibig_premiums                = isset($_POST['spn_pagibig_premiums'])?$_POST['spn_pagibig_premiums']:$defaultchar;
$spn_pagibig_loan                    = isset($_POST['spn_pagibig_loan'])?$_POST['spn_pagibig_loan']:$defaultchar;
$spn_reg_holiday                     = isset($_POST['spn_reg_holiday'])?$_POST['spn_reg_holiday']:$defaultchar;
$spn_reg_holiday_excess_of_8_hrs     = isset($_POST['spn_reg_holiday_excess_of_8_hrs'])?$_POST['spn_reg_holiday_excess_of_8_hrs']:$defaultchar;
$spn_reg_hol_sun_ot                  = isset($_POST['spn_reg_hol_sun_ot'])?$_POST['spn_reg_hol_sun_ot']:$defaultchar;
$spn_reg_hol_sun_ot_excess_of_8_hrs  = isset($_POST['spn_reg_hol_sun_ot_excess_of_8_hrs'])?$_POST['spn_reg_hol_sun_ot_excess_of_8_hrs']:$defaultchar;
$spn_special_holiday_excess_of_8_hrs = isset($_POST['spn_special_holiday_excess_of_8_hrs'])?$_POST['spn_special_holiday_excess_of_8_hrs']:$defaultchar;
$spn_spl_hol_sun_ot                  = isset($_POST['spn_spl_hol_sun_ot'])?$_POST['spn_spl_hol_sun_ot']:$defaultchar;
$spn_spl_hol_sun_ot_excess_of_8_hrs  = isset($_POST['spn_spl_hol_sun_ot_excess_of_8_hrs'])?$_POST['spn_spl_hol_sun_ot_excess_of_8_hrs']:$defaultchar;
$spn_shutdown_leave                  = isset($_POST['spn_shutdown_leave'])?$_POST['spn_shutdown_leave']:$defaultchar;
$spn_special_holiday                 = isset($_POST['spn_special_holiday'])?$_POST['spn_special_holiday']:$defaultchar;
$spn_bdl_pl_ml_bl                    = isset($_POST['spn_bdl_pl_ml_bl'])?$_POST['spn_bdl_pl_ml_bl']:$defaultchar;
$spn_sss_loan                        = isset($_POST['spn_sss_loan'])?$_POST['spn_sss_loan']:$defaultchar;
$spn_tax_adjustments                 = isset($_POST['spn_tax_adjustments'])?$_POST['spn_tax_adjustments']:$defaultchar;
$spn_ytd_gross_income                = isset($_POST['spn_ytd_gross_income'])?$_POST['spn_ytd_gross_income']:$defaultchar;
$spn_ytd_tax_witheld                 = isset($_POST['spn_ytd_tax_witheld'])?$_POST['spn_ytd_tax_witheld']:$defaultchar;
$spn_forecast_earnings               = isset($_POST['spn_forecast_earnings'])?$_POST['spn_forecast_earnings']:$defaultchar;
$spn_less_ytd_tax_exemption          = isset($_POST['spn_less_ytd_tax_exemption'])?$_POST['spn_less_ytd_tax_exemption']:$defaultchar;
$spn_ytd_taxable_earnings_forecast   = isset($_POST['spn_ytd_taxable_earnings_forecast'])?$_POST['spn_ytd_taxable_earnings_forecast']:$defaultchar;

                                        
$file_type = isset($_POST['file_type'])?$_POST['file_type']:$defaultval;
$ymdhis = isset($_POST['ymdhis'])?$_POST['ymdhis']:$defaultval;






// Set some content to print
$html = <<<EOD


    <div style="border:1px solid black;">
        <div>
            <table style="padding:10px 0 0 30px;">
                <tr>
                    <td colspan="4"><h1 style="font-size:42px;">PRICON MICROELECTRONICS, INC.</h1></td>
                </tr>
            </table>
            <table style="padding:9px 0 0 35px;">            
                <tr>
                    <td width="15%">EMPLOYEE NUMBER:</td>
                    <td width="35%">$employee_number</td>
                    <td width="10%">DEPARTMENT:</td>
                    <td width="40%">$department</td>
                </tr>
                <tr>
                    <td>EMPLOYEE NAME:</td>
                    <td>$employee_name</td>
                    <td>PAY PERIOD:</td>
                    <td>$pay_period</td>
                </tr>
            </table>
        </div>
        <div style="border-top:1px solid black;">
            <table>
                <tr>
                    <td style="border-right:1px solid black;">
                        <div>
                            <table style="padding:2px;">
                                <tr>
                                    <td width="2%"></td>
                                    <td width="78%" colspan="2"><b>REGULAR PAY:</b>
                                    </td>
                                    <td width="17%" style="text-align:right;font-weight:bold;">$spn_regular_pay
                                    </td>
                                    <td width="3%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td width="10%">PLUS:
                                    </td>
                                    <td width="68%">OVERTIME/NIGHT DIFF.
                                    </td>
                                    <td style="text-align:right;">$spn_overtime_night_diff
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>SL/VL/BL/SD/BDL:
                                    </td>
                                    <td style="text-align:right;">$spn_sl_vl_bl_sd_sdl
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>ECOLA
                                    </td>
                                    <td style="text-align:right;">$spn_ecola
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>CTPA/PROD. ALLOW.
                                    </td>
                                    <td style="text-align:right;">$spn_ctpa_prod_allow
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>SOCIO ECO. ALLOW.
                                    </td>
                                    <td style="text-align:right;">$spn_socio_eco_allow
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>SALARY ADJUSTMENTS
                                    </td>
                                    <td style="text-align:right;">$spn_salary_adjustments
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>BONUS/RICE SUBSIDY
                                    </td>
                                    <td style="text-align:right;">$spn_bonus_rice_subsidy
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>3th MONTH PAY
                                    </td>
                                    <td style="text-align:right;">$spn_13th_month_pay
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>ALLOWANCE
                                    </td>
                                    <td style="text-align:right;">$spn_allowance
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>OTHER EARNINGS
                                    </td>
                                    <td style="text-align:right;">$spn_other_earnings
                                    </td>
                                    <td></td>
                                </tr>
                                
                                <tr>
                                    <td></td>
                                    <td>LESS:
                                    </td>
                                    <td>ABSENCES/UNDERTIME:
                                    </td>
                                    <td style="text-align:right;">$spn_less_absences_undertime
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2">GROSS PAY
                                    </td>
                                    <td style="text-align:right;font-weight:bold;">$spn_gross_pay
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>LESS:
                                    </td>
                                    <td>SSS PREMIUMS
                                    </td>
                                    <td style="text-align:right;">$spn_sss_premiums
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>PHIL HEALTH PREMIUMS
                                    </td>
                                    <td style="text-align:right;">$spn_philhealth_premiums
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>PAG-IBIG PREMIUMS
                                    </td>
                                    <td style="text-align:right;">$spn_pagibig_premiums
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>WITH TAX
                                    </td>
                                    <td style="text-align:right;">$spn_with_tax
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>SSS LOAN
                                    </td>
                                    <td style="text-align:right;">$spn_sss_loan
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>PAG-IBIG LOAN
                                    </td>
                                    <td style="text-align:right;">$spn_pagibig_loan
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>HIPAR
                                    </td>
                                    <td style="text-align:right;">$spn_hipar
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>PAR
                                    </td>
                                    <td style="text-align:right;">0.00
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>SSS
                                    </td>
                                    <td style="text-align:right;">0.00
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>SSS CONDONATION
                                    </td>
                                    <td style="text-align:right;">0.00
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>ADVANCES/OTHER DEDUCTIONS
                                    </td>
                                    <td style="text-align:right;">$spn_advances_other_deductions
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>TAX ADJUSTMENT
                                    </td>
                                    <td style="text-align:right;">$spn_tax_adjustments
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="2">NET PAY:
                                    </td>
                                    <td style="text-align:right;font-weight:bold;">$spn_net_pay
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                    <td>
                        <div>
                            <table style="padding:2px;">
                                <tr>
                                    <td width="2%"></td>
                                    <td width="33%"><b>LEAVES:</b>
                                    </td>
                                    <td width="13%">
                                    </td>
                                    <td width="2%"></td>
                                    <td width="35%"><b>DAYS ABSENT/UNDERTIME</b>
                                    </td>
                                    <td width="13%">
                                    </td>
                                    <td width="2%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>SICK LEAVE
                                    </td>
                                    <td style="text-align:right;">$spn_sick_leave
                                    </td>
                                    <td></td>
                                    <td>ABSENCES:
                                    </td>
                                    <td style="text-align:right;">$spn_absences
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>VACATION LEAVE
                                    </td>
                                    <td style="text-align:right;">$spn_vacation_leave
                                    </td>
                                    <td></td>
                                    <td>UNDERTIME:
                                    </td>
                                    <td style="text-align:right;">$spn_undertime
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>BDL/PL/ML/BL
                                    </td>
                                    <td style="text-align:right;">$spn_bdl_pl_ml_bl
                                    </td>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>SHUTDOWN LEAVE:
                                    </td>
                                    <td style="text-align:right;">$spn_shutdown_leave
                                    </td>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><br><br><b>OVERTIME HOURS</b>
                                    </td>
                                    <td>
                                    </td>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>REGULAR OT
                                    </td>
                                    <td style="text-align:right;">$spn_regular_ot
                                    </td>
                                    <td></td>
                                    <td>NIGHT DIFF
                                    </td>
                                    <td style="text-align:right;">$spn_night_diff
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>SUNDAY OT
                                    </td>
                                    <td style="text-align:right;">$spn_sunday_ot
                                    </td>
                                    <td></td>
                                    <td>EXCESS OF 8 HRS
                                    </td>
                                    <td style="text-align:right;">$spn_sunday_ot_excess_of_8_hrs
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>REG. HOLIDAY
                                    </td>
                                    <td style="text-align:right;">$spn_reg_holiday
                                    </td>
                                    <td></td>
                                    <td>EXCESS OF 8 HRS
                                    </td>
                                    <td style="text-align:right;">$spn_reg_holiday_excess_of_8_hrs
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>REG HOL/SUN OT
                                    </td>
                                    <td style="text-align:right;">$spn_reg_hol_sun_ot
                                    </td>
                                    <td></td>
                                    <td>EXCESS OF 8 HRS
                                    </td>
                                    <td style="text-align:right;">$spn_reg_hol_sun_ot_excess_of_8_hrs
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>SPECIAL HOLIDAY
                                    </td>
                                    <td style="text-align:right;">$spn_special_holiday
                                    </td>
                                    <td></td>
                                    <td>EXCESS OF 8 HRS
                                    </td>
                                    <td style="text-align:right;">$spn_special_holiday_excess_of_8_hrs
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>SPL HOL/SUN OT
                                    </td>
                                    <td style="text-align:right;">$spn_spl_hol_sun_ot
                                    </td>
                                    <td></td>
                                    <td>EXCESS OF 8 HRS
                                    </td>
                                    <td style="text-align:right;">$spn_spl_hol_sun_ot_excess_of_8_hrs
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                        <hr>
                        <div>
                            <table style="padding:2px;">
                                <tr>
                                    <td width="2%"></td>
                                    <td width="58%">EXCEMPTION CODE:
                                    </td>
                                    <td width="38%" style="text-align:right;">$spn_exemption_code
                                    </td>
                                    <td width="2%"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>YTG GROSS INCOME:
                                    </td>
                                    <td style="text-align:right;">$spn_ytd_gross_income
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>TAXABLE BENEFITS:
                                    </td>
                                    <td style="text-align:right;">0.00
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>FORECAST EARNINGS:
                                    </td>
                                    <td style="text-align:right;">$spn_forecast_earnings
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>LESS YTD TAX EXEMPTION:
                                    </td>
                                    <td style="text-align:right;">$spn_less_ytd_tax_exemption
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>YTD TAXABLE EARNINGS W/ FORECAST:
                                    </td>
                                    <td style="text-align:right;">$spn_ytd_taxable_earnings_forecast
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>YTD TAX WITHHELD:
                                    </td>
                                    <td style="text-align:right;">$spn_ytd_tax_witheld
                                    </td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>


EOD;





// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
// $file_name = date('Ymd');

switch ($file_type) {
    case 'F':
        write_logs::getInstance()->save_logs('5','Pay Slip Email '.$employee_number.' '.$employee_name.' '.$pay_period);
        $pdf->Output('/var/www/e-portal/ep_custom_modules/dist/generated_reports/pdf_report/'.$employee_number.$ymdhis.'.pdf', 'F');
        break;    
    case 'D':
        write_logs::getInstance()->save_logs('4','Pay Slip PDF '.$employee_number.' '.$employee_name.' '.$pay_period);
        $pdf->Output($employee_number.$ymdhis.'.pdf', 'D');
        break;
    default:
        break;
}

//============================================================+
// END OF FILE
//============================================================+
