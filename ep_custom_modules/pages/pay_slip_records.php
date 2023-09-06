<html>
<head>
	<?php
  		require_once('../dist/includes.php');
  		
  		/* 04-25-2023 Administrative */
		// require_once('../dist/handler/query.php');
		// $user_ip = $_SERVER['REMOTE_ADDR'];
		// $valid_ip = db_ep::getInstance()->get_valid_ip($user_ip);
		// if(!$valid_ip){
		// 	// echo "<br><br><br><br><br><br><br><br><center><h1>PLEASE 🤸 WAIT 🤸 BESHY 🤸</h1></center> ";
		// 	echo "<br><br><br><br><br><br><br><br><center><h1>Maintenance Mode 🤸🤸🤸</h1></center> ";
		// 	exit;
		// }
		
	?>
</head>
<body style="overflow:auto;">
	<div class="card" style="margin-right:1px;">
	  <div class="card-header" style="height:55px;">
		<div class="row form-inline" id="div_data_filter">
			<?php
				if($_SESSION['s_username']=='K010' || $_SESSION['s_username']=='Q108' || $_SESSION['s_username']=='P121' ){
					echo '<div class="col-auto">';
					echo '	<div class="form-group mx-sm-1 mb-2">';
					echo '		<label for="slt_employee_number">Staff:&nbsp;</label>';
					echo '	    <input class="form-control" id="slt_employee_number" placeholder="Employee Number" style="border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;">'; 
					echo '		<button type="button" class="btn btn-secondary" style="border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;">Go</button>'; 
					echo '	</div>';
					echo '</div>';
				}
			?>
			<div class="col-auto">
				<div class="form-group mx-sm-1 mb-2">
	        		<label for="slt_data_end_data">Pay Period:&nbsp;</label>
				    <select class="form-control" id="slt_pay_period" style="border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;min-width:100px;">
				    </select>
					<button type="button" id="btn_send_to_my_email" class="btn btn-secondary" style="border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;"><i class="fa fa-paper-plane"></i> Email</button>
					<button type="button" id="btn_save_as_pdf" class="btn btn-secondary" style="border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;"><i class="fa fa-download"></i> Download</button>	
					<div id="cont_loading">
						&nbsp;<i class="fa fa-spinner fa-spin" style="color:green;"></i>
					</div>
				</div>
			</div>
		</div>
	  </div>
	  <div class="card-body">
			<div class="row">
				    <?php
						if($_SESSION['s_username']=='K010'){
							echo '<div id="cont_pay_slip_no_data" class="col-xs-12 mx-3" style="display:none;width:100%;">';
							echo '	<div class="alert alert-info alert-dismissable fade show" role="alert">';
							echo '	  Data not found.&nbsp;';
							echo '	</div>';
							echo '</div>';					
						}
						else{
							echo '<div id="cont_pay_slip_no_data" class="col-xs-12 mx-3" style="display:none;width:100%;">';
							echo '	<div class="alert alert-info alert-dismissable fade show" role="alert">';
							echo '	  Data not found.&nbsp;';
							echo '	</div>';
							echo '</div>';						
						}
					?>			
			</div>
			<div class="row">
				<div id="cont_pay_slip_data" style="height:auto;display:none;margin:auto;margin-top:0px;">
				    <table style="display:inline-block;">
				        <tbody style="font-size:70%;">
				            <tr style="font-size:107%;">
				                <td style="text-align:left;padding-right:5px;padding-left:5px;width:120px;font-weight:bold;;">REGULAR PAY:</td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;width150px;"></td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;font-weight:bold;"><span id="spn_regular_pay"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;width:155px;font-weight:bold;">LEAVES</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;width:210px;font-weight:bold;">DAYS ABSENT/UNDERTIME</td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">PLUS:</td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">OVERTIME/NIGHT DIFF.</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_overtime_night_diff"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">SICK LEAVE</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;width:85px;"><span id="spn_sick_leave"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">ABSENCES</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_absences"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">SL/VL/BL/SD/SDL</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_sl_vl_bl_sd_sdl"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">VACATION LEAVE</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_vacation_leave"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">UNDERTIME</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_undertime"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;width:150pxpx;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">ECOLA</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_ecola"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">BDL/PL/ML/BL</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_bdl_pl_ml_bl"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">CTPA/PROD. ALLOW.</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_ctpa_prod_allow"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">SHUTDOWN LEAVE</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_shutdown_leave"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">SOCIO ECO. ALLOW.</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_socio_eco_allow"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;font-weight:bold;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">SALARY ADJUSTMENTS</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_salary_adjustments"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;font-weight:bold;">OVERTIME HOURS</td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">BONUS/RICE SUBSIDY</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_bonus_rice_subsidy"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">REGULAR OT</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_regular_ot"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">NIGHT DIFF</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_night_diff"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">13th MONTH PAY</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_13th_month_pay"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">SUNDAY OT</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_sunday_ot"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">EXCESS OF 8 HRS</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_sunday_ot_excess_of_8_hrs"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">ALLOWANCE</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_allowance"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">REG. HOLIDAY</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_reg_holiday"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">EXCESS OF 8 HRS</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_reg_holiday_excess_of_8_hrs"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">OTHER EARNINGS</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_other_earnings"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">REG. HOL./SUN. OT</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_reg_hol_sun_ot"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">EXCESS OF 8 HRS</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_reg_hol_sun_ot_excess_of_8_hrs"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">LESS:</td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">ABSENCES</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_less_absences_undertime"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">SPECIAL HOLIDAY</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_special_holiday"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;">EXCESS OF 8 HRS</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;"><span id="spn_special_holiday_excess_of_8_hrs"></span></td>
				            </tr>
				            <tr style="font-size:107%;">
				                <td style="text-align:left;padding-right:5px;padding-left:5px;font-weight:bold;">GROSS PAY:</td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;width:150px;font-weight:bold;"><span id="spn_gross_pay"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;border-bottom:1px solid black;" valign="top" rowspan="2">SPL. HOL./SUN. OT</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;border-bottom:1px solid black;" valign="top" rowspan="2"><span id="spn_spl_hol_sun_ot"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;border-bottom:1px solid black;" valign="top" rowspan="2">EXCESS OF 8 HRS</td>
				                <td style="text-align:right;padding-right:5px;padding-left:5px;border-bottom:1px solid black;" valign="top" rowspan="2"><span id="spn_spl_hol_sun_ot_excess_of_8_hrs"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">LESS:</td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">SSS PREMIUMS</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_sss_premiums"></span></td>
				                
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">PHIL. HEALTH PREMIUMS</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_philhealth_premiums"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;width:155px;" colspan="2" rowspan="2" valign="bottom">EXEMPTION CODE</td>
				                <td style="text-align:right;padding-right:59px;padding-left:5px;font-weight:bold;text-align:right;" colspan="2" rowspan="2" valign="bottom"><span id="spn_exemption_code"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;width:150pxpx;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">PAG-IBIG PREMIUMS</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_pagibig_premiums"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">WITH TAX</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_with_tax"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;width:155px;" colspan="2">YTD GROSS INCOME:</td>
				                <td style="text-align:right;padding-right:59px;padding-left:5px;font-weight:bold;text-align:right;" colspan="2"><span id="spn_ytd_gross_income"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">SSS LOAN</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_sss_loan"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;width:155px;" colspan="2">TAXABLE BENEFITS:</td>
				                <td style="text-align:right;padding-right:59px;padding-left:5px;font-weight:bold;" colspan="2"><span id="spn_taxable_benefits">0.00</span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">PAG-IBIG LOAN</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_pagibig_loan"></span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;width:155px;" colspan="2">FORECAST EARNINGS:</td>
				                <td style="text-align:right;padding-right:59px;padding-left:5px;font-weight:bold;" colspan="2"><span id="spn_forecast_earnings"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">HIPAR</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_hipar">0.00</span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;width:155px;" colspan="2">LESS YTD TAX EXEMPTION:</td>
				                <td style="text-align:right;padding-right:59px;padding-left:5px;font-weight:bold;" colspan="2"><span id="spn_less_ytd_tax_exemption">0.00</span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
<!-- 				                <td style="text-align:left;padding-right:5px;padding-left:5px;">PAR</td>
 -->				                <td style="text-align:left;padding-right:5px;padding-left:5px;">PHIL. HEALTH ADJUSTMENT</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_pemco">0.00</span></td>
				                <td style="text-align:left;padding-left:35px;width:155px;" colspan="2">YTD TAXABLE EARNINGS W/ FORECAST:</td>
				                <td style="text-align:right;padding-right:59px;padding-left:5px;font-weight:bold;" colspan="2"><span id="spn_ytd_taxable_earnings_forecast"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">BDO</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_bdo">0.00</span></td>
				                <td style="text-align:left;padding-right:5px;padding-left:35px;width:155px;" colspan="2">YTD TAX WITHELD:</td>
				                <td style="text-align:right;padding-right:59px;padding-left:5px;font-weight:bold;" colspan="2"><span id="spn_ytd_tax_witheld"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">SSS CONDONATION</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_sss_condonation">0.00</span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">ADVANCES/OTHER DEDUCTIONS</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_advances_other_deductions"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;">TAX ADJUSTMENTS</td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;"><span id="spn_tax_adjustments"></span></td>
				            </tr>
				            <tr>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;ont-weight:bold;">NET PAY:</td>
				                <td style="text-align:left;padding-right:5px;padding-left:5px;"></td>
				                <td style="text-align:right;padding-right:35px;border-right:1px solid black;padding-left:5px;width:150px;font-weight:bold;color:#00592b;"><span id="spn_net_pay" style="font-size:120%;"></span></td>
				            </tr>
				        </tbody>
				    </table>
				</div>
			</div>
	  </div>
	</div>
	<div class="modal fade" id="mdl_send_to_my_email" tabindex="1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fas fa-envelope"></i> Send to Email</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"> 
			        <form id="frm_send_to_my_email">
			          <div class="form-group">
			            <label for="recipient-name" class="col-form-label">Recipient:</label>
			            <input type="email" class="form-control" name="recipient-name" required>
			          </div>
			          <div class="form-group">
			            <label class="col-form-label">Attachment: <a href="#">my_payslip.pdf</a></label>
			          </div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_send_to_my_email" class="btn btn-primary"><i class="fas fa-share-square"></i> Send Message</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
    <form id="iframeform" target="iframe_pdf" method="post" action="../dist/generated_reports/pdf_report/pay_slip_generate_pdf_report.php">
        <input type="hidden" name="x" value="xx">
    </form>
	<div class="row" style="display:none;">
		<iframe id="iframe_pdf" name="iframe_pdf" src="" style="border:none;"></iframe>
	</div>
	<script type="text/javascript" src="../dist/js/pay_slip_records.js?19"></script>
	<script type="text/javascript">
		$('#cont_loading').fadeIn();
	    load_latest_pay_period('0','0');
	</script>
</body>
</html>