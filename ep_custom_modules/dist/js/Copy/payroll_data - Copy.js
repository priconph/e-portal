var handler = "../dist/handler/handler.php";
$(document).ready(function(){
	$('#t_pay_slip_records tbody').on('dblclick','tr',function(){
		var description 	= $(this).find('td:eq(0)').text();
    	var pay_period 		= $(this).attr('id');
    	$('#slt_pay_period').val(pay_period);
    	$('#lbl_description').text(description+':');
		$('#cont_pay_slip_records').css('display','none');
		var data = {
			'action'		: 'load_pay_slip_records_data',
			'pay_period'	: pay_period
		}
		data = $.param(data);
		$.ajax({
			type 	: "post",
			dataType: "json",
			data 	: data,
			url 	: handler,
			success : function(data){
				$('#cont_pay_slip_data').fadeIn();
				var html = '';
				$('#employees').empty();
				for(var i=0;i<data['ctr'];i++){
					html += '<option value="'+data['EmpNo'][i]+'">'+data['FirstName'][i]+' '+data['LastName'][i]+'</option>';
				}
				$('#slt_employee_number').val(data['EmpNo'][0]);
				$('#employees').append(html);

				load_employee_info();

			},
			error 	: function(data){
				console.log(data);
				alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
			}
		});
    });

	$('#btn_go_to_main_table').click(function(){;
		$('#cont_pay_slip_data').css('display','none');
		$('#cont_pay_slip_records').fadeIn();
	});

	$("#t_pay_slip_records tbody").on('click', '.a_post_pay_slip', function(){
    	var pay_period = $(this).parent().parent().attr('id');
		var confirmation = confirm("Are you sure you want to proceed?");
		if(confirmation==true){
			var data = {
				'action'		: 'post_pay_slip_data',
				'pay_period' 	: pay_period
			}
			data = $.param(data);
			$.ajax({
				type 	: "post",
				dataType: "json",
				data 	: data,
				url 	: handler,
				success : function(data){
					$('#cont_loading').fadeIn();
				    $('#t_pay_slip_records').DataTable().clear().destroy();
				    load_pay_slip_records();
				},
				error 	: function(data){
					console.log(data);
					alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
				}
			});
		}
	});

    $('#slt_employee_number').change(function(){
        $('#cont_loading_cpsd').fadeIn();
        load_employee_info();
    });

																    $('input[name=cec_employee_number], select[name=cec_year]').change(function(){
																    	$('.btn').prop('disabled',true);
																    	$('.btn-go').empty();
																    	$('.btn-go').append('<i class="fa fa-spinner fa-spin"></i>');
																    	$('.btn-go').removeClass('btn-secondary');
																    	$('.btn-go').addClass('btn-success');

																		$('input[name=cec_name]').val('');
																		$('input[name=cec_date_hired]').val('');
																		$('input[name=cec_position]').val('');

																		$('input[name=cec_sl]').val('');
																		$('input[name=cec_vl]').val('');
																		$('input[name=cec_bonus]').val('');
																		$('input[name=cec_13th_month_pay]').val('');
																		$('input[name=cec_ecola]').val('');

																		var cec_employee_number 	= $('input[name=cec_employee_number]').val();	    
																		var cec_year 				= $('select[name=cec_year]').val();
																		var data = {
																			'action'				: 'load_employees_cec',
																			'cec_employee_number'	: cec_employee_number,
																			'cec_year'				: cec_year
																		}

																		data = $.param(data);
																		$.ajax({
																			type 	: "post",
																			dataType: "json",
																			data 	: data,
																			url 	: handler,
																			success : function(data){
																    			$('.btn').prop('disabled',false);
																		    	$('.btn-go').empty();
																		    	$('.btn-go').append('Go');
																		    	$('.btn-go').removeClass('btn-success');
																		    	$('.btn-go').addClass('btn-secondary');

																				$('input[name=cec_name]').val(data['FirstName']+' '+data['LastName']);
																				$('input[name=cec_date_hired]').val(data['DateHired']);
																				$('input[name=cec_position]').val(data['Position']);

																				$('input[name=cec_sl]').val(data['sum_sl']);
																				$('input[name=cec_vl]').val(data['sum_vl']);
																				$('input[name=cec_bonus]').val(data['sum_bonus']);
																				$('input[name=cec_13th_month_pay]').val(data['sum_13th_month']);

																				if(data['SalaryType']=='1'){
																					$('input[name=cec_ecola]').val((data['Ecola']).toFixed(2));
																				}
																				else{
																					$('input[name=cec_ecola]').val((data['Ecola']*=2).toFixed(2));
																				}
																			},
																			error 	: function(data){
																				console.log(data);
																				alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
																			}
																		});
																    });

																    $('#frm_create_cec').submit(function(e){
																		e.preventDefault();
																		var data = {
																			'action'		: 'send_employee_cec'
																		}
																		data = $.param(data) + "&" + $(this).serialize();
																		$.ajax({
																			type 	: "post",
																			dataType: "json",
																			data 	: data,
																			url 	: handler,
																			success : function(data){
																				alert('Successfully Sent!');
																		    	$('#mdl_create_cec').modal('hide');
																			},
																			error 	: function(data){
																				console.log(data);
																				alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
																			}
																		});
																    });

																	$('input[name=cec_employee_number]').keypress(function(e){
																		if(e.which == 13){
																			$('input[name=cec_employee_number]').change();
																			return false;
																		}
																	});

																	$('#btn_generate_cec').click(function(){
																		$('#mdl_create_cec').modal('show');
																	});

	$('#btn_generate_loan_balance').click(function(){
		$('#mdl_create_loan_balance').modal('show');
	});
});

function load_pay_slip_records(){
	var status = Number($('#hdn-status-to-view').val());
    var data = {
		'action'		: 'load_pay_slip_records',
		'status'		: status
	}

	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
			var html = '';
			$('#t_pay_slip_records tbody').empty();
			for(var i=0;i<data['ctr'];i++){

				switch(data['Posted'][i]){
					case '0':
						html += '<tr id="'+data['Pkid'][i]+'">';
						html += '    <td style="background-color:#ffd3d3;">'+data['Description'][i]+'</td>';
						html += '    <td>'+data['StartingDate'][i]+'</td>';
						html += '    <td>'+data['EndingDate'][i]+'</td>';
						html += '    <td>'+data['CutOff'][i]+'</td>';
						html += '    <td>'+data['Lastupdate'][i]+'</td>';
						html += '    <td class="text-center"><a style="cursor:pointer;color:white;" class="a-add-complaint btn btn-sm btn-primary a_post_pay_slip">Post Data</a></td>';
						break;
					case '1':
						html += '<tr id="'+data['Pkid'][i]+'">';
						html += '    <td style="background-color:#d3ffe9;">'+data['Description'][i]+'</td>';
						html += '    <td>'+data['StartingDate'][i]+'</td>';
						html += '    <td>'+data['EndingDate'][i]+'</td>';
						html += '    <td>'+data['CutOff'][i]+'</td>';
						html += '    <td>'+data['Lastupdate'][i]+'</td>';
						html += '    <td class="text-center text-success font-weight-light font-italic">Data Posted</td>';
						break;
					default:
						break;
				}

				html += '</tr>';
			}


		    $('#t_pay_slip_records tbody').append(html);

			var t_pay_slip_records = $('#t_pay_slip_records').DataTable({
        	    "select"        : true,
                "scrollX"		: "auto",
                "scrollY"		: "300px",
                "scrollCollapse": false,
                "paging"		: false,
                "dom"			: "Bfrtip",
                "info"			: false,
  				"ordering"		: false,
  				"responsive"	: false,
        		"order"			: [[ 0, "desc" ]],
                lengthChange: false,

                "buttons"		: [
                 //    {text: 'Generate C.E.C.',
		               //  action: function ( e, dt, node, config ){
		               //      $('#mdl_create_cec').modal('show');
		               //  }
                	// }, 
                    {extend: "copy", footer: true},
                    "excel", 
                    "colvis"
                ],
				"initComplete": function(settings, json){
		    		$('#cont_loading').fadeOut();
				}
        	});
        	
        	t_pay_slip_records.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
		},
		error 	: function(data){
			console.log(data);
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}

function load_employee_info(){
    var emp_no 		= $('#slt_employee_number').val();
    var pay_period 	= $('#slt_pay_period').val();
    var data = {
		'action'	    : 'load_employee_info',
		'emp_no'      	: emp_no,
		'pay_period'    : pay_period,
		'action_child'	: '1'
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
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
		    $('#spn_shutdown_leave').text(numeral((data['field_name']['SDOWN']/=80)).format('0,0.00'));
		    $('#spn_sl_vl_bl_sd_sdl').text(numeral(data['field_name']['LVPTAM']).format('0,0.00'));
		    $('#spn_salary_adjustments').text(numeral(data['field_name']['RETBAS']).format('0,0.00'));
		    $('#spn_hipar').text(numeral(data['field_name']['HMOEE']).format('0,0.00'));
		    $('#spn_regular_ot').text(numeral(data['field_name']['OTREGH']).format('0,0.00'));
		    $('#spn_sunday_ot').text(numeral(data['field_name']['RDH']).format('0,0.00'));
		    $('#spn_special_holiday').text(numeral(data['field_name']['SHH']).format('0,0.00'));
		    $('#spn_bdl_pl_ml_bl').text(numeral(data['field_name']['BDAYC']).format('0,0.00'));
		    $('#spn_sss_loan').text(numeral(data['field_name']['SSSAL']).format('0,0.00'));
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

        	$('#cont_loading_cpsd').fadeOut();

        	$('select').attr('disabled',false);
		},
		error 	: function(data){
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}