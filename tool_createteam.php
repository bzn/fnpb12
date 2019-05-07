<?php
include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");
include_once("class/myteam.class.php");
include_once("validateCookie.php");

if($_POST['action'] == 1)
{
  //echo "teamname:".$_POST['teamname'];
  //echo "<BR>";
  //echo "favteam.".$_POST['favteam'];
  // 如果已創帳但未創隊
  if(!$_COOKIE['myteamid'] && $_COOKIE['userid'])
  {
    if(!empty($_COOKIE['userid']) && !empty($_POST['teamname']) && !empty($_POST['favteam']))
    {
      CreateMyTeam($_COOKIE['userid'], $_POST['teamname'], $_POST['favteam']);
      ?>
      <script language="javascript">
      //轉址
      self.location.href='index.php';
      </script>
      <?php
    }
    else
    {
      ?>
      <script language="javascript">
      alert("輸入資料不完全");
      </script>
      <?php
    }
  }
  else
  {
    ?>
    <script language="javascript">
    alert("創隊失敗");
    </script>
    <?php
  }
}

?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title></title>
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <script src="js/libs/modernizr-2.0.6.min.js"></script>
<!--  <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css"> -->
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" media="screen" title="no title">
  <link rel="stylesheet" href="css/master.css" type="text/css" media="screen" title="no title">
  <script src="js/libs/i18n.js" type="text/javascript" charset="utf-8"></script>
  <script src="l10n/tw.js" type="text/javascript"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.min.js"><\/script>')</script>
  <script src="js/api.js" type="text/javascript" charset="utf-8"></script>
  <script src="js/bootstrap-tabs.js"></script>
  <script src="js/bootstrap-twipsy.js"></script>
  <script src="js/bootstrap-dropdown.js"></script>
  <script src="js/handlebars.js" type="text/javascript" charset="utf-8"></script>
</head>
<body id="create_team">
  <div id="content" class="container canvas">
    <header>
      <?php include(dirname(__FILE__)."/header.php"); ?>
    </header>
    <div class="row">
      <form id="form1" name="form1" method="post" action="" class="form-horizontal">
        <input type="hidden" name="hiddenString" />
        <legend>建立你的球隊</legend>
        <div class="control-group">
          <label for="teamname" class="control-panel">球隊名稱</label>
          <div class="controls">
            <input type="text" class="control-xlarge focus" name="teamname" id="teamname" placeholder="輸入....">
          </div>
        </div>
        <div class="control-group">
          <label for="team_name" class="control-panel">喜愛的球隊</label>
          <div class="controls">
            <select name="favteam" id="favteam">
              <option>選擇球隊</option>
              <option value="1">Giants</option>
              <option value="2">Swallows</option>
              <option value="3">BayStars</option>
              <option value="4">Dragons</option>
              <option value="5">Tigers</option>
              <option value="6">Carps</option>
              <option value="7">Lions</option>
              <option value="8">Fighters</option>
              <option value="9">Marines</option>
              <option value="10">Eagles</option>
              <option value="11">Buffaloes</option>
              <option value="12">Hawks</option>
            </select>
          </div>
        </div>
        <div class="form-actions">
          <input type="submit" name="Submit" value="建立球隊" class="mar5 v12"/>
        </div>
        <input type="hidden" name="action" value="1" />
      </form>
    </div>
    <footer>
      © oh!dada 2012
    </footer>
  </div>
  <!-- scripts concatenated and minified via ant build script-->
  <script src="js/plugins.js"></script>
  <script src="js/script.js"></script>
  <script>
    var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']]; // Change UA-XXXXX-X to be your site's ID
    (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
  </script>
  <!--[if lt IE 7 ]>
    <script src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
    <script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
  <![endif]-->
</body>
</html>
