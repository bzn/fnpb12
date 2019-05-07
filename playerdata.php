<?php
session_start();
$playerid = $_GET['playerid'];

include_once("include/init.php");
include_once("include/define.php");
include_once("include/func.php");
include_once("class/db.class.php");

$playerBaseData = GetPlayerBaseData($playerid);
$playerInfo = GetPlayerInfo($playerid);
$playerRec2011 = GetPlayerRec2011($playerid);
$playerRecDaily = GetPlayerRecDaily($playerid);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Fantasy Baseball powered by oh!dada</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.js"></script>

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="css/prettify.css" rel="stylesheet">
    <link href="css/overwrite.css" rel="stylesheet">

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon" href="assets/ico/bootstrap-apple-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/ico/bootstrap-apple-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/ico/bootstrap-apple-114x114.png">
  </head>

  <body>
    
    <!-- Masthead (blueprinty thing)
    ================================================== -->
    <header class="jumbotron masthead" id="overview">
      <div class="inner">
        <div class="container">
          <h1>Fantasy Baseball '12</h1>
          <p class="lead">
            Powered by oh!dada<br />
          </p>
        </div><!-- /container -->
      </div>
    </header>

    <div class="container">
      <div class="content">
        <div class="topbar-wrapper" style="z-index: 5;">
          <div class="topbar" data-dropdown="dropdown" >
            <div class="topbar-inner">
              <div class="container">
                
                <ul class="nav">
                  <li class="active"><a href="#">首頁</a></li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle">我的球隊</a>
                    <ul class="dropdown-menu">
                      <li><a href="#">球員名單</a></li>
                      <li><a href="#">交易記錄</a></li>
                      <li><a href="#">積分記錄</a></li>
                      <li><a href="#">更換隊伍名稱</a></li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle">我的聯盟</a>
                    <ul class="dropdown-menu">
                      <li><a href="#">聯盟首頁</a></li>
                      <li><a href="#">查看所有聯盟</a></li>
                      <li><a href="#">積分記錄</a></li>
                      <li><a href="#">更換聯盟</a></li>
                      <li><a href="#">邀請朋友加入聯盟</a></li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle">記錄室</a>
                    <ul class="dropdown-menu">
                      <li><a href="#">薪資變動</a></li>
                      <li><a href="#">對戰賽程分析</a></li>
                      <li><a href="#">昨日最佳表現</a></li>
                      <li><a href="#">物超所值球員</a></li>
                      <li><a href="#">最有價值球員</a></li>
                    </ul>
                  </li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle">排行榜</a>
                    <ul class="dropdown-menu">
                      <li><a href="#">聯盟積分排行榜</a></li>
                      <li><a href="#">聯盟資產排行榜</a></li>
                      <li><a href="#">積分Top20</a></li>
                      <li><a href="#">資產Top20</a></li>
                    </ul>
                  </li>

                </ul>

                
                <ul class="nav secondary-nav">
                  <form class="pull-left" action="">
                    <input type="text" placeholder="Search" />
                  </form>
                </ul>
             </div>
           </div><!-- /topbar-inner -->
          </div><!-- /topbar -->
        </div><!-- /topbar-wrapper -->

        <!-- About Bootstrap
        ================================================== -->
        <section id="player">
          <div class="row">
            <div class="span2">
              <ul class="media-grid">
                <li>
                  <a href="#">
                    <img class="thumbnail" src="http://placehold.it/90x90" alt="">
                  </a>
                </li>
              </ul>
            </div>
            <div class="span8 info">
              <h4>
                <?php echo $playerBaseData->name;?>
                <span class="label warning">HOT</span>
                <span class="label notice">COLD</span>
                 | #<?php echo $playerBaseData->no;?>
                 | <?php 
                    if($playerBaseData->p) echo "P ";
                    if($playerBaseData->c) echo "C ";
                    if($playerBaseData->fb) echo "1B ";
                    if($playerBaseData->sb) echo "2B ";
                    if($playerBaseData->tb) echo "3B ";
                    if($playerBaseData->ss) echo "SS ";                    
                    if($playerBaseData->of) echo "OF ";
                    if($playerBaseData->dh) echo "DH ";
                    ?>
                </h4>
                <p>生年月日: <?php echo $playerInfo->birthday;?></p>
                <p>投打: <?php if($playerInfo->pitch === "R") echo "右"; else echo "左";?>投<?php if($playerInfo->bat === "R") echo "右"; else if($playerInfo->bat === "L") echo "左"; else if($playerInfo->bat === "S") echo "兩"?>打</p>
                <p>順位: 
                <?php 
                if($playerInfo->draft_year>0) 
                  echo $playerInfo->draft_year."年";
                if($playerInfo->draft_round>0) 
                  echo "第".$playerInfo->draft_round."輪";
                if($playerInfo->draft_pick>0) 
                  echo "第".$playerInfo->draft_pick."選";
                if($playerInfo->draft_year == 0 && $playerInfo->draft_round && $playerInfo->draft_pick)
                  echo "-";
                ?>
                </p>
            </div>
            <div class="span2">
              <button class="btn small danger">$19.00M買入</button> 
              <button class="btn small success">$19.00M賣出</button> 
            </div>
          </div>

          <ul class="tabs" data-tabs="tabs">
            <li class="active"><a href="#profile">球員資料</a></li>
            <li><a href="#log">本季明細</a></li>
          </ul>
          <div id="my-tab-content" class="tab-content">
            <div class="active tab-pane" id="profile">
              <?php if($playerBaseData->p){?>
              <table class="bordered-table condensed-table zebra-striped">
                <thead>
                  <tr>
                    <th colspan="14" style="background-color: #0077ff;">
                      記錄
                    </th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>G</th>
                    <th>IP</th>
                    <th>W</th>
                    <th>L</th>
                    <th>SV</th>
                    <th>BB</th>
                    <th>K</th>
                    <th>ERA</th>
                    <th>WHIP</th>
                    <th>積分</th>
                    <th>平均</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>                
                    <td>本季</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>                
                  </tr>
                  <tr>
                    <td>昨天</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>  
                  </tr>
                  <tr>
                    <td>7天</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>  
                  </tr>
                  <tr>
                    <td>15天</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>  
                  </tr>
                  <tr>
                    <td>30天</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>  
                  </tr>
                </tbody>
              </table>
              <?php }else{?>
              <table class="bordered-table condensed-table zebra-striped">
                <thead>
                  <tr>
                    <th colspan="14" style="background-color: #0077ff;">
                      記錄
                    </th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>G</th>
                    <th>AB</th>
                    <th>R</th>
                    <th>HR</th>
                    <th>RBI</th>
                    <th>SB</th>
                    <th>AVG</th>
                    <th>OBP</th>
                    <th>SLG</th>
                    <th>積分</th>
                    <th>平均</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>                
                    <td>本季</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>                
                  </tr>
                  <tr>
                    <td>昨天</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>  
                  </tr>
                  <tr>
                    <td>7天</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>  
                  </tr>
                  <tr>
                    <td>15天</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>  
                  </tr>
                  <tr>
                    <td>30天</td>
                    <td>20</td>
                    <td>200.1</td>
                    <td>18</td>
                    <td>6</td>
                    <td>0</td>
                    <td>54</td>
                    <td>203</td>
                    <td>1.23</td>
                    <td>0.80</td>
                    <td>1866</td>
                    <td>101.2</td>  
                  </tr>
                </tbody>
              </table>
              <?php }?>      

              <?php if($playerBaseData->p){?>
              <table class="bordered-table condensed-table zebra-striped">
                <thead>
                  <tr>
                    <th colspan="14" style="background-color: #cccc99;">
                      最近5日記錄
                    </th>
                  </tr>
                  <tr>
                    <th>日期</th>
                    <th>對手</th>
                    <th>比分</th>
                    <th>Dev</th>
                    <th>IP</th>
                    <th>H</th>
                    <th>R</th>
                    <th>ER</th>
                    <th>HR</th>
                    <th>BB</th>
                    <th>K</th>
                    <th>積分</th>
                    <th>價錢</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>                
                    <td>04-30</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19.00M</td>               
                  </tr>
                  <tr>
                    <td>04-29</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19.00M</td>  
                  </tr>
                  <tr>
                    <td>04-28</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19.00M</td>  
                  </tr>
                  <tr>
                    <td>04-27</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19.00M</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19.00M</td>  
                  </tr>
                </tbody>
              </table>

              <?php }else{?>

              <table class="bordered-table condensed-table zebra-striped">
                <thead>
                  <tr>
                    <th colspan="14" style="background-color: #cccc99;">
                      最近5日記錄
                    </th>
                  </tr>
                  <tr>
                    <th>日期</th>
                    <th>對手</th>
                    <th>比分</th>
                    <th>AB</th>
                    <th>H</th>
                    <th>R</th>
                    <th>HR</th>
                    <th>RBI</th>
                    <th>SB</th>
                    <th>K</th>
                    <th>TBBs</th>
                    <th>積分</th>
                    <th>價錢</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>                
                    <td>04-30</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>               
                  </tr>
                  <tr>
                    <td>04-29</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-28</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-27</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                </tbody>
              </table>
              <?php }?>
              <?php if($playerBaseData->p){?>
              <table class="bordered-table condensed-table zebra-striped">
                <thead>
                  <tr>
                    <th colspan="14" style="background-color: #cccc99;">
                      過去成績
                    </th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>G</th>
                    <th>IP</th>
                    <th>W</th>
                    <th>L</th>
                    <th>SV</th>
                    <th>BB</th>
                    <th>K</th>
                    <th>ERA</th>
                    <th>WHIP</th>
                    <th >積分</th>
                    <th>平均</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2011</td>
                    <td><?php echo $playerRec2011->g;?></td>
                    <td><?php echo $playerRec2011->ip;?></td>
                    <td><?php echo $playerRec2011->w;?></td>
                    <td><?php echo $playerRec2011->l;?></td>
                    <td><?php echo $playerRec2011->sv;?></td>
                    <td><?php echo $playerRec2011->bb;?></td>
                    <td><?php echo $playerRec2011->k;?></td>
                    <td><?php printf("%.2f",$playerRec2011->era);?></td>
                    <td><?php printf("%.2f",$playerRec2011->whip);?></td>
                    <td><?php echo $playerRec2011->points;?></td>
                    <td><?php printf("%.1f",$playerRec2011->ppg);?></td>  
                  </tr>
                </tbody>
              </table>
              <?php }else{?>
              <table class="bordered-table condensed-table zebra-striped">
                <thead>
                  <tr>
                    <th colspan="14" style="background-color: #cccc99;">
                      過去成績
                    </th>
                  </tr>
                  <tr>
                    <th></th>
                    <th>G</th>
                    <th>AB</th>
                    <th>R</th>
                    <th>HR</th>
                    <th>RBI</th>
                    <th>SB</th>
                    <th>AVG</th>
                    <th>OBP</th>
                    <th>SLG</th>
                    <th>積分</th>
                    <th>平均</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2011</td>
                    <td><?php echo $playerRec2011->g;?></td>
                    <td><?php echo $playerRec2011->ab;?></td>
                    <td><?php echo $playerRec2011->r;?></td>
                    <td><?php echo $playerRec2011->hr;?></td>
                    <td><?php echo $playerRec2011->rbi;?></td>
                    <td><?php echo $playerRec2011->sb;?></td>
                    <td><?php printf("%.3f",$playerRec2011->avg);?></td>
                    <td><?php printf("%.3f",$playerRec2011->obp);?></td>
                    <td><?php printf("%.3f",$playerRec2011->slg);?></td>
                    <td><?php echo $playerRec2011->points;?></td>
                    <td><?php printf("%.1f",$playerRec2011->ppg);?></td>  
                  </tr>
                </tbody>
              </table>
              <?php }?>
            </div>
            <div class="tab-pane" id="log">
              <?php if($playerBaseData->p){?>
              <table class="bordered-table condensed-table zebra-striped">
                <thead>
                  <tr>
                    <th colspan="14" style="background-color: #cccc99;">
                      本季記錄明細
                    </th>
                  </tr>
                  <tr>
                    <th>日期</th>
                    <th>對手</th>
                    <th>比分</th>
                    <th>Dev</th>
                    <th>IP</th>
                    <th>H</th>
                    <th>R</th>
                    <th>ER</th>
                    <th>HR</th>
                    <th>BB</th>
                    <th>K</th>
                    <th>積分</th>
                    <th>價錢</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>                
                    <td>04-30</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>               
                  </tr>
                  <tr>
                    <td>04-29</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-28</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-27</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                </tbody>
              </table>
              <?php }else{?>
              
              <table class="bordered-table condensed-table zebra-striped">
                <thead>
                  <tr>
                    <th colspan="14" style="background-color: #cccc99;">
                      本季記錄明細
                    </th>
                  </tr>
                  <tr>
                    <th>日期</th>
                    <th>對手</th>
                    <th>比分</th>
                    <th>AB</th>
                    <th>H</th>
                    <th>R</th>
                    <th>HR</th>
                    <th>RBI</th>
                    <th>SB</th>
                    <th>K</th>
                    <th>TBBs</th>
                    <th>積分</th>
                    <th>價錢</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  $count = count($playerRecDaily);
                  for($i=0;$i<$count;$i++)
                  {
                    
                  }
                ?>
                  <tr>                
                    <td>04-30</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>               
                  </tr>
                  <tr>
                    <td>04-29</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-28</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-27</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                  <tr>
                    <td>04-26</td>
                    <td>@G</td>
                    <td>W 1-0</td>
                    <td>W</td>
                    <td>9.0</td>
                    <td>3</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>2</td>
                    <td>12</td>
                    <td>113</td>
                    <td>19,000,000</td>  
                  </tr>
                </tbody>
              </table>
              <?php }?>
            </div>
          </div>
        </section>
        
      </div><!-- /content -->
      <footer>
          <p>&copy; oh!dada 2012</p>
          <div class="result"></div>
        </footer>
    </div><!-- /container -->

    <!-- Le javascript -->
    <script src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
    <script src="http://autobahn.tablesorter.com/jquery.tablesorter.min.js"></script>
    <script src="js/prettify.js"></script>
    <script>$(function () { prettyPrint() })</script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-twipsy.js"></script>
    <script src="js/bootstrap-tabs.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/application.js"></script>

  </body>
</html>
 