<?
require_once "\\PC\\PORTAL\\app_init.php";
$btnValue = $_POST['data'];
if($btnValue=="cp1Btn")
{
	$sql="SELECT * FROM [OSRR4-G2].[dbo].[Models]";
	
	$qry = $mssql->prepare($sql);
	if($qry->execute())
	{
		while($row = $qry->fetch( PDO::FETCH_OBJ ))
		{
			if(count($row)>1) // check if "Models" has more than 1 row
			{
				echo "Error on getting information, result more than 1!";
				exit;
			}
			else
			{
				$sql="update [OSRR4-G2].[dbo].[Models] set Data = (select Data from [OSRR4-G1].[dbo].Models)";
				$qry = $mssql->prepare($sql);
				if($qry->execute())
				{
					echo "Overwrite successfully";
					exit;
				}
				else
				{
					echo "Error on overwrite";
					exit;
				}
			}
		}
	}
	else
	{
		echo "Error on connection!";
		exit;
	}
}
else
{
	$sql="SELECT * FROM [OSRR4-G1].[dbo].[Models]";
	
	$qry = $mssql->prepare($sql);
	if($qry->execute())
	{
		while($row = $qry->fetch( PDO::FETCH_OBJ ))
		{
			if(count($row)>1) // check if "Models" has more than 1 row
			{
				echo "Error on getting information, result more than 1!";
				exit;
			}
			else
			{
				$sql="update [OSRR4-G1].[dbo].[Models] set Data = (select Data from [OSRR4-G2].[dbo].Models)";
				$qry = $mssql->prepare($sql);
				if($qry->execute())
				{
					echo "Overwrite successfully";
					exit;
				}
				else
				{
					echo "Error on overwrite";
					exit;
				}
			}
		}
	}
	else
	{
		echo "Error on connection!";
		exit;
	}
}
?>