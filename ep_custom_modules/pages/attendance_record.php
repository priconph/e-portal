<html>
<head>
	<?php
  		require_once('../dist/includes.php');
	?>
</head>
<body>
	<div class="card" style="margin-right:1px;">
	  <div class="card-header" style="height:55px;">
		<div class="row form-inline" id="div_data_filter">
			<?php
				if($_SESSION['s_username']=='K010'){
					echo '<input type="hidden" id="hdn_employee_number" value="'.$_SESSION['s_username'].'">';
					echo '<div class="col-xs-3">';
					echo '	<div class="form-group mx-sm-1 mb-2">';
					echo '	    <input class="form-control" id="slt_employee_number" placeholder="Employee Number" style="border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;">'; 
					echo '		<button type="button" class="btn btn-secondary" style="border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;">Go</button>'; 
					echo '	</div>';
					echo '</div>';
				}
			?>
			<div class="col-xs-3">
				<div class="form-group mx-sm-1 mb-2">
					<div class="btn-group btn-group-toggle" data-toggle="buttons">
						<label class="btn btn-secondary">
							<input type="radio" name="opt_cut_off" id="opt_cut_off_one" value="1"> 1st Cut-off
						</label>
						<label class="btn btn-secondary">
							<input type="radio" name="opt_cut_off" id="opt_cut_off_two" value="2"> 2nd Cut-off
						</label>
					</div>
				</div>
			</div>	
			<div class="col-xs-3">
				<div class="form-group mx-sm-1 mb-2">
				    <select class="form-control pointer" id="slt_cut_off_month" style="border-top-right-radius: 0 0;border-bottom-right-radius: 0 0;">
						<option value="01">January</option>
						<option value="02">February</option>
						<option value="03">March</option>
						<option value="04">April</option>
						<option value="05">May</option>
						<option value="06">June</option>
						<option value="07">July</option>
						<option value="08">August</option>
						<option value="09">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
				    <select class="form-control" style="width:80px;border-top-left-radius: 0 0;border-bottom-left-radius: 0 0;" id="slt_cut_off_year">
<!-- 						<option value="2018">2018</option>
						<option value="2019">2019</option> -->
					</select>
				</div>
			</div>
			<div class="col-xs-3">
				<div class="form-group mx-sm-1 mb-2">
				    <input type="date" class="form-control form-control-sm" id="slt_data_start_data" data-date="" data-date-format="DD MMMM YYYY" v autocomplete="off" onkeydown="return false">&nbsp;-&nbsp;
				    <input type="date" class="form-control form-control-sm" id="slt_data_end_data" autocomplete="off" onkeydown="return false">
					<span id="cont_loading">
				  		&nbsp;<i class="fa fa-spinner fa-spin" style="color:green;"></i>
					</span>
				</div>
			</div>
		</div>
	  </div>
	  <div class="card-body">
		<div class="row">
			<div class="alert alert-warning alert-dismissible fade show" role="alert" id="cont-unread-remark" style="display:none;margin-bottom:8px;">
			  <span id="spn-unread-remark-dates"></span>
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			</div>
		</div>
		<div id="cont_attendance_record" class="row" style="margin-left:5px;width:100%;overflow-x:scroll;display:none;">
			<div class="col-12">
				<table id="t_attendance_record" class="table table-striped table-bordered nowrap" style="width:100% !important;">
			        <thead>
			            <tr style="text-align:center;font-size:70%;">
			                <th style="vertical-align:middle;">Date</th>
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
			                <th style="vertical-align:middle;text-align:left;">Complaint<br>Updates: <span class="badge badge-danger" id="spn_unread_updates"></span></th>
			            </tr>
			        </thead>
			        <tbody style="font-size:80%;">
			        </tbody>
			        <tfoot>
			            <tr style="text-align:center;font-size:70%;">
			                <th></th>
			                <th style="vertical-align:middle;"><span id="spn_total_lates" style="color:red;"></span></th>
			                <th></th>
			                <th></th>
			                <th style="vertical-align:middle;text-align:right;"><span id="spn_total_regular_ot"></span></th>
			                <th style="vertical-align:middle;text-align:right;"><span id="spn_total_sunday_ot"></span></th>
			                <th style="vertical-align:middle;text-align:right;"><span id="spn_total_special_holiday"></span></th>
			                <th style="vertical-align:middle;text-align:right;"><span id="spn_total_holiday_ot"></span></th>
			                <th style="vertical-align:middle;text-align:right;"><span id="spn_total_undertime"></span></th>
			                <th style="vertical-align:middle;text-align:right;"><span id="spn_total_night_diff"></span></th>
			                <th style="vertical-align:middle;text-align:right;"><span id="spn_total_absent"></span></th>
			                <th style="vertical-align:middle;text-align:right;"><span id="spn_total_sl_vl"></span></th>
			                <th style="vertical-align:middle;text-align:right;"></th>
			            </tr>
			        </tfoot>
			    </table>
			</div>
		</div>
	  </div>
	</div>
	<div class="modal fade" id="mdl_send_complaint" tabindex="1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><i class="far fa-envelope"></i> Send Complaint</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"> 
			        <form id="frm_send_complaint">
			          <!-- <div class="form-group">
			            <label for="recipient-name" class="col-form-label">Recipient:</label>
			            <input type="text" class="form-control" id="recipient-name" value="Joy Serrac(jgserrac@pricon.ph)" required readonly="true">
			          </div> -->
			          <div class="form-group">
			            <label for="recipient-name" class="col-form-label">Concern Date:</label>
			            <input type="text" class="form-control" id="concern-date" name="concern-date" required readonly="true">
			          </div>
			          <div class="form-group">
			            <label for="recipient-name" class="col-form-label">Immediate Superior:</label>
			            <!-- <input type="text" class="form-control" id="txt_superior" name="txt_superior" required> -->
			            <input class="form-control" list="id_superior_list" name="name_superior_check" id="id_superior_check" required autocomplete=off>
						<datalist id="id_superior_list"></datalist>
						<input type="text" class="form-control" name="name_superior" id="id_superior" required readonly>
			          </div>
			          <div class="form-group">
			            <label for="message-text" class="col-form-label">Message:</label>
			            <textarea class="form-control" name="message-text" required></textarea>
			          </div>
			        </form>
				</div>
				<div class="modal-footer">
					<button type="submit" form="frm_send_complaint" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send Complaint</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="mdl_complaint_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg" style="margin-top:1%;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Complaint Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="overflow-y:scroll;">
		        	<input name="rc_id" type="hidden">
		      		<div class="row">
		      			<div class="col-sm-4">
							<div class="form-group">
								<label for="concern-name" class="col-form-label">Employee:</label>
								<input type="email" class="form-control" id="concern-employee" readonly="true">
							</div>
		      			</div>
		      			<div class="col-sm-4">
							<div class="form-group">
								<label for="concern-name" class="col-form-label">Contact:</label>
								<a id="a-concern-contact" class="pointer"><input type="email" class="form-control pointer" id="concern-contact" readonly="true" style="color:blue;"></a>
							</div>
		      			</div>
		      			<div class="col-sm-4">
							<div class="form-group">
								<label for="concern-name" class="col-form-label">Concern Date:</label>
								<input type="email" class="form-control" id="concern-details-date" readonly="true">
							</div>
		      			</div>
		      		</div>
		      		<div class="row" style="margin-top:-10px;">	
		      			<div class="col-sm-12">	      			
							<div class="form-group">
								<label for="message-text" class="col-form-label">My Remarks:</label>
								<textarea class="form-control" id="message-text" style="font-size:90%;" readonly="true"></textarea>
							</div>
		      			</div>
	      			</div>

		      		<div class="row" style="margin-top:-10px;">		
		      			<div class="col-sm-5">	      			
							<div class="form-group">
								<label for="message-text" class="col-form-label">Approver's Remarks:</label>
								<textarea class="form-control" id="approval_remarks" style="font-size:90%;" readonly="true"></textarea>
							</div>
		      			</div>
		      			<div class="col-sm-5">	      			
							<div class="form-group">
								<label for="message-text" class="col-form-label">Timekeeper's Remarks:</label>
								<textarea class="form-control" id="my-remarks-text" style="font-size:90%;" readonly="true"></textarea>
							</div>
		      			</div>
		      			<div class="col-sm-2">	      			
							<div class="form-group">
								<br>
								<div class="btn-group-toggle" data-toggle="buttons">
								  <label class="btn btn-secondary active" style="border-bottom-left-radius: 0 0 !important;border-bottom-right-radius: 0 0 !important;">
								    <input type="radio" name="opt_complaint_options" id="opt_complaint_active" value="1" autocomplete="off" readonly="true">&nbsp;Active&nbsp;
								  </label>
								  <label class="btn btn-secondary" style="border-top-left-radius: 0 0 !important;border-top-right-radius: 0 0 !important;">
								    <input type="radio" name="opt_complaint_options" id="opt_complaint_closed" value="0" autocomplete="off" readonly="true">Closed&nbsp;
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
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal_notif" tabindex="-1">
	    <div class="modal-dialog modal-lg modal-dialog-centered">
	      <div class="modal-content">
	        <div class="modal-header border-bottom-0 pb-0">
	          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	          </button>
	        </div>
	        <div class="modal-body pt-0">
	          <div class="text-center text-secondary">

	            <p style="font-size:40px; color:blue;">Good day! Please be informed that all COMPLAINTS must be approved first by your Immediate Superior. Thank you!  </p>

	          </div>
	        </div>
	      </div>
	    </div>
	  </div>

	<script type="text/javascript" src="../dist/js/attendance_record.js?34"></script>
	<script type="text/javascript">		
		$('#cont_loading').fadeIn();

		$(document).ready(function(){
	        var current_year 		= moment().format('YYYY');
			var html = '';
			for (var i = current_year; i >=2018; i--) {
				html += '<option value="'+i+'">'+i+'</option>';
				console.log(i);
			}
			$('#slt_cut_off_year').html(html);

	        var day 						= moment().format('DD');
	        var month 						= moment().format('MM');
	        var prev_month 					= moment().subtract(1, 'month').format('MM');
	        var year 						= moment().format('YYYY');
	        var prev_year 					= moment().subtract(1, 'month').format('YYYY');

			const first_cut_off 			= ["31","30","1","2","3","4","5","6","7","8","9","10","11","12","13","14"];
			const second_cut_off 			= ["15","16","17","18","19","20","21","22","23","24","25","26","27","28","29"];
			const view_first_cut_off 		= ["06","07","08","09","10","11","12","13","14","15","16","17","18","19","20"];
			const view_second_cut_off 		= ["21","22","23","24","25","26","27","28","29","31", "30", "01", "02","03","04","05"];
			const foreign_days 				= ["01", "02","03","04","05"];

	        $('#slt_cut_off_year').val(year);

	       	console.log(day);

			if(view_first_cut_off.includes(day)==true){
	       		$('#slt_cut_off_month').val(month);
				setTimeout(function(){
					$('#opt_cut_off_one').parent().click();
				}, 100);
			}
			else if(view_second_cut_off.includes(day)==true){
				if(foreign_days.includes(day)==true){
	       			$('#slt_cut_off_month').val(prev_month);
				}
				else{
	       			$('#slt_cut_off_month').val(month);
				}

				setTimeout(function(){
					$('#opt_cut_off_two').parent().click();
				}, 100);
			}

		});
	</script>
</body>
</html>