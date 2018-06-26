<?php
namespace app\manage\model;
use think\Model;
class Number extends Model{
	protected $name = 'number';
	protected $field = true;
	protected $pk = 'number_code';
	public function grade()
    {
        return $this->belongsTo('Grade','number_class');
    }
}
