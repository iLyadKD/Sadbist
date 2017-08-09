<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>در حال اتصال ...</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			#main
			{
				background-color: #F1F1F1;
				border: 1px solid #CACACA;
				height: 90px;
				left: 50%;
				margin-left: -265px;
				position: absolute;
				top: 200px;
				width: 530px;
			}
			#main p
			{
				color: #757575;
				direction: rtl;
				font-family: Arial;
				font-size: 16px;
				font-weight: bold;
				line-height: 27px;
				margin-top: 30px;
				padding-right: 60px;
				text-align: right;
			}
		</style>
		<script type="text/javascript">
		function closethisasap()
		{
			document.forms["redirectpost"].submit();
		}
		</script>
	</head>
	<body onload="closethisasap();">
		<form name="redirectpost" method="post" action="<?php echo $url; ?>">
		<?php
			if(!is_null($data))
				foreach ($data as $k => $v)
					echo '<input type="hidden" name="' . $k . '" value="' . $v . '">'."\n";
		?>

		</form>
		<div id="main">
			<p>درحال اتصال به درگاه بانک</p>
		</div>
	</body>
</html>