<?
require_once "\\PC\\PORTAL\\app_init.php";
$allowed = array('xml');
$dbNameArray = array("OSRR4-G1","OSRR4-G2");
$version = 1;
$description = 2;
$data = 3;
$group1 = 0;
$group2 = 1;
$id=4;

if(isset($_FILES['file']) && $_FILES['file']['error'] == 0)
{
	$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	if(!in_array(strtolower($extension), $allowed))
	{
		echo "Wrong file type! ";
		echo $user;
		exit;
	}
	if(move_uploaded_file($_FILES['file']['tmp_name'], 'upload/'.$_FILES['file']['name']))
	{
		$i=1;
		$allModelData = array();
		foreach($dbNameArray as $dbName)
		{
			$sql="SELECT * FROM [".$dbName."].[dbo].[Models]";
			$qry = $mssql->prepare($sql);
			if($qry->execute())
			{
				while($row = $qry->fetch( PDO::FETCH_OBJ ))
				{
					if(count($row)>1) // check if "Models" has more than 1 row
					{
						echo "Error on getting information!";
						exit;
					}
					else
					{
						$modelData = array("OSRR4-G".$i, $row->Version,$row->Description,$row->Data, $row->Id);
						$i++;
					}
				}
				array_push ($allModelData, $modelData);
			}
			else
			{
				echo "Error on connection!";
				exit;
			}
		}
		if($allModelData[$group1][$data]==$allModelData[$group2][$data])
		{
			$dataFileG1 = fopen("G1.xml", "w") or die("Unable to open file!");
			fwrite($dataFileG1, $allModelData[$group1][$data]);
			fclose($dataFileG1);
			
			$newDataFile = "upload/".$_FILES['file']['name'];
			$newData =file_get_contents("upload/".$_FILES['file']['name'], true);
			$link = "<script>window.location='bi360_config_compare.php?firstFile=".$newDataFile."&secondFile=G1.xml'</script>";
			//$link = "<script>window.open('bi360_config_compare.php?firstFile=".$newDataFile."&secondFile=G1.xml')</script>";
			echo $link;
		}
		else
		{
			$dataFileG1 = fopen("G1.xml", "w") or die("Unable to open file!");
			fwrite($dataFileG1, $allModelData[$group1][$data]);
			fclose($dataFileG1);
			
			$dataFileG2 = fopen("G2.xml", "w") or die("Unable to open file!");
			fwrite($dataFileG2, $allModelData[$group2][$data]);
			fclose($dataFileG2);
			echo "Diff";
		}
	}
	else
	{
		echo "Error on file process!";
		exit;
	}
}
else
{
	echo "Error!";
	exit;
}



?>