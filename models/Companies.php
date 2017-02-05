<?php

namespace app\models;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "companies".
 *
 * @property integer $id
 * @property string $name
 * @property integer $quota
 *
 * @property Users[] $users
 */
class Companies extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'quota'], 'required'],
            [['quota'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'quota' => 'Quota',
        ];
    }

    public function getBigExceed($limit = 10000)
    {
        $date= '2017-01-01 00:00:00';
        $date2= '2017-03-01 00:00:00';
        return $this->hasMany(Users::className(), ['company_id' => 'id'])
            ->leftJoin('transfers',['transfers.user_id'=>'users.id'])
            ->sum('transfers.transferred as summary')
            ->where("transfers.date > :date and transfers.date < :date2",
                    [":date"=>$date,":date2"=>$date2,])
            ->groupBy("users.company_id")
            ->having('summary > companies.quota')
            ->all();

    }

    public function getWithExceededQuota($month){

        $date= '2016-'.$month.'-01 00:00:00';

        $rows = (new Query())
            ->select(['companies.id', 'companies.name', 'companies.quota', 'summary'=>'sum(transferred)'])
            ->from('transfers')
            ->join('LEFT JOIN', 'users', 'transfers.user_id=users.id')
            ->join('LEFT JOIN', 'companies', 'companies.id=users.company_id')
            ->where("MONTH(transfers.date) = '".$month ."'")
            ->groupBy('users.company_id')
            ->having('summary > companies.quota')
//           ->where(['company_id'=>'1'])
//           ->limit(2)
            ->all();

        return $rows;

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['company_id' => 'id']);
    }
}
