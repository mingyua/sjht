<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
class Index extends Auth
{
    public function index()
    {

		$this->assign('menudata',$this->menudata);
		return $this->fetch();
    }
    public function right()
    {    	
    	
    	
	   	$dplist=db('store')->where('user_code','eq',$this->uname)->select();
		$this->assign('dplist',$dplist);
		$this->assign('dpid',$this->dpid);
		return $this->fetch();
    }
    
    public function dpchange(){
    	$dpid=input('code');
    	$dpinfo=db('store')->where('store_code','eq',$dpid)->find();
    	if($dpinfo){
    	session(null);
		session('uid',$dpinfo['TEL']);
		session('stcode',$dpinfo['store_code']);
		session('uname',$dpinfo['user_code']);
		session('sjname',$dpinfo['store_name']);
		session('access',2);
			return ['msg'=>'切换店铺成功','status'=>1];
		}else{
			return ['msg'=>'切换店铺失败','status'=>2];
		}
		
    }
}
