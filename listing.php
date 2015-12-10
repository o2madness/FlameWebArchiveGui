<link href="../adsk_web_entry/css/bbStyles.css" rel="stylesheet" type="text/css">
<?php

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    // $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}


$dir = $_GET[archiveName];

$files1 = scandir($dir);

foreach ($files1 as $value) {
	if ($value !="." && $value !=".." && $value !=".DS_Store"){
		$fullFile=$dir."/".$value;
		$units=formatBytes (filesize($fullFile));
		$theFileSize_array=explode(' ',$units);
		switch(trim($theFileSize_array[1])){
		case "TB":
		$theFileSize=number_format($theFileSize_array[0]/1000000000000,2);
		break;
		case "GB":
		$theFileSize=number_format($theFileSize_array[0]/1000000000,2);
		break;
		case "MB":
		$theFileSize=number_format($theFileSize_array[0]/1000000,2);
		break;
		default:
		$theFileSize=number_format($theFileSize_array[0]/1000,2);
		}
    		echo "file: $value $theFileSize $theFileSize_array[1]<br>\n";

	}
}

echo "<meta http-equiv=refresh content='5;URL=$_SERVER[PHP_SELF]?archiveName=$_GET[archiveName]' >";
echo "<title>Archive Files</title>";
?>
