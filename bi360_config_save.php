<?
require_once "\\PC\\PORTAL\\app_init.php";
$logData = $_POST['logData'];
$firstFilePath = $_POST['newFilePath'];
$newData =file_get_contents($firstFilePath, true);
$sql = file_get_contents(str_replace('.php','.sql',basename(__FILE__)));
$qry = $mssql->prepare($sql);
$qry->bindParam(":New_Data", $newData, PDO::PARAM_STR);
$qry->bindParam(":ChangeLog", $logData, PDO::PARAM_STR);
if ($qry->execute())
{	
	if(is_file($firstFilePath))
	{
		unlink($firstFilePath);
	}
	echo "Success!";
}
else
{
	echo "Error: ".$qry->errorInfo()[2];
}

?>
