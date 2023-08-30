<html>
<head>
	<?php
  		require_once('../dist/includes.php');

  		session_start();
  		$username = $_SESSION['s_username'];
  		// $username = 'Q121';
	?>
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
		<div class="card" style="margin-right:1px;">
		  <div class="card-header" style="height:55px;">
			<div class="row form-inline">
				<div class="col-auto">
					<div class="form-group">
						<button type="button" id="btn_add_cec" class="btn btn-primary"><i class="fa fa-plus"></i> Request CEC</button>
					</div>
				</div>
			</div>
		  </div>
		  <div class="body">
			<input type="hidden" id="username" value="<?php echo $username; ?>"> 

		  	<div id="cont_list_of_cec" class="card-body" style="margin-left:3px;width:99%;overflow-x:scroll;">
				
				<table id="t_list_of_cec" class="table table-striped compact table-hover table-bordered nowrap" style="width:100%;">
			        <thead style="width:100% !important;">
			            <tr style="text-align:center;">
			                <th>Status</th>
			                <th>Requested by</th>
			                <th>Date/Time</th>
			                <th>Purpose</th>
			                <th>CEC</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tbody style="font-size:80%;width:100%">
			        </tbody>
			    </table>
			</div>
		  </div>
	</div>

	<div class="modal fade" id="mdl_create_cec" data-id="0" style="z-index:10000;">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body">
			        <form id="frm_create_cec">
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="cec_purpose" class="col-form-label">Purpose</label>
									<textarea type="text" class="form-control" id="cec_purpose" name="cec_purpose" autocomplete="off" required></textarea> 
								</div>
			      			</div>
			      		</div>
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="cec_datetime" class="col-form-label">Date/Time</label>
									<input type="text" class="form-control" name="cec_datetime" placeholder="Auto-generated" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly>
								</div>
			      			</div>
			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_create_cec" class="btn btn-primary"><i class="fas fa-share-square"></i> Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="mdl_approved_cec" data-id="0" style="z-index:10000;">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body">
			        <form id="frm_approved_cec">
			        	<input id="cec_app_id" name="cec_app_id" type="hidden">
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="cec_approved_remarks" class="col-form-label">Approve Remarks</label>
									<textarea type="text" class="form-control" name="cec_approved_remarks" autocomplete="off" required></textarea> 
								</div>
			      			</div>
			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_approved_cec" class="btn btn-primary"><i class="fas fa-share-square"></i> Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="mdl_disapproved_cec" data-id="0" style="z-index:10000;">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body">
			        <form id="frm_disapproved_cec">
			        	<input id="cec_dis_id" name="cec_dis_id" type="hidden">
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="memo_disapproved_remarks" class="col-form-label">Disapprove Remarks</label>
									<textarea type="text" class="form-control" name="memo_disapproved_remarks" autocomplete="off" required></textarea> 
								</div>
			      			</div>
			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_disapproved_cec" class="btn btn-primary"><i class="fas fa-share-square"></i> Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="mdl_gen_cec" data-id="0" style="z-index:10000;">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body">
			        <form id="frm_gen_cec">
			        	<input id="cec_gen_id" name="cec_gen_id" type="hidden">
			      		<div class="row" style="margin-top:-0px;">
			      			<div class="col-sm-6">
								<div class="form-group">
									<label for="cec_gen_emp_name" class="col-form-label">Employee Name</label>
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-6">
								<div class="form-group">
									<label for="cec_gen_emp_name" class="col-form-label">Employee No.</label>
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-6">
								<div class="form-group">
									<label for="cec_gen_emp_name" class="col-form-label">Position</label>
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-6">
								<div class="form-group">
									<label for="cec_gen_emp_name" class="col-form-label">Effective date</label>
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
		      				</div>
			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">PRESENT</label>
		      				</div>
			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">PREVIOUS</label>
		      				</div>

			      			<div class="col-sm-4">
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="cec_gen_emp_name" class="col-form-label">Effective on</label>
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="cec_gen_emp_name" class="col-form-label">As of</label>
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>

			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">BASIC SALARY</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-12">
								<label for="cec_gen_emp_name" class="col-form-label">Other Monthly Allowances Received, Specify:</label>
			      			</div>
			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">TRANSPORTATION</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">RICE SUBSIDY</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">ECOLA</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">TOTALS</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>

			      			<div class="col-sm-12">
								<label for="cec_gen_emp_name" class="col-form-label">Further, he / she received the following additional remuneration other than the above during the
twelve - month period ended. (Present effective cut-off date, as above).</label>
			      			</div>

			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">13TH MONTH PAY</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
			      			</div>

			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">BONUSES</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
			      			</div>

			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">SL/VL</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
			      			</div>

			      			<div class="col-sm-4">
								<label for="cec_gen_emp_name" class="col-form-label">TOTAL OTHER INCOME: P</label>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-4">
			      			</div>

			      			<div class="col-sm-12">
								<label for="cec_gen_emp_name" class="col-form-label">This certification is being issued upon the request of the above named employee for whatever legal purpose it may serve.</label>
			      			</div>

			      			<div class="col-sm-6">
								<label for="cec_gen_emp_name" class="col-form-label">Date issued</label>
			      			</div>
			      			<div class="col-sm-6">
								<div class="form-group">
									<input type="date" class="form-control" name="cec_gen_emp_name" autocomplete="off" required></input> 
								</div>
			      			</div>
			      			<div class="col-sm-6">
								<label for="cec_gen_emp_name" class="col-form-label">Approver</label>
			      			</div>
			      			<div class="col-sm-6">
								<div class="form-group">
									<input type="text" class="form-control" name="cec_gen_emp_name" value="Elenita Pamaong" readonly></input> 
								</div>
			      			</div>


			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_gen_cec" class="btn btn-primary"><i class="fas fa-share-square"></i> Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="../dist/js/cec.js?1"></script>
	<script type="text/javascript">
		$('#cont_loading').fadeIn();
	</script>
</body>
</html>