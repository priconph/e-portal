<?php
    session_start();
    $_SESSION["s_username"] = $_POST['ruksaername'];
    $_SESSION["s_group"] = $_POST['rgkraoupid'];
    
?>

<!-- <script src="../dist/jquery/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script> -->
<script type="text/javascript" src="../dist/jquery/jquery-3.3.1.js"></script>
<script src="../dist/cdnjs/popper.min.js" crossorigin="anonymous"></script>

<!-- <link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"> -->
<!-- <script src="../dist/bootstrap/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> -->

<link rel="stylesheet" href="../dist/bootstrap/css/bootstrap.min.css">
<script src="../dist/bootstrap/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="../dist/datatables/datatables.css"/> 
<script type="text/javascript" src="../dist/datatables/datatables.min.js"></script>


<script type="text/javascript" src="../dist/datatables/DataTables-1.10.18/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../dist/datatables/Buttons-1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="../dist/datatables/Buttons-1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="../dist/datatables/FixedHeader-3.1.4/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="../dist/datatables/Select-1.2.6/js/dataTables.select.min.js"></script>
<script type="text/javascript" src="../dist/cloudflare/jszip.min.js"></script>
<script type="text/javascript" src="../dist/cloudflare/pdfmake.min.js"></script>
<script type="text/javascript" src="../dist/cloudflare/vfs_fonts.js"></script>
<script type="text/javascript" src="../dist/datatables/Buttons-1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="../dist/datatables/Buttons-1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../dist/online/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="../dist/online/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="../dist/online/buttons.colVis.min.js"></script>
<script type="text/javascript" src="../dist/momentjs/moment.js"></script>
<script type="text/javascript" src="../dist/numeraljs/numeral.js"></script>


<!-- 

https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css
https://cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap4.min.css -->


<!-- <link rel="stylesheet" type="text/css" href="../dist/datatables/Buttons-1.5.2/css/buttons.dataTables.min.css"/>  -->
<link rel="stylesheet" type="text/css" href="../dist/bootstrap/css/bootstrap.css"/> 
<link rel="stylesheet" type="text/css" href="../dist/datatables/DataTables-1.10.18/css/dataTables.bootstrap4.min.css"/> 
<link rel="stylesheet" type="text/css" href="../dist/datatables/Buttons-1.5.2/css/buttons.bootstrap4.min.css"/> 
<link rel="stylesheet" type="text/css" href="../dist/datatables/Select-1.2.6/css/select.bootstrap4.css"/> 
<link rel="stylesheet" href="../dist/fontawesome-free-5.5.0-web/css/all.css">



<style type="text/css">
    @font-face {
        font-family: 'abadi_regular';
        src: url('../dist/fonts/Abadi MT Condensed Regular.ttf');
    }
    
    @font-face {
        font-family: 'abadi_light';
        src: url('../dist/fonts/abadi_mt_condensed_light/ABALC.ttf');
    }
	html{
		overflow-x: hidden;
		overflow-y:auto;
		font-size: 90%;
		font-family:'Calibri';
	}
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        display: none;
    }
	div.container {
        width:140%;
    }
    .dt-buttons{
    	margin-bottom:-37px;
    	padding-bottom:0;
    }
    .bg-fffecc{
        background-color: #fffecc;
    }
    #div_data_filter{
        margin-left:0;
    }
    .modal-backdrop{

       background-color: white !important;
    }
    .pointer:hover{
        cursor: pointer;
    }
    .complainant-picture{
        width: 50px;
        height: 50px;
        margin-top:6px;
    }
    .border-bottom-idle{
        border-bottom: 1px dashed #adbedb;
    }
    .media-body{
        padding-bottom:5px;
    }
    .r-bg-color-green{
        background-color: green !important;
        box-shadow: none !important;
    }
    .r-bg-color-red{
        background-color: red !important;
        box-shadow: none !important;
    }
    .item-complaint:hover{
        opacity: 0.8;
    }
    .btn-primary, .btn-danger{
        border: 1px solid #dadada;
    }
/*    .col-form-label{
        float: right !important;
    }*/
    datalist {
        max-height: 500px;
        overflow-y: auto;
    }
    .disabled{
        cursor: not-allowed;
    }
    .glow{
        -webkit-box-shadow: 2px 15px 149px -1px rgba(37,181,23,1);
        -moz-box-shadow: 2px 15px 149px -1px rgba(37,181,23,1)
        ;box-shadow: 2px 15px 149px -1px rgba(37,181,23,1);
    }
    .limited-text{
        font-size: 90%;
    }
    input[type="time"]::-webkit-clear-button {
        display: none;
    }
    .modal-w-100{
      max-width: 100% !important;
    }
    .modal-w-60{
      max-width: 60% !important;
    }
    .cont_financial_information, .cont_salary_history, .cont_loan_information{
        display: none;
    }
</style>