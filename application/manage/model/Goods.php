<?php
namespace app\manage\model;
use think\Model;
class Goods extends Model{
	protected $table = 'tbl_xc_shangpin';
	protected $field = true;
	protected $pk = 'id'; 	
	public function kinds()
    {
        return $this->belongsTo('Kinds','shangpinflid','id');
    }


    }
?>