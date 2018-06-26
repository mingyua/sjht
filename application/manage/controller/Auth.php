<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
use think\Request;
use \think\Cache;
class Auth extends Controller
{
	protected function _initialize() {
		header("Content-type: text/html; charset=utf-8");
		$request= Request::instance();
		$this->dpid=session('stcode');
		$this->uname=session('uname');
		$this->assign('md_name',$request->module());
		$this->assign('ct_name',$request->controller());
		$this->assign('ac_name',$request->action());
		$this->uid=$uid=session('uid');
		$diqu=db('xc_diqu')->cache(true)->select();	
	    $this->assign('diqu',json_encode($diqu));

		if(empty($uid)){
			$this->redirect('Login/index');
		}
		
		$access=session('access');
		if(empty(Cache::get('menulist'.$uid))){		
				if($access>0){
					$map['Status']=array('eq',1);			
					$map['IsFz']=array('eq',1);			
					$menulist=db('xc_menu')->where($map)->order('Menu_order DESC')->select();	
					$data=cate($menulist,'newarr',0);				
					Cache::set('menulist'.$this->dpid,$data);					
				}else{					
					$syy=Db::name('cashier')->where('TEL','EQ',$uid)->value('update_user_code');
					$map['ID']=array('in',$syy);			
					$map['Status']=array('eq',1);			
					$map['IsFz']=array('eq',1);			
					$menulist=db('xc_menu')->where($map)->order('Menu_order DESC')->select();	
					$data=cate($menulist,'newarr',0);				
					Cache::set('menulist'.$this->dpid,$data);
				}
			$this->menudata=$data;	
		}else{
			$this->menudata=Cache::get('menulist'.$uid);			
		}				
	} 
	
   
}
