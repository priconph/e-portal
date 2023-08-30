<html>
<head>
	<?php
  		require_once('../dist/includes.php');
	?>
</head>
<body>				
	<div class="card" style="margin-right:1px;">
	  <div class="card-header">
	  	<i class="fas fa-money-check"></i> Payroll Data
		<span id="cont_loading">
			&nbsp;<i class="fa fa-spinner fa-spin" style="color:green;"></i>
		</span>
	  </div>
	  <div class="card-body">
			<div id="cont_pay_slip_records" class="row" style="width:100%;overflow-x:hidden;padding-left:20px;padding-top:0;">
				<div class="col-12">
					<table id="t_pay_slip_records" class="table table-striped table-bordered nowrap" style="width:100%;">
				        <thead>
				            <tr style="text-align:center;font-size:70%;">
								<th style="vertical-align:middle;">Description</th>
								<th style="vertical-align:middle;">Starting Date</th>
								<th style="vertical-align:middle;">Ending Date</th>
								<th style="vertical-align:middle;">Cut-off Date</th>
								<th style="vertical-align:middle;">Last Modified</th>
								<th style="vertical-align:middle;">Posted</th>
				            </tr>
				        </thead>
				        <tbody style="font-size:80%;" class="pointer">
				        </tbody>
				        <tfoot>
				            <tr style="text-align:center;font-size:70%;">
								<th style="vertical-align:middle;">Description</th>
								<th style="vertical-align:middle;">Starting Date</th>
								<th style="vertical-align:middle;">Ending Date</th>
								<th style="vertical-align:middle;">Cut-off Date</th>
								<th style="vertical-align:middle;">Last Modified</th>
								<th style="vertical-align:middle;">Posted</th>
				            </tr>
				        </tfoot>
				    </table>					
				</div>
			</div>
			<div id="cont_pay_slip_data" style="display:none;">
				<div class="row form-inline" id="div_data_filter">
					<div class="col-xs-3">
						<div class="form-group mx-sm-1 mb-2">
							<input id="slt_pay_period" type="hidden">
							<label id="lbl_description" style="padding-right:5px;"></label>
							<input list="employees" id="slt_employee_number" class="form-control" style="border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;" placeholder="Choose employee...">
						    <datalist id="employees"> 
						    </datalist>
							<button type="button" class="btn btn-secondary" style="border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;" id="btn_go_to_main_table"><i class="fa fa-table"></i> Go to Main Table</button>
							<div id="cont_loading_cpsd">
								&nbsp;<i class="fa fa-spinner fa-spin" style="color:green;"></i>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div id="cont_pay_slip_data" style="height:auto;margin-left:27px;margin-top:13px;">
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
					                <td style="text-align:left;padding-right:5px;padding-left:5px;ont-weight:bold;">GROSS PAY:</td>
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
					                <td style="text-align:left;padding-right:5px;padding-left:5px;">PAR</td>
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
	</div>
	<div class="modal fade" id="mdl_create_cec" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body" style="overflow-y:auto;">
			        <form id="frm_create_cec">
			        	<input name="rc_id" type="hidden">
			      		<div class="row">
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="cec_recipients" class="col-form-label w-100">Recipient/s <small class="float-right text-muted"><span style="font-size:140%;">;</span> - Seperator</small></label>
									<input type="text" class="form-control" name="cec_recipients" value="cvvillabroza@pricon.ph">
								</div>
							</div>      			
			      		</div>
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-5">
								<div class="form-group">
									<label for="cec_employee_number" class="col-form-label">E.N.</label>
									<div class="input-group" role="group">
										<input type="text" class="form-control" name="cec_employee_number" style="border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;" autocomplete="off" required>
										<button type="button" class="btn btn-secondary btn-go" style="border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;">Go</button>
									</div>
								</div>
			      			</div>
			      			<div class="col-sm-3">
								<div class="form-group">
									<label for="cec_position" class="col-form-label">Year</label>
									<select type="text" class="form-control" name="cec_year">
										<option value="2017">2017</option>
										<option value="2018" selected>2018</option>
									</select>
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="cec_date_hired" class="col-form-label">Date Hired</label>
									<input type="text" class="form-control" name="cec_date_hired" readonly required>
								</div>
			      			</div>
			      		</div>
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-6">
								<div class="form-group">
									<label for="cec_name" class="col-form-label">Name</label>
									<input type="text" class="form-control" name="cec_name" readonly required>
								</div>
			      			</div>
			      			<div class="col-sm-6">
								<div class="form-group">
									<label for="cec_position" class="col-form-label">Position</label>
									<input type="text" class="form-control" name="cec_position" readonly required>
								</div>
			      			</div>
			      		</div>
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="cec_sl" class="col-form-label">SL</label>
									<input type="text" class="form-control" name="cec_sl" required>
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="cec_vl" class="col-form-label">VL</label>
									<input type="text" class="form-control" name="cec_vl" required>
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="cec_bonus" class="col-form-label">Bonus</label>
									<input type="text" class="form-control" name="cec_bonus" required>
								</div>
			      			</div>
			      		</div>
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="cec_13th_month_pay" class="col-form-label">13th Month Pay</label>
									<input type="text" class="form-control" name="cec_13th_month_pay" required>
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="cec_ecola" class="col-form-label">Ecola</label>
									<input type="text" class="form-control" name="cec_ecola" required>
								</div>
			      			</div>
			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" form="frm_create_cec" class="btn btn-primary">Send as Email</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mdl_create_loan_balance" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body" style="overflow-y:auto;">
			        <form id="frm_create_loan_balance">
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-auto">
								<div class="form-group">
									<label for="cec_employee_number" class="col-form-label">E.N.</label>
									<div class="input-group" role="group">
										<input type="text" class="form-control" name="cec_employee_number" style="border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;" autocomplete="off" required>
										<button type="button" class="btn btn-secondary btn-go" style="border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;">Go</button>
									</div>
								</div>
			      			</div>
			      		</div>			      			
			        </form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="../dist/js/payroll_data.js?4"></script>
	<script type="text/javascript">
		$('#cont_loading').fadeIn();
		load_pay_slip_records();
	</script>
</body>
</html>