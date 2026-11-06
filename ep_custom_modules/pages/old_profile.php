
<body>

	<div class="progress" id="cont_loading" style="margin-bottom:20px;display:none;width:20%;">
	  <span style="color:#86888c;font-weight:bold;font-size:70%;margin-top:2px;">&nbsp;Loading&nbsp;</span>
	  <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width:66%;">
	  	<i class="fa fa-spinner fa-spin"></i>
	  </div>
	</div>

	<div class="row" style="width:100%;overflow-x:hidden;">
		<div class="col-sm-3">
  		<button class="btn btn-primary btn-block" id="btn_user_manual"><i class="fa fa-eye"></i> USER MANUAL</button>
			<input type="hidden" id="hdn-status-to-view" value="0">
			<ul class="list-group">
			  <li class="list-group-item bg-light" id="li-active-complaints"><img id="usr_profile_picture" alt="..." src="../../images/default_profile_pic.jpg" onerror="image_error()" class="rounded-circle w-100" style="min-height:100px;"></li>
			  <li class="list-group-item bg-light">
			  	<button class="btn btn-primary btn-block" id="btn_edit_profile"><i class="fa fa-edit"></i> Edit Profile</button> 	
			  	<button class="btn btn-primary btn-block" id="btn_form_2316"><i class="fa fa-eye"></i> BIR Form 2316 (ITR)</button>
			  	<button class="btn btn-primary btn-block" id="btn_form_pas"><i class="fa fa-file-pdf"></i> Performance Appraisal</button><!-- Added by Nessa 01232025 -->
			  </li>
			  <li class="list-group-item bg-light" style="height: 250px;">
			  	<h4 id="usr_name"></h4>
			  	<span class="text-muted text-bold" id="usr_employee_number"></span><br>
			  	<span class="text-muted" id="usr_position"></span><br>
			  	<span class="text-muted" id="usr_section_department"></span><br>
			  	<span class="text-muted" id="usr_date_hired"></span>
			  	<span class="text-muted" id="usr_rcbcaccountnum"></span>
			  	<span class="text-muted" id="usr_birthday"></span>
			  </li>	
			</ul>
		</div>
		<div class="col-sm-9" style="padding:9px;height:550px;overflow-y:auto;overflow-x:hidden;padding-top:0;padding-right:5px;">
			<div class="card-deck">
				<div class="card mb-4">
					<div class="card-header text-primary">
						<i class="fa fa-plus-circle"></i> Leave Credits
					</div>
					<div class="card-body bg-white text-dark" style="padding-top:0;padding-bottom:0;height:140px;overflow-y:auto;margin-bottom:10px;">
						<table class="table table-sm text-dark border-0" style="font-size:.77em;margin-bottom:0;">
							<thead>
								<tr>
									<td class="border-top-0"></td>
									<td class="border-top-0">VL</td>
									<td class="border-top-0">SL</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Previous balance</td>
									<td id="td_previous_balance_vl"></td>
									<td id="td_previous_balance_sl"></td>
								</tr>
								<tr>
									<td>Credit for 1 year</td>
									<td id="td_credit_vl"></td>
									<td id="td_credit_sl"></td>
								</tr>
								<tr>
									<td style="font-weight:bold;">Available</td>
									<td id="td_current_avail_vl" style="color:green;font-weight:bold;"></td>
									<td id="td_current_avail_sl" style="color:green;font-weight:bold;"></td>
								</tr>
								<tr>
									<td>Used</td>
									<td id="td_total_vl_used"></td>
									<td id="td_total_sl_used"></td>
								</tr>
								<tr>
									<td>Balance for the year</td>
									<td id="td_total_vl_balance"></td>
									<td id="td_total_sl_balance"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="card mb-4">
					<div class="card-header text-primary">
						<i class="fa fa-calendar"></i> Leaves
						<!-- <button class="btn btn-sm btn-secondary float-right" id="btn_change_data_last_30_leaves"><i class="fas fa-calendar-alt"></i> Change</button> -->
					</div>
					<div class="card-body bg-white text-dark" style="padding-top:0;padding-bottom:0;height:140px;overflow-y:auto;margin-bottom:1px;padding-bottom:0;">
						<table id="t_my_leaves" class="table table-sm text-dark border-0" style="font-size:.77em;margin-bottom:0;margin-top:0;">
							<tbody>
								<tr>
									<td class="w-100">No data found</td>
								</tr>
							</tbody>
						</table>
					</div>
 					<?php
						if($_SESSION['s_username']==''){
							echo '<div class="card-footer small">Total SL: VL:</div>';
						}
					?>  			
				</div>
				<div class="card mb-4">
				  <div class="card-header text-success">
				  	<i class="fa fa-clock"></i> Overtime Requests
				  </div>
				  <div class="card-body bg-white text-dark" style="padding-top:0;padding-bottom:0;height:140px;overflow-y:auto;margin-bottom:1px;">
				    <table id="t_overtime_requests" class="table table-sm text-dark" style="font-size:.77em;margin-bottom:0;">
				    	<tbody>
				    	</tbody>
				    </table>	
				  </div>
				</div>
			</div>
			<div class="card mb-4">
			  <div class="card-header text-danger">
			  	<i class="fa fa-question-circle"></i> Last 10 Complaints
				<div class="btn-group float-right" role="group">
					<button id="btn_filter_my_complaints" type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fa fa-filter"></i> Filter
					</button>
					<div class="dropdown-menu" aria-labelledby="btn_filter_my_complaints">
						<a class="dropdown-item disabled" id="a_bfor_pending">Active</a>
						<a class="dropdown-item disabled" id="a_bfmc_approved">Closed</a>
						<a class="dropdown-item active" id="a_bfmc_last_10_entries">Last 10 entries</a>
					</div>
				</div>
			  </div>
			  <div class="card-body bg-white text-danger" style="padding-top:0;padding-bottom:0;height:195px;overflow-y:auto;margin-bottom:1px;">
			    <table id="t_my_complaints" class="table table-sm text-dark" style="font-size:.77em;margin-bottom:0;">
			    	<tbody>
			    	</tbody>
			    </table>
			  </div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="../dist/js/profile.js?9"></script>
	<script type="text/javascript">
		load_profile();
	</script>
</body>