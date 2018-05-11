<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
class Shop extends Auth
{
		
		public function index(){
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
    	$Store=model('Store');		
		$data=$Store->where($where)->paginate(12); 	     
		$this->assign('storelist',$data);   	
		
			
		
		return $this->fetch();	
		}
		public function addshop(){
			if($this->request->post()){
				$store=model('Store');
				$post = $this->request->post(); 
		        
		        
		        if(!isset($post['Statue'])) $post['Statue']='0';
				if($post['TEL']==$post['user_code']){
					$post['user_flag']=1;
				}else{
					$post['user_flag']=2;
				}
				$post['store_code']=$post['TEL'];
				$post['trade_id']=10;
				
				if(isset($post['sebei'])){
					$post['sebeiIDs']=implode(',',$post['sebei']);
				}else{
					$post['sebeiIDs']='';
				}
				if(isset($post['id'])){
					if(empty($post['Store_pass'])){
						unset($post['Store_pass']);
					}else{
						$post['Store_pass']=MD5($post['Store_pass']);
					}
					$result = $store->allowField(true)->save($post,['TEL'=>$post['TEL']]);
		       		if(false === $result){
					    $back=['msg'=>$store->getError(),'status'=>2];
					}else{	       		
		       			$back=['msg'=>'修改成功','status'=>1];
		       		}					
					
					
				}else{
					$post['Store_pass']=md5($post['Store_pass']);	      
					$result = $store->validate(true)->allowField(true)->save($post);
		       		if(false === $result){
					    $back=['msg'=>$store->getError(),'status'=>2];
					}else{	       		
		       			$back=['msg'=>'添加成功','status'=>1];
		       		}					
				}
				return $back;
			}else{
				$id=input('tel');
				if(isset($id)){
					$info=db('store')->where('TEL','eq',$id)->find();
				}else{
					$info='';
				}
				//dump($info);
				$sheb=Db::name('xc_shebei')->cache(true)->select();
				$this->assign('info',$info);
				$this->assign('shebei',$sheb);
				return $this->fetch();					
			}
		}
		public function jsonarr(){
			
			$key=input('wd');
			$map['store_name']=['like','%'.$key.'%'];
			$user=model('user')->where($map)->field('store_name,TEL,Address,email,N_code,position_id,position_id1,position_id2,Lianxir')->select();
			$data=['q'=>$key,'p'=>false,'s'=>$user];

		return $data;
		}
    public function status()
    {
    	$id=input('id');
    	$status=Db::name('store')->where('TEL',$id)->value('Statue');
    	
    	if($status==1){
    		$data['Statue']=0;
    	}else{
    		$data['Statue']=1;
    	}
    	$result=model('store')->save($data,['TEL'=>$id]);
   		if(false === $result){
		    $back=['msg'=>'操作失败','status'=>2];
		}else{	       		
   		$back=['msg'=>'操作成功','status'=>1];
   		}
		return $back;
	}		
	}

?>