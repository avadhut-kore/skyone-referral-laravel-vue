


<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>{{ (Config::get('constants.settings.projectname'))}}</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" type="image/ico" href="{{ asset('public/admin/images/favicon.ico') }}">
        <link href="{{ asset('public/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('public/admin/plugins/datatables/dataTables.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/admin/plugins/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="{{ asset('public/admin/plugins/morris/morris.css') }}">
        <link rel="stylesheet" href="{{ asset('public/admin/plugins/chartist/css/chartist.min.css') }}">
        <!-- Summernote css -->
        <link href="{{ asset('public/admin/plugins/summernote/summernote.css') }}" rel="stylesheet" />
        <!--bootstrap-wysihtml5-->
        <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}">
        <link href="{{ asset('public/admin/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/admin/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://unpkg.com/vue-toastr-2/dist/vue-toastr-2.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Source+Sans+Pro:400,600,700">
        <!-- <link href="{{ asset('public/admin/css/style.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('public/admin/css/custom.css') }}" rel="stylesheet" type="text/css"> -->

        <!-- <style type="text/css">
        	section.pay-bg {
			    position: relative;
			    background-image: url(public/images/payment-bg.png);
			    min-height: 700px;
			}
			
			.pay-mid h2 {
			    margin: 0;
			    color: #ffffff;
			}
			.pay-mid {
			    margin-top: 30%;
			    padding: 20px 20px;
			    border: 2px solid #fff;
			    text-align: center;
			    background-color: #0000006b;
			}
        </style> -->
        
    </head>
    <body>
      

      	<section class="pay-bg">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="pay-mid">
							<h2 style="color:red">Please pay remaining amount</h2>
						</div>
						
					</div>
				</div>
			</div>
		</section>
        <!-- jQuery  -->
        <!-- <script src="{{ asset('public/js/app.js')}}"></script> 
 -->
   
    <!-- https://code.jquery.com/jquery-3.3.1.js
https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js
https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js
https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js
https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js
https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js
https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js
https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js
https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js -->  

    
        
        <script src="{{ asset('public/admin/js/jquery.min.js') }}"></script>
        <script src="{{ asset('public/admin/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/admin/js/jquery-ui.min.js') }}"></script>

        <!--Date Picker-->
        <script src="{{ asset('public/admin/plugins/timepicker/bootstrap-timepicker.js') }}"></script>
        <script src="{{ asset('public/admin/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
        <script src="{{ asset('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('public/admin/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/admin/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('public/admin/pages/form-advanced.js') }}"></script>
        <script src="{{ asset('public/admin/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('public/admin/js/detect.js') }}"></script>
        <script src="{{ asset('public/admin/js/fastclick.js') }}"></script>
        <script src="{{ asset('public/admin/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('public/admin/js/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('public/admin/js/waves.js') }}"></script>
        <script src="{{ asset('public/admin/js/jquery.nicescroll.js') }}"></script>
        <script src="{{ asset('public/admin/js/jquery.scrollTo.min.js') }}"></script>
        <script src="{{ asset('public/admin/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}"></script>
        <script src="{{ asset('public/admin/plugins/summernote/summernote.min.js') }}"></script>
        <script src="{{ asset('public/admin/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
        
        <!-- <script src="{{ asset('public/admin/plugins/morris/morris.min.js') }}"></script> -->
        <script src="{{ asset('public/admin/plugins/raphael/raphael-min.js') }}"></script>
        
        <!--RSPV data tables-->
        <script src="{{ asset('public/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('public/admin/plugins/datatables/dataTables.bootstrap.js') }}"></script> 
        <script src="{{ asset('public/admin/plugins/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('public/admin/plugins/datatables/responsive.bootstrap.min.js') }}"></script>
<!--          <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 -->        <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
         <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>  
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> 
         <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>  
           <script src="https://cdn.datatables.net/buttons/1.0.1/js/buttons.print.min.js"></script>
           <script src="https://cdn.datatables.net/buttons/1.0.1/js/buttons.colVis.min.js"></script>
           <!-- <script src="{{ asset('public/admin/pages/dashborad.js') }}"></script> -->

        <!-- Datatable init js -->
        <!-- <script src="{{ asset('public/admin/pages/datatables.init.js') }}"></script> -->

        <script src="{{ asset('public/admin/js/app.js') }}"></script>
        <script>
            $(document).ready(function(){
                $('.summernote').summernote({
                    height: 200,                 // set editor height
                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor
                });

            });
            
            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
            });
        </script>
    </body>
</html>