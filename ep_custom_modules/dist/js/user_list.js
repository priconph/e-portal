var handler = "../dist/handler/handler.php";
var t_list_of_users_status = false;
$(document).ready(function(){
    $('#t_list_of_users').on('click', '.a-create-account', function(){
        var login_name = $(this).parent().parent().find('td:eq(0)').text();
        var account_name = $(this).parent().parent().find('td:eq(1)').text();

        var data = {
            "action"            : 'select_employee_details',
            "en"                : login_name,
        }
        $.ajax({
            type                : "post",
            dataType            : "json",
            data                : data,
            url                 : handler,
            success             : function(data){
                if( data['email_add'] ){
                    $('#txt_email').val(data['email_add']);
                }
            }
        });

        $('#txt_account_name').val(account_name);
        $('#txt_login_name').val(login_name);
        $('#txt_password').val( get_random_code(6) );
        $('#mdl_create_account').modal('show');
    });

    $('#frm_create_account').on('submit', function(e){
        e.preventDefault();
        var data = {
            "action"            : 'create_new_account',
        }
        data = $.param(data) + "&" + $(this).serialize();
        $.ajax({
            type                : "post",
            dataType            : "json",
            data                : data,
            url                 : handler,
            success             : function(data){
                var email_msg = ' Login details were not sent to the user\'s email.';
                if(data['mail_status'] == '1'){
                    email_msg = ' Login details were sent to the user\'s email.'
                }
                $('#mdl_create_account').closest('.modal').modal("hide");
                show_alert_notif('success', 'fa fa-check', 'Successfully created new account!'+email_msg, 'prepend', '#cont_list_of_users');
                // $('#t_list_of_users').DataTable().clear().destroy();
                load_list_of_users();
                // setTimeout(function() {
                //     load_list_of_users();
                // }, 1000);
            }
        });
    });

    /* Original Code */
    /*$('#t_list_of_users tbody').on('dblclick','tr',function(){
        $('#cont_loading_emp_info').fadeIn();
        $('#btn_generate_cec').addClass('disabled');
        $('#btn_reset_password').addClass('disabled');
        var emp_no = $(this).find('td:eq(0)').text();
        var data = {
            'action'        : 'load_employee_information',
            'emp_no'        : emp_no
        }
        data = $.param(data);
        $.ajax({
            type    : "post",
            dataType: "json",
            data    : data,
            url     : handler,
            success : function(data){
                if(data['EmpNo']!==undefined){
                    $('#a_employee_picture').attr('src','//192.168.3.240/otks_main/otks_attendance/pmi_pictures/'+data['EmpNo']+'.jpg');
                    $('#employee_number').val(data['EmpNo']);
                    $('#employee_name').val(data['FirstName']+' '+data['LastName']);
                    $('#employee_section_division_department').val(data['Section']+'/'+data['Division']+'/'+data['Department']);
                    $('#employee_position').val(data['Position']);
                    $('#employee_basic_pay').val(data['BasicPay']);
                    $('#employee_rcbc_number').val(data['AccountNumber']);
                    $('#employee_ssn').val(data['SSN']);
                    $('#employee_philhealth').val(data['PhilHealth']);
                    $('#employee_tin').val(data['TIN']);
                    $('#employee_pagibig').val(data['Hdmf']);
                    $('#employee_pay_mode').val(data['Paymode']);
                    $('#employee_pay_type').val(data['PayType']);
                    $('#employee_exemption_code').val(data['ExemptBaseCode']);
                    $('#employee_ecola').val(data['Ecola']);
                    $('#employee_allowance').val(data['Allowance']);
                    $('.cont_financial_information').fadeIn();
                    $('#mdl_show_employee_information').modal('show');
                    $('#btn_generate_cec').removeClass('disabled');
                    $('#btn_reset_password').removeClass('disabled');
                    load_loan_information(data['EmpNo']);
                }
            },
            error   : function(data){
                console.log(data);
                alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
            }
        });
    });*/

    $('#t_list_of_users tbody').on('dblclick','tr',function(){
        $('#cont_loading_emp_info').fadeIn();
        $('#btn_generate_cec').addClass('disabled');
        $('#btn_reset_password').addClass('disabled');
        var emp_no = $(this).find('td:eq(0)').text();
        var data = {
            'action'        : 'load_employee_information_rapid',
            'emp_no'        : emp_no
        }
        data = $.param(data);
        $.ajax({
            type    : "post",
            dataType: "json",
            data    : data,
            url     : handler,
            success : function(data){
                console.log(data)
                if(data['EmpNo']!==undefined){
                    $('#a_employee_picture').attr('src','//192.168.3.240/otks_main/otks_attendance/pmi_pictures/'+data['EmpNo']+'.jpg');
                    $('#employee_number').val(data['EmpNo']);
                    $('#employee_name').val(data['FirstName']+' '+data['LastName']);
                    $('#employee_section_division_department').val(data['Section']+'/'+data['Division']+'/'+data['Department']);
                    $('#employee_position').val(data['Position']);
                    $('#employee_ssn').val(data['SSN']);
                    $('#employee_philhealth').val(data['PhilHealth']);
                    $('#employee_tin').val(data['TIN']);
                    $('#employee_pagibig').val(data['PagIbig']);
                    $('#employee_exemption_code').val(data['personal_exemption']);
                    $('#employee_ecola').val(data['ecola']);
                    $('#employee_allowance').val(data['allowance']);
                    $('#employee_basic_pay').val(data['basic_salary']);
                    $('#employee_rcbc_number').val(data['account_no']);

                    /* Original Code*/
                    // $('#employee_basic_pay').val(data['BasicPay']);
                    // $('#employee_rcbc_number').val(data['AccountNumber']);
                    // $('#employee_ssn').val(data['SSN']);
                    // $('#employee_philhealth').val(data['PhilHealth']);
                    // $('#employee_tin').val(data['TIN']);
                    // $('#employee_pagibig').val(data['Hdmf']);
                    // $('#employee_pay_mode').val(data['Paymode']);
                    // $('#employee_pay_type').val(data['PayType']);
                    // $('#employee_exemption_code').val(data['ExemptBaseCode']);
                    // $('#employee_ecola').val(data['Ecola']);
                    // $('#employee_allowance').val(data['Allowance']);

                    $('.cont_financial_information').fadeIn();
                    $('#mdl_show_employee_information').modal('show');
                    $('#btn_generate_cec').removeClass('disabled');
                    $('#btn_reset_password').removeClass('disabled');
                    load_loan_information(data['EmpNo']);
                }
            },
            error   : function(data){
                console.log(data);
                alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
            }
        });
    });

    /* Original Code */
    /*$('#employee_number').change(function(){
        $('#cont_loading_emp_info').fadeIn();
        $('#btn_generate_cec').addClass('disabled');
        $('#btn_reset_password').addClass('disabled');
        var emp_no = $('#employee_number').val();
        $('#btn_generate_cec').addClass('disabled');
        var data = {
            'action'        : 'load_employee_information',
            'emp_no'        : emp_no
        }
        data = $.param(data);
        $.ajax({
            type    : "post",
            dataType: "json",
            data    : data,
            url     : handler,
            success : function(data){
                if(data['EmpNo']!==undefined){
                    $('#a_employee_picture').attr('src','//192.168.3.240/otks_main/otks_attendance/pmi_pictures/'+data['EmpNo']+'.jpg');
                    $('#employee_number').val(data['EmpNo']);
                    $('#employee_name').val(data['FirstName']+' '+data['LastName']);
                    $('#employee_section_division_department').val(data['Section']+'/'+data['Division']+'/'+data['Department']);
                    $('#employee_position').val(data['Position']);
                    $('#employee_basic_pay').val(data['BasicPay']);
                    $('#employee_rcbc_number').val(data['AccountNumber']);
                    $('#employee_ssn').val(data['SSN']);
                    $('#employee_philhealth').val(data['PhilHealth']);
                    $('#employee_tin').val(data['TIN']);
                    $('#employee_pagibig').val(data['Hdmf']);
                    $('#employee_pay_mode').val(data['Paymode']);
                    $('#employee_pay_type').val(data['PayType']);
                    $('#employee_exemption_code').val(data['ExemptBaseCode']);
                    $('#employee_ecola').val(data['Ecola']);
                    $('#employee_allowance').val(data['Allowance']);
                    $('.cont_financial_information').fadeIn();
                    $('#mdl_show_employee_information').modal('show');
                    $('#btn_generate_cec').removeClass('disabled');
                    $('#btn_reset_password').removeClass('disabled');
                    load_loan_information(data['EmpNo']);
                }
            },
            error   : function(data){
                console.log(data);
                alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
            }
        });
    });*/

    $('#employee_number').change(function(){
        $('#cont_loading_emp_info').fadeIn();
        $('#btn_generate_cec').addClass('disabled');
        $('#btn_reset_password').addClass('disabled');
        var emp_no = $('#employee_number').val();
        $('#btn_generate_cec').addClass('disabled');
        var data = {
            'action'        : 'load_employee_information_rapid',
            'emp_no'        : emp_no
        }
        data = $.param(data);
        $.ajax({
            type    : "post",
            dataType: "json",
            data    : data,
            url     : handler,
            success : function(data){
                if(data['EmpNo']!==undefined){
                    $('#a_employee_picture').attr('src','//192.168.3.240/otks_main/otks_attendance/pmi_pictures/'+data['EmpNo']+'.jpg');
                    $('#employee_number').val(data['EmpNo']);
                    $('#employee_name').val(data['FirstName']+' '+data['LastName']);
                    $('#employee_section_division_department').val(data['Section']+'/'+data['Division']+'/'+data['Department']);
                    $('#employee_position').val(data['Position']);
                    $('#employee_ssn').val(data['SSN']);
                    $('#employee_philhealth').val(data['PhilHealth']);
                    $('#employee_tin').val(data['TIN']);
                    $('#employee_pagibig').val(data['PagIbig']);
                    $('#employee_exemption_code').val(data['personal_exemption']);
                    $('#employee_ecola').val(data['ecola']);
                    $('#employee_allowance').val(data['allowance']);
                    $('#employee_basic_pay').val(data['basic_salary']);
                    $('#employee_rcbc_number').val(data['account_no']);


                    /* Original Code*/
                    // $('#employee_basic_pay').val(data['BasicPay']);
                    // $('#employee_rcbc_number').val(data['AccountNumber']);
                    // $('#employee_ssn').val(data['SSN']);
                    // $('#employee_philhealth').val(data['PhilHealth']);
                    // $('#employee_tin').val(data['TIN']);
                    // $('#employee_pagibig').val(data['Hdmf']);
                    // $('#employee_pay_mode').val(data['Paymode']);
                    // $('#employee_pay_type').val(data['PayType']);
                    // $('#employee_exemption_code').val(data['ExemptBaseCode']);
                    // $('#employee_ecola').val(data['Ecola']);
                    // $('#employee_allowance').val(data['Allowance']);
                    $('.cont_financial_information').fadeIn();
                    $('#mdl_show_employee_information').modal('show');
                    $('#btn_generate_cec').removeClass('disabled');
                    $('#btn_reset_password').removeClass('disabled');
                    load_loan_information(data['EmpNo']);
                }
            },
            error   : function(data){
                console.log(data);
                alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
            }
        });
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

        var cec_employee_number     = $('input[name=cec_employee_number]').val();       
        var cec_year                = $('select[name=cec_year]').val();
        var data = {
            'action'                : 'load_employees_cec',
            'cec_employee_number'   : cec_employee_number,
            'cec_year'              : cec_year
        }

        data = $.param(data);
        $.ajax({
            type    : "post",
            dataType: "json",
            data    : data,
            url     : handler,
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
            error   : function(data){
                console.log(data);
                alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
            }
        });
    });

    $('#frm_create_cec').submit(function(e){
        e.preventDefault();
        var data = {
            'action'        : 'send_employee_cec'
        }
        data = $.param(data) + "&" + $(this).serialize();
        $.ajax({
            type    : "post",
            dataType: "json",
            data    : data,
            url     : handler,
            success : function(data){
                alert('Successfully Sent!');
                $('#mdl_create_cec').modal('hide');
            },
            error   : function(data){
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
        $('input[name=cec_employee_number]').val($('#employee_number').val());
        $('input[name=cec_employee_number]').change();
    });

    $('#t_loan_information tbody').on('dblclick','tr',function(){
        $('#cont_loading_emp_info').fadeIn();
        var loan_id = $(this).find('td:eq(0) .txt_loan_id').val();
        $('#spn_loan_description').text($(this).find('td:eq(1)').text());
        $('#spn_loan_principal_amount').text($(this).find('td:eq(2)').text());
        load_loan_payments(loan_id);
    });

    $('#btn_reset_password').click(function(){
        var login_name = $(this).closest('.modal').find('#employee_number').val();
        var account_name = $(this).closest('.modal').find('#employee_name').val();

        var data = {
            "action"            : 'select_employee_details',
            "en"                : login_name,
        }
        $.ajax({
            type                : "post",
            dataType            : "json",
            data                : data,
            url                 : handler,
            success             : function(data){
                if( data['email_add'] ){
                    $('#txt_email_reset_pw').val(data['email_add']);
                    console.log()
                }
            }
        });

        $('#txt_account_name_reset_pw').val(account_name);
        $('#txt_login_name_reset_pw').val(login_name);
        $('#txt_password_reset_pw').val( get_random_code(6) );
        $('#mdl_reset_password').modal('show');
    });

   $('#frm_reset_password').on('submit', function(e){
        e.preventDefault();
        var data = {
            "action"            : 'reset_account_password',
        }
        data = $.param(data) + "&" + $(this).serialize();
        $.ajax({
            type                : "post",
            dataType            : "json",
            data                : data,
            url                 : handler,
            success             : function(data){
                
                var email_msg = ' Login details were not sent to the user\'s email.';
                if(data['mail_status'] == '1'){
                    email_msg = ' Login details were sent to the user\'s email.'
                }
                
                $('#mdl_reset_password').closest('.modal').modal("hide");
                show_alert_notif('success', 'fa fa-check', 'Password reset successful!'+email_msg, 'prepend', '#mdl_show_employee_information .modal-footer');
                // $('#t_list_of_users').DataTable().clear().destroy();
                // load_list_of_users();
                // setTimeout(function() {
                    load_list_of_users();
                // }, 1000);
            }
        });
    })

    $(document).on("mouseover",'.td_basic_pay', function () {
        // alert()
        // $(this).css('color','#aaa');
        //stuff to do on mouseover
    });
});

/* Original Code */   
/*function load_list_of_users(){
    var data = {
        'action'        : 'load_list_of_users'
    }
    data = $.param(data);
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        success : function(data){
            console.log(data);
            $('#t_list_of_users tbody').empty();
            var html ='';
            for(var i=0;i<data['ctr'];i++){
                html += '<tr style="width:100%;">';
                html += '   <td>'+data['EmpNo'][i]+'</td>';
                html += '   <td>'+data['FirstName'][i]+' '+data['LastName'][i]+'</td>';
                html += '   <td>'+data['Department'][i]+'</td>';
                html += '   <td class="td_basic_pay" style="color:transparent;">'+data['BasicPay'][i]+'</td>';
                html += '   <td>'+data['AccountNumber'][i]+'</td>';
                switch(data['active_account'][i]){
                    case '1':
                        html += '   <td>Account Active</td>';
                        break;
                    case '0':
                        html += '   <td><a class="a-create-account btn btn-sm btn-primary" style="color:white !important;font-size:70%;cursor:pointer;"><i>Create Account</i></a></td>';
                        break;
                }
                html += '</tr>';
            }
            $('#t_list_of_users tbody').append(html);

            if(t_list_of_users_status==false){
                var t_list_of_users = $('#t_list_of_users').DataTable({
                    "select"        : true,
                    "scrollX"       : "auto",
                    "scrollY"       : "330px",
                    "dom"           : "Bfrtip",
                    "order"         : [[ 0, "desc" ]],
                    "scrollCollapse": false,
                    "paging"        : false,
                    "info"          : false,
                    "ordering"      : true
                });

                t_list_of_users_status = true;
                
            }

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
}*/

function load_list_of_users(){
    var data = {
        'action'        : 'load_list_of_users_rapid'
    }
    data = $.param(data);
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        success : function(data){
            console.log(data);
            $('#t_list_of_users tbody').empty();
            var html ='';
            for(var i=0;i<data['ctr'];i++){
                html += '<tr style="width:100%;">';
                html += '   <td>'+data['EmpNo'][i]+'</td>';
                html += '   <td>'+data['FirstName'][i]+' '+data['LastName'][i]+'</td>';
                html += '   <td>'+data['Department'][i]+'</td>';

                /* Original Code*/
                // html += '   <td class="td_basic_pay" style="color:transparent;">'+data['BasicPay'][i]+'</td>';
                // html += '   <td>'+data['AccountNumber'][i]+'</td>';

                html += '   <td>'+data['basic_salary'][i]+'</td>';
                if (data['account_no'][i] == 0){
                    html += '   <td><i>--Not available--</i></td>';
                }else{
                    html += '   <td>'+data['account_no'][i]+'</td>';
                }

                switch(data['active_account'][i]){
                    case '1':
                        html += '   <td>Account Active</td>';
                        break;
                    case '0':
                        html += '   <td><a class="a-create-account btn btn-sm btn-primary" style="color:white !important;font-size:70%;cursor:pointer;"><i>Create Account</i></a></td>';
                        break;
                }
                html += '</tr>';
            }
            $('#t_list_of_users tbody').append(html);

            if(t_list_of_users_status==false){
                var t_list_of_users = $('#t_list_of_users').DataTable({
                    "select"        : true,
                    "scrollX"       : "auto",
                    "scrollY"       : "330px",
                    "dom"           : "Bfrtip",
                    "order"         : [[ 0, "desc" ]],
                    "scrollCollapse": false,
                    "paging"        : false,
                    "info"          : false,
                    "ordering"      : true
                });

                t_list_of_users_status = true;
                
            }

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
}

function load_loan_information(emp_no){
    var data = {
        'action'        : 'load_loan_information',
        'emp_no'        : emp_no
    }
    data = $.param(data);
    $.ajax({
        type        : "post",
        dataType    : "json",
        data        : data,
        url         : handler,
        complete    : function(data){
            load_pay_slip_history(emp_no);
        },
        success : function(data){
            var html = '';
            $('#t_loan_information tbody').empty();
            if(data['ctr']=='0'){
                html += '<tr>';
                html += '   <td class="text-center" colspan="8">No data</td>';
                html += '</tr>';
            }
            else{
                for(var i=0;i<data['ctr'];i++){
                    html += '<tr>';
                    html += '   <td class="border"><input class="d-none txt_loan_id" value="'+data['pkid'][i]+'"> '+data['StartDate'][i]+'</td>';
                    html += '   <td class="border">'+data['LoanDescription'][i]+'</td>';
                    html += '   <td class="border text-right">'+data['PrincipalAmount'][i]+'</td>';
                    html += '   <td class="border text-right">'+data['CurrentBalance'][i]+'</td>';
                    html += '   <td class="border text-right">'+data['InterestRate'][i]+'</td>';
                    html += '   <td class="border text-right">'+data['MonthsToPay'][i]+'</td>';
                    html += '   <td class="border">'+data['FreqDescription'][i]+'</td>';
                    html += '   <td class="border text-right">'+data['Amortization'][i]+'</td>';
                    html += '</tr>';
                }                
            }
            $('#t_loan_information tbody').append(html);
        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });    
}

function load_pay_slip_history(emp_no){
    var data = {
        'action'        : 'load_pay_slip_history',
        'emp_no'        : emp_no
    }
    data = $.param(data);
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        complete    : function(data){
            $('#cont_loading_emp_info').fadeOut();
        },
        success : function(data){
            var html = '';
            $('#t_pay_slip_history tbody').empty();
            if(data['ctr']=='0'){
                html += '<tr>';
                html += '   <td class="text-center" colspan="5">No data</td>';
                html += '</tr>';
            }
            else{
                for(var i=0;i<data['ctr'];i++){
                    html += '<tr>';
                    html += '   <td class="border">'+data['StartingDate'][i]+'</td>';
                    html += '   <td class="border">'+data['EndingDate'][i]+'</td>';
                    html += '   <td class="border">'+data['CutOff'][i]+'</td>';
                    html += '   <td class="border">'+data['Description'][i]+'</td>';
                    html += '   <td class="border">'+data['Lastupdate'][i]+'</td>';
                    html += '</tr>';
                }                
            }
            $('#t_pay_slip_history tbody').append(html);
        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
}

function load_loan_payments(loan_id){
    var data = {
        'action'        : 'load_loan_payments',
        'loan_id'       : loan_id
    }
    data = $.param(data);
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        complete    : function(data){
            $('#cont_loading_emp_info').fadeOut();
        },
        success : function(data){
            var html = '';
            $('#t_loan_payments tbody').empty();
            if(data['ctr']=='0'){
                html += '<tr>';
                html += '   <td class="text-center" colspan="5">No data</td>';
                html += '</tr>';
            }
            else{
                for(var i=0;i<data['ctr'];i++){
                    html += '<tr>';
                    html += '   <td class="border">'+data['SchedofPayment'][i]+'</td>';
                    html += '   <td class="border">'+data['DatePaid'][i]+'</td>';
                    html += '   <td class="border text-right">'+data['Principal'][i]+'</td>';
                    html += '   <td class="border text-right">'+data['Interest'][i]+'</td>';
                    html += '   <td class="border text-right">'+data['Balance'][i]+'</td>';
                    html += '</tr>';
                }                
            }
            $('#spn_loan_balance').text(data['CurrentBalance']);
            $('#t_loan_payments tbody').append(html);
            $('#mdl_loan_payments').modal('show');
            $('#cont_loading_emp_info').fadeIn();
        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
}

















function get_random_code(n){
  var text = "";
  var possible = "abcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i <= n; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}

function show_alert_notif(type,icon,body,append_type,append_to){
    if(!type){
        type = 'info';
    }
    type = 'alert-'+type;
    if(!icon){
        icon = 'fa-info-circle';
    }
    var content = '';
    content = '<div class="alert alert_notif '+type+' alert-dismissible fade show my-2" role="alert">';
    content += '    <i class="'+icon+' fa-lg"></i> ';
    content +=      body;
    content += '    <button type="button" class="close" data-dismiss="alert" aria-label="Close">';
    content += '        <span aria-hidden="true">&times;</span>';
    content += '    </button>';
    content += '</div>';

    if(append_type == 'before'){
        $(append_to).prev('.alert_notif').alert('close');
        $(append_to).before( content );
        window.setTimeout(function() { $(append_to).prev('.alert_notif').alert('close'); }, 2500);
    }
    else if(append_type == 'after'){
        $(append_to).next('.alert_notif').alert('close');
        $(append_to).after( content );
        window.setTimeout(function() { $(append_to).next('.alert_notif').alert('close'); }, 2500);
    }
    else if(append_type == 'append'){
        $(append_to).find('.alert_notif').alert('close');
        $(append_to).append( content );
        window.setTimeout(function() { $(append_to).find('.alert_notif').alert('close'); }, 2500);
    }
    else{//default prepend
        $(append_to).find('.alert_notif').alert('close');
        $(append_to).prepend( content );
        window.setTimeout(function() { $(append_to).find('.alert_notif').alert('close'); }, 2500);
    }
}

function certain_image_error(){
    $('#a_employee_picture').attr('src','../../images/default_profile_pic.jpg');
}