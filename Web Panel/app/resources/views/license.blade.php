<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

<!-- your_view.blade.php -->

<form id="externalForm" action="https://xguard.xpanel.pro/api/license/pay" method="post">
    <input type="hidden" name="email" value="{{ $email }}">
    <input type="hidden" name="domain" value="{{ $domain }}">
</form>

<script type="text/javascript">
    // تابع جاوااسکریپت برای اجرای ارسال فرم به صورت خودکار
    function submitExternalForm() {
        document.getElementById("externalForm").submit();
    }

    $(document).ready(function() {
        submitExternalForm();
    });
</script>