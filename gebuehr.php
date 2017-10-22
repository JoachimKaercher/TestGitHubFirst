<?php

	// following variables are passed by GET method
	
	$WertGebuehr = "";
	$WertGebuehrOhnePorto = "";
	$xBestDefault = "";

	if ($_GET["WertGebuehr"] != "") {
		$WertGebuehr = $_GET["WertGebuehr"];
		$WertGebuehr = sprintf("%-10.2f",(double)$WertGebuehr / 100.00);
  }
	if ($_GET["WertGebuehrOhnePorto"] != "") {
		$WertGebuehrOhnePorto = $_GET["WertGebuehrOhnePorto"];
		$WertGebuehrOhnePorto = sprintf("%-10.2f",(double)$WertGebuehrOhnePorto / 100.00);
  }
	if ($_GET["yBestDefault"] != "") {
		$xBestDefault = $_GET["yBestDefault"];
  }

?>

<html>
<head>
<title>Geb&uuml;hrenerhebung</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="Content-Style-Type" content="text/css">

<script language="JavaScript" type="text/javascript" src="js/scripts.js">
</script>

<script language="JavaScript" type="text/javascript">
	var headSize = 16, inputSize = 16, captSize = 18, captPadBot = 24, padTop = 10, padBot = 10, buttonWidth = 140, padLeft = 30, padRight = 30;

	if (browserIsMozilla()) {
		headSize = 14; inputSize = 14; captSize = 16; captPadBot = 24; padTop = 5; padBot = 5; buttonWidth = 120;
	}
	<?php
	echo "\txBestDefault = \"$xBestDefault\";\n";
  ?>
	headSize = parseInt(headSize * screen.width/1280);
	inputSize = parseInt(inputSize * screen.width/1280);
	captSize = parseInt(captSize * screen.width/1280);
	captPadBot = parseInt(captPadBot * screen.width/1280);
	padTop = parseInt(padTop * screen.width/1280);
	padBot = parseInt(padBot * screen.width/1280);
	padLeft = parseInt(padLeft * screen.width/1280);
	padRight = parseInt(padRight * screen.width/1280);
	buttonWidth = parseInt(buttonWidth * screen.width/1280);
	document.write("<style type=\"text/css\">\n"+
	".headings { font-family: arial, helvetica, sans-serif; font-size: "+headSize+"pt; padding-top: "+padTop+"pt; padding-bottom: "+padBot+"pt; padding-left: "+padLeft+"pt; padding-right: "+padRight+"pt; }\n"+
	"input { font-family: arial, helvetica, sans-serif; font-size: "+inputSize+"pt; }\n"+
	"input.but { font-family: arial, helvetica, sans-serif; font-size: "+inputSize+"pt; width:"+buttonWidth+"pt; }\n"+
	"caption { font-family: arial, helvetica, sans-serif; font-size: "+captSize+"pt; padding-bottom:"+captPadBot+"pt; }\n"+
	"</style>");

	function inputHandler(ev)
	{
		var pressedKey = (navigator.appName == "Netscape") ? ev.which : ev.keyCode;
		if (pressedKey == 13) {
			document.forms[0].printButton.click();
			return false;
		}
		return true;
	}

	function SetNameGenPfl()
	{
	  if (document.forms[0].gebuehr[0].checked == true)  {
  	  document.forms[0].nameGenPfl.value = "";
    } else {
  	  document.forms[0].nameGenPfl.value = xBestDefault;
    }
	  return true;
  }

</script>
</head>

<body bgcolor="FFF4CE" text="#000000" onLoad="window.focus();">
<form name="gebuehrForm">

<table border="1" frame="box" rules="none" align="center" bordercolor="black" cellspacing="1">
  <caption><b><u>Geb&uuml;hrenerhebung</u></b></caption>
  <tr>
		<td class="headings"><input type="radio" name="gebuehr" value="gebuehrJa" onClick="SetNameGenPfl()" checked>&nbsp;Geb&uuml;hr von &euro; <?php echo $WertGebuehrOhnePorto; ?> erheben</td>
  </tr>
  <tr>
		<td class="headings"><input type="radio" name="gebuehr" value="gebuehrNein" onClick="SetNameGenPfl()">&nbsp;Geb&uuml;hr von &euro; <?php echo $WertGebuehrOhnePorto; ?> <b><u>nicht</u></b> erheben</td>
  </tr>
  <tr>
    <td class="headings">genehmigt von</td>
  </tr>
  <tr>
		<td class="headings"><input type="text" name="nameGenPfl" size="30" maxlength="30" onKeyPress="return inputHandler(event);"></td>
  </tr>
</table>
<table border="0" frame="void" rules="none" align="center" cellspacing="1">
	<tr>
		<td type="text/css" style="padding-top: 20pt;" align="center"><input type="button" class="but" name="printButton" value="OK" onClick="setValues(false,xBestDefault);" onMouseover="changeBackColor(this, '#FFFF00');" onMouseout="changeBackColor(this, '#D0D0D0');"></td>
		<td width="20">&nbsp;</td>
		<td type="text/css" style="padding-top: 20pt;" align="center"><input type="button" class="but" name="resetButton" value="Abbruch" onClick="cancel();" onMouseover="changeBackColor(this, '#FFFF00');" onMouseout="changeBackColor(this, '#D0D0D0');"></td>
	</tr>
</table>
<input type="hidden" name="sidField" value="">
<script type="text/javascript">document.forms[0].sidField.value = opener.document.forms[0].sid.value</script>
</form>
</body>
</html>
