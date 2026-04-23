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
	
				$sell=$model->getIdMenu()->one();
			
				
				$prints[$i][1] ="!!!!-------DIQQET--------!!!!\n !!!!-------Legv etme--------!!!!\n".Place::find()->where(['id'=>$id_place])->one()->name. "\n".Users::find()->where(['id_user'=>$id_user])->one()->fio. "\n".date("Y-m-d H:i:s")."\n"."\n";
				$prints[$i][1] =$prints[$i][1] . utf8_encode($sell['name']). " ---- ".$quantity."(".$reason.") \n\n\n\n\n\n\n\n\n\n\n";
				$prints[$i][0]= Store::find()->where(["id" => $sell->id_store])->one()->ip;
				
	
	$i++;
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
			// send string to server
			$socket_wrt = socket_write($socket,$print[1]);


			// close socket
			socket_close($socket);
				
		}
	}

?>