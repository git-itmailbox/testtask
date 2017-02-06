<?php


namespace app\controllers;


use app\models\Companies;
use app\models\Users;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\data\Pagination;
use Yii;

class UsersController extends Controller
{

    public function actionIndex()
    {
        $queryUsr = Users::find();
        $users = new ActiveDataProvider([
            'query' => $queryUsr
        ]);
        $queryCmp = Companies::find()->select(['id','name'])->asArray()->all();

        return $this->render('index', ['users' => $users, 'companies'=>$queryCmp]);
    }

    public function actionUsersabusers()
    {
        $request = Yii::$app->request;
        $month = $request->get('month', -1);
        $companyId = $request->get('idcompany');
        $usersAbusers = Users::find()->select("users.name, tr.date, tr.resource, tr.transferred")->leftJoin('transfers tr', 'tr.user_id=users.id')
            ->where("tr.transferred > (SELECT avg(transferred) from transfers where users.company_id = $companyId)")
            ->andWhere(" users.company_id=$companyId")->andWhere("MONTH(tr.date)= '".$month."'")->orderBy('date')->asArray()->all();

        foreach ($usersAbusers as &$users) {
            $users['transferred'] = $this->formatBytes($users['transferred']);
        }
        return $this->render('usersabusers', [
            'users' => new ArrayDataProvider([
                'allModels' => $usersAbusers,
                'pagination' => ['pageSize' => 20]
            ]),
        ]);
    }

    public function actionCreate()
    {

        $request = Yii::$app->request;
        if ($request->isGet)
            return $this->redirect('index');

        $model = new Users;
        $model->attributes = $_POST;
       if($model->validate()){
		        $model->save();
	}
	else{
	
        return $this->sendAjaxResponseError($model,$model->errors);
	
	}
        return $this->sendAjaxResponse($model);

//
    }

    public function sendAjaxResponse($model)
    {
        header('Content-Type: application/json',true, 200);
        echo json_encode([
            'data'=>$model->attributes,
        ]);
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
        $user = Users::findOne($id);
        if ($user->delete())
            return json_encode(['id'=>$id])  ;

    }

    public function actionUpdate()
    {
        $id = $_POST['id'];
        $model = Users::findOne($id);
        $model->attributes = $_POST;
	if($model->validate()){
		        $model->save();
	}
	else{
	
        return $this->sendAjaxResponseError($model,$model->errors);
	
	}
        return $this->sendAjaxResponse($model);

    }


    public function actionGetuser()
    {
        $id = $_POST['id'];

        return $this->sendAjaxResponse(Users::findOne( $id ));

    }


    function formatBytes($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }


}
