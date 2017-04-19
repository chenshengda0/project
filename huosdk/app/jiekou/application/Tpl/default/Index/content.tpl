<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN">
<html>
<head>
<title>后台管理中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv=Pragma content=no-cache>
<meta http-equiv=Cache-Control content=no-cache>
<meta http-equiv=Expires content=-1000>
<link href="<{$WEBSITE}>/css/admin/admin.css" rel="stylesheet" type="text/css" />
</head>
<frameset id="fuiframe" border=0 frameSpacing=0 rows="60, *" frameBorder=0>
<input type="hidden" name="test1" id="test1" value="22">
	<frame id="header" name="header" src="<{$WEBSITE}>/admin/index.php?m=Index&a=header" frameBorder=0 noResize scrolling=no>
	<frameset cols="170, *">
		<frame id="mainmenu" name="mainmenu" src="<{$WEBSITE}>/admin/index.php?m=Index&a=mainmenu" frameBorder=0 noResize>
		<frame id="main" name="main" src="<{$WEBSITE}>/admin/index.php?m=Index&a=main" frameBorder=0 noResize scrolling=yes>
	</frameset>
</frameset>
<noframes></noframes>
</html>

