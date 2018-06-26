<?php
namespace app\manage\model;
use think\Model;
class Indetail extends Model{
	protected $name = 'Inventory_detail';
	protected $field = true;
	protected $pk = ''; 	
	public function goods()
    {
        return $this->belongsTo('Goods','commodity_code','commodity_code');
    }
	public function usercode()
    {
        return $this->hasMany('Goods','user_code','user_code');
    }
	public function storecode()
    {
        return $this->hasMany('Goods','Store_code','Store_code');
    }


    }
?>