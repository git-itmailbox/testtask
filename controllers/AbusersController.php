<?php


namespace app\controllers;

use DateTime;

use app\models\Companies;
use app\models\FakeTransfer;
use app\models\Transfers;
use app\models\Users;
use app\models\UsersQuery;
use yii\db\Query;
use yii\helpers\Html;
use yii\web\Controller;
use yii\data\Pagination;
use Yii;
use yii\helpers\Url;

class AbusersController extends Controller
{

    public function actionIndex()
    {
        $transfers = (new Transfers());
        $months = $transfers->getDistinctMonthOfTransfers();
        return $this->render('index',['months'=>$months]);
    }

    public function actionCompanies()
    {
        $request = Yii::$app->request;
        $month = $request->get('month', -1);
        $companiesExceededlimit = ($month > 0) ? (new Companies())->getWithExceededQuota($month) : null;
        foreach ($companiesExceededlimit as &$company) {
            $company['url'] = Html::a($company['name'], '@web/users/usersabusers/' . $company['id'] . '/' . $month, ['class' => 'showUsersAbusers']);
        }

        header('Content-Type: application/json', true, 200);
        echo json_encode($companiesExceededlimit, JSON_UNESCAPED_UNICODE);
        Yii::$app->end();

    }

    public function actionGeneratedata()
    {
        $transfers = (new Transfers());
        $generator = \Faker\Factory::create();
        $users = new Users();
        $months = $transfers->getDistinctMonthOfTransfers();
        $allUsers = $users->find()->asArray()->all();
        $batchArr = [];
        foreach ($allUsers as $user) {
            $start = new DateTime('first day of this month');
            $d = new DateTime('first day of this month');
            date_modify($d, '-6 month');
            $dataArr = [];
            while ($d < $start) {
                $dataArr[] = $d->format('Y-m-d H:i:s');
                date_modify($d, '+1 day');
            }

            $rndmTransPerUser = $generator->numberBetween(49, 500);
            while ($rndmTransPerUser > 0):
                $randomKeyDate = $generator->randomKey($dataArr);
                $tmp = $transfers->getTransfersOfDay($dataArr[$randomKeyDate], $user['id']);
                $rndmTransPerUser -= sizeof($tmp);
                unset($dataArr[$randomKeyDate]);
                $dataArr = array_values($dataArr);

                if (is_array($tmp)) $batchArr = array_merge($batchArr, $tmp);
            endwhile;
            foreach ([1, 2, 3, 4, 5, 6] as $m) {
                $start = new DateTime('first day of this month');
                date_modify($d, '-1 month');
                $daysOfCurMonth = $transfers->dayInMonth($d->format('m'), $d->format('Y'));
                $randomDateInMonth = $generator->dateTimeBetween($d, $generator->dateTimeInInterval($d, "+" . $daysOfCurMonth . " days"));

                $generatedData = $transfers->getTransfersOfDay($randomDateInMonth, $user['id']);
//                    $batchArr=array_merge($batchArr,$generatedData);
            }
        }

        $newArray = array();
        foreach ($batchArr as $item) {
            if (sizeof($item) != 4) continue;
            array_push($newArray, $item);
        }

        $message = "";

        \Yii::$app->db->createCommand()->batchInsert('transfers', ['date', 'user_id', 'resource', 'transferred'], $newArray)->execute();

        $message = "Data successfully generated";

        return $this->render('index', ['message' => $message, 'months'=>$months]);
    }
}