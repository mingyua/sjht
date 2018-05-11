<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
class Index extends Auth
{
    public function index()
    {
    	
    	//dump($this->menudata);die();
	$this->assign('menudata',$this->menudata);

	return $this->fetch();
    }
    public function right()
    {    	
   	$dplist=db('store')->where('user_code','eq',$this->uid)->select();
	$this->assign('dplist',$dplist);
	return $this->fetch();
    }
}
