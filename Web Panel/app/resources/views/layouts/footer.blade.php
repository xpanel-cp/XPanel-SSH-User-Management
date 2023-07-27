<footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
        <div class="row">
            <div class="col my-1">

            </div>
            <div class="col-auto my-1">
                <ul class="list-inline footer-link mb-0">
                    <li class="list-inline-item"><a href="{{route('document')}}">API</a></li>
                    <li class="list-inline-item"><a href="https://github.com/xpanel-cp/XPanel-SSH-User-Management/blob/master/README-EN.md#supporting-us-hearts">Supporting us</a></li>
                    <li class="list-inline-item"><a href="https://github.com/xpanel-cp/XPanel-SSH-User-Management/">GitHub</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- [Page Specific JS] start -->
<script src="/assets/js/plugins/apexcharts.min.js"></script>

<!-- [Page Specific JS] end -->
<!-- Required Js -->
<script src="/assets/js/plugins/popper.min.js"></script>
<script src="/assets/js/plugins/simplebar.min.js"></script>
<script src="/assets/js/plugins/bootstrap.min.js"></script>
<script src="/assets/js/fonts/custom-font.js"></script>
<script src="/assets/js/config.js?v=3.7.6"></script>
<script src="/assets/js/pcoded.js"></script>
<script src="/assets/js/plugins/feather.min.js"></script>
<!-- [Page Specific JS] start -->
<script src="/assets/js/plugins/simple-datatables-en-us.js"></script>
<script src="/assets/js/clipboard.min.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="/assets/js/persian-date.js"></script>
<script src="/assets/js/persian-datepicker.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".example1").persianDatepicker({
            initialValue: false,
            observer: false,
            format: 'YYYY/MM/DD',
            altField: '.observer-example-alt',
            autoClose: true
        });
    });
</script>
<script>

    // basic example
    new ClipboardJS('[data-clipboard=true]').html()('success', function (e) {
        e.clearSelection();
        alert('Copied!');
    });
</script>
<script>
    const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
        sortable: true,
        perPage: 25
    });
</script>

<!-- [Page Specific JS] end -->
</body>
<!-- [Body] end -->

</html>
