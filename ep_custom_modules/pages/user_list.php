<html>
<head>
	<?php
  		require_once('../dist/includes.php');

  		/* 04-25-2023 Administrative */
		require_once('../dist/handler/query.php');

		$user_ip = $_SERVER['REMOTE_ADDR'];
		$valid_ip = db_ep::getInstance()->get_valid_ip($user_ip);

		if(!$valid_ip){
			echo "WARNING YOU ARE NOT ALLOWED TO VIEW THIS PAGE!";
			exit;
		}
	?>
	<link rel="stylesheet" type="text/css" href="../dist/sticky_table/sticky-table.min.css">
	</script><script type="text/javascript" src="../dist/sticky_table/sticky-table.min.js"></script>
	<style type="text/css">
		thead tr, tfoot tr{
			font-size:70%;
			text-align: center;
			vertical-align: middle;
		}
		#t_loan_information tbody, #t_pay_slip_history tbody, #t_loan_payments tbody{
			font-size: 80%;
			cursor: pointer;
		}
		.wrapper-sticky{
			max-height: 165px !important;
			overflow-x: hidden;
		}
	</style>
</head>
<body>
	<div class="col-sm-12" style="padding:0;margin-top:0">
		<div class="card">
		  <div class="card-header">
		  	<i class="fas fa-users text-danger"></i> List of Users
		  </div>
			<div id="cont_list_of_users" class="card-body" style="margin-left:3px;width:99%;overflow-x:scroll;">
				<div class="alert alert-info py-1 px-2 m-0 mb-1"><i class="fa fa-exclamation-circle"></i> Click the account to view the Basic Pay.</div>
				<table id="t_list_of_users" class="table table-striped compact table-hover table-bordered nowrap" style="width:100%;">
			        <thead style="width:100% !important;">
			            <tr style="text-align:center;">
			                <th>E. N.</th>
			                <th>Name</th>
			                <th>Department</th>
			                <th>Basic Pay</th>
			                <th>RCBC #</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tbody style="font-size:80%;cursor:pointer;width:100%">
			        </tbody>
			    </table>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mdl_show_employee_information" tabindex="1" role="dialog" aria-hidden="true" style="z-index:9999;">
		<div class="modal-dialog modal-w-100 modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-id-card"></i> Employment Information <small id="cont_loading_emp_info" class="text-success">Loading <i class="fa fa-spinner fa-spin"></i></small></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding-bottom:0;"> 
			        <form id="frm_show_employee_information">
			      		<div class="row">
			      			<div class="col-sm-2">
			      				<img id="a_employee_picture" class="img-thumbnail" onerror="certain_image_error('a_employee_picture')" alt="Employee's Picture" style="width:100px;height:100px;display: block;margin-left: auto;margin-right: auto;">
			      			</div>
			      			<div class="col-sm-2">
								<div class="form-group">
									<label for="employee_number" class="col-form-label">E.N.</label>
									<div class="input-group" role="group">
										<input type="text" class="form-control" id="employee_number" style="border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;" autocomplete="off" required>
										<button type="button" class="btn btn-secondary btn-go" style="border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;">Go</button>
									</div>
								</div>
			      			</div>
			      			<div class="col-sm-3">
								<div class="form-group">
									<label for="employee_name" class="col-form-label">Name</label>
									<input type="text" class="form-control pointer" id="employee_name" readonly="true">
								</div>
			      			</div>
			      			<div class="col-sm-3">
								<div class="form-group">
									<label for="employee_section_divistion_department" class="col-form-label">Section/Division/Department</label>
									<input type="text" class="form-control" id="employee_section_division_department" readonly="true">
								</div>
							</div>
			      			<div class="col-sm-2">
								<div class="form-group">
									<label for="employee_basic_pay" class="col-form-label">Position</label>
									<input type="text" class="form-control" id="employee_position" readonly="true">
								</div>
			      			</div>
			      		</div>
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
							    <div class="nav nav-tabs justify-content-end">
							    	<!-- Hide tempo. -->
							    	<!-- <a class="nav-item nav-link" data-toggle="tab" aria-selected="true" id="li_pay_slip_history" data-toggle="tab" href="#cont_pay_slip_history" role="tab" aria-controls="cont_financial_information">Pay Slip History</a> -->
							        <a class="nav-item nav-link" data-toggle="tab" aria-selected="false" id="li_loan_information" data-toggle="tab" href="#cont_loan_information" role="tab" aria-controls="cont_loan_information">Loan Information</a>
							        <a class="nav-item nav-link active" data-toggle="tab" aria-selected="true" id="li_financial_information" data-toggle="tab" href="#cont_financial_information" role="tab" aria-controls="cont_financial_information">Financial Information</a>
							    </div>
			      			</div>
			      		</div>
			      		<div class="tab-content" id="nav-tabContent">
							<div class="tab-pane fade show active" id="cont_financial_information" role="tabpanel" style="padding-top:10px;">
								<div class="row">
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_basic_pay" class="col-form-label">Basic Pay</label>
											<input type="text" class="form-control" id="employee_basic_pay" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_rcbc_number" class="col-form-label">RCBC #</label>
											<input type="text" class="form-control" id="employee_rcbc_number" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_ssn" class="col-form-label">SSS #</label>
											<input type="text" class="form-control" id="employee_ssn" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_philhealth" class="col-form-label">Philhealth #</label>
											<input type="text" class="form-control" id="employee_philhealth" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_tin" class="col-form-label">TIN #</label>
											<input type="text" class="form-control" id="employee_tin" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_pagibig" class="col-form-label">Pagibig #</label>
											<input type="text" class="form-control" id="employee_pagibig" readonly="true">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_pay_mode" class="col-form-label">Pay Mode</label>
											<input type="text" class="form-control" id="employee_pay_mode" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_pay_type" class="col-form-label">Pay Type</label>
											<input type="text" class="form-control" id="employee_pay_type" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_exemption_code" class="col-form-label">Exemption Code</label>
											<input type="text" class="form-control" id="employee_exemption_code" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_allowance" class="col-form-label">Allowance</label>
											<input type="text" class="form-control" id="employee_allowance" readonly="true">
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="employee_exemption_code" class="col-form-label">Ecola</label>
											<input type="text" class="form-control" id="employee_ecola" readonly="true">
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="cont_loan_information" role="tabpanel">
					      		<div class="row">
					      			<div class="col-sm-12" style="overflow-y:auto;margin-top:10px;height:166.5px !important;">
						      			<table id="t_loan_information" style="height:160px !important;" class="table table-striped table-sticky table-hover" data-sticky-top="tr:first-child" data-sticky-left="tr td:first-child" data-sticky-bottom="tr:last-child" data-sticky-right="tr td:last-child">
						      				<thead>
						      					<tr>
						      						<th>Start Date</th>
						      						<th>Description</th>
						      						<th>Principal Amnt.</th>
						      						<th>Current Bal.</th>
						      						<th>Interest Rate</th>
						      						<th>Months to Pay</th>
						      						<th>Frequency</th>
						      						<th>Amortization</th>
						      					</tr>
						      				</thead>
						      				<tbody>
						      				</tbody>
						      			</table>
					      			</div>
					      		</div>
							</div>
							<div class="tab-pane fade" id="cont_pay_slip_history" role="tabpanel">
					      		<div class="row">
					      			<div class="col-sm-12" style="overflow-y:auto;margin-top:10px;height:166.5px !important;">
						      			<table id="t_pay_slip_history" style="height:160px !important;" class="table table-striped table-sticky" data-sticky-top="tr:first-child" data-sticky-left="tr td:first-child" data-sticky-bottom="tr:last-child" data-sticky-right="tr td:last-child">
						      				<thead>
						      					<tr>
						      						<th>Start Date</th>
						      						<th>End Date</th>
						      						<th>Cut Off</th>
						      						<th>Description</th>
						      						<th>Last Update</th>
						      					</tr>
						      				</thead>
						      				<tbody>
						      				</tbody>
						      			</table>
					      			</div>
					      		</div>
							</div>
						</div>
			        </form>
				</div>
				<div class="modal-footer">
			  		<button type="button" class="btn btn-primary" id="btn_reset_password">Reset password</button>
			  		<button type="button" class="btn btn-primary" id="btn_generate_cec">Generate C.E.C.</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mdl_create_account">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fas fa-user-circle"></i> Create New Account</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"> 
			        <form id="frm_create_account">
			          <div class="row">
				          <div class="form-group col-sm-6">
				            <label for="txt_account_name" class="col-form-label">Name</label>
				            <input type="text" class="form-control" id="txt_account_name" name="txt_account_name" readonly="true">
				          </div>
				          <div class="form-group col-sm-6">
				            <label for="txt_email" class="col-form-label">E-mail</label>
				            <input type="email" class="form-control" id="txt_email" name="txt_email">
				          </div>
			          </div>
			          <div class="row">
				          <div class="form-group col-sm-6">
				            <label for="txt_login_name" class="col-form-label">Login Name</label>
				            <input type="text" class="form-control" id="txt_login_name" name="txt_login_name" readonly="true">
				          </div>			          	
				          <div class="form-group col-sm-6">
				            <label for="txt_password" class="col-form-label">Password <i class="fa fa-info-circle text-info" title="This password is automatically generated by the system."></i></label>
				            <input type="text" class="form-control" id="txt_password" name="txt_password" required>
				          </div>
			          </div>
					  <div class="custom-control custom-switch">
					    <input type="checkbox" class="custom-control-input" id="ckb_require_pw_reset" name="ckb_require_pw_reset" value="1" checked onclick="return false;">
					    <label class="custom-control-label" for="ckb_require_pw_reset">Require Password Reset</label>
					  </div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_create_account" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mdl_create_cec" style="z-index:10000;">
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
										<option value="2018">2018</option>
										<option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option>
										<option value="2022" selected>2022</option>
										<option value="2023">2023</option>
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
					<button type="submit" form="frm_create_cec" class="btn btn-primary"><i class="fas fa-share-square"></i> Send as Email</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mdl_loan_payments" style="z-index:10000;">
		<div class="modal-dialog modal-w-60 modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fas fa-money-check-alt"></i> Loan Payments</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding-bottom:0;">
					<div class="row">
						<div class="col-sm-6">
							Description: <b id="spn_loan_description"></b>
						</div>
						<div class="col-sm-3">
							Principal: <b id="spn_loan_principal_amount" class="text-success"></b>
						</div>
						<div class="col-sm-3">
							Balance: <b id="spn_loan_balance" class="text-danger"></b>
						</div>
					</div>
					<div class="row" style="margin-top:5px;">
						<div class="col-sm-12">
							<table id="t_loan_payments" class="table table-striped table-sticky">
								<thead>
			      					<tr>
			      						<th>Schedule Date</th>
			      						<th>Date Paid</th>
			      						<th>Principal</th>
			      						<th>Interest</th>
			      						<th>Balance</th>
			      					</tr>
			      				</thead>
			      				<tbody>
			      				</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>			
		</div>
	</div>
	<div class="modal fade" id="mdl_reset_password" style="z-index:10000;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fas fa-user-circle"></i> Reset Passwords</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"> 
			        <form id="frm_reset_password">
			          <div class="row">
				          <div class="form-group col-sm-6">
				            <label for="txt_account_name_reset_pw" class="col-form-label">Name</label>
				            <input type="text" class="form-control" id="txt_account_name_reset_pw" name="txt_account_name_reset_pw" readonly="true">
				          </div>
				          <div class="form-group col-sm-6">
				            <label for="txt_email_reset_pw" class="col-form-label">E-mail</label>
				            <input type="email" class="form-control" id="txt_email_reset_pw" name="txt_email_reset_pw">
				          </div>
			          </div>
			          <div class="row">
				          <div class="form-group col-sm-6">
				            <label for="txt_login_name_reset_pw" class="col-form-label">Login Name</label>
				            <input type="text" class="form-control" id="txt_login_name_reset_pw" name="txt_login_name_reset_pw" readonly="true">
				          </div>			          	
				          <div class="form-group col-sm-6">
				            <label for="txt_password_reset_pw" class="col-form-label">Password <i class="fa fa-info-circle text-info" title="This password is automatically generated by the system."></i></label>
				            <input type="text" class="form-control" id="txt_password_reset_pw" name="txt_password_reset_pw" required>
				          </div>
			          </div>
					  <div class="custom-control custom-switch">
					    <input type="checkbox" class="custom-control-input" id="ckb_require_pw_reset_reset_pw" name="ckb_require_pw_reset_reset_pw" value="1" checked onclick="return false;">
					    <label class="custom-control-label" for="ckb_require_pw_reset_reset_pw">Require Password Reset</label>
					  </div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_reset_password" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="../dist/js/user_list.js?2"></script>
	<script type="text/javascript">
		$('#cont_loading').fadeIn();
		load_list_of_users();
	</script>
</body>
</html>