var handler = "../dist/handler/handler.php";
function load_activity_logs(action){
    var data = {
		'action'		: 'load_activity_logs'
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: handler,
		success : function(data){
            console.log('Sucess');
        	$('#t_activity_logs tbody').empty();
        	var html    = '';
        	for(var i=0;i<data['ctr'];i++){
        		html += '<tr class="latest_data_'+data['tl_adate'][i]+'">';
        		html += '	<td>'+data['formatted_tl_adate'][i]+'</td>';
        		html += '	<td>'+data['tl_type'][i]+'</td>';
        		html += '	<td>'+data['tl_system_name'][i]+'</td>';
        		html += '	<td class="limited-text">'+data['tl_page'][i]+'</td>';
                html += '   <td class="limited-text">'+data['tl_query'][i]+'</td>';
                html += '   <td>'+data['tl_employee_number'][i]+'</td>';
        		html += '	<td>'+data['tl_ip_address'][i]+'</td>';
        		html += '</tr>';
        	}

            $('#t_activity_logs tbody').append(html);

            if(action=='initial'){
                t_activity_logs = $('#t_activity_logs').DataTable({
                    "select"        : true,
                    "scrollX"       : "auto",
                    "scrollY"       : "300px",
                    "scrollCollapse": false,
                    // "paging"        : false,
                    "dom"           : "Bfrtip",
                    "info"          : false,
                    "ordering"      : false,
                    "bProcessing"   : true,
                    "order"         : [[ 0, "desc" ]],
                    "drawCallback"  : function(settings){
                        setTimeout(function(){
                            // $('#cont_loading').fadeOut();
                            $('#t_activity_logs_processing').removeClass('d-inline');    
                            $('#t_activity_logs_processing').addClass('d-none');
                            $('#t_activity_logs_processing').css('z-index','99999999999');

                            // setInterval(function(){
                            //     load_activity_logs();
                            // },3000);
                        },100);
                    }
                });

                t_activity_logs.columns.adjust();
            }
            $('#active_users').text(data['cts']);
            var html = '';
            $('#cont_active_users').empty();
            for(var i=0;i<data['cts'];i++){
                html += '<span>'+data['active_users_emp_no'][i]+'</span><br>';
            }
            $('#cont_active_users').append(html);
            $('#cont_loading').fadeOut();
		},
		error 	: function(data){
			console.log(data);
			alert('Something is wrong, please reload the page. If error persists, kindly contact ISS at local 205. Thank you!'); 
		}
	});
}