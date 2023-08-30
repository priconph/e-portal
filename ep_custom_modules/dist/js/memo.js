var handler = "../dist/handler/handler.php";
var t_list_of_memo_status = false;
$(document).ready(function(){

    var username = $('#username').val();
    // alert(username)
    if ( username == 'P121' || username == 'R144'){
    // if ( username == 'P121' || username == 'R144' || username == 'K010'){
        load_memo_fin();
    }
    else{
        load_memo();
    }
	
	$("#btn_add_memo").click(function(){
        $("#mdl_create_memo").modal('show');
    }); 

    $('#frm_create_memo').submit(function(e){
        e.preventDefault();
        var serialData = $(this).serialize();

        var form_data = new FormData(this);
        var file_data = $('#memo_filename').prop('files')[0];   
        form_data.append('memo_file', file_data);

        form_data.append("action","upload_memo_document");

        upload_memo_document(form_data);
    }); 

});

function upload_memo_document(form_data){
    $.ajax({
      url           : handler, // point to server-side PHP script 
      dataType      : 'json',  // what to expect back from the PHP script, if anything
      cache         : false,
      contentType   : false,
      processData   : false,
      data          : form_data,                         
      type          : 'post',

         success:function(data){
            console.log(data);

            if(data['sent_message_memo']=='1'){
                alert('Successfully sent to your Immediate Superior!');
                $('input[name=memo_title]').val('');
            }
            else{
                alert('Memo saved, but message has not been delivered.');
            }

            location.reload();  
            $('#mdl_create_memo').modal('hide');

         },
         error: function(data){
             console.log("error");
             console.log(data);
         }
     });
}

function load_memo(){
   var data = {
        'action'        : 'load_memo'
    }
    data = $.param(data);
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        success : function(data){
            console.log(data);

            $('#t_list_of_memo tbody').empty();

            var html ='';
            for(var i=0;i<data['ctr'];i++){

            	if (data['status'][i] == 0){
            		var status = '<span style="color:blue">Pending for Approval</span>';
            	}else if (data['status'][i] == 1){
            		var status = '<span style="color:green">Approved</span>';
            	}else if (data['status'][i] == 2){
                    var status = '<span style="color:red">Dispproved</span>';
                }else if (data['status'][i] == 3){
                    var status = '<span style="color:green">Approved; Conformed by Finance</span>';
                }else{
            		var status = '---';
            	}

                if( data['memo_file_name'][i] ){
                    var actual_doc = '../dist/uploads/'+data['id'][i]+data['memo_file_name'][i];
                }
                var btn_view = '<center><a href="'+actual_doc+'" class="" target="_blank">VIEW FILE</a></center>';

                var s_username = $('#username').val();
                if (data['approver'][i] == s_username){
                // if (data['approver'][i] == 'O103'){
                    if (data['status'][i] == '1'){
                        var approval_btn = '';   
                        var approval_dtls = '<span> '+data['approver_name'][i]+' </span><br><span style="color:green"><i>'+data['approved_remarks'][i]+' </i><br><i>'+data['approved_date'][i]+' </i></span>';   
                    
                    }else if (data['status'][i] == '2'){
                        var approval_btn = '';   
                        var approval_dtls = '<span> '+data['approver_name'][i]+' </span><br><span style="color:red"><i>'+data['disapproved_remarks'][i]+' </i><br><i>'+data['disapproved_date'][i]+' </i></span>';   
                    }
                    else{
                        var approval_btn = '<center><button class="btn btn-success btn-sm btn_approve_memo" app_id="'+data['id'][i]+'">Approve</button>'+' '+'<button class="btn btn-danger btn-sm btn_disapprove_memo" disapp_id="'+data['id'][i]+'">Disapprove</button></center>';   
                        var approval_dtls = data['approver_name'][i];   
                    }
                }else{
                    var approval_btn = '';  
                        var approval_dtls = data['approver_name'][i];   
                }

                html += '<tr style="width:100%;">';
                html += '   <td>'+status+'</td>';
                html += '   <td>'+data['title'][i]+'</td>';
                html += '   <td>'+btn_view+'</td>';
                html += '   <td>'+data['date'][i]+'</td>';
                html += '   <td>'+approval_dtls+'</td>';
                html += '   <td>'+approval_btn+'</td>';
                html += '</tr>';
            }
            $('#t_list_of_memo tbody').append(html);

            if(t_list_of_memo_status==false){
                var t_list_of_memo = $('#t_list_of_memo').DataTable({
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

                t_list_of_memo_status = true;
                
            }

            $(".btn_approve_memo").click(function(){
                var id = $(this).attr('app_id');
                $("#memo_app_id").val(id);
                $("#mdl_approved_memo").modal('show');
            });

            $(".btn_disapprove_memo").click(function(){
                var id = $(this).attr('disapp_id');
                $("#memo_dis_id").val(id);
                $("#mdl_disapproved_memo").modal('show');
            });

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
}

function load_memo_fin(){
   var data = {
        'action'        : 'load_memo_fin'
    }
    data = $.param(data);
    $.ajax({
        type    : "post",
        dataType: "json",
        data    : data,
        url     : handler,
        success : function(data){
            console.log(data);

            $('#t_list_of_memo tbody').empty();

            var html ='';
            for(var i=0;i<data['ctr'];i++){

                if (data['status'][i] == 0){
                    var status = '<span style="color:blue">Pending for Approval</span>';
                }else if (data['status'][i] == 1){
                    var status = '<span style="color:orange">For Finance Conformance</span>';
                }else if (data['status'][i] == 2){
                    var status = '<span style="color:red">Dispproved</span>';
                }else if (data['status'][i] == 3){
                    var status = '<span style="color:green">Completed</span>';
                }else{
                    var status = '---';
                }

                if( data['memo_file_name'][i] ){
                    var actual_doc = '../dist/uploads/'+data['id'][i]+data['memo_file_name'][i];
                }
                var btn_view = '<center><a href="'+actual_doc+'" class="" target="_blank">VIEW FILE</a></center>';

                var s_username = $('#username').val();
                // if (data['approver'][i] == s_username){
                // if (data['approver'][i] == 'Q121'){
                    if (data['status'][i] == '1'){
                        var approval_btn = '<center><button class="btn btn-success btn-sm btn_conform_memo" app_id="'+data['id'][i]+'">Conform</button></center>';   
                        var approval_dtls = '<span> '+data['approver_name'][i]+' </span><br><span style="color:green"><i>'+data['approved_remarks'][i]+' </i><br><i>'+data['approved_date'][i]+' </i></span>';   
                    
                    }else if (data['status'][i] == '2'){
                        var approval_btn = '';   
                        var approval_dtls = '<span> '+data['approver_name'][i]+' </span><br><span style="color:red"><i>'+data['disapproved_remarks'][i]+' </i><br><i>'+data['disapproved_date'][i]+' </i></span>';   
                    
                    }else if (data['status'][i] == '3'){
                        var approval_btn = '';
                        var approval_dtls = '<span> '+data['approver_name'][i]+' </span><br><span style="color:red"><i>'+data['disapproved_remarks'][i]+' </i><br><i>'+data['disapproved_date'][i]+' </i></span>';   
                    }
                    else{
                        var approval_btn = '<center><button class="btn btn-success btn-sm btn_approve_memo" app_id="'+data['id'][i]+'">Approve</button>'+' '+'<button class="btn btn-danger btn-sm btn_disapprove_memo" disapp_id="'+data['id'][i]+'">Disapprove</button></center>';   
                        var approval_dtls = data['approver_name'][i];   
                    }
                // }else{
                    // var approval_btn = '';  
                        // var approval_dtls = data['approver_name'][i];   
                // }

                html += '<tr style="width:100%;">';
                html += '   <td>'+status+'</td>';
                html += '   <td>'+data['title'][i]+'</td>';
                html += '   <td>'+btn_view+'</td>';
                html += '   <td>'+data['date'][i]+'</td>';
                html += '   <td>'+approval_dtls+'</td>';
                html += '   <td>'+approval_btn+'</td>';
                html += '</tr>';
            }
            $('#t_list_of_memo tbody').append(html);

            if(t_list_of_memo_status==false){
                var t_list_of_memo = $('#t_list_of_memo').DataTable({
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

                t_list_of_memo_status = true;
                
            }

            $(".btn_approve_memo").click(function(){
                var id = $(this).attr('app_id');
                $("#memo_app_id").val(id);
                $("#mdl_approved_memo").modal('show');
            });

            $(".btn_conform_memo").click(function(){
                var id = $(this).attr('app_id');
                $("#memo_con_id").val(id);
                $("#mdl_conform_memo").modal('show');
            });

            $(".btn_disapprove_memo").click(function(){
                var id = $(this).attr('disapp_id');
                $("#memo_dis_id").val(id);
                $("#mdl_disapproved_memo").modal('show');
            });

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
}

$('#frm_approved_memo').submit(function(e){
    e.preventDefault();
    var data = {
        'action'        : 'approved_memo'
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
                load_memo();
                $('#mdl_approved_memo').modal('hide');
            }

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
});

$('#frm_conform_memo').submit(function(e){
    e.preventDefault();
    var data = {
        'action'        : 'conform_memo'
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
                load_memo();
                $('#mdl_conform_memo').modal('hide');
            }

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
});

$('#frm_disapproved_memo').submit(function(e){
    e.preventDefault();
    var data = {
        'action'        : 'disapproved_memo'
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
                load_memo();
                $('#mdl_disapproved_memo').modal('hide');
            }

        },
        error   : function(data){
            console.log(data);
            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
        }
    });
});

$('#memo_approver_check').on('keyup',function(e){
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
}
