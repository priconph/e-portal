var handler = "../dist/handler/handler.php";
var t_list_of_users_status = false;
$(document).ready(function(){
	load_disabled_users();

	$(document).on('click','.btn_enable_user',function(){
		if( !confirm('Enable user?') ){
			return;
		}
		var data = {
	        'action'  		      : 'enable_user',
	        'query_username'      :  $(this).closest('tr').find('td:eq(0)').text() ,
	    }
	    data = $.param(data);
	    $.ajax({
	        type    : "post",
	        dataType: "json",
	        data    : data,
	        url     : handler,
	        success : function(data){
                show_alert_notif('success', 'fa fa-check', 'User account has been enabled!', 'prepend', '#cont_list_of_users');
                load_disabled_users();
	            console.log(data);
	        },
	        error   : function(data){
	            console.log(data);
	            alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
	        }
	    });
	});
});

function load_disabled_users(){
   var data = {
        'action'        : 'load_disabled_users'
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
                html += '   <td>'+data['username'][i]+'</td>';
                html += '   <td>'+data['name'][i]+'</td>';
                html += '   <td>'+data['datetime_disabled_enabled'][i]+'</td>';
                html += '   <td>'+'<button class="btn btn-success btn-sm btn_enable_user"><i class="fa fa-toggle-on"></i> Enable</button>'+'</td>';
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