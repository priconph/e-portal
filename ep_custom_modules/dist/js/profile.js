var handler = "../dist/handler/handler.php";
$(document).ready(function(){
    $('#btn_edit_profile').click(function(){
    	window.top.location.href = "http://192.168.3.235/e-portal/index.php/edit";
    });
    

    $('#btn_change_data_last_30_leaves').click(function(){
    	$('#mdl_change_data_last_30_leaves').modal('show');
    });
});

function load_profile(){
    var data = {
		'action'	    : 'load_profile'
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){

			$('#btn_user_manual').click(function(){
				window.open("http://rapid/e-portal/ep_custom_modules/dist/files/USER MANUAL_Complaints&MEMO_2023.pdf", '_blank');
			});
			//-02092022
			$('#btn_form_2316').click(function(){

				// if ( 
				// 	data['EmpNo'] == '4771'||
				// 	data['EmpNo'] == 'M001'||
				// 	data['EmpNo'] == '1810'||

				// 	data['EmpNo'] == 'J004'||
				// 	data['EmpNo'] == 'Q111'||
				// 	data['EmpNo'] == 'Q004'||

				// 	data['EmpNo'] == '1195'|| 
				// 	data['EmpNo'] == '1288'|| 
				// 	data['EmpNo'] == '1436'|| 
				// 	data['EmpNo'] == '1923'|| 
				// 	data['EmpNo'] == '1974'|| 
				// 	data['EmpNo'] == '2055'|| 
				// 	data['EmpNo'] == '2063'|| 
				// 	data['EmpNo'] == '2137'|| 
				// 	data['EmpNo'] == '2364'|| 
				// 	data['EmpNo'] == '3802'|| 
				// 	data['EmpNo'] == '4205'|| 
				// 	data['EmpNo'] == '4743'|| 
				// 	data['EmpNo'] == '7651'|| 
				// 	data['EmpNo'] == '7894'|| 
				// 	data['EmpNo'] == '8909'|| 
				// 	data['EmpNo'] == 'A279'|| 
				// 	data['EmpNo'] == 'B101'|| 
				// 	data['EmpNo'] == 'B158'|| 
				// 	data['EmpNo'] == 'M011'|| 
				// 	data['EmpNo'] == 'N010'|| 
				// 	data['EmpNo'] == 'O088'|| 
				// 	data['EmpNo'] == 'O103'|| 
				// 	data['EmpNo'] == 'O104'|| 
				// 	data['EmpNo'] == 'Q008'|| 
				// 	data['EmpNo'] == 'R003'
				// 	){
				// 	alert('Sorry, your ITR is not yet available. Please call local 222 and look for Ms. Noreen. Thank you!');
				// }else{
		    		window.open("http://systemone/bir/bir_form.php?x="+data['EmpNo'], '_blank');

				// }
		    	
		    });


			if(data['EmpNo']!==undefined){
				$('#usr_profile_picture').attr('src','//192.168.3.240/otks_main/otks_attendance/pmi_pictures/'+data['EmpNo']+'.jpg');
			}

			$('#usr_name').text(data['FirstName']+' '+data['LastName']);
			$('#usr_employee_number').text(data['EmpNo']);
			$('#usr_position').text(data['Position']);
			$('#usr_section_department').text(data['Section']);
			
			var age = compute_age(data['DateHired']);
			$('#usr_date_hired').empty();
			$('#usr_date_hired').append('Employee since '+data['DateHired']+'<br>'+age+'<br>');
			
			/* Original Code */
			/*if(data['AccountNumber'] == null ){
				$('#usr_rcbcaccountnum').append('RCBC Account #: <b><i>Not Available </i></b>');
			}else{
				$('#usr_rcbcaccountnum').append('RCBC Account #: <b>'+data['AccountNumber']+'</b>');
			}*/

			if(data['account_no'] == null ){
				$('#usr_rcbcaccountnum').append('RCBC Account #: <b><i>Not Available </i></b>');
			}else{
				$('#usr_rcbcaccountnum').append('RCBC Account #: <b>'+data['account_no']+'</b>');
			}
			
			//-BDay
			var today = new Date();
			var dd = String(today.getDate()).padStart(2, '0');
			var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
			var yyyy = today.getFullYear();
			today = yyyy + '-' + mm + '-' + dd;

			if ( data['DOBirth'] == today ){
				$('#usr_birthday').append('HAPPY BIRTHDAY!!');
			}


			$('#td_previous_balance_vl').text(data['previous_balance_VL']);
			$('#td_previous_balance_sl').text(data['previous_balance_SL']);
			$('#td_credit_vl').text(data['credit_VL']);
			$('#td_credit_sl').text(data['credit_SL']);
			$('#td_current_avail_vl').text(data['current_avail_VL']);
			$('#td_current_avail_sl').text(data['current_avail_SL']);
			$('#td_total_vl_used').text(data['total_VL_used']);
			$('#td_total_sl_used').text(data['total_SL_used']);
			$('#td_total_vl_balance').text(data['total_VL_balance']);
			$('#td_total_sl_balance').text(data['total_SL_balance']);

			$('#t_my_leaves tbody').empty();
			var html 	= '';
			var j 		= 1;
			if(data['ctr_my_leaves']=='0'){
					html += '<tr style="font-size:120%;">';
					html += '	<td colspan="5" class="text-center border-0"><br><br><i class="fa fa-exclamation-triangle text-danger"></i> <span class="text-light">No Data</span></td>';
					html += '</tr>';
					$('#t_my_leaves').parent().removeClass('bg-white');
					$('#t_my_leaves').parent().addClass('bg-secondary');
			}
			else{
				for(var i = 0;i<data['ctr_my_leaves'];i++){
					html += '<tr>';
					html += '	<td style="font-weight:bold;">'+j+'</td>';
					html += '	<td>'+data['date_absent_from'][i]+'</td>';
					html += '	<td>'+data['leave_code'][i]+'</td>';
					html += '	<td style="color:#dc3545;">'+data['no_of_days'][i]+'</td>';
					html += '	<td style="color:#28a745;">'+data['no_of_paid'][i]+'</td>';
					html += '</tr>';
					j++;
				}				
			}
			$('#t_my_leaves tbody').append(html);

			$('#t_my_complaints tbody').empty();
			var html 	= '';
			var j 		= 1;

			// if(data['ctr_overtime_requests']=='0'){
			if(data['ctr_my_complaints']=='0'){
					html += '<tr style="font-size:120%;">';
					html += '	<td colspan="5" class="text-center border-0"><br><br><i class="fa fa-exclamation-triangle text-danger"></i> <span class="text-light">No Data</span></td>';
					html += '</tr>';
					$('#t_my_complaints').parent().removeClass('bg-white');
					$('#t_my_complaints').parent().addClass('bg-secondary');
			}
			else{
				for(var i = 0;i<data['ctr_my_complaints'];i++){
					html += '<tr>';
					html += '	<td style="font-weight:bold;">'+j+'</td>';
					html += '	<td>'+data['rc_concern_date'][i]+'</td>';

					switch(data['rc_status'][i]){
						case '0':
							html += '	<td style="color:#dc3545;">Active</td>';
							break;
						case '1':
							html += '	<td style="color:#28a745;">Closed</td>';
							break;
					}
					
					html += '	<td style="width:37%;">'+data['rc_message'][i]+'</td>';
					
					if(data['rc_timekeeper_remarks'][i]==''){
						html += '	<td style="width:37%;">No message found</td>';
					}
					else{
						html += '	<td style="width:37%;">'+data['rc_timekeeper_remarks'][i]+'</td>';
					}
					
					html += '	<td style="font-size:.8em;text-align:right;color:#6c757d;">'+data['x_ago'][i]+'</td>';
					html += '</tr>';
					j++;
				}	
			}

			$('#t_my_complaints tbody').append(html);

			$('#t_overtime_requests tbody').empty();
			var html 	= '';
			var j 		= 1;

			if(data['ctr_overtime_requests']=='0'){
					html += '<tr style="font-size:120%;">';
					html += '	<td colspan="5" class="text-center border-0"><br><br><i class="fa fa-exclamation-triangle text-danger"></i> <span class="text-light">No Data</span></td>';
					html += '</tr>';
					$('#t_overtime_requests').parent().removeClass('bg-white');
					$('#t_overtime_requests').parent().addClass('bg-secondary');
			}
			else{
				for(var i = 0;i<data['ctr_overtime_requests'];i++){
					html += '<tr>';
					html += '	<td style="font-weight:bold;">'+j+'</td>';
					html += '	<td>'+data['ot_date'][i]+'</td>';

					switch(data['req_status'][i]){
						case '1':
							html += '	<td style="color:#dc3545;">Pending</td>';
							break;
						case '2':
							html += '	<td style="color:#28a745;">Approved</td>';
							break;
						default:
							html += '	<td></td>';
							break;
					}

					html += '	<td>'+data['filed_ot'][i]+'</td>';
					html += '</tr>';
					j++;
				}			
			}
			$('#t_overtime_requests tbody').append(html);
		},
		error 	: function(data){
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}

function compute_age(date){
    var age 		= '';
    var date 		= new Date(date);
    var today		= new Date();
    var diff 		= today.getTime() - date.getTime();
    var oneDay 		= 1000*60*60*24;
    var oneMonth 	= oneDay * (365.25/12);
    var oneyear 	= oneDay * 365.25;
    
    var y 			= Math.floor(diff/oneyear);
    var m 			= Math.floor((diff/oneMonth)%12);
    var d 			= (Math.ceil((diff/oneDay)%(365.25/12))-1);


    if(y>1) age += y+' years ';
    else if(y==1) age += y+' year ';

    if(m>1) age += m+' months ';
    else if(m==1) age += m+' month ';

    if(d>1) age += d+' days';
    else if(d==1) age += d+' day';

    return age;
}

function image_error(){
	$('#usr_profile_picture').attr('src','../../images/default_profile_pic.jpg');
}