<?php
include 'db_config.php';
$face_id=$_GET['face_id'];
if(empty($_GET["select_date"])){
	$post_date=date('Y-m-d');
}
else if(!empty($_GET["select_date"])){
	$post_date=$_GET["select_date"];
}

$users=array();
try {
	//接続
	$dbh = new PDO(PDO_DSN,DB_USERNAME,DB_PASSWORD);
	$sql_select = "SELECT ext,img FROM users WHERE id = ?";
	

	
//選手１
    $img1=$dbh->prepare($sql_select);
    //パラメータをセット
    $id=1;
    $img1->bindparam(1,$id,PDO::PARAM_INT);
    $img1->execute();
    $row01 = $img1 -> fetch(PDO::FETCH_ASSOC);
    //取得した画像バイナリデータをbase64で変換。
    $img01 = base64_encode($row01['img']);
	
//選手2
	$img2=$dbh->prepare($sql_select);
    //パラメータをセット
    $id=2;
    $img2->bindparam(1,$id,PDO::PARAM_INT);
    $img2->execute();
    $row02 = $img2 -> fetch(PDO::FETCH_ASSOC);
    //取得した画像バイナリデータをbase64で変換。
    $img02 = base64_encode($row02['img']);
	
//選手3
	$img3=$dbh->prepare($sql_select);
    //パラメータをセット
    $id=3;
    $img3->bindparam(1,$id,PDO::PARAM_INT);
    $img3->execute();
    $row03 = $img3 -> fetch(PDO::FETCH_ASSOC);
    //取得した画像バイナリデータをbase64で変換。
    $img03 = base64_encode($row03['img']);
	
//選手4
	$img4=$dbh->prepare($sql_select);
    //パラメータをセット
    $id=4;
    $img4->bindparam(1,$id,PDO::PARAM_INT);
    $img4->execute();
    $row04 = $img4 -> fetch(PDO::FETCH_ASSOC);
    //取得した画像バイナリデータをbase64で変換。
    $img04 = base64_encode($row04['img']);
	
//選手5
	$img5=$dbh->prepare($sql_select);
    //パラメータをセット
    $id=5;
    $img5->bindparam(1,$id,PDO::PARAM_INT);
    $img5->execute();
    $row05 = $img5 -> fetch(PDO::FETCH_ASSOC);
    //取得した画像バイナリデータをbase64で変換。
    $img05 = base64_encode($row05['img']);

	$dbh=null;
}
catch(PDOException $e)
{
	echo $e->getMessage();
	exit;
}

try {
	//接続
	$db=new PDO(PDO_DSN,DB_USERNAME,DB_PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	
	//目標値取得
	$stmt1=$db->query("SELECT percent FROM percent WHERE user_id=1");
	$target1=$stmt1->fetchAll(PDO::FETCH_ASSOC);
	$stmt2=$db->query("SELECT percent FROM percent WHERE user_id=2");
	$target2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
	$stmt3=$db->query("SELECT percent FROM percent WHERE user_id=3");
	$target3=$stmt3->fetchAll(PDO::FETCH_ASSOC);
	$stmt4=$db->query("SELECT percent FROM percent WHERE user_id=4");
	$target4=$stmt4->fetchAll(PDO::FETCH_ASSOC);
	$stmt5=$db->query("SELECT percent FROM percent WHERE user_id=5");
	$target5=$stmt5->fetchAll(PDO::FETCH_ASSOC);
	
	//目標達成率取得
	$stmt6=$db->query("SELECT pasento FROM demo_mokuhyoutasseiritu WHERE user_id=1");
	$percent1=$stmt6->fetchAll(PDO::FETCH_ASSOC);
	$stmt7=$db->query("SELECT pasento FROM demo_mokuhyoutasseiritu WHERE user_id=2");
	$percent2=$stmt7->fetchAll(PDO::FETCH_ASSOC);
	$stmt8=$db->query("SELECT pasento FROM demo_mokuhyoutasseiritu WHERE user_id=3");
	$percent3=$stmt8->fetchAll(PDO::FETCH_ASSOC);
	$stmt9=$db->query("SELECT pasento FROM demo_mokuhyoutasseiritu WHERE user_id=4");
	$percent4=$stmt9->fetchAll(PDO::FETCH_ASSOC);
	$stmt10=$db->query("SELECT pasento FROM demo_mokuhyoutasseiritu WHERE user_id=5");
	$percent5=$stmt10->fetchAll(PDO::FETCH_ASSOC);
	
	//途中
	/*$stmt01=$db->query("SELECT * FROM value WHERE user_id=? AND date=?");
	$user_id1=$stmt01->fetchAll(PDO::FETCH_ASSOC);
	$stmt01->bindvalue($user_id,$date, PDO::PARAM_STR);
    $stmt01->execute();
    $row = $stmt01->fetch();
	$user_id = $row['user_id'];*/
	$stmt01=$db->query("SELECT percent FROM percent WHERE user_id=1 AND date=date");
	$date01=$stmt01->fetchAll(PDO::FETCH_ASSOC);
	$stmt02=$db->query("SELECT * FROM users WHERE face_id='$face_id'");
	$face=$stmt02->fetchAll(PDO::FETCH_ASSOC);
	foreach($face as $f)//実行値のあたいを配列
	{
		$userid[] = $f['id'];
	}
	
	$db=null;
	}
	
	catch(PDOException $e)
	{
	echo $e->getMessage();
	exit;
	}
	

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
 	
	 //途中
	/*date_default_timezone_set('Asia/Tokyo');
 	 $date = new DateTime('now');
 	$user_id=$_POST['user_id'] AND $_POST['date']=$today;*/
	 
	
	 
	
 ?>
<!DOCTYPE html>
<html lang="ja">
     <script>
      var xhr1 = new XMLHttpRequest();
 
     xhr1.open('GET', 'https://web2-17423.azurewebsites.net/ISHINDENSHIN/users.php');//選手名取得
     xhr1.send();
     xhr1.onreadystatechange = function() {
 
    if(xhr1.readyState === 4 && xhr1.status === 200) {
		console.log( xhr1.responseText );
    	const json1= xhr1.responseText;
    	const obj1=JSON.parse(json1);
		console.log(obj1);
		console.log(document.getElementById('id01'));
    	document.getElementById('id01').innerHTML = obj1[0].name"<br>";
   		console.log(obj1[0].name);
		document.getElementById('id02').innerHTML = obj1[1].name"<br>";
   		console.log(obj1[1].name);
		document.getElementById('id03').innerHTML = obj1[2].name"<br>";
   		console.log(obj1[2].name);
		document.getElementById('id04').innerHTML = obj1[3].name"<br>";
   		console.log(obj1[3].name);
		document.getElementById('id05').innerHTML = obj1[4].name"<br>";
   		console.log(obj1[4].name);

    	}
    }
    let b = {name:"jjkj",faceid:"ddd"};
    console.log(b);
    </script>
	
	

	
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="styles.css">
	
	<title>選手一覧ページ</title>
</head>

<body>
	
	<div class="search">
		<img src="img/ishindenshin.png" alt="システム名" width="300px" height="150px" >
		<!--アプリに飛ぶ-->
		<input type="button" name="start" onclick="location.href='ishindenshin://'" value="練習開始！">
		<ul >
			<li>選手名検索<br>
			<div class="wrapper">
    		<div class="search-area">
      		<form>
	        		<input type="text" id="search-text" placeholder="選手名を入力">
      		</form>
      		<div class="search-result">
        	<div class="search-result__hit-num"></div>
        	<div id="search-result__list"></div>
     		</div>
    		</div>
				<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<script type="text/javascript">

				$(document).on("click", ".add", function() {
    			$(this).parent().clone(true).insertAfter($(this).parent());});
				
				$(document).on("click", ".del", function() {
    			var target = $(this).parent();
    			if (target.parent().children().length > 1) {
        			target.remove();
    			}});</script>
				
				<div id="input_pluralBox">
    				<div id="input_plural">
        				<input type="text" size="15" class="form-control" placeholder="選手">
        				<input type="button" value="＋" class="add pluralBtn">
        				<input type="button" value="－" class="del pluralBtn">
    				</div>
				</div>			
				</select>-->
					
					
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>				
				<script>	
				$(function () {
  					searchWord = function(){
    				var searchText = $(this).val(), // 検索ボックスに入力された値
			        targetText;

    			$('.target-area li').each(function() {
      				targetText = $(this).text();

      				// 検索対象となるリストに入力された文字列が存在するかどうかを判断
      				if (targetText.indexOf(searchText) != -1) {
        				$(this).removeClass('hidden');
     				} else {
       					$(this).addClass('hidden');
      				}
    				});
  					};

  				// searchWordの実行
  				$('#search-text').on('input', searchWord);
					});
				</script>
				
				<script>	
				$(function () {
  					searchWord = function(){
    				var searchText = $(this).val(), // 検索ボックスに入力された値
			        targetText;

    			$('.target-area li').each(function() {
      				targetText = $(this).text();

      				// 検索対象となるリストに入力された文字列が存在するかどうかを判断
      				if (targetText.indexOf(searchText) != -1) {
        				$(this).removeClass('hidden');
     				} else {
       					$(this).addClass('hidden');
      				}
    				});
  					};

  				// searchWordの実行
  				$('#search').on('input', searchWord);
					});
				</script>
					
					
			
			</li>
			<!--<li>目標達成<br>
			<form>
				<div class="search-box">
				<input type="radio" name="percent" value="">全て<br>
				<input type="radio" name="percent" value="success">できた<br>
				<input type="radio" name="percent" value="failure" >できなかった
				</div>
			</form>
			</li><br>
			
		
		
		
			<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
			<script>
			var searchBox = '.search-box'; // 絞り込む項目を選択するエリア
			var listItem = '.list_item';   // 絞り込み対象のアイテム
			var hideClass = 'is-hide';     // 絞り込み対象外の場合に付与されるclass名
			
			$(function() {
				// 絞り込み項目を変更した時
				$(document).on('change', searchBox + ' input', function() {
					search_filter();
				});
			});
			
			/**
			 * リストの絞り込みを行う
			 */
			function search_filter() {
				// 非表示状態を解除
				$(listItem).removeClass(hideClass);
				for (var i = 0; i < $(searchBox).length; i++) {
					var name = $(searchBox).eq(i).find('input').attr('name');
					// 選択されている項目を取得
					var searchData = get_selected_input_items(name);
					// 選択されている項目がない、または全てを選択している場合は飛ばす
					if(searchData.length === 0 || searchData[0] === '') {
						continue;
					}
					// リスト内の各アイテムをチェック
					for (var j = 0; j < $(listItem).length; j++) {
						// アイテムに設定している項目を取得
						var itemData = $(listItem).eq(j).data(name);
						// 絞り込み対象かどうかを調べる
						if(searchData.indexOf(itemData) == -1) {
							$(listItem).eq(j).addClass(hideClass);
						}
					}
				}
			}
			
			/**
			 * inputで選択されている値の一覧を取得する
			 * @param  {String} name 対象にするinputのname属性の値
			 * @return {Array}       選択されているinputのvalue属性の値
			 */
			 
			function get_selected_input_items(name) {
				var searchData = [];
				$('[name=' + name + ']:checked').each(function() {
					searchData.push($(this).val());
				});
				return searchData;
			}
		</script>
			
			<li>サーブの種類<br>
				<input type="radio" name="serve" value="floater">フローター<br>
				<input type="radio" name="serve" value="drive">ドライブ
			</li><br>-->
			<li>日付選択<br>
			<form action="web.php" method = "get">
				<input type="hidden" name="face_id" value="<?php echo $face_id;?>">
				<input type="date"　 id="day" name="select_date" value="<?php echo $select_date = $post_date; ?>"> 
				<input id="submit_button" name="date_change" type="submit" value="選択">
			</form>
			</li><br>
			</ul>
			
				<form action="teammokuhyou.php?user_id=<?php $user_id=$userid[0]; echo $user_id; ?>&date=<?php echo $post_date; ?>" method = "post">
					<input id="submit_button" type="submit" name="team" value="チーム評価">
					<input type='hidden' name='face_id' value=<?php echo $face_id;?>></form>
				
				<form action="mokuhyoutasseiritu.php?date=<?php $date=$post_date; echo $date;?>&user_id=<?php $user_id=$userid[0]; echo $user_id;?>" method = "post">
					<input id="submit_button" type="submit" name="team_target" value="目標達成率">
					<input type='hidden' name='face_id' value=<?php echo $face_id;?>></form>
		<br>
	</div>
	<div class=main> 
		<ul class="target-area">

		<li class="list_item" ><div class="player">
			<div class="name" id="id01"></div>
			<div class="pictures">
			<div><img width="200" height="300" src="data:<?php echo $row01['ext'] ?>;base64,<?php echo $img01; ?>"></div>
			
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
		<canvas id="line-chart1"  width="100px" height="50px"></canvas>

			<script>
			//目標値
			var target_arr1 =[];
			var target_count1 =[];
			var target1 = new XMLHttpRequest();
		    target1=JSON.parse('<?php echo $target_json1;?>');
				for(i=0;i<target1.length;i++){
					target_arr1[i]=(target1[i].percent);
					}
			
			for(j=1;j<target1.length;j++){
				target_count1[j]=j;
			}
			
			//目標達成値
			var percent_arr1 =[];
			var percent1 = new XMLHttpRequest();
		    percent1=JSON.parse('<?php echo $percent_json1; ?>');
				for(k=0;k<percent1.length;k++){
					percent_arr1[k]=(percent1[k].pasento);
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
					  
					  {label:'目標達成率',  // データのラベル
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
				
		<form action ="afterrensyu.php?user_id=<?php $user_id = 1; echo $user_id; ?>&select_date=<?php $select_date=$post_date; echo $select_date;?>"  method = "post">
			<input id="submit_button" type="submit" onclick="location:href='afterrensyu.php://'" name="serve01" value="サーブを見る">
			<?php
			?>
		</div><hr></li>
		
		<li class="list_item"><div class="player">
			<div class="name" id="id02"></div>
			<div class="pictures">
			<div><img width="200" height="300" src="data:<?php echo $row02['ext'] ?>;base64,<?php echo $img02; ?>"></div>
		
			<canvas id="line-chart2"  width="100px" height="50px"></canvas>
			<script>
			//目標値
			var target_arr2 =[];
			var target_count2 =[];
			var target2 = new XMLHttpRequest();
		    target2=JSON.parse('<?php echo $target_json2; ?>');
				for(i=0;i<target2.length;i++){
					target_arr2[i]=(target2[i].percent);
					}
			
			for(j=1;j<target2.length;j++){
				target_count2[j]=j;
			}
			
			//目標達成値
			var percent_arr2 =[];
			var percent2 = new XMLHttpRequest();
		    percent2=JSON.parse('<?php echo $percent_json2; ?>');
				for(k=0;k<percent2.length;k++){
					percent_arr2[k]=(percent2[k].pasento);
					}

			  var context = document.getElementById('line-chart2').getContext('2d');
			  var line_chart = new Chart(context, {
			    type:'line', // グラフのタイプを指定
			    data:{
			      labels:target_count2, // グラフ下部のラベル
			      datasets:[
					  {label:'目標値',  // データのラベル
				  	  data:target_arr2, // グラフ化するデータの数値
			          fill:false, // グラフの下部を塗りつぶさない
			          borderColor:'rgb(50,144,229)'}, // 線の色
					  
					  {label:'目標達成率',  // データのラベル
			          data:percent_arr2, // グラフ化するデータの数値
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
			
			<form action ="afterrensyu.php?user_id=<?php $user_id = 2; echo $user_id; ?>&select_date=<?php $select_date=date('Y-m-d'); echo $select_date;?>"  method = "post">
			<input id="submit_button" type="submit" onclick="location:href='afterrensyu.php://'" name="serve02" value="サーブを見る"></form>
		</div><hr></li>
		
		<li class="list_item"><div class="player">
			<div class="name" id="id03"></div>
	
			<div class="pictures">
			<div><img width="200" height="300" src="data:<?php echo $row03['ext'] ?>;base64,<?php echo $img03; ?>"></div>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
		<canvas id="line-chart3"  width="100px" height="50px"></canvas>

			<script>
			//目標値
			var target_arr3 =[];
			var target_count3 =[];
			var target3 = new XMLHttpRequest();
		    target3=JSON.parse('<?php echo $target_json3; ?>');
				for(i=0;i<target3.length;i++){
					target_arr3[i]=(target3[i].percent);
					}
			
			for(j=1;j<target3.length;j++){
				target_count3[j]=j;
			}
			
			//目標達成値
			var percent_arr3 =[];
			var percent3 = new XMLHttpRequest();
		    percent3=JSON.parse('<?php echo $percent_json3; ?>');
				for(k=0;k<percent3.length;k++){
					percent_arr3[k]=(percent3[k].pasento);
					}

			  var context = document.getElementById('line-chart3').getContext('2d');
			  var line_chart = new Chart(context, {
			    type:'line', // グラフのタイプを指定
			    data:{
			      labels:target_count3, // グラフ下部のラベル
			      datasets:[
					  {label:'目標値',  // データのラベル
				  	  data:target_arr3, // グラフ化するデータの数値
			          fill:false, // グラフの下部を塗りつぶさない
			          borderColor:'rgb(50,144,229)'}, // 線の色
					  
					  {label:'目標達成率',  // データのラベル
			          data:percent_arr3, // グラフ化するデータの数値
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
			<form action ="afterrensyu.php?user_id=<?php $user_id = 3; echo $user_id; ?>&select_date=<?php $select_date=date('Y-m-d'); echo $select_date;?>"  method = "post">
			<input id="submit_button" type="submit" onclick="location:href='afterrensyu.php://'" name="serve03" value="サーブを見る"></form>
		</div><hr></li>
		
		<li class="list_item" ><div class="player">
			<div class="name" id="id04"></div>

			<div class="pictures">
			<div><img width="200" height="300" src="data:<?php echo $row04['ext'] ?>;base64,<?php echo $img04; ?>"></div>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
		<canvas id="line-chart4"  width="100px" height="50px"></canvas>

			<script>
			//目標値
			var target_arr4 =[];
			var target_count4 =[];
			var target4 = new XMLHttpRequest();
		    target4=JSON.parse('<?php echo $target_json4; ?>');
				for(i=0;i<target4.length;i++){
					target_arr4[i]=(target4[i].percent);
					}
			
			for(j=1;j<target4.length;j++){
				target_count4[j]=j;
			}
			
			//目標達成値
			var percent_arr4 =[];
			var percent4 = new XMLHttpRequest();
		    percent4=JSON.parse('<?php echo $percent_json4; ?>');
				for(k=0;k<percent4.length;k++){
					percent_arr4[k]=(percent4[k].pasento);
					}

			  var context = document.getElementById('line-chart4').getContext('2d');
			  var line_chart = new Chart(context, {
			    type:'line', // グラフのタイプを指定
			    data:{
			      labels:target_count4, // グラフ下部のラベル
			      datasets:[
					  {label:'目標値',  // データのラベル
				  	  data:target_arr4, // グラフ化するデータの数値
			          fill:false, // グラフの下部を塗りつぶさない
			          borderColor:'rgb(50,144,229)'}, // 線の色
					  
					  {label:'目標達成率',  // データのラベル
			          data:percent_arr4, // グラフ化するデータの数値
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
			<form action ="afterrensyu.php?user_id=<?php $user_id = 4; echo $user_id; ?>&select_date=<?php $select_date=date('Y-m-d'); echo $select_date;?>"  method = "post">
			<input id="submit_button" type="submit" onclick="location:href='afterrensyu.php://'" name="serve04" value="サーブを見る"></form>
		</div><hr></li>
		
		<li class="list_item" ><div class="player">
			<div class="name" id="id05"></div>

			<div class="pictures">
			<div><img width="200" height="300" src="data:<?php echo $row05['ext'] ?>;base64,<?php echo $img05; ?>"></div>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
		<canvas id="line-chart5"  width="100px" height="50px"></canvas>

			<script>
			//目標値
			var target_arr5 =[];
			var target_count5 =[];
			var target5 = new XMLHttpRequest();
		    target5=JSON.parse('<?php echo $target_json5; ?>');
				for(i=0;i<target5.length;i++){
					target_arr5[i]=(target5[i].percent);
					}
			
			for(j=1;j<target5.length;j++){
				target_count5[j]=j;
			}
			
			//目標達成値
			var percent_arr5 =[];
			var percent5 = new XMLHttpRequest();
		    percent5=JSON.parse('<?php echo $percent_json5; ?>');
				for(k=0;k<percent5.length;k++){
					percent_arr5[k]=(percent5[k].pasento);
					}

			  var context = document.getElementById('line-chart5').getContext('2d');
			  var line_chart = new Chart(context, {
			    type:'line', // グラフのタイプを指定
			    data:{
			      labels:target_count5, // グラフ下部のラベル
			      datasets:[
					  {label:'目標値',  // データのラベル
				  	  data:target_arr5, // グラフ化するデータの数値
			          fill:false, // グラフの下部を塗りつぶさない
			          borderColor:'rgb(50,144,229)'}, // 線の色
					  
					  {label:'目標達成率',  // データのラベル
			          data:percent_arr5, // グラフ化するデータの数値
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
			<form action ="afterrensyu.php?user_id=<?php $user_id = 5; echo $user_id; ?>&select_date=<?php $select_date=date('Y-m-d'); echo $select_date;?>"  method = "post">
			<input id="submit_button" type="submit" onclick="location:href='afterrensyu.php://'" name="serve05" value="サーブを見る"></form>
		</div><hr></li>
		
		</ul>
		</div>
	</div>
	
	<script type="text/javascript" src="main.js"></script>
</body>
</html>