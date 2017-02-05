<?php


namespace app\controllers;
use app\models\Companies;
use yii\web\Controller;
use yii\data\Pagination;
use yii\data\ArrayDataProvider;
use Yii;

class CompaniesController extends Controller
{

    public function actionIndex()
    {
        $query = Companies::find();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $query->count(),
        ]);

        $companies = $query->orderBy('name')
            ->offset($pagination->offset)
            ->limit($pagination->limit)->asArray()
            ->all();

        return $this->render('index', [
            'companies' => new ArrayDataProvider([
                'allModels' => $companies,
                'pagination' => ['pageSize' => 20]
            ]),
        ]);
    }

    public function sendAjaxResponse($model)
    {
        header('Content-Type: application/json',true, 200);
        echo json_encode(['data'=>$model->attributes,]);
        Yii::$app->end();
    }

    public function sendAjaxResponseError($model,$message)
    {
        header('Content-Type: application/json',true, 200);
        echo json_encode(['data'=>$model->attributes, 'message'=>$message]);
        Yii::$app->end();
    }

    public function actionDelete()
    {
        $id = $_POST['id'];
        $company = Companies::findOne($id);
        if ($company->delete())
            return json_encode(['id'=>$id])  ;

    }

    public function actionUpdate()
    {
        $id = $_POST['id'];
        $model = Companies::findOne($id);

        $model->attributes = $_POST;
        $model->save();
        return $this->sendAjaxResponse($model);

    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        if ($request->isGet)
            return $this->redirect('index');

        $model = new Companies;
        $model->attributes = $_POST;
        if($model->quota<1000)
        $model->quota *= pow(1024,4);
        else $this->sendAjaxResponseError($model, "Quota must be between 1 and 1000 TB");
        $model->save();
        return $this->sendAjaxResponse($model);
//
    }
    public function actionGetcompany()
    {
        $id = $_POST['id'];

        return $this->sendAjaxResponse(Companies::findOne( $id ));

    }
}
