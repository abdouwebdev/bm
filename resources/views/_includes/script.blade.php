    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('all.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/js/vendors.min.js') }}"></script>
    @livewireScripts()
    
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <script src="{{ asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets/js/core/app-menu.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/core/app.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/customizer.min.js') }}"></script>
    <!-- END: Theme JS-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script> 

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('app-assets/js/scripts/extensions/ext-component-sweet-alerts.min.js') }}"></script>
    <!-- END: Page JS-->

    <!-- BEGIN DATATABLE -->
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/js/buttons.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/buttons.flash.min.js')}}"></script>
    <script src="{{ asset('assets/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/js/jszip.min.js')}}"></script>
    <script src="{{ asset('assets/js/pdfmake.min.js')}}"></script>
    <script src="{{ asset('assets/js/vfs_fonts.js')}}"></script>
    <!-- END DATATABLE -->

    <script>
        if (currentSkin == 'dark-layout') {
            $('.nav-link-style').html('<i class="ficon" data-feather="sun"></i>');
        } else {
            $('.nav-link-style').html('<i class="ficon" data-feather="moon"></i>');
        }

        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
@stack('script')
