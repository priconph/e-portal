<html>
<head>
	<?php
  		require_once('../dist/includes.php');
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
			<input type="hidden" id="hdn-status-to-view" value="0">
			<ul class="list-group">
			  <li class="list-group-item active pointer" id="li-active-complaints">Active <span class="badge badge-danger" id="spn_active_complaints"></span></li>
			  <li class="list-group-item pointer" id="li-closed-complaints">Closed</li>
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
			        <form id="frm_complaint_details">
			        	<input name="rc_id" type="hidden">
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
									<label for="message-text" class="col-form-label">My Remarks</label>
									<textarea class="form-control" name="my-remarks-text" required></textarea>
								</div>
			      			</div>
			      			<div class="col-sm-2">	      			
								<div class="form-group">
									<br>
									<div class="btn-group-toggle" data-toggle="buttons">
									  <label class="btn btn-secondary active" style="border-bottom-left-radius: 0 0 !important;border-bottom-right-radius: 0 0 !important;">
									    <input type="radio" name="opt_complaint_options" id="opt_complaint_active" value="0" autocomplete="off">&nbsp;Active&nbsp;
									  </label>
									  <label class="btn btn-secondary" style="border-top-left-radius: 0 0 !important;border-top-right-radius: 0 0 !important;">
									    <input type="radio" name="opt_complaint_options" id="opt_complaint_closed" value="1" autocomplete="off">Closed&nbsp;
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
					<button type="submit" form="frm_complaint_details" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="../dist/js/complaints.js?5"></script>
	<script type="text/javascript">
		load_complaints();
	</script>
</body>
</html>