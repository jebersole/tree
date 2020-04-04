<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use frontend\models\Apple;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    /**
     * Отображает яблоню.
     *
     * @return yii\web\Response
     */
    public function actionIndex()
    {
        $apples = Apple::find()->all();
        $onGround = [];
        $onTree = [];
        foreach ($apples as $apple) {
            if ($apple->status === Apple::ON_TREE) {
                $onTree[] = $apple;
            } else {
                $onGround[] = $apple;
            }
        }
        return $this->render('index', ['onTree' => $onTree, 'onGround' => $onGround]);
    }
    
    /**
     * Случайно генерирует 1-18 яблок (зеленый, желтый или красный). Удаляет старые яблоки.
     * Яблоки появляются либо на дереве, либо на земле. Обновляет страницу.
     *
     * @return yii\web\Response
     */
    public function actionGenerate()
    {
        Apple::deleteAll();
        $numApples = rand(1,18);
        $colors = ['green', 'red', 'yellow'];
        for ($i = 0; $i < $numApples; $i++) { 
            $apple = new Apple();
            $apple->percent_eaten = 0;
            $apple->color = $colors[rand(0, 2)];
            $apple->status = rand(0, 1); // 0 === на дереве, 1 === на земле
            $apple->created_at = time();
            $apple->fallen_at = $apple->status ? $apple->created_at : 0;
            $apple->save(false);
        }
        return $this->redirect(['site/index']);
    }
    
    /**
     * Попробовать съесть яблоко
     *
     * @param $id apple id
     * @return yii\web\Response
     */
    public function actionEat($id)
    {
        $params = Yii::$app->request->getBodyParams();

        $model = Apple::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        
        $model->percent_eaten += (int) $params['percent_eaten'];
        if ($model->validate() && $model->save()) {
            $response->statusCode = 200;
            $response->data = ['success'];
            if ($model->percent_eaten === 100) {
                $model->delete();
                $response->data['allEaten'] = 1;
            }
        } else {
            $response->statusCode = 400;
            $response->data = ['errors' => $model->errors];
        }
        
        return $response;
    }
    
    /**
     * Снять яблоко с дерева
     *
     * @param $id apple id
     * @return yii\web\Response
     */
    public function actionFall($id)
    {
        $model = Apple::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException;
        }

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        
        $model->status = 1;
        $model->fallen_at = time();
        if ($model->validate() && $model->save()) {
            $response->statusCode = 200;
            $response->data = ['success', 'fallen' => 1];
        } else {
            $response->statusCode = 400;
            $response->data = ['errors' => $model->errors];
        }
        
        return $response;
    }
    
}
