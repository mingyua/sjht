<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
class Business extends Auth
{
    public function index()
    {
    	$data=array_filter(input());
    	$where='';
    	if(is_array($data)){
    		foreach($data as $k=>$v){
    			
    			if($k=='keyword'){
    				$where['store_name']=array('like','%'.$v.'%');
    			}else{
    				$where[$k]=$v;	
    			}
    			unset($where['page']);
    		}
    		
    	}
    	$user=model('User');		
		$data=$user->where($where)->paginate(12, false, ['query' => request()->param()]); 	     
		$this->assign('userlist',$data);   	
	return $this->fetch();
    }
    public function addbus()
    {
    	$user=model('User');
	    if($this->request->isPost()) {
	        $post = $this->request->post(); 
	        
	        if(!isset($post['Statue'])) $post['Statue']='0';
	        if(!isset($post['My_shop'])) $post['My_shop']='0';
	        if(!isset($post['Micro_channel_store'])) $post['Micro_channel_store']='0';
	        if(!isset($post['Micro_channel_infor'])) $post['Micro_channel_infor']='0';
	        if(!isset($post['Short_message'])) $post['Short_message']='0';
	        if(!isset($post['empty'])) $post['empty']='0';	        
	        $post['user_code']=$post['TEL'];
	        
	       if(isset($post['id'])){
	       		if(empty($post['Store_pass'])){
	       			unset($post['Store_pass']);
	       		}else{
	       			$post['Store_pass']=md5($post['Store_pass']);	
	       		}
	       		$result = $user->save($post,['TEL'=>$post['TEL']]);
	       		if(false === $result){
				    $back=['msg'=>$user->getError(),'status'=>2];
				}else{	       		
	       		$back=['msg'=>'修改成功','status'=>1];
	       		}
	       }else{
	        $post['Store_pass']=md5($post['Store_pass']);	        
				$result = $user->validate('User')->allowField(true)->save($post);
				if(false === $result){
				    $back=['msg'=>$user->getError(),'status'=>2];
				}else{
				   $num= Db::name('user')->max('N_code');
				   if($num==0){
				   	$code="000001";
				   }else{
				   	$new=$num+1;
				   	$code=str_pad($new,6,'0',STR_PAD_LEFT);
				   }				    
					$user->where('TEL', $post['TEL'])->update(['N_code' => $code]);
					$back=['msg'=>'添加成功','status'=>1];
				}	        
			}	        
	       return $back; 
	    }else{
	    $id=input('tel');
	    if(isset($id)){
	    	$info=db('user')->where("TEL=".$id)->find();
	    }else{
	    	$info="";
	    } 
	    	
	    $this->assign('info',$info);
		return $this->fetch();
	    	
	    }
	}
    public function view()
    {
	return $this->fetch();
	}	
    public function status()
    {
    	$id=input('id');
    	$status=Db::name('user')->where('TEL',$id)->value('Statue');
    	
    	if($status==1){
    		$data['Statue']=0;
    	}else{
    		$data['Statue']=1;
    	}
    	$result=model('user')->save($data,['TEL'=>$id]);
   		if(false === $result){
		    $back=['msg'=>'操作失败','status'=>2];
		}else{	       		
   		$back=['msg'=>'操作成功','status'=>1];
   		}
		return $back;
	}
}
