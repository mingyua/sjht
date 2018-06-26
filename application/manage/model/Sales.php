<?php
namespace app\manage\model;
use think\Model;
class Sales extends Model{
	protected $name = 'sales';
	protected $field = true;
	protected $pk = ''; 	
	public function saledetail()
    {
        return $this->hasMany('Saledetail','selas_code','selas_code');
    }



    }
?>