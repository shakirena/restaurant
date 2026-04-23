<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
AppAsset::register($this);


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>	TuReN</title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ;
if (  Yii::$app->user->identity->id_role==2)  $role=1;

else $role=0;

?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'TuReN',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar  navbar-inverse',
            'id' => 'main-menu'
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-inverse navbar-right'],
        'items' => [
		       $role? ['label' => 'Mal Qəbulu', 'url' => ['/arrival/index']] : '',
                [
                    'label' => 'Hesabat',
                    'items'=>[
                      
                        ['label' => 'Mal Qəbulu', 'url' => ['/arrival/report']],
						['label' => 'Mal qalığı', 'url' => ['/arrival/rest']],
                      
                        ['label' => 'Satışlar', 'url' => ['/sell/report']], 
						['label' => 'Satışlar menu üzrə', 'url' => ['/sell/report-prod']],
						['label' => 'Satışlar sənədlər üzrə', 'url' => ['/sell/report-doc']],
                        ['label' => 'Satışlar mallar üzrə', 'url' => ['/sell/consist']], 
						['label' => 'Ləğv edilənlər', 'url' => ['/returnp/index']], 
                    ]
                ] ,
              
                [
                    'label' => 'Pul Əməliyyatları',
                    'items'=>[
                        ['label' => 'Şirkətlər', 'url' => ['/debt/index']],
                        ['label' => 'Xərc əməliyyatı', 'url' => ['/costs/create']],
                        ['label' => 'Medaxil əməliyyatı', 'url' => ['/costs/prixod']],
                        ['label' => 'Balans ve transfer', 'url' => ['/costs/transfer']],
                        ['label' => 'Xərclər həsabatı', 'url' => ['/costs/index']],
                        ['label' => 'Medaxil həsabatı', 'url' => ['/costs/index2']],
                        ['label' => 'Kassa hesabatı', 'url' => ['/costs/kassa']], 
                        ['label' => 'Günün sonu', 'url' => ['/move/itog']],					
                        
                    ]
                ] ,    
               $role ?		
                [
                    'label' => 'Əməliyyat',
                    'items'=>[
                        ['label' => 'Düzəliş', 'url' => ['/arrival/edit']],
    
                    ]
                ] :  "",   

            ['label' => 'İdarə Etmə', 'url' =>  ['/admin/index']],
	                  

            ['label' => 'Satış', 'url' => ['/sell/index']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : "",
           //  ['label' => Html::input('text','exchange',$exchange['rates'],['id'=>'rates','class' =>'form-control input-sm input-rena','onChange' => 'editExchange($("#rates").val())']),
           //  'encode' => false],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] :"",
        ]) ?>
        <?= $content ?>
    </div>
    <div id="Resultat"></div>
</div>

<!--<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
