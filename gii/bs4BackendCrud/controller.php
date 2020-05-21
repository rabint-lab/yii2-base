<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends \rabint\controllers\AdminController <?php //= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{

    const BULK_ACTION_SETDRAFT = 'bulk-draft';
    const BULK_ACTION_SETPUBLISH = 'bulk-publish';
    const BULK_ACTION_DELETE = 'bulk-delete';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors([
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'bulk' => ['POST'],
                ],
            ],
        ]);
    }

     /**
     * list of bulk action as static
     * @return array
     */
    public static function bulkActions() {
        return [
            //static::BULK_ACTION_SETPUBLISH => ['title' =>  <?= $generator->generateString('set publish');?>,'class'=>'success','icon'=>'fas fa-check'],
            //static::BULK_ACTION_SETDRAFT => ['title' =>  <?= $generator->generateString('set draft');?>,'class'=>'warning','icon'=>'fas fa-times'],
            static::BULK_ACTION_DELETE => ['title' =>  <?= $generator->generateString('delete all');?>, 'class' => 'danger', 'icon' => 'fas fa-trash-alt'],
        ];
    }
   
    
    /**
     * bulk action
     * @return mixed
     */
    public function actionBulk($action)
    {

        $request = Yii::$app->request;
        $pks = explode(',', $request->post('pks')); // Array or selected records primary keys

        if (!isset(static::bulkActions()[$action])) {
            Yii::$app->session->setFlash('warning',  <?= $generator->generateString('Bulk action Not found!');?>);
            return $this->redirect(\rabint\helpers\uri::referrer());
        }
        $selection = (array) $pks;
        $err = 0;
        switch ($action) {
            case static::BULK_ACTION_SETPUBLISH:
                if (<?= $modelClass ?>::updateAll(['status' => <?= $modelClass ?>::STATUS_DRAFT], ['id' => $selection])) {
                    Yii::$app->session->setFlash('success',  <?= $generator->generateString('Bulk action was successful');?>);
                } else {
                    $err++;
                }
                break;
            case static::BULK_ACTION_SETDRAFT:
                if (<?= $modelClass ?>::updateAll(['status' => <?= $modelClass ?>::STATUS_DRAFT], ['id' => $selection])) {
                    Yii::$app->session->setFlash('success',  <?= $generator->generateString('Bulk action was successful');?>);
                } else {
                    $err++;
                }
                break;
            case static::BULK_ACTION_DELETE:
                if (<?= $modelClass ?>::deleteAll(['id' => $selection])) {
                    Yii::$app->session->setFlash('success',  <?= $generator->generateString('Bulk action was successful');?>);
                } else {
                    $err++;
                }
                break;
        }
        if ($err) {
            Yii::$app->session->setFlash('danger',  <?= $generator->generateString('عملیات ناموفق بود');?>);
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#ajaxCrudDatatable'];
        }
        return $this->redirect(\rabint\helpers\uri::referrer());
    }

    /**
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new <?= $modelClass ?>();

        $request = Yii::$app->request;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success',  <?= $generator->generateString('Item successfully created.');?>);

                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'forceReload' => '#ajaxCrudDatatable',
                        'title' =>  <?= $generator->generateString('Create new');?>.' '. <?= $generator->generateString($modelClass);?>,
                        'content' => '<span class="text-success">' .  <?= $generator->generateString('Create {item} success',['item'=>'<?= $modelClass ?>']);?> . '</span>',
                        'footer' => Html::button( <?= $generator->generateString('Close');?>, ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a( <?= $generator->generateString('Create More');?>, ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                    ];
                }
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', str::modelErrors($model->errors));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {

        
        $model = $this->findModel(<?= $actionParams ?>);

        $request = Yii::$app->request;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success',  <?= $generator->generateString('Item successfully updated.');?>);

                if ($request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    [
                        'forceReload' => '#ajaxCrudDatatable',
                        'title' =>  <?= $generator->generateString('Updating');?>.' '. <?= $generator->generateString($modelClass);?>,
                        'content' => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'footer' => Html::button( <?= $generator->generateString('Close')?>, ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                            Html::a('Edit', [ <?= $generator->generateString('update');?>, 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
                    ];
                }
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('danger', str::modelErrors($model->errors));
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
                    
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {

        $request = Yii::$app->request;

        if($this->findModel(<?= $actionParams ?>)->delete()){
            Yii::$app->session->setFlash('success', <?= $generator->generateString('Item successfully deleted.')?>);
        }else{
            Yii::$app->session->setFlash('danger', <?= $generator->generateString('Unable to delete item.')?>);
        }

        if ($request->isAjax) {
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose' => true, 'forceReload' => '#ajaxCrudDatatable'];
        } 

        return $this->redirect(['index']);

    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rabint', 'The requested page does not exist.'));
        }
    }
}
