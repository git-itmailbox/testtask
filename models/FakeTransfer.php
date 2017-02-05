<?php

namespace app\models;

class FakeTransfer extends \Faker\Provider\Base
{
    private $format;
    protected $myGenerator;
    function __construct(){
        $this->format='Y-m-d H:i:s';
        $this->myGenerator = \Faker\Factory::create();

    }

    //get array like [
    //                  ['date', 'user_id', 'resource', 'size']
    //                  ]
//
    public function getTransfersOfDay($numDay, $month, $year, $user_id)
    {
        $randomPerDay = $this->myGenerator->numberBetween(0,2);
        $transfers=[];
        for($i=0; $i<$randomPerDay;$i++)
        {
            $transfers[]=$this->getOneTransfer($numDay, $month, $year, $user_id);
        }

        if(is_array($transfers)) {return $transfers;}


    }


}