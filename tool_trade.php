<?php
include_once("validateCookie.php");
$myteamID = $_COOKIE['myteamid'];

$buyID = $_GET['buyid'];
$sellID = $_GET['sellid'];
$buyPos = $_GET['buypos'];
include_once("include/init.php");
include_once("include/func.php");

if($_POST['action']==1)
{
	try {
		Trade($myteamID, $sellID, $buyID, $buyPos);	
		// 交易成功，導回myteam_lineup頁面
		?>
		<script language="javascript">
		//轉址
		self.location.href='myteam_lineup.php';
		</script>
		<?php
	} catch (Exception $e) {
		$errmsg = $e->getMessage();
		?>
		<script language="javascript">
		// 交易失敗，跳回上一頁並提示錯誤訊息
		alert("<?php echo $errmsg;?>");
		window.history.back();
		</script>
		<?php
	}
}

//買賣確認訊息
$msg = "確認";
if($sellID)
{
	$sellName = GetPlayerName($sellID);
	$msg = $msg." 賣出 ".$sellName;
}
if($sellID&&$buyID)
	$msg = $msg." 並 ";
//如果有買進
if($buyID)
{
	$buyName = GetPlayerName($buyID);
	$msg = $msg." 買進 ".$buyName;
}
?>

<html>
<body>
<form id="form1" name="form1" method="post" action="">
<input type="hidden" name="action" value="1">
<input type="hidden" name="sellID" value="<?php echo $sellID;?>">
<input type="hidden" name="buyID" value="<?php echo $buyID;?>">
<input type="hidden" name="buyPos" value="<?php echo $buyPos;?>">
<script language="javascript">
if(confirm("<?php echo $msg;?>"))
{
	//alert('交易!');
	window.document.form1.submit();
}
else
{
	//轉址
	//alert('轉址!');
	//self.location.href='myteam_lineup.php';
	window.history.back();
}
</script>
</form>
</body>
</html>