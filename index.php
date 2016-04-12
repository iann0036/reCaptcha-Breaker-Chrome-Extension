<?php
    header("Access-Control-Allow-Origin: https://www.google.com");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
?><html>
<head>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<form action="verify.php" method="post">
<div class="g-recaptcha" data-sitekey="6LfKAhITAAAAAAVxIX_T7CAmnaq2yv3t-u93ivRz"></div>
<input type="submit" value="Submit For Verification">
</form>
<hr />
<pre>
</pre>
<pre id="debug"></pre>
<script>
    window.onload = function() {
        var iframes = document.getElementsByTagName('iframe');
        var iframe = iframes[0];
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById("debug").innerHTML = xhttp.responseText;
            }
        };
        xhttp.open("GET", "http://127.0.0.1/getdata.php?url=" + encodeURI(iframe.getAttribute('src')), true);
        xhttp.send();
    }
</script>
</body>
</html>