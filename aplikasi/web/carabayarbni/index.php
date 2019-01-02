<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Direktorat Keuangan IPB - Tata Cara Pembayran SPP</title>
        <style type="text/css">
<!--

ul {
        list-style: none;
        margin: 0;
        padding: 0;
        }

img {
    border: none;
}


/*- Menu 2--------------------------- */

#menu2 {
       font-family: Verdana, Arial, Helvetica, sans-serif;
	     font-size: 80%;
        font-weight: bold;
	    width: 200px;
       
        border-style: solid solid none solid;
        border-color: #D8D5D1;
        border-size: 1px;
        border-width: 1px;
        }

#menu2 li a {
        height: 32px;
          voice-family: "\"}\"";
          voice-family: inherit;
          height: 24px;
        text-decoration: none;
        }

#menu2 li a:link, #menu2 li a:visited {
        color: #3688BA;
        display: block;
        background:  url(menu2.gif);
        padding: 8px 0 0 30px;
        }

#menu2 li a:hover, #menu2 li #current {
        color: #3688BA;
        background:  url(menu2.gif) 0 -32px;
        padding: 8px 0 0 32px;
        }
-->
</style>
<style>
html, body {
				height: 100%;
			}

body {
margin:0px 0px 0px 0px;
background:#EFEFEF;
}
#header {
	background:url(img/header1.jpg) repeat;
	height:103px;
}

#footer {
	position: relative;
	bottom: 0;
	background-color: #7B3E15;
	border-top:solid thick #C0ED18;
	font-family:tahoma;
	font-size:11px;
	color:#FFFFFF;
	width: 100%;

}
#footer table{
padding:2px;
}
#webname {
font-family:tahoma;
font-size:18px;
color:#FFFFFF;
padding-top:2em;
padding-right:1em;
font-weight:bolder;
}
#titlecontent {
	margin:0px 0px 0px 0px;
	padding-top:0.2em;
	padding-bottom:0.2em;
	padding-left:1em;
	font-family:tahoma;
	font-size:14px;
	font-weight:bold;
	border-top:solid thin #FFFFFF;
	background:#CCCCCC;
	text-transform:uppercase;
	color:#3E3E3E;
}
blink {
font-size:9px;
font-size:xx-small; vertical-align:top;
	color:#FF6600;
	
}
#content {
	margin:0px 0px 0px 0px;
	padding-top:0.2em;
	padding-bottom:0.2em;
	padding-left:1em;
	font-family:tahoma;
	font-size:14px;
	position: relative;
	min-height: 100%;

}
#logo {
	background:url(img/ipb.png) no-repeat;
}
</style>
</head>

<body>
<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="3"><div id="header">
	<div id="webname" align="right"><img src="img/ipb.png" align="absmiddle"/><br />
	MANUAL TATA CARA PEMBAYARAN MELALUI BANK BNI  </div>
	
	</div></td>
  </tr>
  <tr>
    <td colspan="3"><div id="titlecontent">Tata Cara Pembayaran MELALUI BANK BNI </div></td>
  </tr>
  <tr>
    <td width="17%" valign="top"><div id="menu2">
                        <ul>
                                <!-- CSS Tabs -->
<li><a href="?open=teller">Melalui Teller </a></li>
<li><a href="?open=atm">Melalui ATM </a></li>
<!-- <li><a href="?open=internet">Melalui Internet</a></li> -->


                        </ul>
    </div></td>
    <td width="1%">&nbsp;</td>
    <td width="82%" valign="top" id="content"><?php
	if (!isset($_REQUEST['open']))
		{
			include "front.php";
		}
	else
		{
			$open = $_REQUEST['open'];
			include $open . ".php";
		}
	?></td>
  </tr>
  <tr>
    <td colspan="3" valign="top"></td>
  </tr>
  <tr>
    <td colspan="3" valign="top"><div id="footer"></div></td>
  </tr>
</table>
</body>
</html>
