<?php
	include("php/funcs.inc.php");
  include("php/params.php");
	include("php/validSid.php");
	include("php/anredeParams.inc.php");
	include("php/privRights.php");

	// following variables are passed by GET method
	$anredeKeys = ""; $versArtSel = ""; $versArtSelOpt = "";
	$versArtChgAct = ""; $anschrFldVersArt = ""; $anschrFldZusatz = ""; $anschrFldLand = "";
	$privUsrAddrOpt = ""; $versArtChgOrt = ""; $versArtChgOrtVal = "";
  $AdrStrasse = ""; $AdrPostfach = ""; $AdrHausNr = ""; $AdrOrt = ""; $AdrZusatz = ""; $AdrPLZ = ""; $AdrLand = "";
  $EingabeformatBestellerfeld=0;
  
	if ($_GET["anredeKeys"] != "")
		$anredeKeys = $_GET["anredeKeys"];
	if ($_GET["versArtSel"] != "")
		$versArtSel = $_GET["versArtSel"];
	if ($_GET["versArtSelOpt"] != "")
		$versArtSelOpt = $_GET["versArtSelOpt"];
	if ($_GET["versArtChgAct"] != "")
		$versArtChgAct = $_GET["versArtChgAct"];
	if ($_GET["anschrFldVersArt"] != "")
		$anschrFldVersArt = $_GET["anschrFldVersArt"];
	if ($_GET["anschrFldZusatz"] != "")
		$anschrFldZusatz = $_GET["anschrFldZusatz"];
	if ($_GET["anschrFldLand"] != "")
		$anschrFldLand = $_GET["anschrFldLand"];
	if ($_GET["privUsrAddrOpt"] != "")
		$privUsrAddrOpt = $_GET["privUsrAddrOpt"];
	if ($_GET["versArtChgOrt"] != "")
		$versArtChgOrt = $_GET["versArtChgOrt"];
	if ($_GET["versArtChgOrtVal"] != "")
		$versArtChgOrtVal = $_GET["versArtChgOrtVal"];
	if ($_GET["changeDispModeSelVal"] != "")
		$changeDispModeSelVal = $_GET["changeDispModeSelVal"];
	if ($_GET["AdrStrasse"] != "")
		$AdrStrasse = $_GET["AdrStrasse"];
	if ($_GET["AdrHausNr"] != "")
		$AdrHausNr = $_GET["AdrHausNr"];
	if ($_GET["AdrOrt"] != "")
		$AdrOrt = $_GET["AdrOrt"];
	if ($_GET["AdrZusatz"] != "")
		$AdrZusatz = $_GET["AdrZusatz"];
	if ($_GET["AdrPLZ"] != "")
		$AdrPLZ = $_GET["AdrPLZ"];
	if ($_GET["AdrLand"] != "")
		$AdrLand = $_GET["AdrLand"];
	if ($_GET["AdrPostfach"] != "")
		$AdrPostfach = $_GET["AdrPostfach"];
	if ($_GET["EingabeformatBestellerfeld"] != "")
		$EingabeformatBestellerfeld = $_GET["EingabeformatBestellerfeld"];

	// sets all variables passed by POST method
	include("php/setAllPostVars.inc.php");
      if ($_POST["action"] == "2") {
    	  include("php/holeort.php");
        if ($bsuche == true) {
          $arLaender = array();
          foreach ($Laendertabelle as $xSatz) {
            $xgeodbSatz = explode(";",$xSatz);
            $anzLaender = array_push($arLaender,$xgeodbSatz[0]);
            $anzLaender = array_push($arLaender,$xgeodbSatz[1]);
          } 
          if (count($suchErgebnis) > 1) {
            $xort1 = "";
            $anzOrte = 0;
            $anzOrtLC = 0;
            $arOrt = array();
            $arOrtLaendercode = array();
            $arOrtBereinigt = array();
            foreach ($suchErgebnis as $xSatz) {
              $xgeodbSatz = explode(";",$xSatz);
              $anzOrte = array_push($arOrt,$xgeodbSatz[2]);
              $anzOrtLC = array_push($arOrtLaendercode,$xgeodbSatz[2]);
              $anzOrtLC = array_push($arOrtLaendercode,$xgeodbSatz[1]);
            }
            array_multisort($arOrt,SORT_ASC,SORT_STRING);
            $arOrtBereinigt = array_unique($arOrt);
            $anzOrte = count($arOrtBereinigt);
            $suchIndex = array_search($arOrtBereinigt[0],$arOrtLaendercode,TRUE);
            if ($suchIndex === False) {
              $land = "";
            } else {
              $LCODE = $arOrtLaendercode[$suchIndex+1];
              if (($LCODE != "DE") && ($LCODE != "")) {
                $suchIndex = array_search($LCODE,$arLaender,TRUE);
                if ($suchIndex === False) {
                  $land = "";
                } else {
                  $land = $arLaender[$suchIndex+1];
                }  
              } else {
                $land = "";
              }
            }                 
          }
        } else {
          $ort = "";
        }  
      } else {
        if ($_POST["action"] == "3") {
          $bsuche = false;
          $ort = $versArtChgOrtVal;
        } else {
          $bsuche = false;
        }
      }
?>

<html>
<head>
<title>Postanschrift</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="Content-Style-Type" content="text/css">

<script language="JavaScript" type="text/javascript" src="js/scripts.js">
</script>

<script language="JavaScript" type="text/javascript">
	var defaultValArr = new Array();	// associative array containing default address values
  var JSLaendercode = new Array();    // Laendercodes für Update des Ländercodefeldes im Formular bei Auswahl eines Ortes
	var changeDispModeSel = 0; 		// contains info if a dispatch mode with change effect is selected
	var PLZ_ist_bestueckt = false;    // contains info if PLZ is in Formular
  var EingabeformatBestellerfeld = 0; // Eingabeformat des Bestellerfeldes
	var headSize = 10, headSmallSize = 8, inputSize = 10, captSize = 16, captPadBot = 20, padTop = 6, padBot = 6, padLeft = 30, padRight = 140,
			marginRight = 30,	buttonWidth = 150, anredeWidth = 240, canceled = false, sent = false, getCity = false;


<?php  
      echo "var JSbsuche;\n";
      echo "EingabeformatBestellerfeld=" . $EingabeformatBestellerfeld . ";\n"; 
      if (($_POST["action"] == "2") && ($bsuche == true) && (count($suchErgebnis) > 1)) {
        echo "opener.document.forms[0].ort.value = \"$arOrtBereinigt[0]\";\n";
        echo "getCity = 1;\n";
      }
      if (($_POST["action"] == "2") && ($bsuche == true) && (count($suchErgebnis) > 1) && ($anschrFldLand)) {
         
        echo "var JSarLaender = new Array();\n";
        echo "var JSarOrtLaendercode = new Array();\n";
        echo "var AnzElemente;\n";
        echo "var LCode;\n";
        foreach ($arLaender as $xSatz) {
          echo "AnzElemente = JSarLaender.push(\"$xSatz\");\n";          
        }
        foreach ($arOrtLaendercode as $xSatz) {
          echo "AnzElemente = JSarOrtLaendercode.push(\"$xSatz\");\n";
        } 
        echo "LCode = HoleLaenderbezeichnung(false);\n";
      }
      if ($_POST["action"] == "3") {
        echo "defaultValArr[\"postfach\"] = \"" . decodeHTMLEntities($AdrPostfach) . "\";\n";
        echo "defaultValArr[\"strasse\"] = \"" . decodeHTMLEntities($AdrStrasse) . "\";\n";
        echo "defaultValArr[\"hausnr\"] = \"" . decodeHTMLEntities($AdrHausNr) . "\";\n";
        echo "defaultValArr[\"plz\"] = \"" . decodeHTMLEntities($AdrPLZ) . "\";\n";
        echo "defaultValArr[\"ort\"] = \"" . decodeHTMLEntities($AdrOrt) . "\";\n";
        echo "defaultValArr[\"land\"] = \"" . decodeHTMLEntities($AdrLand) . "\";\n";
        echo "defaultValArr[\"zusatz\"] = \"" . decodeHTMLEntities($AdrZusatz) . "\";\n";
        echo "getCity = 1;\n";
      }
                      
?>
  JSbsuche = <?php settype ($bsuche, integer); echo $bsuche; ?>;
  changeDispModeSel = <?php settype ($changeDispModeSelVal, integer); echo $changeDispModeSelVal; ?>;

	if (browserIsMozilla()) {
		headSize = 10; headSmallSize = 10; inputSize = 10; captSize = 14; captPadBot = 16;
		padRight = 120; buttonWidth = 120; anredeWidth = 200;
	}

	headSize = parseInt(headSize * screen.width/1280);
	headSmallSize = parseInt(headSmallSize * screen.width/1280);
	inputSize = parseInt(inputSize * screen.width/1280);
	captSize = parseInt(captSize * screen.width/1280);
	captPadBot = parseInt(captPadBot * screen.width/1280);
	padTop = parseInt(padTop * screen.width/1280);
	padBot = parseInt(padBot * screen.width/1280);
	padLeft = parseInt(padLeft * screen.width/1280);
	padRight = parseInt(padRight * screen.width/1280);
	marginRight = parseInt(marginRight * screen.width/1280);
	buttonWidth = parseInt(buttonWidth * screen.width/1280);
	anredeWidth = parseInt(anredeWidth * screen.width/1280);
	document.write("<style type=\"text/css\">\n"+
	".headings { font-family: arial, helvetica, sans-serif; font-size: "+headSize+"pt; padding-top: "+padTop+"pt; padding-bottom: "+padBot+"pt; padding-left: "+padLeft+"pt; }\n"+
	".headingsSmall { font-family: arial, helvetica, sans-serif; font-size: "+headSmallSize+"pt; font-weight:bold; padding-top: "+parseInt(padTop*0.5)+"pt; }\n"+
	".headingsSmallPad { font-family: arial, helvetica, sans-serif; font-size: "+headSmallSize+"pt; font-weight:bold; padding-top: "+parseInt(padTop*0.5)+"pt; padding-right: "+padRight+"pt;}\n"+
	"input,textarea { font-family: arial, helvetica, sans-serif; font-size: "+inputSize+"pt; margin-right: "+marginRight+"pt; }\n"+
	"select { font-family: arial, helvetica, sans-serif; font-size: "+inputSize+"pt; width: "+anredeWidth+"pt; margin-right: "+marginRight+"pt; }\n"+
	"input.but { font-family: arial, helvetica, sans-serif; font-size: "+inputSize+"pt; width:"+buttonWidth+"pt; }\n"+
	"caption { font-family: arial, helvetica, sans-serif; font-size: "+captSize+"pt; padding-bottom:"+captPadBot+"pt; }\n"+
	"</style>");
</script>
</head>

<body bgcolor="#FFF4CE" text="#000000" onLoad="<?php echo ((($_POST["action"] == 1) || ($_POST["action"] == 2) || ($_POST["action"] == 3)) ? "" : "setDefaultValues($anredeKeys, JSbsuche);"); ?> window.focus();" onUnload="if (!canceled && !sent && !opener.closed && !getCity) resetMain();">

<form name="anredeForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<table border="1" frame="box" rules="none" align="center" bordercolor="black" cellspacing="1">
  <caption><b><u>Postanschrift</u></b></caption>
<?php
if ($anschrFldVersArt) {
?>
  <tr>
    <td class="headings">Versandart:</td>
		<td>
<?php
	if ($versArtSel) {
	echo "<select name=\"versandart\" size=\"1\" onChange=\"vaChangeField(JSbsuche); opener.document.forms[0].versandart.value = this.options[this.options.selectedIndex].text;\">\n";
		$optionArr = explode("|", $versArtSelOpt);
		for ($i=0; $i < count($optionArr); $i++)
			echo "<option". (($versandart == $optionArr[$i]) ? " selected" : ""). ">". $optionArr[$i]. "</option>\n";
		echo "<option". (($versandart == "") ? " selected" : ""). "></option>\n";
		echo "</select>\n";
	}
	else echo <<<VERSART_INP
		<input type="text" name="versandart" value="$versandart" size="30" maxlength="54" onKeyPress="return keyHandler(event, this);" onKeyUp="vaChangeField(); return setMain(event, this);">
VERSART_INP;
?>
		</td>
  </tr>
<?php
}
  echo "<tr>\n";
  echo "<td class=\"headings\">Anrede:</td>\n";
  echo "<td>\n";
if ($anredeKeys) {
	echo "<select name=\"anrede\" size=\"1\" onChange=\"opener.document.forms[0].anrede.value = this.options[this.options.selectedIndex].text;\">\n";
	for ($i=0; $i<count($salutValArr); $i++)
		echo "<option". (($anrede == $salutValArr[$i]) ? " selected" : ""). ">". $salutValArr[$i]. "</option>\n";
	echo "</select>\n";
}
else echo <<<NO_ANRKEYS
			<input type="text" name="anrede" value="$anrede" size="60" maxlength="54" onKeyPress="return keyHandler(event, this);" onKeyUp="return setMain(event, this);">
NO_ANRKEYS;

  echo "</td>\n";
  echo "</tr>\n";
  if (WHICHMACHINE != 2) {
  echo "<tr>\n";
  echo "<td class=\"headings\"></td>\n";
  echo "<td>\n";
if ($anredeKeys) {
	echo "<select name=\"anrede2\" size=\"1\" onChange=\"opener.document.forms[0].anrede2.value = this.options[this.options.selectedIndex].text;\">\n";
	for ($i=0; $i<count($salutValArr); $i++)
		echo "<option". (($anrede == $salutValArr[$i]) ? " selected" : ""). ">". $salutValArr[$i]. "</option>\n";
	echo "</select>\n";
}
else echo <<<NO_ANRKEYS2
    <input type="text" name="anrede2" value="$anrede2" size="60" maxlength="54" onKeyPress="return keyHandler(event, this);" onKeyUp="return setMain(event, this);">
NO_ANRKEYS2;
  echo "</td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class=\"headings\">Anrede Brief 1:</td>\n";
	echo "<td><input type=\"text\" name=\"anredebrief1\" value=\"" . $anredebrief1 . "\" size=\"60\" maxlength=\"60\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  echo "</tr>\n";      	
  echo "<tr>\n";
  echo "<td class=\"headings\">Anrede Brief 2:</td>\n";
	echo "<td><input type=\"text\" name=\"anredebrief2\" value=\"" . $anredebrief2 . "\" size=\"60\" maxlength=\"60\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class=\"headings\">Titel:</td>\n";
	echo "<td><input type=\"text\" name=\"titel\" value=\"" . $titel . "\" size=\"45\" maxlength=\"45\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  echo "</tr>\n";      	
  echo "<tr>\n";
  echo "<td class=\"headings\">Vorname:</td>\n";
	echo "<td><input type=\"text\" name=\"vorname\" value=\"" . $vorname . "\" size=\"48\" maxlength=\"48\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";  	
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class=\"headings\">Nachname:</td>\n";
	echo "<td><input type=\"text\" name=\"nachname\" value=\"" . $nachname . "\" size=\"48\" maxlength=\"48\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";  	
  echo "</tr>\n";
}  
?>        	
  <tr>
		<td class="headings">Name:</td>
<?php
  if (WHICHMACHINE != 2) {
		echo "<td><input type=\"text\" name=\"name\" value=\"" . $name . "\" size=\"54\" maxlength=\"54\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  } else {
		echo "<td><input type=\"text\" name=\"name\" value=\"" . $name . "\" size=\"35\" maxlength=\"35\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  }
?>
  </tr>
<?php
if ($anschrFldZusatz) echo <<<ANSCHRIFT_ZUSATZ
  <tr>
    <td class="headings">Zusatz:</td>
		<td><input type="text" name="zusatz" value="$zusatz" size="54" maxlength="54" onKeyPress="return keyHandler(event, this);" onKeyUp="return setMain(event, this);"></td>
  </tr>
ANSCHRIFT_ZUSATZ;
?>
<?php
  if (WHICHMACHINE != 2) {
  echo "<tr>\n";
  echo "<td class=\"headings\">Empfängererg&auml;nzung:</td>\n";
	echo "<td><input type=\"text\" name=\"empfaenger_erg_1\" value=\"" . $empfaenger_erg_1. "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class=\"headings\">Postalische Erg&auml;nzung:</td>\n";
	echo "<td><input type=\"text\" name=\"postalische_erg_1\" value=\"" . $postalische_erg_1 . "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  echo "</tr>\n";
  echo "<tr>\n";
  echo "<td class=\"headings\">Postfach:</td>\n";
	echo "<td><input type=\"text\" name=\"postfach\" value=\"" . $postfach. "\" size=\"10\" maxlength=\"10\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  echo "</tr>\n";
}
?>  
  <tr>
    <td class="headings">Strasse:</td>
		<td><input type="text" name="strasse" value="<?php echo $strasse; ?>" size="54" maxlength="54" onKeyPress="return keyHandler(event, this);" onKeyUp="return setMain(event, this);"></td>
  </tr>
  <tr>
    <td class="headings">Haus-Nr:</td>
		<td><input type="text" name="hausnr" value="<?php echo $hausnr; ?>" size="10" maxlength="10" onKeyPress="return keyHandler(event, this);" onKeyUp="return setMain(event, this);"></td>
  </tr>
  <tr>
		<td class="headings">Postleitzahl:</td>
<?php
  if (WHICHMACHINE != 2) {
		echo "<td><input type=\"text\" name=\"plz\" value=\"" . $plz ."\" size=\"20\" maxlength=\"20\" onBlur=\"return BearbeitePLZ(this, 'Postleitzahl');\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  } else {
		echo "<td><input type=\"text\" name=\"plz\" value=\"" . $plz ."\" size=\"5\" maxlength=\"5\" onBlur=\"return BearbeitePLZ(this, 'Postleitzahl');\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  }  
?>
  </tr>
  <tr>
<?php
    if ($bsuche == true) {
      if (count($suchErgebnis) > 1) {
          
        if ($anzOrte > 1) {
            echo "<td class=\"headings\">Ort:</td>\n";
            echo "<td>";

            if ($anschrFldLand) {
       	      echo "<select name=\"ort\" size=\"1\" onChange=\"opener.document.forms[0].ort.value = this.options[this.options.selectedIndex].text; opener.document.forms[0].land.value = HoleLaenderbezeichnung(true);\">\n";
            } else {
       	      echo "<select name=\"ort\" size=\"1\" onChange=\"opener.document.forms[0].ort.value = this.options[this.options.selectedIndex].text;\">\n";
            }
            $i = 0;
            foreach($arOrtBereinigt as $xSatz) {
              if ($i == 0) {
                $AdrOrt = $xSatz;
	              echo "<option selected>". $xSatz. "</option>\n";
              } else {
	              echo "<option>". $xSatz. "</option>\n";
              }
              $i++;
            }
	          echo "</select>\n";
            $ort = $arOrtBereinigt[0];            
            $AdrOrt = $ort;
            $suchIndex = array_search($ort,$arOrtLaendercode,TRUE);
            if ($suchIndex === False) {
              $land = "";
            } else {
              $LCODE = $arOrtLaendercode[$suchIndex+1];
              if (($LCODE != "DE") && ($LCODE != "")) {
                $suchIndex = array_search($LCODE,$arLaender,TRUE);
                if ($suchIndex === False) {
                  $land = "";
                } else {
                  $land = $arLaender[$suchIndex+1];
                }  
              } else {
                $land = "";
              } 
            }  
            $AdrLand = $land;
        } else {
          $ort = $arOrtBereinigt[0];
          $AdrOrt = $ort;
          echo "<td class=\"headings\">Ort:</td>\n";
	        echo "<td><input type=\"text\" name=\"ort\" value=\"". $ort . "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
          $suchIndex = array_search($ort,$arOrtLaendercode,TRUE);
          if ($suchIndex === False) {
            $land = "";
          } else {
            $LCODE = $arOrtLaendercode[$suchIndex+1];
            if (($LCODE != "DE") && ($LCODE != "")) {
              $suchIndex = array_search($LCODE,$arLaender,TRUE);
              if ($suchIndex === False) {
                $land = "";
              } else {
                $land = $arLaender[$suchIndex+1];
              }  
            } else {
              $land = "";
            }
          }  
          $AdrLand = $land;
        }  
      } else {
        foreach ($suchErgebnis as $xSatz) {
          $xgeodbSatz = explode(";",$xSatz);
          $ort = $xgeodbSatz[2];
          $AdrOrt = $ort;
          if (($xgeodbSatz[1] != "DE") && ($xgeodbSatz[1] != "")) {
            $suchIndex = array_search($xgeodbSatz[1],$arLaender,TRUE);
            if ($suchIndex === False) {
              $land = "";
            } else {
              $land = $arLaender[$suchIndex+1];
            }  
          } else {
            $land = "";
          } 
          $AdrLand = $land;          
        }  
        echo "<td class=\"headings\">Ort:</td>\n";
	      echo "<td><input type=\"text\" name=\"ort\" value=\"" . $ort . "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
        
      }
      $bsuche = false;
    } else {
	    echo "<td class=\"headings\">Ort:</td>\n";
	    echo "<td><input type=\"text\" name=\"ort\" value=\"". $ort. "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
    }
?>
  </tr>
<?php
if ($anschrFldLand) {
  echo "<tr>\n";
  echo "<td class=\"headings\">Land:</td>\n";
  if (WHICHMACHINE != 2) {
		echo "<td><input type=\"text\" name=\"land\" value=\"" . $land . "\" size=\"54\" maxlength=\"54\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
	} else {
		echo "<td><input type=\"text\" name=\"land\" value=\"" . $land . "\" size=\"20\" maxlength=\"20\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
  }	
  echo "</tr>\n";
}  
?>

<?php
  if (WHICHMACHINE != 2) {
    echo "<tr>\n";
    echo "<td class=\"headings\">Ansprechpartner:</td>\n";
    echo "<td><input type=\"text\" name=\"ansprechpartner\" value=\"" . $ansprechpartner . "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"headings\">Telefonnummer:</td>\n";
    echo "<td><input type=\"text\" name=\"telefon\" value=\"" . $telefon . "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"headings\">Faxnummer:</td>\n";
    echo "<td><input type=\"text\" name=\"faxnr\" value=\"" . $faxnr . "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"headings\">Berater:</td>\n";
    echo "<td><input type=\"text\" name=\"berater\" value=\"" . $berater . "\" size=\"30\" maxlength=\"30\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td class=\"headings\">Gesch&auml;ftsstelle:</td>\n";
    echo "<td><input type=\"text\" name=\"geschaeftsstelle\" value=\"" . $geschaeftsstelle ."\" size=\"54 maxlength=\"54\" onKeyPress=\"return keyHandler(event, this);\" onKeyUp=\"return setMain(event, this);\"></td>\n";
    echo "</tr>\n";
  }  
?>

<?php
if ($userInfoArr["opt"] == "1") echo <<<BESTELLER_USRNAME
  <tr>
		<td class="headings">Besteller:</td>
		<td><input type="text" name="besteller" value="$besteller" size="50" maxlength="50" onKeyPress="return keyHandler(event, this);"></td>
  </tr>
BESTELLER_USRNAME;
?>
</table>
<table border="0" align="center" cellspacing="1">
	<tr>
		<td class="headingsSmall">Postanschrift speichern:&nbsp;</td>
<?php 
echo "<td class=\"headingsSmall" . (($privUsrAddrOpt && $privileged) ? "" : "Pad") . "\"><input type=\"radio\" style=\"margin-right:0pt;\" name=\"anschrSpeichern\" value=\"1\" onClick=\"return checkSaveAddrData(". WHICHMACHINE . ");\"" . (($anschrSpeichern == "1") ? "checked" : "") . ">&nbsp;Ja</td>"
?>
<?php
if ($privUsrAddrOpt && $privileged) echo <<<BELEGDRUCK_OPT
	<td class="headingsSmall" style="padding-left:15pt;"><input type="checkbox" style="margin-right:0pt;" name="addrBelegDruck" value="0" onClick="opener.document.forms[0].belegDrk.value = (this.checked) ? 1 : this.value;" checked>&nbsp;Adressbeleg drucken</td>
BELEGDRUCK_OPT;
?>
	</tr>
	<tr>
		<td class="headingsSmall"></td>
		<td class="headingsSmall<?php echo ($privUsrAddrOpt && $privileged) ? "" : "Pad"; ?>"><input type="radio" style="margin-right:0pt;" name="anschrSpeichern" value="0" <?php echo ($anschrSpeichern == "0") ? "checked" : ""; ?>>&nbsp;Nein</td>
<?php
	if ($privUsrAddrOpt && $privileged)
		echo "<td class=\"headingsSmall\"></td>";
?>
	</tr>
</table>
<table border="0" align="center" cellspacing="1">
	<tr>
<?php
  $Bef1 = "printJ(event," . WHICHMACHINE .");";
  $Bef2 = "if (checkData(". WHICHMACHINE . ")) document.forms[0].submit();";
  $Bef3 = (($_POST["action"] == 1) ? $Bef1 : $Bef2);
	echo "<td type=\"text/css\" style=\"padding-top: 20pt;\" align=\"center\"><input type=\"button\" class=\"but\" name=\"printButton\" value=\"Auftrag abschicken\" onClick=\"sent = true; document.forms[0].action.value = 1; " . $Bef3 . "\" onMouseover=\"changeBackColor(this, '#FFFF00');\" onMouseout=\"changeBackColor(this, '#D0D0D0');\"></td>\n";
?>

		<td width="20">&nbsp;</td>
		<td type="text/css" style="padding-top: 20pt;" align="center"><input type="button" class="but" name="resetButton" value="Abbruch" onClick="canceled = true; cancel();" onMouseover="changeBackColor(this, '#FFFF00');" onMouseout="changeBackColor(this, '#D0D0D0');"></td>
	</tr>
</table>
<input type="hidden" name="sidField" value="<?php echo $sid; ?>">
<input type="hidden" name="privileged" value="<?php echo ($privileged) ? 1 : 0; ?>">
<input type="hidden" name="anredeKeys" value="<?php echo $anredeKeys; ?>">
<input type="hidden" name="action" value="<?php echo $action; ?>">
<input type="hidden" name="versArtSel" value="<?php echo $versArtSel; ?>">
<input type="hidden" name="versArtSelOpt" value="<?php echo $versArtSelOpt; ?>">
<input type="hidden" name="versArtChgAct" value="<?php echo $versArtChgAct; ?>">
<input type="hidden" name="anschrFldVersArt" value="<?php echo $anschrFldVersArt; ?>">
<input type="hidden" name="anschrFldZusatz" value="<?php echo $anschrFldZusatz; ?>">
<input type="hidden" name="anschrFldLand" value="<?php echo $anschrFldLand; ?>">
<input type="hidden" name="privUsrAddrOpt" value="<?php echo $privUsrAddrOpt; ?>">
<input type="hidden" name="versArtChgOrt" value="<?php echo $versArtChgOrt; ?>">
<input type="hidden" name="versArtChgOrtVal" value="<?php echo $versArtChgOrtVal; ?>">
<input type="hidden" name="changeDispModeSelVal" value="<?php echo $changeDispModeSelVal; ?>">
<input type="hidden" name="AdrStrasse" value="<?php echo htmlentities($AdrStrasse); ?>">
<input type="hidden" name="AdrHausNr" value="<?php echo htmlentities($AdrHausNr); ?>">
<input type="hidden" name="AdrOrt" value="<?php echo $AdrOrt; ?>">
<input type="hidden" name="AdrPLZ" value="<?php echo $AdrPLZ; ?>">
<input type="hidden" name="AdrZusatz" value="<?php echo $AdrZusatz; ?>">
<input type="hidden" name="AdrLand" value="<?php echo $AdrLand; ?>">
<input type="hidden" name="AdrPostfach" value="<?php echo $AdrPostfach; ?>">
<script language="JavaScript" type="text/javascript">
<?php
	if ($_POST["action"] == 2) {
    echo "opener.document.forms[0].ort.value = \"$ort\";\n";
	  echo "opener.document.forms[0].land.value = \"$land\";\n";
    if ($userInfoArr["opt"] == "1") {
  	  echo "opener.document.forms[0].besteller.value = \"$besteller\";\n";
    }
    echo "opener.document.forms[0].vorname = \"$vorname\";\n";
    echo "opener.document.forms[0].nachname = \"$nachname\";\n";
    if ($anschrFldVersArt) {
      echo "defaultValArr[\"versandart\"] = opener.document.forms[0].versandart.value;\n";
    } else {
      echo "defaultValArr[\"versandart\"] = \"\";\n";
    }

    echo "defaultValArr[\"anrede\"] = opener.document.forms[0].anrede.value;\n";
    if (WHICHMACHINE != 2) {
      echo "defaultValArr[\"anrede2\"] = opener.document.forms[0].anrede2.value;\n";
      echo "defaultValArr[\"anredebrief1\"] = opener.document.forms[0].anredebrief1.value;\n";
      echo "defaultValArr[\"anredebrief2\"] = opener.document.forms[0].anredebrief2.value;\n";
      echo "defaultValArr[\"titel\"] = opener.document.forms[0].titel.value;\n";
      echo "defaultValArr[\"vorname\"] = opener.document.forms[0].vorname.value;\n";
      echo "defaultValArr[\"nachname\"] = opener.document.forms[0].nachname.value;\n";
      echo "defaultValArr[\"ansprechpartner\"] = \"$ansprechpartner\";\n";
      echo "defaultValArr[\"telefon\"] = \"$tefon\";\n";
      echo "defaultValArr[\"faxnr\"] = \"$faxnr\";\n";
      echo "defaultValArr[\"berater\"] = \"$berater\";\n";
      echo "defaultValArr[\"geschaeftsstelle\"] = \"$geschaeftsstelle\";\n";
    } else {
      echo "defaultValArr[\"anrede2\"] = \"\"\;n";
      echo "defaultValArr[\"anredebrief1\"] = \"\";\n";
      echo "defaultValArr[\"anredebrief2\"] = \"\";\n";
      echo "defaultValArr[\"titel\"] = \"\";\n";
      echo "defaultValArr[\"vorname\"] = \"\";\n";
      echo "defaultValArr[\"nachname\"] = \"\";\n";
      echo "defaultValArr[\"ansprechpartner\"] = \"\";\n";
      echo "defaultValArr[\"telefon\"] = \"\";\n";
      echo "defaultValArr[\"faxnr\"] = \"\";\n";
      echo "defaultValArr[\"berater\"] = \"\";\n";
      echo "defaultValArr[\"geschaeftsstelle\"] = \"\";\n";
    }
    echo "defaultValArr[\"name\"] = opener.document.forms[0].name.value;\n";
    echo "defaultValArr[\"zusatz\"] = opener.document.forms[0].zusatz.value;\n";
    echo "defaultValArr[\"empfaenger_erg_1\"] = opener.document.forms[0].empfaenger_erg_1.value;\n";
    echo "defaultValArr[\"postalische_erg_1\"] = opener.document.forms[0].postalische_erg_1.value;\n";
    echo "defaultValArr[\"postfach\"] = opener.document.forms[0].postfach.value;\n";
    echo "defaultValArr[\"strasse\"] = opener.document.forms[0].strasse.value;\n";
    echo "defaultValArr[\"hausnr\"] = opener.document.forms[0].hausnr.value;\n";
	  echo "defaultValArr[\"plz\"] = opener.document.forms[0].plz.value;\n";
    echo "if (document.forms[0].ort.type == \"text\") {\n";
    echo "defaultValArr[\"ort\"] = opener.document.forms[0].ort.value;\n";
    echo "} else {\n";
    echo "defaultValArr[\"ort\"] = document.forms[0].ort.selectedIndex;\n";
    echo "}\n";  
    if ($anschrFldLand) {
      echo "defaultValArr[\"land\"] = \"$land\";\n";
    } else {
      echo "defaultValArr[\"land\"] = \"\";\n";
    }
  }
?>
<?php
	if ($_POST["action"] == 1) {
    $ort = decodeHTMLEntities($ort);
    echo "opener.document.forms[0].ort.value = \"$ort\";\n";
		echo "document.forms[0].printButton.click();\n";
	}	
?>
</script>


</form>
</body>
</html>
