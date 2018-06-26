<?php
namespace app\manage\controller;
use think\Controller;
use think\Request;
use think\Db;
class Member extends Auth
{
	public function index() {	
		$admin=model('Member');		
		$where['Cashier_code']=array("gt",0);		
		$where['store_code']=array("eq",$this->dpid);				
		$data=$admin->where($where)->order('Cashier_code ASC')->paginate(14, false, ['query' => request()->param()]);
		//dump($data);
		$this->assign('adminlist',$data);		
		return $this -> fetch();
	}
		
	public function access() {
		if($this->request->isPost()) {
            $post = $this->request->post(); 
			$menu=model('Member');
			$id=$post['id'];
			
			if(isset($post['allck'])){
				$mid=implode(',',$post['allck']);
			}else{
				$mid='';
			}
			
			if($menu->save(['update_user_code'=>$mid],['Cashier_code'=>$id])){
				$back=['msg'=>'操作成功','status'=>1];					
			}else{
				$back=['msg'=>'操作失败','status'=>2];	
				
			}	
			return $back;
		}else{
			$id=input('id');
			$info=db('cashier')->where('Cashier_code','EQ',$id)->find();				
			$menu=db('xc_menu')->where('IsFz','eq','1')->order('Menu_order DESC')->select();
			$mid=explode(',',$info['update_user_code']);
			foreach($menu as $k =>$v){
				$id=$v['ID'];	
				if(in_array($id,$mid)){
					$menu[$k]['checked']='checked';
				}else{
					$menu[$k]['checked']='';
				}		
			}
			
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
	public function addmember(){
		if($this->request->isPost()) {
				
            $post = $this->request->post(); 
           // return $post;	
			$manage=model('member');
			$post['user_code']=session('uname');
			if(isset($post['id'])){
				if(!empty($post['password'])){
						$post['Cashier_pass']=MD5($post['password']);
				}	
				$post['update_date']=date('Y-m-d H:i:s');						
				$result = $manage->allowField(true)->save($post,['Cashier_code' => $post['id']]);
				if(false === $result){									
				    $back=['msg'=>'修改失败','status'=>2];
				}else{
					$back=['msg'=>'修改成功','status'=>1];
				}							
				
			}else{
				   $num= Db::name('cashier')->max('Cashier_code');
				   if($num==0){
				   	$code="00001";
				   }else{
				   	$new=$num+1;
				   	$code=str_pad($new,5,'0',STR_PAD_LEFT);
				   	}				
				$post['create_user_code']=session('uname');
				$post['create_date']=date('Y-m-d H:i:s');
				
				$post['Cashier_code']=$code;
				$post['Cashier_pass']=MD5($post['password']);
				$result = $manage->allowField(true)->save($post);
				if(false === $result){
				    $back=['msg'=>'添加失败','status'=>2];
				}else{
					$back=['msg'=>'添加成功','status'=>1];
				}
						
				
			}
			
			return $back;		
		}else{
		$id=input('id');
		$where['user_code']=array("eq",session('uname'));
		if(session('access')==2 || session('access')==0){
			$where['store_code']=array("eq",$this->dpid);
		}		
		$role=db('store')->where($where)->select();
		
		$this->assign('role',$role);
		if($id){
			$info=db('cashier')->where('Cashier_code','eq',$id)->find();
		}else{
			$info=NULL;
		}
		
		//dump($info);die();
		$this->assign('info',$info);			
			
		return $this->fetch();
		}
	}	
	public function delmember(){
		if($this->request->isPost()) {
            $post = $this->request->post(); 

			$id=input('id');
			$user = db("cashier"); // 实例化User对象

			if($user->where('Cashier_code','eq',$id)->delete()){
				$back=['msg'=>'删除成功','status'=>1];	
				
			}else{
				$back=['msg'=>'删除失败','status'=>2];	
				
			}

			return $back;
						
		}
	}	
}