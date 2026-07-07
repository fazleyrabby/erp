  <!-- Bootstrap tether Core JavaScript -->
  <script src="{{asset('backend/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
  <script src="{{asset('backend/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('backend/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
  <script src="{{asset('backend/assets/extra-libs/sparkline/sparkline.js')}}"></script>
  <!--Wave Effects -->
  <script src="{{asset('backend/dist/js/waves.js')}}"></script>
  <!--Menu sidebar -->
  <script src="{{asset('backend/dist/js/sidebarmenu.js')}}"></script>
  <!--Custom JavaScript -->
  <script src="{{asset('backend/dist/js/custom.min.js')}}"></script>
  <!--This page JavaScript -->
  <!-- <script src="{{asset('public/backend/')}}dist/js/pages/dashboards/dashboard1.js"></script> -->
  <!-- Charts js Files -->
  <script src="{{asset('backend/assets/libs/flot/excanvas.js')}}"></script>
  <script src="{{asset('backend/assets/libs/flot/jquery.flot.js')}}"></script>
  <script src="{{asset('backend/assets/libs/flot/jquery.flot.pie.js')}}"></script>
  <script src="{{asset('backend/assets/libs/flot/jquery.flot.time.js')}}"></script>
  <script src="{{asset('backend/assets/libs/flot/jquery.flot.stack.js')}}"></script>
  <script src="{{asset('backend/assets/libs/flot/jquery.flot.crosshair.js')}}"></script>
  <script src="{{asset('backend/assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js')}}"></script>
  <script src="{{asset('backend/dist/js/pages/chart/chart-page-init.js')}}"></script>

  <!-- jquery-validation -->
<script src="{{asset('backend/assets/assets/libs/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{asset('backend/assets/assets/libs/jquery-validation/dist/additional-methods.js')}}"></script>

  <!-- delete sweetalert2 -->
<script src="{{asset('backend/dist/js/sweetalert2.js')}}"></script>

  <!--DataTable
  <script src="{{asset('backend/assets/extra-libs/multicheck/datatable-checkbox-init.js')}}"></script>
  <script src="{{asset('backend/assets/extra-libs/multicheck/jquery.multicheck.js')}}"></script>-->
  <script src="{{asset('backend/assets/extra-libs/DataTables/datatables.min.js')}}"></script>
	<!--<script src="https://jafree.alitechbd.com/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="https://jafree.alitechbd.com/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="https://jafree.alitechbd.com/js/dataTables.buttons.min.js"></script>-->
  <!--mousetrap ShortCut Keys-->
  <script src="{{asset('backend/jslibrary/mousetrap.min.js')}}"></script>

  <script>
        /****************************************
         *       Basic Table                   *
         ****************************************/
		
		//$('#zero_config').DataTable();
		
    </script>

<!---Ajax Toaster Notifications -->
<script src="{{asset('backend/jslibrary/toastr.min.js')}}"></script>
<script src="{{asset('backend/jslibrary/select2.min.js')}}"></script>

<script src="{{asset('/')}}ckeditor/ckeditor.js"></script>

<script>
var ckeditorElements = ['company_report_header', 'application_body', 'footer_body', 'summary_header', 'summary_description', 'terms_conditions', 'remarks', 'editRemarks', 'editApplication_body', 'editSummary_header', 'editSummary_description', 'editTerms_conditions'];
ckeditorElements.forEach(function(id) {
    if (document.getElementById(id)) {
        CKEDITOR.replace(id);
    }
});
</script>

