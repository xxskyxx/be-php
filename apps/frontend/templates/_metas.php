<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<link rel="shortcut icon" href="/customization/images/favicon.png" />
<?php include_stylesheets() ?>
<?php include_javascripts() ?>
<script type="text/javascript">
  function startTime() {
    var time = new Date();
    var h = time.getHours();
    var m = time.getMinutes();
    var s = time.getSeconds();
    if (m < 10) { m = "0" + m };
    if (s < 10) { s = "0" + s };
    document.getElementById('serverTime').innerHTML = h + ":" + m + ":" + s;
    html = setTimeout('startTime()',500);
  }
</script>
</head>
