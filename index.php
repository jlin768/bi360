<?
require_once "\\PC\\PORTAL\\app_init.php";
$toDate = date("Y-m-d H:i:s");
$fromDate = date("Y-m-d H:i:s");
if (isset($_POST['submit'])) {
    $fromDate = $_POST['datepickerFrom'];
    $toDate = $_POST['datepickerTo'];
  }
  $sql = "select ChangeLog, Time_Stamp from [VCI-Intranet].[dbo].[BI360_Change_History] where Time_Stamp between '".$fromDate."' and '".$toDate."'";
  $stmt = $mssql->prepare( $sql );
  if($stmt->execute())
 {
	$result = $stmt->fetchAll( PDO::FETCH_OBJ );
 }
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BI360 Config File Upload</title>
<script type="text/javascript" src="http://COMMON/jquery.php?version=2.1.1"></script>
<script type="text/javascript" src="http://COMMON/jqueryui.php?version=1.11.0"></script>
<link rel="stylesheet" href="http://COMMON/CSS/intranet.css" type="text/css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="http://COMMON/CSS/jquery-ui-1.11.0.css">
<script type="text/javascript" src="http://COMMON/JS/zebra_datepicker.js"></script>
<link rel="stylesheet" href="http://COMMON/CSS/zebra_datepicker/default.css" type="text/css">
<style>
* { font-size: 10pt; font-family: Verdana; font-weight:bold;}
.pop-up {
  display: none;
  position: absolute;
  width: 280px;
  padding: 10px;
  background: #eeeeee;
  color: #000000;
  border: 1px solid #1a1a1a;
  font-size: 90%;
  word-wrap: break-word;
}
</style>
</head>


<body style="text-align:center;margin-top:40px;">
<label class="btn btn-primary" for="my-file-selector">
    <input name="file" id="my-file-selector" type="file" style="display:none;">
    Choose File
</label>&nbsp;<span id="filePath"></span>
	<br></br>
<button type="button" class="btn btn-primary" id="uploadBtn"/>Upload</button>
<br></br>
<div id="result"></div>
<hr>
Search Log
<br></br>
<form method="post" name = "form">
From Date: <input type="text" id="datepickerFrom" name="datepickerFrom">&nbsp;&nbsp;&nbsp;&nbsp;To Date: <input type="text" id="datepickerTo" name="datepickerTo" value=<?=$toDate?>>&nbsp;&nbsp;&nbsp;&nbsp;<input name="submit" type="submit" />
</form>

<div id="logSearchResult">
	<?
		if(count($result)>0)
		{
			$index=0;
			echo "<table style='margin-left:22%;' cellspacing='0' cellpadding='2' border='1'><thead><tr><th style='text-align:center;'>Change Log</th><th style='text-align:center;'>Changed Date</th></tr></thead><tbody>";
			foreach($result as $resultRow): $index=$index+1;?>
			<tr>
				<td style="text-align:left; padding:5px; ">
					<div id="trigger-<?=$index?>" class="logInfo" style="overflow:hidden; text-overflow:ellipsis; width:800px;cursor:pointer;"><?=$resultRow->{'ChangeLog'};?></div>
					<div id="pop-up-<?=$index?>" class="pop-up">
					  <p>
						<?=$resultRow->{'ChangeLog'};?>
					  </p>
					</div>
				</td>
				<td style="text-align:center;padding:5px; "><?=$resultRow->{'Time_Stamp'};?></td>
			</tr>
			<?endforeach;
		}
	?>
	</tbody>
</table>
</div>
</body>

<script>
	$(".logInfo").hover(
		function() 
		{
			var popUpId = (this.id).replace("trigger","pop-up");
			$("#"+popUpId).show();
		},
		function()
		{
			var popUpId = (this.id).replace("trigger","pop-up");
			$("#"+popUpId).hide();
		});


	$("#datepickerFrom").Zebra_DatePicker();
	$("#datepickerTo").Zebra_DatePicker();
	
	
	$( "#my-file-selector" ).change
	(
		function() 
		{
			$( "#filePath" ).html(this.value.replace("C:\\fakepath\\",""));
		}
	);
	$('#uploadBtn').click(
		function() 
		{
			var file_data = $('#my-file-selector').prop('files')[0]; 
			var form_data = new FormData();                  
			form_data.append('file', file_data);
			form_data.append('changeLog',$("#logArea").val());
			$.ajax({
				url: 'bi360_config_process.php',
				type: 'post',
				contentType: false,
				processData: false,
				data: form_data,				
				success: function(php_script_response){
					if(php_script_response=="Diff")
					{
						$("#result").fadeIn('faster');
						alert("Group 1 and Group 2 data doesn't match. Page will redirect to a comparison page."); 
						window.location='bi360_config_compare.php?firstFile=G1.xml&secondFile=G2.xml';
					}
					else
					{
						$("#result").fadeIn('faster');
						$("#result").html(php_script_response); 
						$("#result").delay(5000).fadeOut('slow');
					}

				}
			});	
		}
	);
</script>
</html>