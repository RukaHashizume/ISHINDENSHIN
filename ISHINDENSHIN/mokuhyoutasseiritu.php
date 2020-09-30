<?php
	include 'db_config.php';
  $user_id1 = $_GET['user_id'];      
  // $today = date("Y-m-d");
  // $today = '2020-09-27';
  $today = $_GET['date'];
  $tomorrow = date("Y-m-d", strtotime($today.'+1 day'));
  $face_id=$_POST['face_id'];
  $id=0;
  
  try{
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM demomokuhyouti WHERE user_id='".$user_id1."' AND date='".$today."'";
    $stmt = $db->query("SELECT * FROM demomokuhyouti WHERE user_id='".$user_id1."' AND date='".$today."'");// 目標値テーブルを引っ張ってくる
    $target = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!empty($target['id'])){
      $id = $target['id'];
      $user_id = $target['user_id'];
      $mokuhyou_speed = $target['mokuhyou_speed'];
      $mokuhyou_top = $target['mokuhyou_top'];
      $mokuhyoucourt_id = $target['court_id'];
      $date = $target['date'];
    }
    //  foreach($target as $t)//動画のあたいをデータベースから取ってくる
    //      {
	//        $id[] = $t['id'];
    //        $user_id[] = $t['user_id'];
	//        $mokuhyou_speed[] = $t['mokuhyou_speed'];
	//        $mokuhyou_top[] = $t['mokuhyou_top'];
	//        //$course[] = $t['course'];             
    //        $court_id[] = $t['court_id'];
    //        $date[] = $t['date'];
    //       }
    $stmt = $db->query("SELECT * FROM demojikkouti WHERE user_id='".$user_id1."' AND date='".$today."'"); // 実行値テーブルを引っ張ってくる
    $value = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($value as $index => $v)//動画のあたいをデータベースから取ってくる
    {
      // $id[$index] = $v['id'];
      // $user_id[$index] = $v['user_id'];
      $movie_id[$index] = $v['movie_URL'];
      $court_id[$index] = $v['court_id'];
	    $speed[$index] = $v['speed'];
	    $top[$index] = $v['top'];
	    //$course[] = $v['course'];       
      $top_px[$index] = $v['x_px'];
      $left_px[$index] = $v['y_px'];
      $date[$index] = $v['date'];
    }
    
    if(!empty($id['id'])){
      $stmt = $db->query("SELECT * FROM court_img WHERE id='".$mokuhyoucourt_id."'"); // コートテーブルを引っ張ってくる
      $court_img = $stmt->fetch(PDO::FETCH_ASSOC);   
      $court = $court_img['id'];
      $court_url = $court_img['img_url'];
      $top_px1 = $court_img['top_px'];
      $left_px1 = $court_img['left_px'];
    
      $mokuhyouspeed_point = 0;
      $mokuhyoutop_point = 0;
      $mokuhyoutotal_point = 0;
      if($mokuhyou_speed < 10){
        $mokuhyouspeed_point = 0;
      }else if($mokuhyou_speed >= 10 && $mokuhyou_speed < 11.5){
        $mokuhyouspeed_point = 1;
      }else if($mokuhyou_speed >= 11.5 && $mokuhyou_speed < 13){
        $mokuhyouspeed_point = 2;
      }else if($mokuhyou_speed >= 13 && $mokuhyou_speed < 14.5){
        $mokuhyouspeed_point = 3;
      }else if($mokuhyou_speed >= 14.5 && $mokuhyou_speed < 16){
        $mokuhyouspeed_point = 4;
      }else{
        $mokuhyouspeed_point = 5;
      }
      
      if($mokuhyou_top >= 293 || $mokuhyou_top < 243){
        $mokuhyoutop_point = 0;
      }else if($mokuhyou_top >= 283 && $mokuhyou_top < 293){
        $mokuhyoutop_point = 1;
      }else if($mokuhyou_top >= 273 && $mokuhyou_top < 283){
        $mokuhyoutop_point = 2;
      }else if($mokuhyou_top >= 263 && $mokuhyou_top < 273){
        $mokuhyoutop_point = 3;
      }else if($mokuhyou_top >= 253 && $mokuhyou_top < 263){
        $mokuhyoutop_point = 4;
      }else{//($mokuhyou_top[0] >= 243 && $mokuhyou_top[0] < 253){
        $mokuhyoutop_point = 5;
      }
      
      $mokuhyoutotal_point = $mokuhyouspeed_point + $mokuhyoutop_point + 5;//3つ足した値をmokuhyoutotalとして格納
  
            //これはやらんとこかな
            // if($course < 10){
              //   $course_point = 0
            // }else if($course[$i] < 11.5){
              //   $course_point = 1
            // }else if($course[$i] < 13){
              //   $course_point = 2
            // }else if($course[$i] < 14.5){
              //   $course_point = 3
            // }else if($course[$i] < 16){
              //   $course_point = 4
            // }else ($course[$i] >=16){
              //   $course_point = 5
            // }
            // if($maxcourse=<$course[$i]){
              //   $course[$i] = $maxcourse  //今日のベスト落下地点
                
            // }         
            
            // $total = $speed_point + $top_point + $course_point;　　//3つ足した値をtotalとして格納
            // if($totalmax=<$total)
            // {
              //   $totalmax = $total;　　　　//三つの合計のMAX値
            // }
  
  
      $totalmax = 0;
      $maxspeed = 0;
      $best_top = 0;
      $maxcourse = 0;
      $maxspeed_point = 0;
      $maxtop_point = 0;
      $maxcourse_point =0;
      $mokuhyoutasseiritu =0;
      for($i=0;$i<=3;$i++)
      {
        if($speed[$i] < 10){
          $speed_point = 0;
        }else if($speed[$i] >= 10 && $speed[$i] < 11.5){
          $speed_point = 1;
        }else if($speed[$i] >= 11.5 && $speed[$i] < 13){
          $speed_point = 2;
        }else if($speed[$i] >= 13 && $speed[$i] < 14.5){
          $speed_point = 3;
        }else if($speed[$i] >= 14.5 && $speed[$i] < 16){
          $speed_point = 4;
        }else if($speed[$i] >=16){
          $speed_point = 5;
        }
        
        if($maxspeed<=$speed[$i])
        {
          $maxspeed = $speed[$i];//今日の最速値
        }
            
        if($top[$i] >= 293 || $top[$i] < 243){
          $top_point = 0;
        }else if($top[$i] >= 283 && $top[$i] < 293){
          $top_point = 1;
        }else if($top[$i] >= 273 && $top[$i] < 283){
          $top_point = 2;
        }else if($top[$i] >= 263 && $top[$i] < 273){
          $top_point = 3;
        }else if($top[$i] >= 253 && $top[$i] < 263){
          $top_point = 4;
        }else if($top[$i] >= 243 && $top[$i] < 253){
          $top_point = 5;
        }
        
        if ($maxtop_point<=$top_point){
          $maxtop_point=$top_point;
          $best_top=$top[$i];
        }
           
        //コーすポイント
        $a=0;//top
        $b=0;//left
        $sabun=0;
        $a = $top_px1 - $top_px[$index];
        $b = $left_px1 - $left_px[$index];
        $sabun = sqrt($a*$a + $b*$b);
        $minsabun = 0;
        $meichu_point=0;
        //sabun（ピクセル）の変換
        if($sabun >= 33){
          $course_point = 0;
        }else if(27.5 <=$sabun && $sabun < 33){
          $course_point = 1;
        }else if(16.5 <=$sabun && $sabun < 27.5){
          $course_point = 2;
        }else if(11 <=$sabun && $sabun < 16.5){
          $course_point = 3;
        }else if(5.5 <=$sabun && $sabun < 11){
          $course_point = 4;
        }else if(0 <=$sabun && $sabun < 5.5){
          $course_point = 5;
        }
        
        if($minsabun>=$sabun){
          $meichu_point=$course_point;
          $minsabun = $sabun;  //今日のベスト落下地点
        }         
        
        $total = $speed_point + $top_point + $course_point;//3つ足した値をtotalとして格納
        if($totalmax<=$total){
          $totalmax = $total;//三つの合計のMAX値
        }
     }
  
  
     $mokuhyoutasseiritu = $totalmax/$mokuhyoutotal_point*100;
     
     $stmt=$db->query("SELECT * FROM demo_mokuhyoutasseiritu WHERE user_id=$user_id1 AND pasento=$mokuhyoutasseiritu AND total=$totalmax AND max_speed=$maxspeed AND best_top=$best_top AND meichu_point=$meichu_point AND date='$today'");
  	  $temp=$stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($temp as $t){
        $demo_mokuhyoutasseiritu[] = $t['id'];
      }
  	  if(empty($demo_mokuhyoutasseiritu[0])){
  		  $stmt = $db->prepare("INSERT INTO demo_mokuhyoutasseiritu (`user_id`,`pasento`,`total`,`max_speed`,`best_top`,`meichu_point`,`date`) VALUES(?,?,?,?,?,?,?)");
        $stmt->execute([$user_id1,$mokuhyoutasseiritu,$totalmax,$maxspeed,$best_top,$meichu_point,$today]);
  	  }
     
  
      
      $nextspeed = 0;//次の速度
      $nexttop = 0;//次の高さ     
      $nextcourt_id=rand(1,24);
      if ($mokuhyoutotal_point<=$totalmax)
      {
        $nextspeed =$maxspeed *1.03;
        $nexttop =$maxtop;           
      }else{
        $nextspeed =$mokuhyou_speed; 
        $nexttop =$mokuhyou_top;          
      }
     
      $nextmokuhyouspeed_point = 0;
      $nextmokuhyoutop_point = 0;
      $nextmokuhyoutotal_point = 0;
      if($nextspeed < 10){
        $nextmokuhyouspeed_point = 0;
      }else if($nextspeed >= 10 && $nextspeed < 11.5){
        $nextmokuhyouspeed_point = 1;
      }else if($nextspeed >= 11.5 && $nextspeed < 13){
        $nextmokuhyouspeed_point = 2;
      }else if($nextspeed >= 13 && $nextspeed < 14.5){
        $nextmokuhyouspeed_point = 3;
      }else if($nextspeed >= 14.5 && $nextspeed < 16){
        $nextmokuhyouspeed_point = 4;
      }else{
        $nextmokuhyouspeed_point = 5;
      }
     
      if($nexttop >= 293 || $nexttop < 243){
        $nextmokuhyoutop_point = 0;
      }else if($nexttop >= 283 && $nexttop < 293){
        $nextmokuhyoutop_point = 1;
      }else if($nexttop >= 273 && $nexttop < 283){
        $nextmokuhyoutop_point = 2;
      }else if($nexttop >= 263 && $nexttop < 273){
        $nextmokuhyoutop_point = 3;
      }else if($nexttop >= 253 && $nexttop < 263){
        $mokuhyoutop_point = 4;
      }else{//($mokuhyou_top[0] >= 243 && $mokuhyou_top[0] < 253){
        $nextmokuhyoutop_point = 5;
      }
      $nextmokuhyoutotal_point = $nextmokuhyouspeed_point + $nextmokuhyoutop_point +5;//3つ足した値をmokuhyoutotalとして格納
      
      
      $stmt=$db->query("SELECT * FROM demomokuhyouti WHERE teammokuhyou_speed=$next_teammokuhyouspeed AND teammokuhyou_top=$next_teammokuhyoutop AND teammeichu_point=$next_teammokuhyoumeichu AND date=$tomorrow");
  	  $temp=$stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach($temp as $s){
        $demomokuhyouti[] = $s['id'];
      }
  	  if(empty($demomokuhyouti[0])){
        $stmt = $db->prepare("INSERT INTO demomokuhyouti (`user_id`,`mokuhyou_total`,`mokuhyou_speed`,`mokuhyou_top`,`court_id`,`date`) VALUES(?,?,?,?,?,?)");
        $stmt->execute([$user_id, $nextmokuhyoutotal_point, $nextspeed, $nexttop, $nextcourt_id, $tomorrow]);
  	  }
      // $stmt = $db->prepare("INSERT INTO demomokuhyouti (`user_id`,`mokuhyou_total`,`mokuhyou_speed`,`mokuhyou_top`,`court_id`,`date`) VALUES(?,?,?,?,?,?)");
      // $stmt->execute([$user_id, $nextmokuhyoutotal_point, $nextspeed, $nexttop, $nextcourt_id, $today]);
      $stmt = $db->query("SELECT * FROM court_img WHERE id='".$nextcourt_id."'"); // コートテーブルを引っ張ってくる
      $court_img2 = $stmt->fetch(PDO::FETCH_ASSOC);   
      $court = $court_img2['id'];
      $nextcourt_url = $court_img2['img_url'];
      
    	//目標値取得
    	$stmt1=$db->query("SELECT mokuhyou_total FROM demomokuhyouti WHERE user_id=1");
    	$target1=$stmt1->fetchAll(PDO::FETCH_ASSOC);
    	$stmt2=$db->query("SELECT mokuhyou_total FROM demomokuhyouti WHERE user_id=2");
    	$target2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
    	$stmt3=$db->query("SELECT mokuhyou_total FROM demomokuhyouti WHERE user_id=3");
    	$target3=$stmt3->fetchAll(PDO::FETCH_ASSOC);
    	$stmt4=$db->query("SELECT mokuhyou_total FROM demomokuhyouti WHERE user_id=4");
    	$target4=$stmt4->fetchAll(PDO::FETCH_ASSOC);
    	$stmt5=$db->query("SELECT mokuhyou_total FROM demomokuhyouti WHERE user_id=5");
    	$target5=$stmt5->fetchAll(PDO::FETCH_ASSOC);
    
    	//目標達成率取得
    	$stmt6=$db->query("SELECT total FROM demo_mokuhyoutasseiritu WHERE user_id=1");
    	$percent1=$stmt6->fetchAll(PDO::FETCH_ASSOC);
    	$stmt7=$db->query("SELECT total FROM demo_mokuhyoutasseiritu WHERE user_id=2");
    	$percent2=$stmt7->fetchAll(PDO::FETCH_ASSOC);
    	$stmt8=$db->query("SELECT total FROM demo_mokuhyoutasseiritu WHERE user_id=3");
    	$percent3=$stmt8->fetchAll(PDO::FETCH_ASSOC);
    	$stmt9=$db->query("SELECT total FROM demo_mokuhyoutasseiritu WHERE user_id=4");
    	$percent4=$stmt9->fetchAll(PDO::FETCH_ASSOC);
    	$stmt10=$db->query("SELECT total FROM demo_mokuhyoutasseiritu WHERE user_id=5");
    	$percent5=$stmt10->fetchAll(PDO::FETCH_ASSOC);
    
    	//途中
    	/*$stmt01=$db->query("SELECT * FROM value WHERE user_id=? AND date=?");
    	  $user_id1=$stmt01->fetchAll(PDO::FETCH_ASSOC);
    	  $stmt01->bindvalue($user_id,$date, PDO::PARAM_STR);
    	  $stmt01->execute();
    	  $row = $stmt01->fetch();
    	  $user_id = $row['user_id'];*/
    	$stmt01=$db->query("SELECT pasento FROM demo_mokuhyoutasseiritu WHERE user_id=$user_id AND date=$today");
    	$date01=$stmt01->fetchAll(PDO::FETCH_ASSOC);   
    
      $target_json1=json_encode($target1);
      $target_json2=json_encode($target2);
      $target_json3=json_encode($target3);
      $target_json4=json_encode($target4);
      $target_json5=json_encode($target5);
      
      $percent_json1=json_encode($percent1);
      $percent_json2=json_encode($percent2);
      $percent_json3=json_encode($percent3);
      $percent_json4=json_encode($percent4);
      $percent_json5=json_encode($percent5);
    }
        
        //途中
        /*date_default_timezone_set('Asia/Tokyo');
          $date = new DateTime('now');
          $user_id=$_POST['user_id'] AND $_POST['date']=$today;*/
  }catch(PDOException $e){
	  $errorMessage = 'データベースエラー';
	}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
	  <link rel="stylesheet" href="oneman.css">
      <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>今日の目標達成率</title>
</head>

<body>

<div class="all">
     <div class="head">
         <img src="img/rogo.png" width="400" height="90" border="0" alt="ロゴ">
    </div>
    <a><?php if(empty($mokuhyoutasseiritu)) echo"データがありません。日付を変更してください。";?></a>
    <div class="percent">
    	<p>目標達成率：<?php if(!empty($mokuhyoutasseiritu)) echo $mokuhyoutasseiritu;?></p>
    </div>
    <div class="ue1">
        <p>今日の目標値：</p>
        <p>目標値合計点数：<?php if(!empty($mokuhyoutotal_point)) echo $mokuhyoutotal_point;?></p>
        <p>目標速度：<?php if(!empty($mokuhyou_speed)) echo $mokuhyou_speed;?>m/s</p>
        <p>目標の高さ：<?php if(!empty($mokuhyou_top)) echo $mokuhyou_top;?>cm</p>
        <p>目標命中ポイント：5</p>
        
    </div>
        
    <div class="ue2">
        <p>今日のベストサーブ</p>
        <p>サーブの点数：<?php if(!empty($totalmax)) echo $totalmax;?></p>
        <p>最高速度   ：<?php if(!empty($maxspeed)) echo $maxspeed;?>m/s</p>
        <p>高さのベスト：<?php if(!empty($best_top)) echo $best_top;?>cm</p>
        <p>命中ポイント：<?php if(!empty($course_point)) echo $course_point;?></p>       
    </div>
    <div class="sita1">
                <!--<p>-----------------------------------------------------------<p>-->
                <br></br><hr>
                <p>次回の目標値</p>      
                <p>次回の目標速度:<?php if(!empty($nextspeed)) echo $nextspeed;?>m/s</p>
                <p>次回の目標の高さ:<?php if(!empty($nexttop)) echo $nexttop;?>cm</p>     
                <p>次回の目標落下地点</p>
            
                <div class=gazou><img src="<?php if(!empty($nextcourt_url)) echo $nextcourt_url;?>" width="300" height="185" border="0" alt="バレーコート"></div>
                <!--<p>-----------------------------------------------------------<br>目標値・サーブポイントの推移</p>-->
                <br></br><hr>
                <p><br>目標値・サーブポイントの推移</p>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
                <canvas id="line-chart1"  width="100px" height="50px"></canvas>
                
                <script>
                
                //目標値
                var target_arr1 =[];
                var target_count1 =[];
                var target1 = new XMLHttpRequest();
                target1=JSON.parse('<?php echo $target_json1; ?>');
                for(i=0;i<target1.length;i++){
                	target_arr1[i]=(target1[i].mokuhyou_total);
                }
                
                for(j=1;j<target1.length;j++){
                	target_count1[j]=j;
                }
                
                //目標達成値
                var percent_arr1 =[];
                var percent1 = new XMLHttpRequest();
                percent1=JSON.parse('<?php echo $percent_json1; ?>');
                for(k=0;k<percent1.length;k++){
                	percent_arr1[k]=(percent1[k].total);
                }
                
                var context = document.getElementById('line-chart1').getContext('2d');
                var line_chart = new Chart(context, {
                type:'line', // グラフのタイプを指定
                data:{
                labels:target_count1, // グラフ下部のラベル
                datasets:[
                {label:'目標値',  // データのラベル
                data:target_arr1, // グラフ化するデータの数値
                fill:false, // グラフの下部を塗りつぶさない
                borderColor:'rgb(50,144,229)'}, // 線の色
                
                {label:'サーブポイント',  // データのラベル
                data:percent_arr1, // グラフ化するデータの数値
                fill:false, // グラフの下部を塗りつぶさない
                borderColor:'rgb(255, 52, 84)'}, // 線の色
                ]
                },
                options:{
                scales:{
                yAxes:[{
                ticks:{
                min:0, // グラフの最小値
                
                      }
                      }]
                       },
                elements:{
                line:{
                tension: 0 // 線グラフのベジェ曲線を無効にする
                     }
                	 }
                	}
                });
                
                </script>
     </div>
</div>
<div class = botton>
		<form action="https://web2-17423.azurewebsites.net/ISHINDENSHIN/web.php">
		<input id="submit_button" type="submit" name="all"value="選手一覧に戻る">
    <input type='hidden' name='face_id' value=<?php echo $face_id;?>>
    <input type='hidden' name='select_date' value=<?php echo $today;?>></form>
		<br>
</div>

</body>
</html>