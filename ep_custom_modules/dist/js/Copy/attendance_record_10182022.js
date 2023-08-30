var handler = "../dist/handler/handler.php";
$(document).ready(function(){
    $('#slt_data_start_data, #slt_data_end_data').change(function(){
		$('#cont_loading').fadeIn();
		$('#cont_attendance_record').fadeOut();
		$('select, input').attr('disabled',true);
	    $('#t_attendance_record').DataTable().clear().destroy();

		$('#opt_cut_off_one').parent().removeClass('active');
		$('#opt_cut_off_two').parent().removeClass('active');
		$('#opt_cut_off_one').removeClass('active');
		$('#opt_cut_off_one').prop('checked',false);
		$('#opt_cut_off_two').removeClass('active');
		$('#opt_cut_off_two').prop('checked',false);
    	var emp_no = $('#slt_employee_number').val();
    	
	    if(emp_no==''||emp_no==undefined){
       		load_attendance_record();
	    }
	    else{
    		load_attendance_record('1',emp_no);
	    }
    });

    $('#slt_cut_off_month, #slt_cut_off_year').change(function(){
		$('#cont_loading').fadeIn();
		$('#cont_attendance_record').fadeOut();
		$('select, input').attr('disabled',true);
	    $('#t_attendance_record').DataTable().clear().destroy();

    	var emp_no 			= $('#slt_employee_number').val();
		var month 			= $('#slt_cut_off_month').val();
		var year 			= $('#slt_cut_off_year').val();
		var prev_date 		= moment(year+'-'+month+'-01').subtract(1, 'month').format('YYYY-MM-DD');
		var prev_month 		= moment(prev_date).format('MM');
		var prev_year 		= moment(prev_date).format('YYYY');
		var cut_off 		= $('input[name=opt_cut_off]').val();

		switch(cut_off){
			case '1':
				$('#opt_cut_off_one').prop('checked',true);
				$('#opt_cut_off_one').parent().addClass('active');

				$('#opt_cut_off_two').prop('checked',false);
				$('#opt_cut_off_two').parent().removeClass('active');

		    	if(month=='03'){
		    		$('#slt_data_start_data').val(year+'-'+month+'-01');
		    	}
		    	else{
	        		('#slt_data_start_data').val(moment(prev_year+'-'+prev_month+'-30').format('YYYY-MM-DD'));
		    	}

	        	$('#slt_data_end_data').val(moment(year+'-'+month+'-14').format('YYYY-MM-DD'));
				break;
			case '2':
				$('#opt_cut_off_two').prop('checked',true);
				$('#opt_cut_off_two').parent().addClass('active');

				$('#opt_cut_off_one').prop('checked',false);
				$('#opt_cut_off_one').parent().removeClass('active');

	        	$('#slt_data_start_data').val(moment(year+'-'+month+'-15').format('YYYY-MM-DD'));

		    	if(month=='02'){
		    		var max_day = get_days_of_month(month,year);
	        	}
	        	else{
	        		var max_day = '29';
	        	}
		    	$('#slt_data_end_data').val(moment(year+'-'+month+'-'+max_day).format('YYYY-MM-DD'));
				break;
		}

	    if(emp_no==''||emp_no==undefined){
       		load_attendance_record();
	    }
	    else{
    		load_attendance_record('1',emp_no);
	    }
    });

    $('#slt_employee_number').change(function(){
    	var emp_no 			= $('#slt_employee_number').val();
		$('#cont_loading').fadeIn();
		$('#cont_attendance_record').fadeOut();
		$('select, input').attr('disabled',true);
	    $('#t_attendance_record').DataTable().clear().destroy();
    	load_attendance_record('1',emp_no)
    });

	$("#frm_send_complaint").submit(function(e){
		e.preventDefault();
		$('.btn').prop('disabled',true);
		var data = {
			'action'		: 'send_complaint'
		}
		data = $.param(data) + "&" + $(this).serialize();
		$.ajax({
			type 	: "post",
			dataType: "json",
			data 	: data,
			url 	: handler,
			success : function(data){
				console.log(data['sent_message_status']);
				$('#cont_loading').fadeIn();
				$('select, input').attr('disabled',true);
				$('.btn').prop('disabled',false);
			    $('#t_attendance_record').DataTable().clear().destroy();
				load_attendance_record();

				if(data['sent_message_status']=='1'){
					alert('Successfully Sent!');
				}
				else{
					alert('Complaint saved, but message has not been delivered.');
				}


		    	$('#mdl_send_complaint').modal('hide');
			},
			error 	: function(data){
				console.log(data);
				alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
			}
		});
	});

	$('#opt_cut_off_one').parent().click(function(){
		$('#cont_loading').fadeIn();
		$('#cont_attendance_record').fadeOut();
		$('select, input').attr('disabled',true);
	    $('#t_attendance_record').DataTable().clear().destroy();

    	var emp_no 			= $('#slt_employee_number').val();
		var month 			= $('#slt_cut_off_month').val();
		var year 			= $('#slt_cut_off_year').val();
		var prev_date 		= moment(year+'-'+month+'-01').subtract(1, 'month').format('YYYY-MM-DD');
		var prev_month 		= moment(prev_date).format('MM');
		var prev_year 		= moment(prev_date).format('YYYY');

		$('#opt_cut_off_one').prop('checked',true);
		$('#opt_cut_off_one').parent().addClass('active');

		$('#opt_cut_off_two').prop('checked',false);
		$('#opt_cut_off_two').parent().removeClass('active');


    	if(month=='03'){
    		$('#slt_data_start_data').val(year+'-'+month+'-01');
    	}
    	else{
    		$('#slt_data_start_data').val(prev_year+'-'+prev_month+'-30');
    	}

    	$('#slt_data_end_data').val(year+'-'+month+'-14');

	    if(emp_no==''||emp_no==undefined){
       		load_attendance_record();
	    }
	    else{
    		load_attendance_record('1',emp_no);
	    }
	});

	$('#opt_cut_off_two').parent().click(function(){
		$('#cont_loading').fadeIn();
		$('#cont_attendance_record').fadeOut();
		$('select, input').attr('disabled',true);
	    $('#t_attendance_record').DataTable().clear().destroy();

    	var emp_no 			= $('#slt_employee_number').val();
		var month 			= $('#slt_cut_off_month').val();
		var year 			= $('#slt_cut_off_year').val();

		$('#opt_cut_off_two').prop('checked',true);
		$('#opt_cut_off_two').parent().addClass('active');

		$('#opt_cut_off_one').prop('checked',false);
		$('#opt_cut_off_one').parent().removeClass('active');

    	$('#slt_data_start_data').val(moment(year+'-'+month+'-15').format('YYYY-MM-DD'));

    	if(month=='02'){
    		var max_day = get_days_of_month(month,year);
    	}
    	else{
    		var max_day = '29';
    	}
    	$('#slt_data_end_data').val(moment(year+'-'+month+'-'+max_day).format('YYYY-MM-DD'));


	    if(emp_no==''||emp_no==undefined){
       		load_attendance_record();
	    }
	    else{
    		load_attendance_record('1',emp_no);
	    }
	});
});

function load_attendance_record(action_child,emp_no){
    var date_start  = $('#slt_data_start_data').val();
    var date_end    = $('#slt_data_end_data').val();
    var month    	= $('#slt_cut_off_month').val();
    var year    	= $('#slt_cut_off_year').val();
	var prev_date 	= moment(year+'-'+month+'-01').subtract(1, 'month').format('YYYY-MM-DD');
	var prev_month 	= moment(prev_date).format('MM');
	var prev_year 	= moment(prev_date).format('YYYY');
    var cut_off    	= $('input[name=opt_cut_off]:checked').val();
   
    if(cut_off==undefined){
    	cut_off = 0;
    }

    if(action_child==1){
	    var data = {
			'action'		: 'load_attendance_record',
			'date_start'	: date_start,
			'date_end'  	: date_end,
			'action_child'	: action_child,
			'emp_no'		: emp_no,
			'month'			: month,
			'year'			: year,
			'prev_date'		: prev_date,
			'prev_month'	: prev_month,
			'prev_year'		: prev_year,
			'cut_off'		: cut_off
		}

    }
    else{
	    var data = {
			'action'		: 'load_attendance_record',
			'date_start'	: date_start,
			'date_end'  	: date_end,
			'month'			: month,
			'year'			: year,
			'prev_date'		: prev_date,
			'prev_month'	: prev_month,
			'prev_year'		: prev_year,
			'cut_off'		: cut_off
		}
    }

	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
			var htmls = '';
			for(var q = 0;q<data['str'];q++){
				if(q==data['str']-1){
					htmls += '<b>'+data['rc_concern_date'][q]+'</b>.';
				}
				else{
					htmls += '<b>'+data['rc_concern_date'][q]+'</b>, ';
				}
			}

			if(q>0&&q==1){
				var htmlr = '<strong>Unread Messages</strong> You have 1 unread complaint update, dated: ';
				$('#spn-unread-remark-dates').append(htmlr+htmls);
				$('#cont-unread-remark').fadeIn();

			}
			else if(q>0&&q>1){
				var htmlr = '<strong>Unread Messages</strong> You have '+q+' unread complaint updates, dated: ';
				$('#spn-unread-remark-dates').append(htmlr+htmls);
				$('#cont-unread-remark').fadeIn();
			}
			$('#spn_unread_updates').text(data['str']);
		    $('#spn_total_regular_ot').text('');
		    $('#spn_total_sunday_ot').text('');
		    $('#spn_total_special_holiday').text('');
		    $('#spn_total_holiday_ot').text('');
		    $('#spn_total_undertime').text('');
		    $('#spn_total_night_diff').text('');
		    $('#spn_total_absent').text('');
		    $('#spn_total_sl_vl').text('');
		    $('#t_attendance_record tbody').empty();
		    
		    var html = '';
		    for(var i=0;i<data['ctr'];i++){
			    html += '<tr>';
				html += '   <td style="text-align:center;">'+data['date'][i]+'</td>';
				html += '	<td style="text-align:center;">'+data['time_in'][i]+'</td>';
				html += '	<td style="text-align:center;">'+data['time_out'][i]+'</td>';
				if(parseFloat(data['time_rendered'][i])>=9.5){
					html += '	<td style="text-align:right;">'+data['time_rendered'][i]+' <i class="fa fa-plus" style="color:green;"></i></td>';					
				}
				else if(data['time_rendered']>8){
					html += '	<td style="text-align:right;">'+data['time_rendered'][i]+'</td>';	
				}
				else{					
					html += '	<td style="text-align:right;">'+data['time_rendered'][i]+'</td>';	
				}

				if(data['regular_ot'][i]=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['regular_ot'][i]+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['regular_ot'][i]+'</td>';
				}

				if(data['sunday_ot'][i]=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['sunday_ot'][i]+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['sunday_ot'][i]+'</td>';
				}

				if(data['special_holiday_ot'][i]=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['special_holiday_ot'][i]+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['special_holiday_ot'][i]+'</td>';
				}


				if(data['holiday_ot'][i]=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['holiday_ot'][i]+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['holiday_ot'][i]+'</td>';
				}

				if(data['undertime'][i]=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['undertime'][i]+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#f73d3d;color:white;">'+data['undertime'][i]+'</td>';
				}

				if(data['night_diff'][i]=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['night_diff'][i]+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['night_diff'][i]+'</td>';
				}

				if(data['absent'][i]=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['absent'][i]+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#f73d3d;color:white;">'+data['absent'][i]+'</td>';
				}

				if(data['sl_vl'][i]=='0.00'){
					html += '	<td style="text-align:right;" class="bg-fffecc">'+data['sl_vl'][i]+'</td>';
				}
				else{
					html += '	<td style="text-align:right;background-color:#09a036;color:white;">'+data['sl_vl'][i]+'</td>';
				}


				if(data['complaint_active_status'][i]=='0'){
					html += '	<td style="text-align:left;border-bottom:1px solid white;border-top:1px solid white;"><a class="a-add-complaint btn btn-sm btn-primary" style="color:white !important;font-size:70%;cursor:pointer;"><i>Add Complaint?</i></a></td>';
				}
				else{
					if(data['rc_status'][i]=='1'){
						if(data['rc_timekeeper_remarks'][i]==''){
							html += '	<td style="text-align:left;white-space: pre-wrap !important;"><a id="'+data['rc_id'][i]+'" class="a-info-complaint" style="font-size:80%;cursor:pointer;text-decoration:underline;"><i>Complaint is <span style="color:red;">closed</span>. Click this text for more details.</i></a></td>';
						}
						else{
							html += '	<td style="text-align:left;white-space: pre-wrap !important;"><a id="'+data['rc_id'][i]+'" class="a-info-complaint" style="font-size:80%;cursor:pointer;">Finance says:<br><i>'+data['rc_timekeeper_remarks'][i]+'</i></a></td>';
						}
					}
					else if(data['rc_status'][i]=='0'){
						if(data['rc_timekeeper_remarks'][i]==''){
							html += '	<td style="text-align:left;white-space: pre-wrap !important;"><a id="'+data['rc_id'][i]+'" class="a-info-complaint" style="font-size:80%;cursor:pointer;text-decoration:underline;"><i>Click this text for more details.</i></a></td>';
						}
						else{
							html += '	<td style="text-align:left;white-space: pre-wrap !important;"><a id="'+data['rc_id'][i]+'" class="a-info-complaint" style="font-size:80%;cursor:pointer;">Finance says:<br><i>'+data['rc_timekeeper_remarks'][i]+'</i></a></td>';
						}
					}
				}
				html += '</tr>';
		    }
		    
		    $('#spn_total_regular_ot').text(data['total_regular_ot']);
		    $('#spn_total_sunday_ot').text(data['total_sunday_ot']);
		    $('#spn_total_special_holiday').text(data['total_special_holiday_ot']);
		    $('#spn_total_holiday_ot').text(data['total_holiday_ot']);
		    $('#spn_total_undertime').text(data['total_undertime']);
		    $('#spn_total_night_diff').text(data['total_night_diff']);
		    $('#spn_total_absent').text(data['total_absent']);
		    $('#spn_total_sl_vl').text(data['total_sl_vl']);
		    $('#spn_total_lates').text(data['total_lates']);
		    $('#t_attendance_record tbody').append(html);
		    $('select, input').attr('disabled',false);

		    
        	var t_attendance_record = $('#t_attendance_record').DataTable({
        	    "select"        : true,
                "scrollX"		: "auto",
                "scrollY"		: "301px",
                "scrollCollapse": false,
                "paging"		: false,
                "dom"			: "Bfrtip",
                "info"			: false,
  				"ordering"		: false,
  				"responsive"	: false,
        		"order"			: [[ 0, "desc" ]],
                lengthChange	: false,
                "buttons"		: [
                    {extend: "copy", footer: true},
                    {text: 'Excel',
		                action: function ( e, dt, node, config ){
		                    export_attendance_report_excel();
		                }
                	}, 
                    "colvis"
                ],
				"initComplete": function(settings, json){
		    		$('#cont_loading').fadeOut();
		    		$('#cont_attendance_record').fadeIn();
				}
        	});
        	
        	t_attendance_record.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
        	t_attendance_record.columns.adjust();

		    $('.a-add-complaint').click(function(){
		    	var en = $('#hdn_employee_number').val();
			    	var employee_number = $('#hdn_employee_number').val();
			    	var date = $(this).parent().parent().find('td:eq(0) .formatted-date').text();
			    	$('#concern-date').val(date);
			    	$('#mdl_send_complaint').modal('show');
		    });

		    $('.a-info-complaint').click(function(){
		    	var rc_id = $(this).attr('id');
		    	load_complaint(rc_id);

		    });
		},
		error 	: function(data){
			console.log(data);
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}

function export_attendance_report_excel(){
	var emp_no 		= $('#slt_employee_number').val();
    var date_start  = $('#slt_data_start_data').val();
    var date_end    = $('#slt_data_end_data').val();
    var data = {
    	'emp_no'	: emp_no,
		'date_start': date_start,
		'date_end'  : date_end
	}
	data = $.param(data);
	var link = '../dist/generated_reports/excel_export/class/export_attendance_record_report_excel.php?'+data;
    window.open(link);
}

function load_complaint(rc_id){
    var data = {
		'action'		: 'load_complaint',
		'rc_id'			: rc_id
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
			$('input[name=rc_id]').val(data['rc_id']);
			if(data['rc_status']=='0'){
				$('#opt_complaint_active').prop('checked',true);
				$('#opt_complaint_active').parent().addClass('r-bg-color-green');
				$('#opt_complaint_closed').parent().removeClass('r-bg-color-red');
			}
			else if(data['rc_status']=='1'){
				$('#opt_complaint_closed').prop('checked',true);
				$('#opt_complaint_closed').parent().addClass('r-bg-color-red');
				$('#opt_complaint_active').parent().removeClass('r-bg-color-green');
			}

			$('#concern-employee').val(data['rc_username']+' '+data['name']);
			$('#concern-contact').val(data['email']);
			$('#a-concern-contact').attr('href','mailto:'+data['email']);
			$('#concern-details-date').val(data['rc_concern_date']);
			$('#message-text').val(data['rc_message']);
			$('#my-remarks-text').val(data['rc_timekeeper_remarks']);

			$('#t_complaint tbody').empty();
			var html = '';
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
			$('#t_complaint tbody').append(html);

			$('#mdl_complaint_details').modal('show');

			if(data['rc_seen_status']==1){
				var rka = $('#spn_unread_updates').text();
				rka = (Number(rka)-1);
				$('#spn_unread_updates').text(rka);
			}
		},
		error 	: function(data){
			console.log(data);
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}

function get_days_of_month(month,year){
    return new Date(year, month, 0).getDate();
}