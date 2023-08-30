var handler = "../dist/handler/handler.php";
var t_list_of_cec_status = false;
$(document).ready(function(){
	load_cec();

	$("#btn_add_cec").click(function(){
        $("#mdl_create_cec").modal('show');
    }); 

    $("#frm_create_cec").submit(function(e){
        e.preventDefault();
        var data = {
            'action'        : 'request_cec'
        }
        data = $.param(data) + "&" + $(this).serialize();
        $.ajax({
            type    : "post",
            dataType: "json",
            data    : data,
            url     : handler,
            success : function(data){
                console.log(data['sent_message_status']);

                $('#cont_loading').fadeIn();
                load_cec();

                if(data['sent_message_status']=='1'){
                    alert('Successfully sent to HRD!');
                    $('#mdl_create_cec').modal('hide');
                    $('#cec_purpose').val('');

                }
                else{
                    alert('CEC saved, but message has not been delivered.');
                }


                
            },
            error   : function(data){
                console.log(data);
                alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
            }
        });
    });

});

function load_cec(){
   var data = {
        'action'        : 'load_cec'
    }
    data = $.param(data);
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        success : function(data){
            console.log(data);

            $('#t_list_of_cec tbody').empty();

            var html ='';
            for(var i=0;i<data['ctr'];i++){

            	if (data['status'][i] == 0){
            		var status = '<span style="color:blue">Pending for Approval</span>';
                    var btn_view = '<i>Not Available</i>';
                    var approve_btn = '<button class="btn btn-success btn-sm btn_approve_cec" app_id="'+data['id'][i]+'">Approve</button>';
                    var disapprove_btn = '<button class="btn btn-danger btn-sm btn_disapprove_cec" disapp_id="'+data['id'][i]+'">Disapprove</button>';
                    var check_btn = '';
                    var gen_cec_btn = '';
            	
                }else if (data['status'][i] == 1){
            		var status = '<span style="color:green">Approved by HRD Sr. Supervisor</span>';
                    var btn_view = '<i>Not Available</i>';
                    var approve_btn = '';
                    var disapprove_btn = '';
                    var check_btn = '';
                    var gen_cec_btn = '<button class="btn btn-primary btn-sm btn_gen_cec" id="'+data['id'][i]+'">Generate CEC</button>';

                }else if (data['status'][i] == 2){
            		var status = '<span style="color:red">Dispproved by HRD Sr. Supervisor</span>';
                    var btn_view = '<i>Not Available</i>';
                    var approve_btn = '';
                    var disapprove_btn = '';
                    var check_btn = '';
                    var gen_cec_btn = '';

                }else if (data['status'][i] == 3){
                    var status = '<span style="color:blue">For checking of Payroll Officer</span>';
                    var btn_view = '<i>Not Available</i>';
                    var approve_btn = '';
                    var disapprove_btn = '';
                    var check_btn = '<button class="btn btn-warning btn-sm btn_check_cec" id="'+data['id'][i]+'">Check CEC</button>';
                    var gen_cec_btn = '<button class="btn btn-primary btn-sm btn_gen_cec" id="'+data['id'][i]+'">Generate CEC</button>'; // view only
                }else if (data['status'][i] == 4){
                    var status = '<span style="color:blue">For Approval of HRD Manager</span>';
                    var btn_view = '<center><a href="" class="" target="_blank">VIEW FILE</a></center>';
                    var approve_btn = '<button class="btn btn-success btn-sm btn_approve_cec" app_id="'+data['id'][i]+'">Approve</button>';
                    var disapprove_btn = '<button class="btn btn-danger btn-sm btn_disapprove_cec" disapp_id="'+data['id'][i]+'">Disapprove</button>';
                    var check_btn = '';
                    var gen_cec_btn = '<button class="btn btn-primary btn-sm btn_gen_cec" id="'+data['id'][i]+'">Generate CEC</button>'; // view only

                }else{
                    var status = '---';
                    var btn_view = '---';
                    var approve_btn = '---';
                    var disapprove_btn = '---';
                    var check_btn = '---';
                    var gen_cec_btn = '---';
            	}

                

                var s_username = $('#username').val();
                if (data['approver1'][i] == s_username){
                // if (data['approver1'][i] == 'Q121'){
                    var approval_btn = '<center>'+approve_btn+' '+check_btn+' '+disapprove_btn+' '+gen_cec_btn+'</center>';   
                }else {
                    var approval_btn = '';   
                }


                html += '<tr style="width:100%;">';
                html += '   <td>'+status+'</td>';
                html += '   <td><span style="color:blue"><b>'+data['username'][i]+'</b></span> - '+data['emp_name'][i]+'</td>';
                html += '   <td>'+data['date'][i]+'</td>';
                html += '   <td>'+data['purpose'][i]+'</td>';
                html += '   <td>'+btn_view+'</td>';
                html += '   <td>'+approval_btn+'</td>';
                html += '</tr>';
            }
            $('#t_list_of_cec tbody').append(html);

            if(t_list_of_cec_status==false){
                var t_list_of_cec = $('#t_list_of_cec').DataTable({
                    // "select"        : true,
                    "scrollX"       : "auto",
                    "scrollY"       : "330px",
                    "dom"           : "Bfrtip",
                    "order"         : [[ 0, "desc" ]],
                    "scrollCollapse": false,
                    "paging"        : false,
                    "info"          : false,
                    "ordering"      : true
                });

                t_list_of_cec_status = true;
                
            }

            $(".btn_approve_cec").click(function(){
                var id = $(this).attr('app_id');
                $("#cec_app_id").val(id);
                $("#mdl_approved_cec").modal('show');
            });

            $(".btn_disapprove_cec").click(function(){
                var id = $(this).attr('disapp_id');
                $("#cec_dis_id").val(id);
                $("#mdl_disapproved_cec").modal('show');
            });

            $(".btn_gen_cec").click(function(){
                var id = $(this).attr('id');
                $("#cec_gen_id").val(id);
                $("#mdl_gen_cec").modal('show');
            });

            

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
}

$('#frm_approved_cec').submit(function(e){
    e.preventDefault();
    var data = {
        'action'        : 'approved_cec'
    }
    data = $.param(data) + "&" + $(this).serialize();
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        success : function(data){
            console.log(data['mail_status']);

            if(data['mail_status'] == '1'){
                alert('Successfully Approved!');
                load_cec();
                $('#mdl_approved_cec').modal('hide');
            }

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
});

$('#frm_disapproved_cec').submit(function(e){
    e.preventDefault();
    var data = {
        'action'        : 'disapproved_cec'
    }
    data = $.param(data) + "&" + $(this).serialize();
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        success : function(data){
            console.log(data['mail_status']);

            if(data['mail_status'] == '1'){
                alert('Successfully Dispproved!');
                load_cec();
                $('#mdl_disapproved_cec').modal('hide');
            }

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
});

/*$('#memo_approver_check').on('keyup',function(e){
    var key_pressed = e.which;
    if(key_pressed !=38 && key_pressed !=40){
    var pattern_superior = $(this).val();
    get_immediate_superior(pattern_superior);
    }
});

function get_immediate_superior(pattern_superior){
    var data = {
        'action'            : 'get_immediate_superior',
        'pattern_superior'  : pattern_superior
    } 
    data = $.param(data) + "&" + $(this).serialize();   
    $.ajax({
        type    : 'post',
        dataType: 'json',
        data    : data,
        url     : handler,
        success : function(data){
            console.log(data);
            var ctr = data['ctr'];
            $('#id_superior_list').empty();
            
            var htmlx = '';
            $('#id_superior_list').append(htmlx);

            for(i=0;i<ctr;i++){
                var html = '<option value="'+data['emp_name'][i]+'">'+data['emp_name'][i]+'</option>';
                $('#id_superior_list').append(html);
            }
        },
        error   : function(data){
            alert('Error found in Superior Listing.');
            console.log(data);
        }
        
    });
}
    
$('#memo_approver_check').on('change hover',function(e){
    get_superior_email( $(this).val() );
});

function get_superior_email(superior_name){
    var data = {
        'action'            : 'get_superior_email',
        'superior_name'     : superior_name
    } 
    data = $.param(data);
    // alert(data)
    $.ajax({
        type    : 'post',
        dataType: 'json',
        data    : data,
        url     : handler,
        success : function(data){
            $('#memo_approver').val(data['EmpNo']);               
        },
        error   : function(data){
            alert('Error found in Superior Listing.');
            console.log(data);
        }
        
    });
}*/
