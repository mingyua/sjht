<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
class Menu extends Auth
{
    public function index()
    {
		$menu=db('xc_menu')->order('Menu_order DESC')->select();
		$data=menutree($menu,0);
		
		//dump($data);
		$this->assign('menulist',$data);
    	

	return $this->fetch();
    }
	public function addmenu() {
		if($this->request->isPost()) {
            $post = $this->request->post(); 			
			$menu=model('Menu');
			
			if(isset($post['id'])){
					if($menu->allowField(true)->save($post,['ID' => $post['id']])){
						$back=['msg'=>'修改成功','status'=>1];
					}else{
						$back=['msg'=>'修改失败','status'=>0];
					}			     
						
				
			}else{				
					if($menu->allowField(true)->save($post)){
						$back=['msg'=>'添加成功','status'=>1];
					}else{
						$back=['msg'=>'添加失败','status'=>0];
					}			     
						
				
			}
			
			return $back;
				
		}else{
		$id=input('id');	
		$menu=db('xc_menu')->where("Status='1'")->select();
		$data=menutree($menu,0);
		$this->assign('menulist',$data);
		if($id){
			$info=db('xc_menu')->where("ID=".$id)->find();
		}else{
			$info=NULL;
		}
		
		$this->assign('act',input('act'));
		$this->assign('info',$info);
		return $this->fetch();
		}
		
	}
	public function delmenu() {
		if($this->request->isPost()) {
            $post = $this->request->post(); 
			$id=input('id');
			$menu = db("xc_menu"); // 实例化User对象

			if($menu->where("ID=".$id)->delete()){
				$back=['msg'=>'删除成功','status'=>1];	
				
			}else{
				$back=['msg'=>'删除失败','status'=>2];	
				
			}

			return $back;
						
		}
	}
}
