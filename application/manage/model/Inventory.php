<?php
namespace app\manage\model;
use think\Model;
class Inventory extends Model{
	protected $name = 'Inventory';
	protected $field = true;
	protected $pk = ''; 	
	public function indetail()
    {
        return $this->hasMany('Indetail','Inventory_code','Inventory_code');
    }



    }
?>