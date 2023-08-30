<html>
<head>
	<?php
  		require_once('../dist/includes.php');

  		session_start();
  		$username = $_SESSION['s_username'];
	?>
</head>
<body>
	<div class="progress" id="cont_loading" style="margin-bottom:20px;display:none;width:20%;">
	  <span style="color:#86888c;font-weight:bold;font-size:70%;margin-top:2px;">&nbsp;Loading&nbsp;</span>
	  <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:66%;">
	  	<i class="fa fa-spinner fa-spin"></i>
	  </div>
	</div>
	<div class="row" style="width:100%;overflow-x:hidden;">
		<div class="col-sm-2">
			<input type="hidden" id="hdn-status-to-view" value="1">
			<input type="hidden" id="hdn-approver" value="<?php echo $username; ?>"> 
			<ul class="list-group">
			  <li class="list-group-item active pointer" id="li-active-complaints">Pending for Approval <span class="badge badge-danger" id="spn_active_complaints"></span></li>
			  <li class="list-group-item pointer" id="li-closed-complaints">Closed <span class="badge badge-danger" id="spn_closed_complaints"></span></li>
			</ul>
		</div>
		<div class="col-sm-10" style="border:1px gray dotted;padding:9px;height:530px;overflow-y:auto;border-radius:5px;background-color:#dbedff;">
			<ul class="list-unstyled" id="ul_complaints">
			</ul>	
		</div>
	</div>
	<div class="modal fade" id="mdl_complaint_details">
		<div class="modal-dialog modal-lg" style="margin-top:1%;">
			<div class="modal-content">
				<div class="modal-body" style="overflow-y:auto;">
			        <form id="frm_complaint_details_approval">
			        	<input name="rc_id" type="hidden">
			        	<input name="rc_username" type="hidden" value="<?php echo $username; ?>"> 
			      		<div class="row">
			      			<div class="col-sm-2">
			      				<img id="a-concern-employee-picture" class="img-thumbnail" onerror="certain_image_error('a-concern-employee-picture')" alt="Employee's Picture" style="width:100px;height:100px;display: block;margin-left: auto;margin-right: auto;">
			      			</div>
			      			<div class="col-sm-3">
								<div class="form-group">
									<label for="concern-name" class="col-form-label">Employee</label>
									<input type="email" class="form-control" id="concern-employee" readonly="true">
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="concern-name" class="col-form-label">Contact</label>
									<a id="a-concern-contact" class="pointer"><input type="email" class="form-control pointer" id="concern-contact" readonly="true" style="color:blue;"></a>
								</div>
			      			</div>
			      			<div class="col-sm-3">
								<div class="form-group">
									<label for="concern-name" class="col-form-label">Position</label>
									<input type="email" class="form-control" id="concern-position" readonly="true">
								</div>
			      			</div>
			      		</div>
			      		<div class="row" style="margin-top:-20px;">
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="concern-name" class="col-form-label" style="float:right !important;padding-right:35px;">Concern Date</label>
									<input type="email" class="form-control" id="concern-date" style="font-weight:bold;" readonly="true">
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="concern-name" class="col-form-label">Section/Department</label>
									<input type="email" class="form-control pointer" id="concern-section-department" readonly="true" style="color:blue;">
								</div>
			      			</div>
			      			<div class="col-sm-4">
								<div class="form-group">
									<label for="concern-name" class="col-form-label">Local Number</label>
									<input type="email" class="form-control" id="concern-salary-type" readonly="true">
								</div>
			      			</div>
			      		</div>
			      		<div class="row" style="margin-top:-10px;">	
			      			<div class="col-sm-5">	      			
								<div class="form-group">
									<label for="message-text" class="col-form-label">Message</label>
									<textarea class="form-control" id="message-text" readonly="true"></textarea>
								</div>
			      			</div>
			      			<div class="col-sm-5">	      			
								<div class="form-group">
									<label for="message-text" class="col-form-label">Approval Remarks</label>
									<textarea class="form-control" name="my-remarks-text"></textarea>
								</div>
			      			</div>
			      			<div class="col-sm-2">	      			
								<div class="form-group">
									<br>
									<div class="btn-group-toggle" data-toggle="buttons">
									  <label class="btn btn-secondary" style="border-bottom-left-radius: 0 0 !important;border-bottom-right-radius: 0 0 !important;">
									    <input type="radio" name="opt_complaint_app_options" id="opt_complaint_approved" value="2" autocomplete="off">&nbsp;Approved&nbsp;
									  </label>
									  <label class="btn btn-secondary" style="border-top-left-radius: 0 0 !important;border-top-right-radius: 0 0 !important;">
									    <input type="radio" name="opt_complaint_app_options" id="opt_complaint_disapproved" value="3" autocomplete="off">Dispproved&nbsp;
									  </label>
									</div>
								</div>
			      			</div>
			      		</div>
			      		<div class="row">
			      			<div class="col-sm-12">
				      			<table id="t_complaint" class="table table-striped table-bordered nowrap dataTable">
				      				<thead>
										<tr style="text-align:center;font-size:70%;">
											<th style="vertical-align:middle;">Time In</th>
											<th style="vertical-align:middle;">Time Out</th>
											<th style="vertical-align:middle;">Rendered<br>Hrs.</th>
											<th style="vertical-align:middle;">Regular<br>OT</th>
											<th style="vertical-align:middle;">Sunday<br>OT</th>
											<th style="vertical-align:middle;">Special<br>Holiday</th>
											<th style="vertical-align:middle;">Holiday<br>OT</th>
											<th style="vertical-align:middle;">Under-<br>time</th>
											<th style="vertical-align:middle;">Night<br>Diff</th>
											<th style="vertical-align:middle;">Absent</th>
											<th style="vertical-align:middle;">SL/VL</th>
										</tr>
				      				</thead>
				      				<tbody>
				      				</tbody>
				      			</table>	
			      			</div>
			      		</div>
			        </form>
				</div>
				<div class="modal-footer">
<!-- 					<button type="submit" id="approve_complaint" class="btn btn-primary"><i class="fas fa-save"></i> Approve</button>
					<button type="button" id="disapprove_complaint" class="btn btn-danger"><i class="fas fa-save"></i> Disapprove</button>
 -->					
					<button type="submit" form="frm_complaint_details_approval" class="btn btn-primary" id="submit_btn"><i class="fas fa-save"></i> Submit</button>
 					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal approved leave-->	
	<!-- <div class="modal fade" id="approved_modal" role="dialog">
	<div class="modal-dialog">
	  <div class="modal-content">
	    <div class="modal-header modal-header-default">
	      <h4 style="font-family:Euphemia">APPROVED COMPLAINT</h4>
	    </div>
		<div class="modal-body">
			<form id="frm_complaint_approved">
				<input name="rc_id" type="text">
				<center><div id="alert_msg1" style="display:none; color:white; font-weight:bold; font-size: 14px; text-align:center; width:100%" class="row alert alert-danger"></div></center>
				<div class="form-group">
					<label style="font-size: 14px;" for="app_remarks">Remarks</label>
					<textarea class="form-control" rows="3" id="approved_remarks" autocomplete="off"></textarea>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<div style="float:right">
				<button type="submit" form="frm_complaint_approved" class="btn btn-primary"><i class="fas fa-save"></i> SAVE</button>
				<button class="btn btn-danger" id="btn_close_approved">CLOSE</button>
			</div>
		</div>
	  </div>
	</div>
	</div> -->


	<!-- Modal disapproved leave-->	
	<!-- <div class="modal fade" id="disapproved_modal" role="dialog">
	<div class="modal-dialog">
	  <div class="modal-content">
	    <div class="modal-header modal-header-default">
	      <h4 style="font-family:Euphemia">DISAPPROVED COMPLAINT</h4>
	    </div>
		<div class="modal-body">
			<div class="form-group">
				<label style="font-size: 14px;" for="disapp_remarks">Remarks</label>
				<textarea class="form-control" rows="3" id="disapproved_remarks" autocomplete="off" required></textarea>
			</div>
		</div>
		<div class="modal-footer">
			<div style="float:right">
				<button class="btn btn-primary" id="btn_save_disapproved" data-save="modal">SAVE</button>
				<button class="btn btn-danger" id="btn_close_disapproved">CLOSE</button>
			</div>
		</div>
	  </div>
	</div>
	</div> -->

<script type="text/javascript" src="../dist/js/complaints_approval.js?5"></script>
<script type="text/javascript">
	load_complaints_approval();
</script>

</body>
</html>