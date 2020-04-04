<?php

/* @var $this yii\web\View */
use frontend\assets\AppAsset;

$this->title = Yii::$app->name;
AppAsset::register($this);
$appleView = Yii::getAlias('@app') . '/views/site/apple.php';
?>
<div class="site-index">
    
    <div class="body-content">

        <div id="tree-grid">
            <?php 
                $apples = $onTree;
                include($appleView); 
            ?>        
        </div>
        
        <div id="ground-grid">
            <?php 
                $apples = $onGround;
                include($appleView); 
            ?>
        </div>
        
    </div>
</div>
