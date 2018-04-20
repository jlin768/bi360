<?require_once "class.Diff.php";?>
<?

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

parse_str(parse_url($actual_link)['query'], $urlData);
$firstFile= $urlData['firstFile'];
$secondFile= $urlData['secondFile'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BI360 File Comparison</title>
<script type="text/javascript" src="http://COMMON/jquery.php?version=2.1.1"></script>
<script type="text/javascript" src="http://COMMON/jqueryui.php?version=1.11.0"></script>
<link rel="stylesheet" href="http://COMMON/CSS/intranet.css" type="text/css">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="http://COMMON/CSS/jquery-ui-1.11.0.css">
<style>
.diff td{
        padding:0 0.667em;
        vertical-align:top;
        white-space:pre;
        white-space:pre-wrap;
        font-family:Consolas,'Courier New',Courier,monospace;
        font-size:0.75em;
        line-height:1.333;
      }
.diff-g1Unmodified{
		display:none;
	}
.diff-g2Unmodified{
	display:none;
}
.diff span{
        display:block;
        min-height:1.333em;
        margin-top:-1px;
        padding:0 3px;
      }
.diff {
	margin-left:23%;
}
* html .diff span{
        height:1.333em;
      }

.diff span:first-child{
        margin-top:0;
      }

.diff-g1Deleted span{
        border:1px solid rgb(255,192,192);
        background:rgb(255,224,224);
		cursor:pointer;
      }
.diff-g2Deleted span{
        border:1px solid rgb(255,192,192);
        background:rgb(255,224,224);
		cursor:pointer;
      }
.diff-g1Inserted span{
        border:1px solid rgb(192,255,192);
        background:rgb(224,255,224);
		cursor:pointer;
      }
.diff-g2Inserted span{
        border:1px solid rgb(192,255,192);
        background:rgb(224,255,224);
		cursor:pointer;
      }
#toStringOutput{
        margin:0 2em 2em;
      }
textarea {
    width: 700px;
}
</style>
</head>

<body style="text-align:center;margin-top:40px;">
<button type="button" class="btn btn-primary" id="contentBtn"/>Show all content</button>
<br></br>
<textarea rows="2" id="logArea" class="form-control" style="display:inline; width:50%; font-weight:normal"></textarea>
<br></br>
<button type="button" class="btn btn-primary" id="updateBtn"/>Update</button>
<br></br>
<div id="result"></div>
<button type="button" class="btn btn-primary" id="cp1Btn"/>Copy G1 to G2</button>
<button type="button" class="btn btn-primary" id="cp2Btn"/>Copy G2 to G1</button>
<br></br>
<div>
	<?
	echo Diff::toTable(Diff::compareFiles($firstFile, $secondFile),$firstFile,$secondFile);
	?>
</div>

</body>
<script>

//	function spanClicked() {
//    var spanHtml = $(this).html();
//    var editableText = $("<textarea />");
//    editableText.val(spanHtml);
//    $(this).replaceWith(editableText);
//    editableText.focus();
//    // setup the blur event for this new textarea
//    editableText.blur(editableTextBlurred);
//}
//function editableTextBlurred() {
//    var html = $(this).val();
//    var viewableText = $("<span>");
//    viewableText.html(html);
//    $(this).replaceWith(viewableText);
//    // setup the click event for this new span
//    viewableText.click(spanClicked);
//alert($(this).offsetParent());
//}
//	$("span").click(spanClicked);


	if($("#firstFile").text()=="G1.xml" && $("#secondFile").text()=="G2.xml")
	{
		$("#logArea").css("display","none");
		$("#updateBtn").css("display","none");
		$(".diff").css("margin-left","23%");
	}
	else
	{
		$(".diff").css("margin-left","23%");
		$("#cp1Btn").css("display","none");
		$("#cp2Btn").css("display","none");
	}
	
	$('#contentBtn').click(function(){
		
		if($(this).text()=="Show all content")
		{
			$("td.diff-g1Unmodified").css("display","inline");
			$("td.diff-g2Unmodified").css("display","inline");
			$("#contentBtn").html('Hide unmodified content');
			$(".diff").css("margin-left","0");
		}
		else
		{
			$("td.diff-g1Unmodified").css("display","none");
			$("td.diff-g2Unmodified").css("display","none");
			$("#contentBtn").html('Show all content');
			$(".diff").css("margin-left","23%");
		}
	})
	
	$('#updateBtn').click(function() {
		if($("#logArea").val().trim()=="")
		{
			$("#result").fadeIn('faster');
			$("#result").css("color","red");
			$("#result").html("Please type change log!"); // display response from the PHP script, if any
			$("#result").delay(5000).fadeOut('slow');
		}	
		else
		{
			var form_data = new FormData();                  
			form_data.append('logData',$("#logArea").val());
			form_data.append('newFilePath',$("#firstFile").text());
			$.ajax({
					url: 'bi360_config_save.php', // point to server-side PHP script 
					//dataType: 'text',  // what to expect back from the PHP script, if anything
					type: 'post',
					contentType: false,
					processData: false,
					data: form_data,				
					success: function(php_script_response){
					$("#result").fadeIn('faster');
					$("#result").css("color","red");
					$("#result").html(php_script_response+" Will redirect to index page"); // display response from the PHP script, if any
					if(php_script_response=="Success!")
					{
						window.setTimeout(function(){window.location.href = "index.php";}, 5500);
					}
					$("#result").delay(5000).fadeOut('slow');
					}
				});	
		}
	});
	
	$('#cp1Btn').click(function() {
		var form_data = new FormData();                  
		form_data.append('data','cp1Btn');
		$.ajax({
				url: 'bi360_config_merge.php', // point to server-side PHP script 
				//dataType: 'text',  // what to expect back from the PHP script, if anything
				type: 'post',
				contentType: false,
				processData: false,
				data: form_data,				
				success: function(php_script_response){
				$("#result").fadeIn('faster');
				$("#result").css("color","red");
				$("#result").html(php_script_response +"! Will redirect to index page"); // display response from the PHP script, if any
				if(php_script_response=="Overwrite successfully")
				{
					window.setTimeout(function(){window.location.href = "index.php";}, 5500);
				}
				$("#result").delay(5000).fadeOut('slow');
				}
			});	
	});
	$('#cp2Btn').click(function() {
		var form_data = new FormData();                  
		form_data.append('data','cp2Btn');
		$.ajax({
				url: 'bi360_config_merge.php', // point to server-side PHP script 
				//dataType: 'text',  // what to expect back from the PHP script, if anything
				type: 'post',
				contentType: false,
				processData: false,
				data: form_data,				
				success: function(php_script_response){
				$("#result").fadeIn('faster');
				$("#result").css("color","red");
				$("#result").html(php_script_response +"! Will redirect to index page"); // display response from the PHP script, if any
				
				if(php_script_response=="Overwrite successfully")
				{
					window.setTimeout(function(){window.location.href = "index.php";}, 5500);
				}
				$("#result").delay(5000).fadeOut('slow');
				}
			});	
	});
</script>

</html>








