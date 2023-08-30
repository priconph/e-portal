var handler = "../dist/handler/handler.php";
$(document).ready(function(){
    $('#slt_pay_period').change(function(){
    	var emp_no = $('#slt_employee_number').val();
		$('#cont_loading').fadeIn();
        $('select').attr('disabled',true);
        
	    if(emp_no==''||emp_no==undefined){
        	load_employee_info();
	    }
	    else{
    		load_employee_info('1',emp_no);
	    }
    });

    $('#btn_send_to_my_email').click(function(){
    	var en = $('#hdn_employee_number').val();
    	$('#mdl_send_to_my_email').modal('show');
	    var data = {
			'action'	    : 'load_email_address'
		}
		data = $.param(data);
		$.ajax({
			type 	: "post",
			dataType: "json",
			data 	: data,
			url 	: handler,
			success : function(data){
				$('input[name=recipient-name]').val(data['email']);
			},
			error 	: function(data){
				alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
			}
		});
    });

    $('#btn_save_as_pdf').click(function(){
    	$(this).attr('disabled',true);
    	var emp_no = $('#slt_employee_number').val();
    		
	    if(emp_no==''||emp_no==undefined){
        	generate_pay_slip_pdf('D');
	    }
	    else{
        	generate_pay_slip_pdf('D',emp_no,'1');
	    }
    	
    	setTimeout(function(){
    		$('#btn_save_as_pdf').attr('disabled',false);
		}, 3000);
    });

    $('#slt_employee_number').change(function(){
    	var emp_no = $('#slt_employee_number').val();
		$('#cont_loading').fadeIn();
        $('select').attr('disabled',true);
        load_latest_pay_period('1',emp_no);
    });

	$("#frm_send_to_my_email").submit(function(e){
		e.preventDefault();
		$('.btn').attr('disabled',true);
		var pay_period = $( "#slt_pay_period option:selected" ).text();
		var ymdhis = generate_pay_slip_pdf('F');
		var data = {
			'action'		: 'send_to_my_email',
			'ymdhis'		: ymdhis,
			'pay_period'	: pay_period
		}
		data = $.param(data) + "&" + $(this).serialize();
		$.ajax({
			type 	: "post",
			dataType: "json",
			data 	: data,
			url 	: handler,
			success : function(data){
				console.log(data);
				alert('Successfully Sent!');
		    	$('#mdl_send_to_my_email').modal('hide');
				$('.btn').attr('disabled',false);
			},
			error 	: function(data){
				console.log(data);
				alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
			}
		});
	});
});

function load_latest_pay_period(action_child,emp_no){
    if(action_child==1){
		var data = {
			'action'		: 'load_latest_pay_period',
			'emp_no'      	: emp_no,
			'action_child'	: action_child
		}

    }
    else{
	    var data = {
			'action'	: 'load_latest_pay_period'
		}
    }

	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
		    $('#slt_pay_period').empty();
		    var html = '';
		    for(var i=0;i<data['ctr'];i++){
		        html += '<option value="'+data['Pkid'][i]+'">'+data['Description'][i]+'</option>';
		    }
		    $('#slt_pay_period').append(html);

		    if(action_child==1){
    			var emp_no = $('#slt_employee_number').val();
		   		load_employee_info('1',emp_no);

		    }
		    else{
		    	load_employee_info();
		    }

		},
		error 	: function(data){
			console.log(data);
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}

function load_employee_info(action_child,emp_no){
    var pay_period = $('#slt_pay_period').val();


    if(action_child==1){
	    var data = {
			'action'	    : 'load_employee_info',
			'emp_no'      	: emp_no,
			'pay_period'    : pay_period,
			'action_child'	: action_child
		}

    }
    else{
	    var data = {
			'action'	    : 'load_employee_info',
			'pay_period'    : pay_period
		}
    }

	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
			console.log(data);
		    if(data['detector']=='1'||data['ctr']=='0'){
				$('#cont_loading').fadeOut();
		    	$('#cont_pay_slip_data').fadeOut();
		    	$('#cont_pay_slip_no_data').fadeIn();
		    	$('.btn').attr('disabled',true);
		    }
		    else{
		    	$('#cont_pay_slip_no_data').fadeOut();
		    	$('#cont_pay_slip_data').fadeIn();

			    $('#spn_ctpa_prod_allow').text('0.00');
			    $('#spn_socio_eco_allow').text('0.00');
			    $('#spn_other_earnings').text('0.00');
			    $('#spn_bonus_rice_subsidy').text(numeral((data['field_name']['BONUSX'])+(data['field_name']['RICE'])).format('0,0.00'));
			    $('#spn_13th_month_pay').text(numeral(data['field_name']['MO13P']).format('0,0.00'));
			    $('#spn_allowance').text(numeral(data['field_name']['ALLOW']).format('0,0.00'));
			    $('#spn_regular_pay').text(numeral(data['field_name']['BASIC']).format('0,0.00'));                        								
			    $('#spn_less_absences_undertime').text(numeral(data['field_name']['LVUTAM']).format('0,0.00'));
			    $('#spn_gross_pay').text(numeral(data['field_name']['GROSS']).format('0,0.00'));
			    $('#spn_philhealth_premiums').text(numeral(data['field_name']['MEDPRE']).format('0,0.00'));
			    $('#spn_pagibig_premiums').text(numeral(data['field_name']['PBGPRE']).format('0,0.00'));
			    $('#spn_pagibig_loan').text(numeral(data['field_name']['PBGSL']).format('0,0.00'));
			    $('#spn_with_tax').text(numeral(data['field_name']['WTAX']).format('0,0.00'));
			    $('#spn_sss_premiums').text(numeral(data['field_name']['SSSPRE']).format('0,0.00'));
			    $('#spn_net_pay').text(numeral(data['field_name']['NETPAY']).format('0,0.00'));
			    $('#spn_absences').text(numeral(data['field_name']['ABSENC']).format('0,0.00'));
			    $('#spn_undertime').text(numeral(data['field_name']['UNDERC']).format('0,0.00'));
			    $('#spn_ecola').text(numeral(data['field_name']['ECOLA']).format('0,0.00'));
			    $('#spn_overtime_night_diff').text(numeral(data['field_name']['OTTAMT']).format('0,0.00'));
			    $('#spn_reg_holiday').text(numeral(data['field_name']['RHH']).format('0,0.00'));
			    $('#spn_reg_holiday_excess_of_8_hrs').text(numeral(data['field_name']['RH8H']).format('0,0.00'));
			    $('#spn_reg_hol_sun_ot').text(numeral(data['field_name']['RHRDH']).format('0,0.00'));
			    $('#spn_reg_hol_sun_ot_excess_of_8_hrs').text(numeral(data['field_name']['RHRD8H']).format('0,0.00'));
			    $('#spn_special_holiday_excess_of_8_hrs').text(numeral(data['field_name']['SH8H']).format('0,0.00'));
			    $('#spn_spl_hol_sun_ot').text(numeral(data['field_name']['SHRDH']).format('0,0.00'));
			    $('#spn_spl_hol_sun_ot_excess_of_8_hrs').text(numeral(data['field_name']['SHRD8H']).format('0,0.00'));

			    //Nessa 11182020
			    if (data['PayType'] == '2' || data['PayType'] == '4'){
				    $('#spn_shutdown_leave').text(numeral((data['field_name']['SDOWN']/=120)).format('0,0.00'));
			    }else{
				    $('#spn_shutdown_leave').text(numeral((data['field_name']['SDOWN']/=100)).format('0,0.00'));
			    }

			    $('#spn_sl_vl_bl_sd_sdl').text(numeral(data['field_name']['LVPTAM']).format('0,0.00'));
			    $('#spn_salary_adjustments').text(numeral(data['field_name']['RETBAS']).format('0,0.00'));
			    $('#spn_hipar').text(numeral(data['field_name']['HMOEE']).format('0,0.00'));
			    $('#spn_pemco').text(numeral(data['field_name']['PEMCO']).format('0,0.00'));
			    $('#spn_regular_ot').text(numeral(data['field_name']['OTREGH']).format('0,0.00'));
			    $('#spn_sunday_ot').text(numeral(data['field_name']['RDH']).format('0,0.00'));
			    $('#spn_special_holiday').text(numeral(data['field_name']['SHH']).format('0,0.00'));
			    $('#spn_bdl_pl_ml_bl').text(numeral(data['field_name']['BDAYC']).format('0,0.00'));

			    // $('#spn_sss_loan').text(numeral(data['field_name']['SSSAL']).format('0,0.00'));
			    //-12052020 Nessa
				var sss_loans = parseFloat(!isNaN(data['field_name']['SSSAL'])?data['field_name']['SSSAL']:0)+parseFloat(!isNaN(data['field_name']['CAMLO'])?data['field_name']['CAMLO']:0);
			    $('#spn_sss_loan').text(numeral((sss_loans)).format('0,0.00'));
			    
			    $('#spn_sick_leave').text(numeral(data['field_name']['SICKC']).format('0,0.00'));
			    $('#spn_vacation_leave').text(numeral(data['field_name']['VACATC']).format('0,0.00'));
			    $('#spn_night_diff').text(numeral(data['field_name']['REGNDH']).format('0,0.00'));
			    $('#spn_sunday_ot_excess_of_8_hrs').text(numeral(data['field_name']['RD8H']).format('0,0.00'));
			    var advances_other_deductions = parseFloat(data['field_name']['ADVAN'])+parseFloat(data['field_name']['OTHER']);
			    $('#spn_advances_other_deductions').text(numeral((advances_other_deductions)).format('0,0.00'));
			    $('#spn_tax_adjustments').text(numeral(data['field_name']['TAXADJ']).format('0,0.00'));
			    $('#spn_exemption_code').text(data['ExemptBaseCode']);
			    $('#spn_ytd_gross_income').text(numeral(data['sum_gross']).format('0,0.00'));

			    if(data['sum_tax']>0){
			    	$('#spn_ytd_tax_witheld').text(numeral(data['sum_tax']).format('0,0.00'));
			    }
			    else{
			    	$('#spn_ytd_tax_witheld').text(numeral('0.00').format('0,0.00'));
			    }


			    $('#spn_forecast_earnings').text(numeral(data['forecast_earnings']).format('0,0.00'));
			    $('#spn_less_ytd_tax_exemption').text(numeral(data['sum_prem']).format('0,0.00'));
			    var ytd_taxable_earnings_forecast = (parseFloat(data['sum_gross'])+parseFloat(data['forecast_earnings']))-parseFloat(data['sum_prem']);
			    $('#spn_ytd_taxable_earnings_forecast').text(numeral((ytd_taxable_earnings_forecast)).format('0,0.00'));

				$('#cont_loading').fadeOut();	
		    	$('.btn').attr('disabled',false);	    	
		    }

        	$('select').attr('disabled',false);
		},
		error 	: function(data){
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}

function generate_pay_slip_pdf(file_type,emp_no,action_child){
    var pay_period = $('#slt_pay_period').val();
    var ymdhis = '';

    if(action_child==1){
	    var data = {
			'action'	    : 'load_employee_info',
			'emp_no'      	: emp_no,
			'pay_period'    : pay_period,
			'action_child'	: action_child
		}

    }
    else{
	    var data = {
			'action'	    : 'load_employee_info',
			'pay_period'    : pay_period
		}
    }

	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		async 	: false,
		success : function(data){
			
            $('<input />').attr('type', 'hidden').attr('name', "spn_regular_pay").attr('value', numeral(data['field_name']['BASIC']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_less_absences_undertime").attr('value', numeral(data['field_name']['LVUTAM']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_gross_pay").attr('value', numeral(data['field_name']['GROSS']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_philhealth_premiums").attr('value', numeral(data['field_name']['MEDPRE']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_with_tax").attr('value', numeral(data['field_name']['WTAX']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_sss_premiums").attr('value', numeral(data['field_name']['SSSPRE']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_net_pay").attr('value', numeral(data['field_name']['NETPAY']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_absences").attr('value', numeral(data['field_name']['ABSENC']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_undertime").attr('value', numeral(data['field_name']['UNDERC']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_ecola").attr('value', numeral(data['field_name']['ECOLA']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_overtime_night_diff").attr('value', numeral(data['field_name']['OTTAMT']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_sl_vl_bl_sd_sdl").attr('value', numeral(data['field_name']['LVPTAM']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_ctpa_prod_allow").attr('value', '0.00').appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_socio_eco_allow").attr('value', '0.00').appendTo('#iframeform');
			    
            $('<input />').attr('type', 'hidden').attr('name', "spn_other_earnings").attr('value', '0.00').appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_bonus_rice_subsidy").attr('value', numeral((data['field_name']['BONUSX'])+(data['field_name']['RICE'])).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_13th_month_pay").attr('value', numeral(data['field_name']['MO13P']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_allowance").attr('value', numeral(data['field_name']['ALLOW']).format('0,0.00')).appendTo('#iframeform');

            $('<input />').attr('type', 'hidden').attr('name', "spn_salary_adjustments").attr('value', numeral(data['field_name']['RETBAS']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_hipar").attr('value', numeral(data['field_name']['HMOEE']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_pemco").attr('value', numeral(data['field_name']['PEMCO']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_regular_ot").attr('value', numeral(data['field_name']['OTREGH']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_sunday_ot").attr('value', numeral(data['field_name']['RDH']).format('0,0.00')).appendTo('#iframeform');
            // $('<input />').attr('type', 'hidden').attr('name', "spn_sss_loan").attr('value', numeral(data['field_name']['SSSAL']).format('0,0.00')).appendTo('#iframeform');

            //10252021
			var sss_loans = parseFloat(!isNaN(data['field_name']['SSSAL'])?data['field_name']['SSSAL']:0)+parseFloat(!isNaN(data['field_name']['CAMLO'])?data['field_name']['CAMLO']:0);
            $('<input />').attr('type', 'hidden').attr('name', "spn_sss_loan").attr('value', numeral((sss_loans)).format('0,0.00')).appendTo('#iframeform');

            $('<input />').attr('type', 'hidden').attr('name', "spn_sick_leave").attr('value', numeral(data['field_name']['SICKC']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_vacation_leave").attr('value', numeral(data['field_name']['VACATC']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_night_diff").attr('value', numeral(data['field_name']['REGNDH']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_sunday_ot_excess_of_8_hrs").attr('value', numeral(data['field_name']['RD8H']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_advances_other_deductions").attr('value', numeral(((Number(data['field_name']['ADVAN']))+(Number(data['field_name']['OTHER'])))).format('0,0.00')).appendTo('#iframeform');

            $('<input />').attr('type', 'hidden').attr('name', "spn_pagibig_premiums").attr('value', numeral(data['field_name']['PBGPRE']).format('0,0.00')).appendTo('#iframeform');   
            $('<input />').attr('type', 'hidden').attr('name', "spn_pagibig_loan").attr('value', numeral(data['field_name']['PBGSL']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_reg_holiday").attr('value', numeral(data['field_name']['RHH']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_reg_holiday_excess_of_8_hrs").attr('value', numeral(data['field_name']['RH8H']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_reg_hol_sun_ot").attr('value', numeral(data['field_name']['RHRDH']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_reg_hol_sun_ot_excess_of_8_hrs").attr('value', numeral(data['field_name']['RHRD8H']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_special_holiday_excess_of_8_hrs").attr('value', numeral(data['field_name']['SH8H']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_spl_hol_sun_ot").attr('value', numeral(data['field_name']['SHRDH']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_spl_hol_sun_ot_excess_of_8_hrs").attr('value', numeral(data['field_name']['SHRD8H']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_shutdown_leave").attr('value', numeral((data['field_name']['SDOWN']/=80)).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_special_holiday").attr('value', numeral(data['field_name']['SHH']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_bdl_pl_ml_bl").attr('value', numeral(data['field_name']['BDAYC']).format('0,0.00')).appendTo('#iframeform');

			var advances_other_deductions = parseFloat(data['field_name']['ADVAN'])+parseFloat(data['field_name']['OTHER']);
            $('<input />').attr('type', 'hidden').attr('name', "spn_advances_other_deductions").attr('value', numeral(advances_other_deductions).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_tax_adjustments").attr('value', numeral(data['field_name']['TAXADJ']).format('0,0.00')).appendTo('#iframeform');
            
            $('<input />').attr('type', 'hidden').attr('name', "spn_exemption_code").attr('value', data['ExemptBaseCode']).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_ytd_gross_income").attr('value', numeral(data['sum_gross']).format('0,0.00')).appendTo('#iframeform');

		    if(data['sum_tax']>0){
           		$('<input />').attr('type', 'hidden').attr('name', "spn_ytd_tax_witheld").attr('value', numeral(data['sum_tax']).format('0,0.00')).appendTo('#iframeform');
		    }
		    else{
            	$('<input />').attr('type', 'hidden').attr('name', "spn_ytd_tax_witheld").attr('value', numeral('0.00').format('0,0.00')).appendTo('#iframeform');
		    }

            $('<input />').attr('type', 'hidden').attr('name', "spn_forecast_earnings").attr('value', numeral(data['forecast_earnings']).format('0,0.00')).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "spn_less_ytd_tax_exemption").attr('value', numeral(data['sum_prem']).format('0,0.00')).appendTo('#iframeform');

			var ytd_taxable_earnings_forecast = (parseFloat(data['sum_gross'])+parseFloat(data['forecast_earnings']))-parseFloat(data['sum_prem']);
            $('<input />').attr('type', 'hidden').attr('name', "spn_ytd_taxable_earnings_forecast").attr('value', numeral(ytd_taxable_earnings_forecast).format('0,0.00')).appendTo('#iframeform');

            $('<input />').attr('type', 'hidden').attr('name', "employee_number").attr('value', data['EmpNo']).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "employee_name").attr('value', data['LastName']+', '+data['FirstName']).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "department").attr('value', data['Department']).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "description").attr('value', data['Description']).appendTo('#iframeform');

            $('<input />').attr('type', 'hidden').attr('name', "ymdhis").attr('value', data['ymdhis']).appendTo('#iframeform');
            $('<input />').attr('type', 'hidden').attr('name', "file_type").attr('value', file_type).appendTo('#iframeform');

            $('#iframeform').submit();

			ymdhis = JSON.parse(data['ymdhis']);
		},
		error 	: function(data){
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205 or send an e-mail. Thank you!'); 
		}
	});
	return ymdhis;
}