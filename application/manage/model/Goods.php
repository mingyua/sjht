<?php
namespace app\manage\model;
use think\Model;
class Goods extends Model{
	protected $name = 'commodity';
	protected $field = true;
	protected $pk = ''; 	
	public function kinds()
    {
        return $this->belongsTo('Kinds','commodity_type_id','id');
    }


    }
?>