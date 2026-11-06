var handler = "../dist/handler/handler.php";
$(document).ready(function(){
    $('input[name=opt_complaint_app_options]').change(function(){
    	var selected_option = $('input[name=opt_complaint_app_options]:checked').val();

    	if(selected_option=='2'){
			$('#opt_complaint_approved').parent().addClass('r-bg-color-green');
			$('#opt_complaint_disapproved').parent().removeClass('r-bg-color-red');
		}
		else if(selected_option=='3'){
			$('#opt_complaint_disapproved').parent().addClass('r-bg-color-red');
			$('#opt_complaint_approved').parent().removeClass('r-bg-color-green');
    	}
    });

    $('#frm_complaint_details_approval').submit(function(e){
		e.preventDefault();
		var data = {
			'action'		: 'update_complaint_approval'
		}
		data = $.param(data) + "&" + $(this).serialize();
		// alert(data)
		$.ajax({
			type 	: "post",
			dataType: "json",
			data 	: data,
			url 	: handler,
			success : function(data){
		    	console.log(data['mail_status']);

		    	if(data['mail_status'] == '1'){
					alert('Successfully Updated!');
					load_complaints_approval();
					$('#mdl_complaint_details').modal('hide');
				}

				if (data['msg'] == 'no_remarks'){
					alert('Pls. Input Approval Remarks!');
				}

				if (data['msg1'] == 'no_status'){
					alert('Pls. Select status!');
				}

			},
			error 	: function(data){
				console.log(data);
				alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
			}
		});
    });

    $('#li-active-complaints').click(function(){
    	$('#hdn-status-to-view').val('0');
    	$('#li-active-complaints').addClass('active');
    	$('#li-closed-complaints').removeClass('active');
    	load_complaints_approval();
    });

    $('#li-closed-complaints').click(function(){
    	$('#hdn-status-to-view').val('1');
    	$('#li-closed-complaints').addClass('active');
    	$('#li-active-complaints').removeClass('active');
    	load_complaints_approval();
    });
});

function load_complaints_approval(){
	var status 		= Number($('#hdn-status-to-view').val());
	var approver 	= $('#hdn-approver').val();
    var data = {
		'action'		: 'load_complaints_approval',
		'status'		: status,
		'approver'		: approver
	}

	data = $.param(data);
	// alert(data)
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
			var html = '';
			$('#ul_complaints').empty();
			for(var i=0;i<data['ctr'];i++){
				
				if (data['rc_approval_status'][i] == 1){
					var app_stat = '<span class="text-info"> PENDING </span>';
				}else if (data['rc_approval_status'][i] == 2){
					var app_stat = '<span class="text-success"> APPROVED by '+data['app_name'][i]+'</span>';
				}else if (data['rc_approval_status'][i] == 3){
					var app_stat = '<span class="text-danger"> DISAPPROVED by '+data['app_name'][i]+'</span>';
				}else{
					var app_stat = '---';
				}

				html += '<li class="media my-1 border-bottom-idle pointer item-complaint" id="'+data['rc_id'][i]+'">';
				html += '	<img class="mr-3 rounded-circle complainant-picture" id="rc_user_image_'+i+'" onerror="image_error('+i+')" src="//192.168.3.240/otks_main/otks_attendance/pmi_pictures/'+data['rc_username'][i]+'.jpg">';
				html += '   <div class="media-body">';
				html += '      Approval Status: '+app_stat+'<br>';
				html += '      <h5 class="mt-0 mb-1">'+data['name'][i]+' <small class="text-secondary"><i>'+data['rc_username'][i]+'</i></small></h5>';
				html += '      Date: <span class="text-primary">'+data['rc_concern_date'][i]+'</span><br>';
				html += '      Message: <span class="text-primary">'+data['rc_message'][i]+'</span><br>';
				html += '    </div>';
				html += '    &nbsp;<small class="text-muted">'+data['x_ago'][i]+'</small>';
				html += '</li>';

				var rc_approver = data['rc_approver'][i];
			}

		
			var count_stat = data['count_status'];
			var count_close_stat = data['count_close_status'];

			$('#spn_active_complaints').text(Number(count_stat));
			$('#spn_closed_complaints').text(Number(count_close_stat));
		

			// if(Number(data['status'])==0){
			// 	$('#spn_active_complaints').text(i);
			// }
			// else if(data['status']==1){

			// }

			$('#ul_complaints').append(html);

			$('.item-complaint').click(function(){
				var rc_id = $(this).attr('id');
				load_complaints_approval_details(rc_id);
			});
		},
		error 	: function(data){
			console.log(data);
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}

function load_complaints_approval_details(rc_id){
    var data = {
		'action'		: 'load_complaints_approval_details',
		'user'			: '1',
		'rc_id'			: rc_id
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
			$('#a-concern-employee-picture').attr('src','//192.168.3.240/otks_main/otks_attendance/pmi_pictures/'+data['rc_username']+'.jpg');
			$('input[name=rc_id]').val(data['rc_id']);

			// alert(data['rc_approval_status'])
			if(data['rc_approval_status']=='2'){
				$('#opt_complaint_approved').prop('checked',true);
				$('#opt_complaint_approved').parent().addClass('r-bg-color-green');
				$('#opt_complaint_disapproved').parent().removeClass('r-bg-color-red');
			}
			else if(data['rc_approval_status']=='3' || data['rc_approval_status']=='3'){
				$('#opt_complaint_disapproved').prop('checked',true);
				$('#opt_complaint_disapproved').parent().addClass('r-bg-color-red');
				$('#opt_complaint_approved').parent().removeClass('r-bg-color-green');
			}

			$('#concern-employee').val(data['rc_username']+' '+data['name']);
			$('#concern-contact').val(data['email']);
			$('#a-concern-contact').attr('href','mailto:'+data['email']);
			$('#concern-date').val(data['rc_concern_date']);
			$('#message-text').val(data['rc_message']);
			$('textarea[name=my-remarks-text]').val(data['rc_approval_remarks']);

			switch(data['rc_category_type']){
				case '0':
					$('#concern-type').val('Others');
					break;
				case '1':
					$('#concern-type').val('Time In/Out');
					break;
				case '2':
					$('#concern-type').val('Overtime');
					break;
				case '3':
					$('#concern-type').val('Leave');
					break;
				case '4':
					$('#concern-type').val('Offset')
					break;
				default:
					break;
			}

			$('#concern-attachment').attr('href', `../dist/uploads/complaint_attachments/${data['rc_attachment']}`);
			$('#concern-attachment').html(data['rc_attachment_filename']);

			$('#t_complaint tbody').empty();
			var html = '';
			if(data['time_in']==undefined){
			    html += '<tr>';
			    html += '<td colspan="11" style="text-align:center;">No data is available for this date</td>';
				html += '</tr>';
			}
			else{
			    html += '<tr>';
				html += '	<td style="text-align:center;">'+data['time_in']+'</td>';
				html += '	<td style="text-align:center;">'+data['time_out']+'</td>';
				if(parseFloat(data['time_rendered'])>=9.5){
					html += '	<td style="text-align:right;">'+data['time_rendered']+' <i class="fa fa-plus" style="color:green;"></i></td>';					
				}
				else if(data['time_rendered']>8){
					html += '	<td style="text-align:right;">'+data['time_rendered']+'</td>';	
				}
				else{					
					html += '	<td style="text-align:right;">'+data['time_rendered']+'</td>';	
				}

				if(data['regular_ot']=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['regular_ot']+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['regular_ot']+'</td>';
				}

				if(data['sunday_ot']=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['sunday_ot']+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['sunday_ot']+'</td>';
				}

				if(data['special_holiday_ot']=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['special_holiday_ot']+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['special_holiday_ot']+'</td>';
				}

				if(data['holiday_ot']=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['holiday_ot']+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['holiday_ot']+'</td>';
				}

				if(data['undertime']=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['undertime']+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#f73d3d;color:white;">'+data['undertime']+'</td>';
				}

				if(data['night_diff']=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['night_diff']+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['night_diff']+'</td>';
				}

				if(data['absent']=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['absent']+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#f73d3d;color:white;">'+data['absent']+'</td>';
				}

				if(data['sl_vl']=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['sl_vl']+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['sl_vl']+'</td>';
				}
				html += '</tr>';
			}
			$('#t_complaint tbody').append(html);

			$('#mdl_complaint_details').modal('show');

			var $rc_id = data['rc_id'];
			if (data['rc_approval_status'] == '0' || data['rc_approval_status'] == '2' || data['rc_approval_status'] == '3'){
				$("#submit_btn").hide();
			}else{
				$("#submit_btn").show();
			}
		},
		error 	: function(data){
			console.log(data);
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}

function image_error(id){
	// var id = $(this).attr('id');
	// alert(id);
	$('#rc_user_image_'+id).attr('src','../../images/default_profile_pic.jpg');
}

function certain_image_error(){
	$('#a-concern-employee-picture').attr('src','../../images/default_profile_pic.jpg');
}
