<html>
<head>
	<?php
  		require_once('../dist/includes.php');

  		session_start();
  		$username = $_SESSION['s_username'];
  		// $username = '1389';
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
						<button type="button" id="btn_add_memo" class="btn btn-primary"><i class="fa fa-plus"></i> Add Memo</button>
					</div>
				</div>
			</div>
		  </div>
		  <div class="body">
			<input type="hidden" id="username" value="<?php echo $username; ?>"> 

		  	<div id="cont_list_of_memo" class="card-body" style="margin-left:3px;width:99%;overflow-x:scroll;">
				
				<table id="t_list_of_memo" class="table table-striped compact table-hover table-bordered nowrap" style="width:100%;">
			        <thead style="width:100% !important;">
			            <tr style="text-align:center;">
			                <th>Status</th>
			                <th>Document Title</th>
			                <th>Document Name (PDF only)</th>
			                <th>Date/Time Upload</th>
			                <th>Approver</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tbody style="font-size:80%;width:100%">
			        </tbody>
			    </table>
			</div>
		  </div>
	</div>

	<div class="modal fade" id="mdl_create_memo" data-id="0" style="z-index:10000;">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body">
			        <form id="frm_create_memo">
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="memo_title" class="col-form-label">Document Title</label>
									<input type="text" class="form-control" name="memo_title" autocomplete="off" required>
								</div>
			      			</div>
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="memo_filename" class="col-form-label">File <i>(Excel, Word, PDF Only)</i></label>
									<input type="file" class="form-control" name="memo_filename" id="memo_filename" accept=".xls,.xlsx,.docx,.pdf" required>
								</div>
			      			</div>
			      		</div>
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
					          	<div class="form-group">
					            <label for="memo_approver" class="col-form-label">Approver:</label>
					            <input class="form-control" list="id_superior_list" name="memo_approver_check" id="memo_approver_check" autocomplete=off required>
								<datalist id="id_superior_list"></datalist>
								<input type="text" class="form-control" name="memo_approver" id="memo_approver" readonly required>
					          </div>
			      			</div>
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="memo_datetime" class="col-form-label">Date/Time</label>
									<input type="text" class="form-control" name="memo_datetime" placeholder="Auto-generated" value="<?php echo date("Y-m-d H:i:s"); ?>" readonly>
								</div>
			      			</div>
			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_create_memo" class="btn btn-primary"><i class="fas fa-share-square"></i> Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="mdl_approved_memo" data-id="0" style="z-index:10000;">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body">
			        <form id="frm_approved_memo">
			        	<input id="memo_app_id" name="memo_app_id" type="hidden">
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="memo_approved_remarks" class="col-form-label">Approve Remarks</label>
									<textarea type="text" class="form-control" name="memo_approved_remarks" autocomplete="off" required></textarea> 
								</div>
			      			</div>
			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_approved_memo" class="btn btn-primary"><i class="fas fa-share-square"></i> Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="mdl_conform_memo" data-id="0" style="z-index:10000;">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body">
			        <form id="frm_conform_memo">
			        	<input id="memo_con_id" name="memo_con_id" type="hidden">
			      		<div class="row" style="margin-top:-10px;">
			      			<div class="col-sm-12">
								<div class="form-group">
									<label for="memo_conform_remarks" class="col-form-label">Conformance Remarks</label>
									<textarea type="text" class="form-control" name="memo_conform_remarks" autocomplete="off" required></textarea> 
								</div>
			      			</div>
			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_conform_memo" class="btn btn-primary"><i class="fas fa-share-square"></i> Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="mdl_disapproved_memo" data-id="0" style="z-index:10000;">
		<div class="modal-dialog" style="margin-top:0%;">
			<div class="modal-content">
				<div class="modal-body">
			        <form id="frm_disapproved_memo">
			        	<input id="memo_dis_id" name="memo_dis_id" type="hidden">
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
					<button type="submit" form="frm_disapproved_memo" class="btn btn-primary"><i class="fas fa-share-square"></i> Submit</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="../dist/js/memo.js?1"></script>
	<script type="text/javascript">
		$('#cont_loading').fadeIn();
	</script>
</body>
</html>