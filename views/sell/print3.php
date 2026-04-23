<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \app\models\Product;
use yii\helpers\ArrayHelper;
use app\models\Byuer;
use app\models\Place;
use app\models\Users;
use app\models\Store;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArrivalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Arrivals';
//$this->params['breadcrumbs'][] = $this->title;
$i=0;
?>

<?php

	$ms = Place::find()->where(['id'=>$id_place])->one()->name. "\n".Users::find()->where(['id_user'=>$id_user])->one()->fio. "\n".date("Y-m-d H:i:s")."\n"."\n";
	foreach(Store::find()->all() as $store)
	{
		foreach($searchModel as $model)
		{
			$sell=$model->getIdMenu()->one();
			if ($sell->id_store!=$store->id) continue;
			else
			{
				$quantity= $model->quantity - $model->print;
	
				$prints[$i][1] =$prints[$i][1] . utf8_encode($sell['name']). " ---- ".$quantity;
				if ($model->note) $prints[$i][1]= $prints[$i][1]."(".$model->note.")"." \n";
				else $prints[$i][1]= $prints[$i][1]." \n";
				
				$flag = 1;
			}
		}
		
		if ($flag == 1) 		{
									$prints[$i][0]=$store->ip;
									$flag = 0;
									$prints[$i][1]= $prints[$i][1]." \n\n\n\n\n\n\n\n\n\n\n\n\n\n";
									$i++;
								}
					
	}

	
//print_r($prints);
	?>


<?php

$port    = 9100; //default listening port for printer
if ($i)
	{
		foreach ($prints as $print)
		{
			$host =$print[0];
	//	$host ="192.168.1.50";
			// create socket
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			if(!$socket){
				$errorcode = socket_last_error();
				$errormsg = socket_strerror($errorcode);
				socket_close($socket);
			   die("Could not create socket: [$errorcode] $errormsg\n");
			}
			// connect to server
			$result = socket_connect($socket, $host, $port);  
			if(!$result){
					$errorcode = socket_last_error();
					$errormsg = socket_strerror($errorcode);
				socket_close($socket);
				die("Could not connect to server: [$errorcode] $errormsg\n");
			}
			print_r($result);
			$mes = $ms.$print[1];
			// send string to server
			$socket_wrt = socket_write($socket,$mes);


			// close socket
			socket_close($socket);
				
		}
	}

?>