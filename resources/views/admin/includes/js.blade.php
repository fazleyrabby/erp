  <!-- Bootstrap + Tabler Core JS -->
  <script src="{{asset('tabler/js/tabler.min.js')}}"></script>

  <!-- DataTables -->
  <script src="{{asset('backend/assets/extra-libs/DataTables/datatables.min.js')}}"></script>

  <!-- Toastr -->
  <script src="{{asset('backend/jslibrary/toastr.min.js')}}"></script>

  <!-- Select2 -->
  <script src="{{asset('backend/jslibrary/select2.min.js')}}"></script>

  <!-- SweetAlert2 -->
  <script src="{{asset('backend/dist/js/sweetalert2.js')}}"></script>

  <!-- Mousetrap Shortcut Keys -->
  <script src="{{asset('backend/jslibrary/mousetrap.min.js')}}"></script>

  <!-- jquery-validation -->
  <script src="{{asset('backend/assets/assets/libs/jquery-validation/dist/jquery.validate.min.js')}}"></script>
  <script src="{{asset('backend/assets/assets/libs/jquery-validation/dist/additional-methods.js')}}"></script>

  <!-- CKEditor -->
  <script src="{{asset('/')}}ckeditor/ckeditor.js"></script>

  <script>
    var ckeditorElements = ['company_report_header', 'application_body', 'footer_body', 'summary_header', 'summary_description', 'terms_conditions', 'remarks', 'editRemarks', 'editApplication_body', 'editSummary_header', 'editSummary_description', 'editTerms_conditions'];
    ckeditorElements.forEach(function(id) {
        if (document.getElementById(id)) {
            CKEDITOR.replace(id);
        }
    });
  </script>
