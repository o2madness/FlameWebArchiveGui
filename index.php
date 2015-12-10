<link href="../adsk_web_entry/css/bbStyles.css" rel="stylesheet" type="text/css">

<?php
/*
	This document is provided as is and if you would like to contribute
	with features and functionality please ask owners@cinesysinc.com to
	increase the research and development budget ;)
*/
 $flame_archive="/usr/discreet/io/bin/flame_archive";
 $ProjectRawList = shell_exec('/usr/discreet/io/bin/flame_archive -l');
 $prl_array = explode ("Projects:",$ProjectRawList);
 $prl_array2 = explode ("Uninitialising",$prl_array[1]);
 $clean_prl_array =preg_replace('!\s+!', ' ', trim($prl_array2[0]) );
 $prl_only_array = explode(" ", $clean_prl_array );
//echo "<pre>$ProjectRawList</pre>";
/*
	Show the projects in a drop down
*/
if ($_POST[startArchive]){
	$cmd2Format="$flame_archive -v -f -F $_POST[archiveName] -i $_POST[chunk] -n $_POST[projects]";
	$cmd2Archive="$flame_archive -v -m 'MetaData' -a -P $_POST[projects] -F $_POST[archiveName] ";
	//echo "\n<br>$cmd2Format<br>\n";


	echo system($cmd2Format,$formatVal);
	$filename=$_POST[archiveName];
	if (file_exists($filename)) {
    		echo "The archive has been formatted<br>\n";
	} else {
    		echo "The archive segments are not here, check permissions<br>\n";
	}
	//echo "\n<br>$cmd2Archive<br>\n";//
	//echo exec($cmd2Archive);
	echo system($cmd2Archive,$retval);
	//echo "<div id='cli_output' style='position:absolute;display:block;top:400px;width:100%;color:yellow'>$retval</div>";

	$www_otoc_file_cmd = "ls -t /usr/discreet/archive/ | grep ".$_POST[projects]." | grep .html ";
	$www_otoc_file=exec($www_otoc_file_cmd);

	echo "<p><input type=button name='otoc' value='HTML OTOC' onclick=\"window.location.href='../archive/$www_otoc_file';\">";
}else{
?>
<head>
<title>
  Archive GUI | CineSys Oceana
</title>

<style>
table{
	padding:5px;
	border-radius:5px;
	border:1px solid #ccc;
}
.headers{
	text-align:center;
	font-weight:bold;
	font-face:Trebuchet,Helvetica,Arial;
	font-size:12px;
}
.fields{
	text-align:center;
	font-weight:bold;
	font-face:Trebuchet,Helvetica,Arial;
	font-size:12px;
}
#startArchive{
	padding:2px 20px 2px 20px;
	background-color:#c00;
	color:#fff;
}
#archiveNameText{
	text-align:center;
        font-face:Trebuchet,Helvetica,Arial;
        font-size:12px;
}
</style>
<script>
function fullpath(){
  document.getElementById("archiveName").value = document.getElementById("path").value + "/" + document.getElementById("projects").value;
  /*
	document.getElementById("archiveNameText").innerHTML = document.getElementById("archiveName").value + "<br>mkdir -m 777 -p " + document.getElementById("path").value;
  */
  document.getElementById("archiveNameText").innerHTML =  "<p>mkdir -m 777 -p " + document.getElementById("path").value;
}

function listFiles(){
	var path = document.getElementById("path").value ;
	var aName = "listing.php?archiveName=" + path ;

	window.open(aName,'_archive');

}
</script>
</head>
<center>
<form method="post" action="<?php echo $_SERVER[PHP_SELF]; ?>">
<table>
<tr class="headers">
<td>Project Name</td>
<td>Destination Path</td>
<td>Archive Segment Size</td>
</tr>
<tr class="fields">
<td><select name="projects" id="projects" onchange="fullpath();">
	<option value="null">Choose Project</option>
<?php
	for ($i=0;$i< count($prl_only_array);$i++){
		echo "<option>$prl_only_array[$i]</option>";
	}
?>
</select></td>
<td><input type="text" placeholder="/folder/for/the/archive/" id="path" name="path" size="40" onchange="fullpath();"></td>
<td><select name="chunk" id="chunk">
	<option value="4GB">4.7GB (DVD)</option>
	<option value="25GB">25GB (Blu-Ray SL)</option>
	<option value="50GB">50GB (Blu-Ray DL)</option>
	<option value="400GB">400GB (LTO-3)</option>
	<option value="460GB">460GB (500GB Drive)</option>
	<option value="930GB">930GB (1TB Drive)</option>
	<option value="1500GB">1500GB (LTO-5)</option>
</select></td>
</tr>
<tr>
<td colspan=4 style="text-align:center;"><input type="submit" value="Start Archive" name="startArchive" id="startArchive" onclick="listFiles();"></td>
</tr>
<td colspan=4 style="text-align:center;"><span id="archiveNameText">Make sure destination path exists and has full read/write permissions.</span></td>
</tr>
</table>
<input type="hidden" name="archiveName" id="archiveName" size="100">
</form>
<img src='./archiveCreate.svg' border=0 width="400px">
</center>

<?php
} //end if else from $_POST[startArchive]
?>
