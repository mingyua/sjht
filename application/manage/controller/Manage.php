<?php
namespace app\manage\controller;
use think\Controller;
use think\Request;
use think\Db;
class Manage extends Auth
{
	public function index() {	
		$admin=model('Manage');		
		$where['id']=array("gt",0);		
		$data=$admin->where($where)->order('id ASC')->paginate(14);
		//dump($data);
		$this->assign('adminlist',$data);		
		return $this -> fetch();
	}
	
	public function role() {
		if($this->request->isPost()) {
            $post = $this->request->post(); 
			$role=model('Role');
			
			$id=$post['id'];
			$post['Role_name']=$post['name'];
			if($id>0){					
					if($role->allowField(true)->save($post,['ID' => $post['id']])){			
						$back=['msg'=>'修改成功','status'=>1];
					}else{
						$back=['msg'=>'修改失败','status'=>0];
					}			     						
				
			}else{
					if($role->allowField('Role_name')->save($post)){
						$back=['msg'=>'添加成功','status'=>1];
					}else{
						$back=['msg'=>'添加失败','status'=>0];
					}			     
							
				
			}
			
			return $back;	
		}else{
			$id=input('id');
		if($id){
			$info=db('xc_role')->where("ID='$id'")->find();
		}else{
			$info=NULL;
		}
		$this->assign('info',$info);			
			$role=db('xc_role')->select();
			$this->assign('rolelist',$role);
			return $this -> fetch();
			
		}	
	}	

	public function delrole() {
		if($this->request->isPost()) {
            $post = $this->request->post(); 

			$id=input('id');
			$menu = db("xc_role"); // 实例化User对象

			if($menu->where("ID=".$id)->delete()){
				$back=['msg'=>'删除成功','status'=>1];	
				
			}else{
				$back=['msg'=>'删除失败','status'=>2];	
				
			}

			return $back;
						
		}
	}

	
	public function access() {
		if($this->request->isPost()) {
            $post = $this->request->post(); 

			$id=$post['id'];
			$menu = model("RoleMenu");
			$menu->where("RoleID=".$id)->delete();
			$dataList=[];
			if(isset($post['allck'])){
				foreach($post['allck'] as $k=>$v){
					$dataList[$k]['RoleID']=$id;
					$dataList[$k]['MenuID']=$v;
				}
				if($menu->saveAll($dataList)){
					$back=['msg'=>'操作成功','status'=>1];	
					
				}else{
					$back=['msg'=>'操作失败','status'=>2];	
					
				}
			}else{
				$back=['msg'=>'提交数据为空','status'=>2];	
			}		
			
			
			return $back;
		}else{
			$id=input('id');
			$info=db('xc_role')->where("ID=".$id)->find();				
			$menu=db('xc_menu')->order('Menu_order DESC')->select();
			
			
			//dump($menu);die();
			$data=cate($menu,'child',0);
			
			//查询角色所有权限
			//dump($data);die();
			$this->assign('menulist',$data);		
			
			$this->assign('id',$id);
			$this->assign('info',$info);
			return $this -> fetch();	
		}
	}
	
//添加管理员
	public function addmanage(){
		if($this->request->isPost()) {
				
            $post = $this->request->post(); 
           // return $post;	
			$manage=model('Manage');
			if(isset($post['id'])){
						
				$result = $manage->allowField(true)->validate('Admin.edit')->save($post,['id' => $post['id']]);
				if(false === $result){
					if(empty($post['user_pass'])){
						unset($post['user_pass']);
					}else{
						$post['user_pass']=MD5($post['user_pass']);
					}					
				    $back=['msg'=>$manage->getError(),'status'=>2];
				}else{
					$back=['msg'=>'修改成功','status'=>1];
				}							
				
			}else{
				
					
					$post['user_pass']=MD5($post['user_pass']);
				$result = $manage->validate('Admin.add')->allowField(true)->save($post);
				if(false === $result){
				    $back=['msg'=>$manage->getError(),'status'=>2];
				}else{
					$back=['msg'=>'添加成功','status'=>1];
				}
						
				
			}
			
			return $back;		
		}else{
		$id=input('id');	
		$role=db('xc_role')->select();
		
		$this->assign('role',$role);
		if($id){
			$info=model('Manage')->where("id='$id'")->find();
		}else{
			$info=NULL;
		}
		
		
		$this->assign('info',$info);			
			
		return $this->fetch();
		}
	}	
	public function delmanage() {
		if($this->request->isPost()) {
            $post = $this->request->post(); 

			$id=input('id');
			$user = db("admin_user"); // 实例化User对象

			if($user->where("id=".$id)->delete()){
				$back=['msg'=>'删除成功','status'=>1];	
				
			}else{
				$back=['msg'=>'删除失败','status'=>2];	
				
			}

			return $back;
						
		}
	}		
}