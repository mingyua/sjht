<?php
namespace app\manage\controller;
use think\Controller;
use think\Request;
use think\Db;
use \think\Cache;
class User extends Auth
{
	public function index() {			
		$model=model('Number');		
		$where['store_code']=array("eq",$this->dpid);		
		$data=$model->with('Grade')->where($where)->paginate(14);		
		$this->assign('adminlist',$data);		
		return $this -> fetch();
	}
	//会员积分设置
	public function set() {	
		$set=db('number_sz');	
		if($this->request->isPost()) {				
            $post = $this->request->post(); 
            if(isset($post['qy_flag'])){
            	$post['qy_flag']=1;
            }else{
            	$post['qy_flag']=0;
            }
            $result=$set->where('user_code', $this->uname)->update(['qy_flag' =>$post['qy_flag'],'every_time_nsume'=>$post['every_time_nsume']]);
            if(false === $result){
				    $back=['msg'=>'修改失败','status'=>2];
				}else{
					$back=['msg'=>'修改成功','status'=>1];
				}
				return $back;
        }
            
			
		$where['user_code']=array("eq",$this->uname);
		$data=$set->where($where)->find();		
		if(!$data){
			$result=$set->insert(['user_code'=>$this->uid,'qy_flag'=>'0','every_time_nsume'=>'']);
		}
		$this->assign('set',$data);		
		return $this -> fetch();
	}
	




//添加会员 
	public function adduser(){
		if($this->request->isPost()) {
				
            $post = $this->request->post(); 
           // return $post;	
			$user=model('Number');
			if(isset($post['id'])){	
									
				$result = $user->allowField(true)->validate('Number.edit')->save($post,['number_code' => $post['id']]);
				if(false === $result){									
				    $back=['msg'=>$user->getError(),'status'=>2];
				}else{
					$back=['msg'=>'修改成功','status'=>1];
				}							
				
			}else{				
				$post['number_code']=$post['store_code'].time();				
				$result = $user->validate('Number.add')->allowField(true)->save($post);
				if(false === $result){
				    $back=['msg'=>$user->getError(),'status'=>2];
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
			$info=model('Number')->where("number_code='$id'")->find();
		}else{
			$info=NULL;
		}
		
		
		$this->assign('info',$info);			
			
		return $this->fetch();
		}
	}	
	public function deluser() {
		if($this->request->isPost()) {
            $post = $this->request->post(); 

			$id=input('id');
			$user = db("number"); // 实例化User对象

			if($user->where('number_code','eq',$id)->delete()){
				$back=['msg'=>'删除成功','status'=>1];	
				
			}else{
				$back=['msg'=>'删除失败','status'=>2];	
				
			}

			return $back;
						
		}
	}		
}