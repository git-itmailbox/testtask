<?php

namespace app\models;
use Yii;
use app\models\Users;
use DateTime;


/**
 * This is the model class for table "transfers".
 *
 * @property integer $id
 * @property string $date
 * @property integer $user_id
 * @property string $resource
 * @property integer $transferred
 *
 * @property Users $user
 */
class Transfers extends \yii\db\ActiveRecord
{
    private $format;

    private $myFakeGenerator;
    function __construct(){
        $this->format='Y-m-d H:i:s';
        $this->myFakeGenerator = \Faker\Factory::create();

    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'user_id', 'resource'], 'required'],
            [['date'], 'safe'],
            [['user_id', 'transferred'], 'integer'],
            [['resource'], 'string', 'max' => 200],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'user_id' => 'User ID',
            'resource' => 'Resource',
            'transferred' => 'Transferred',
        ];
    }

    public function dayInMonth($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN,$month,$year);

    }


    public function getOneTransfer($date, $user_id)
    {
        $transfers = [$date, $user_id, $this->myFakeGenerator->url, $this->transfer()];
        return $transfers;
    }

    public function transfer(){


        $case1 = $this->myFakeGenerator->numberBetween(100,1000000);
        $case2 = $this->myFakeGenerator->numberBetween(1,  1000000);
        $case3 = $this->myFakeGenerator->numberBetween(100,  10000);
        $condition = $this->myFakeGenerator->numberBetween(1,3);
        switch ($condition){
        case 1:
            return $case1;
            break;

        case 2:
            return $case1 * $case2;
            break;

        case 3:
            return $case3;
            break;
        }

    }

    public function getDistinctMonthOfTransfers()
    {
        $m = self::find()->select('MONTH(date) as months, MONTHNAME(date) as monthname')->distinct('MONTH(date)')->orderBy('MONTH(date)')->asArray()->all();
        $months=[];
        foreach ($m as $month)
        {
            $months[$month['months']]=$month['monthname'];
        }
        return $months;


    }


    public function getTransfersOfDay($date, $user_id)
    {
        $randomPerDay = $this->myFakeGenerator->numberBetween(1,5);
        $transfers=[];
        for($i=0; $i<$randomPerDay;$i++)
        {
            $transfers[]=$this->getOneTransfer($date, $user_id);
        }

        if(is_array($transfers)) {return $transfers;}


    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }
}