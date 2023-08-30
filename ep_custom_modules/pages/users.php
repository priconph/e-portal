<html>
<head>
	<?php
  		require_once('../dist/includes.php');
	?>
	<link rel="stylesheet" type="text/css" href="../dist/sticky_table/sticky-table.min.css">
	</script><script type="text/javascript" src="../dist/sticky_table/sticky-table.min.js"></script>
	<style type="text/css">
		thead tr, tfoot tr{
			font-size:70%;
			text-align: center;
			vertical-align: middle;
		}
		#t_loan_information tbody{
			font-size: 80%;
			cursor: pointer;
		}
		.wrapper-sticky{
			max-height: 168px !important;
		}
	</style>
</head>
<body>
	<div class="col-sm-12" style="padding:0;margin-top:0">
		<div class="card">
		  <div class="card-header">
		    <div class="btn-group float-right">
			  <button type="button" class="btn btn-outline-light text-dark btn-sm" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
			  	Active user/s for the last 5 minutes:
				<span class="badge badge-success" id="active_users">0</span>
			  </button>
			  <div class="dropdown-menu dropdown-menu-right">
			  	<div class="row">
			  		<div class="col-auto small" style="padding-left:20px;width:220px !important;" id="cont_active_users">
			  		</div>
			  	</div>
			  </div>
			</div>
		    <ul class="nav nav-tabs card-header-tabs">
		      <li class="nav-item">
				<a class="nav-link active" href="#" id="a_activity_logs">
					Activity Logs
					<span id="cont_loading_users_al">
				  		&nbsp;<i class="fa fa-spinner fa-spin" style="color:green;"></i>
					</span>
				</a>
		      </li>
		      <li class="nav-item">
				<a class="nav-link d-none" href="#" id="a_list_of_users">
					List of Users
					<span id="cont_loading_users_lu" style="display: none;">
				  		&nbsp;<i class="fa fa-spinner fa-spin" style="color:green;"></i>
					</span>
				</a>
		      </li>
		    </ul>
		  </div>
			<div id="cont_activity_logs" class="card-body" style="width:100%;overflow-x:scroll;">
				<table id="t_activity_logs" class="table table-striped table-hover table-bordered nowrap" style="width:100% !important;">
			        <thead>
			            <tr style="text-align:center;">
			                <th>Date</th>
			                <th>E. N.</th>
			                <th>Name</th>
			                <th>Page</th>
			                <th>IP Address</th>
			            </tr>
			        </thead>
			        <tbody style="font-size:80%;">
			        </tbody>
			    </table>
			</div>
			<div id="cont_list_of_users" class="card-body d-none" style="margin-left:3px;width:99%;overflow-x:scroll;">
				<div id="cont_list_of_users_cover" style="padding:0;margin:0;background-color:white;width:100%;height:450px;position:absolute;z-index:999;">
				</div>
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
	<div class="modal fade" id="mdl_show_employee_information" tabindex="1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog modal-w-100 modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="fa fa-id-card"></i> Employment Information</h5>
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
			      			<div class="col-sm-1">
								<div class="form-group">
									<label for="employee_number" class="col-form-label">E.N.</label>
									<input type="text" class="form-control" id="employee_number" readonly="true">
								</div>
			      			</div>
			      			<div class="col-sm-3">
								<div class="form-group">
									<label for="employee_name" class="col-form-label">Name</label>
									<input type="text" class="form-control pointer" id="employee_name" readonly="true">
								</div>
			      			</div>
			      			<div class="col-sm-4">
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
							    	<a class="nav-item nav-link" data-toggle="tab" aria-selected="true" id="li_pay_slip_history" data-toggle="tab" href="#cont_pay_slip_history" role="tab" aria-controls="cont_financial_information">Pay Slip History</a>
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
									<div class="col-sm-12" style="overflow-y:auto;margin-top:10px;">
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
						</div>
			        </form>
				</div>
				<div class="modal-footer">
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
					<h5 class="modal-title">Create New Account</h5>
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
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" form="frm_create_account" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mdl_create_cec" style="z-index:99999999;">
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
	<script type="text/javascript" src="../dist/js/activity_logs.js?1"></script>
	<script type="text/javascript">
		$('#cont_loading').fadeIn();
        load_activity_logs('initial');
	</script>
</body>
</html>