<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
use \think\Cache;
use app\manage\model\Goods as Gods;
class Goods extends Auth{
	public function index(){
		$goodkinds=db('commodity_type')->cache('kinds',0)->select();
		$kinds=json_encode(ztree($goodkinds,0));
		$this->assign('kinds',$kinds);
		//商品列表
    	$data=array_filter(input());
    	$where='';
    	if(is_array($data)){
    		foreach($data as $k=>$v){
    			
    			if($k=='keyword'){
    				$where['name|code']=array('like','%'.trim($v).'%');
    			}elseif($k=='spflid'){
    				$where['shangpinflid']=array('in',trim($v));
    			}else{
    				$where[$k]=trim($v);	
    			}
    			unset($where['page']);
    		}
    		
    	}
    	$splist=Gods::with("kinds")->where($where)->paginate(12);
		$this->assign('idarr',input('spflid'));
		$this->assign('key',input('keyword'));
		$this->assign('splist',$splist);
		
		return $this->fetch();
	}
	public function addgoods(){
		if($this->request->post()){
			$post=$this->request->post();
			$post['shangmengglyid']=session('uid');
			$post['shichangjg']=0;
			$post['shangpinzid']=0;

			$goods = new Gods();
			
			if(isset($post['id'])){
				$result=$goods->allowField(true)->save($post,['id'=>$post['id']]);
		   		if(false === $result){
				    $back=['msg'=>'修改失败','status'=>2];
				}else{	 
					$data['dizhi']=$post['sptpImg'];
					$data['name']=$post['name'];
					$img=db('xc_shangpintp')->where('shangpinid','EQ',$post['id'])->find();
					if($img){
						if($post['sptpImg']!=='0'){model('Goodsimg')->allowField(true)->save($data,['ID'=>$post['id']]);}  
					}else{
						$data['shangpinid']=$post['id'];
						if($post['sptpImg']!=='0'){model('Goodsimg')->allowField(true)->save($data);}  
					}
					
					      		
			   		$back=['msg'=>'修改成功','status'=>1];
			   	}				
				
			}else{
				$post['shijian']=date('Y-m-d H:i:s',time());
				
				$result=$goods->allowField(true)->save($post);
		   		if(false === $result){
				    $back=['msg'=>'添加失败','status'=>2];
				}else{	
					$gId = Db::name('xc_shangpin')->getLastInsID();
					$data['dizhi']=$post['sptpImg'];
					$data['shangpinid']=$gId;
					$data['name']=$post['name'];
					if($post['sptpImg']!=='0'){model('Goodsimg')->allowField(true)->save($data);}  
					  		
			   		$back=['msg'=>'添加成功'.$post['sptpImg'],'status'=>1];
			   	}				
			}
			return $back;
		}else{
		$id=input('id');
	    if(isset($id)){
	    	$info=db('xc_shangpin')->where("id=".$id)->find();
	    }else{
	    	$info="";
	    } 
	    	
	    $this->assign('info',$info);
		
		
		return $this->fetch();			
		}
		

	}	
	public function addkinds(){
		$kinds=model('Kinds');
		if($this->request->post()){
			$post=$this->request->post();
			$post['cpmmodity_type_name']=$post['name'];
			$post['super_id']=$post['pid'];
			unset($post['id']);
			$result = $kinds->allowField(true)->save($post);
       		if(false === $result){
			    $back=['msg'=>'添加失败','status'=>2];
			}else{
				Cache::rm('kinds'); 	
				$goodkinds=db('commodity_type')->cache('kinds')->select();       		
       			$back=['msg'=>'添加成功','status'=>1];

       		}
       						
			return $back;
		}
	}
	public function editkinds(){
		$kinds=model('Kinds');
		if($this->request->post()){
			$post=$this->request->post();
			$post['cpmmodity_type_name']=$post['name'];			
			$result = $kinds->allowField(true)->save($post,['id'=>$post['id']]);
       		if(false === $result){
			    $back=['msg'=>'修改失败','status'=>2];
			}else{
				Cache::rm('kinds'); 	
				$goodkinds=db('commodity_type')->cache('kinds')->select();       		
					       		
       			$back=['msg'=>'修改成功','status'=>1];

       		}
       						
			return $back;
		}
		
		
	
	}
	public function delkinds(){
		if($this->request->isPost()) {
            $post = $this->request->post(); 
            $goods = db("xc_shangpin"); 
			$kinds = db("commodity_type"); 

			$id=$post['id'];
			//查询商品表是否有此分类商品			
			$sparr=$goods->where('shangpinflid','eq',$id)->field('id,shangpinflid')->order('id ASC')->limit(1)->find();
			//查询是否有子分类		
			$fid=$kinds->where('super_id','eq',$id)->find();
			if($sparr){
				$back=['msg'=>'该分类下有商品，不能删除分类！请先删除商品或转移商品后再进行操作！','status'=>2];	
			}else if($fid){
				$back=['msg'=>'请先删除该分类下所有子分类！','status'=>2];	
			}else if($kinds->where("id=".$id)->delete()){
				Cache::rm('kinds'); 	
				$goodkinds=db('commodity_type')->cache('kinds')->select();       		

				$back=['msg'=>'删除成功','status'=>1];	
				
			}else{
				$back=['msg'=>'删除失败','status'=>2];	
				
			}

			return $back;
						
		}		
		
	}
	public function ckkinds(){
		if($this->request->isPost()) {
            $post = $this->request->post(); 
            $goods = db("xc_shangpin"); 
			$kinds = db("commodity_type"); 

			$id=$post['id'];
			//查询商品表是否有此分类商品			
			$sparr=$goods->where('shangpinflid','eq',$id)->field('id,shangpinflid')->order('id ASC')->limit(1)->find();
			//查询是否有子分类		
			$fid=$kinds->where('super_id','eq',$id)->find();
			if($sparr){
				$back=['msg'=>'该分类下有商品，不能删除分类！请先删除商品或转移商品后再进行操作！','status'=>2];	
			}else if($fid){
				$back=['msg'=>'请先删除该分类下所有子分类！','status'=>2];	
			}else{
				$back=['msg'=>'正在删除...','status'=>1];		
			}
			return $back;	
		}	
		
	}
	

	public function kinds(){
		$goodkinds=db('commodity_type')->cache('kinds',0)->select();
		$kinds=json_encode(ztree($goodkinds,0));
		//dump(ztree($goodkinds,0));die();
		$this->assign('kinds',$kinds);
		
		return $this->fetch();
	}
	public function brand(){
    	$data=array_filter(input());
    	$where='';
    	if(is_array($data)){
    		foreach($data as $k=>$v){
    			
    			if($k=='keyword'){
    				$where['name']=array('like','%'.$v.'%');
    			}else{
    				$where[$k]=$v;	
    			}
    			unset($where['page']);
    		}
    		
    	}
    	$sppp=model('Sppp');
    	
		$data=$sppp->where($where)->order('ID DESC')->paginate(24); 
		//dump($data);
		$this->assign('brandlist',$data);

		
		return $this->fetch();
	}
	
	
	public function addpp(){
		if($this->request->isPost()) {
            $post = $this->request->post(); 
			$sppp=model('Sppp');
			
			$id=isset($post['id']);
			
			if($id>0){					
					if($sppp->allowField(true)->save($post,['ID' => $post['id']])){			
						$back=['msg'=>'修改成功','status'=>1];
					}else{
						$back=['msg'=>'修改失败','status'=>0];
					}			     						
				
			}else{
					if($sppp->allowField('name')->save($post)){
						$back=['msg'=>'添加成功','status'=>1];
					}else{
						$back=['msg'=>'添加失败','status'=>0];
					}			     
							
				
			}
			
			return $back;	
		}		
	}
	
	public function delsppp(){
		if($this->request->isPost()) {
            $post = $this->request->post(); 

			$id=input('id');
			$sppp = db("xc_shangpinpp"); 

			if($sppp->where("ID=".$id)->delete()){
				$back=['msg'=>'删除成功','status'=>1];	
				
			}else{
				$back=['msg'=>'删除失败','status'=>2];	
				
			}

			return $back;
						
		}		
	}
	public function allon(){
		if($this->request->isPost()) {
            $post = $this->request->post(); 
			$sppp=model('Goods');
			$id=$post['id'];
			$data=[];
			foreach($id as $k=>$v){
				if($post['type']=='allon'){$isdel=0;}else{$isdel=1;}
				$data[$k]=['id'=>$v,'isdel'=>$isdel];
			
				
			}
			$result=$sppp->saveAll($data);
			if(false === $result){
			    $back=['msg'=>'修改失败','status'=>2];
			}else{
       			$back=['msg'=>'修改成功','status'=>1];
       		}					
			return $back;						
		}		
	}	
	public function alldel(){
		if($this->request->isPost()) {
            $post = $this->request->post(); 
			$sppp=model('Goods');
			$id=$post['id'];
			if(is_array($id)){
				$allid=implode(',',$id);
			}else{
				$allid=$id;
			}
			
			$result=$sppp->destroy($allid);
			if(false === $result){
			    $back=['msg'=>'删除失败','status'=>2];
			}else{
       			$back=['msg'=>'删除成功','status'=>1];
       		}					
			return $back;						
		}		
	}
    public function status()
    {
		if($this->request->isPost()) {
            $post = $this->request->post(); 
    		$id=$post['id'];
    		$status=Db::name('xc_shangpin')->where('id',$id)->value('isdel');
    	
	    	if($status==1){
	    		$data['isdel']=0;
	    	}else{
	    		$data['isdel']=1;
	    	}
	    	$result=model('Goods')->save($data,['id'=>$id]);
	   		if(false === $result){
			    $back=['msg'=>'操作失败','status'=>2];
			}else{	       		
		   		$back=['msg'=>'操作成功','status'=>1];
	   		}
		return $back;
		}
	}	

	public function getpp(){
		$key=input('keys');
		$pp=db('xc_shangpinpp')->where('name','like','%'.$key.'%')->select();
		foreach($pp as $k=>$v){
			$pp[$k]['id']=$v['ID'];
			
		}
	
		$data=['res'=>'1','data'=>$pp];
		return $data;
	}
	public function getdw(){
		$key=input('keys');
		$pp=db('unit')->where('unit_name','like','%'.$key.'%')->select();
		foreach($pp as $k=>$v){
			$pp[$k]['id']=$v['ID'];
			$pp[$k]['name']=$v['unit_name'];
		}
	
		$data=['res'=>'1','data'=>$pp];
		return $data;
	}
	public function getkinds(){
		$key=input('keys');
		$pp=db('commodity_type')->where('cpmmodity_type_name','like','%'.$key.'%')->select();
		foreach($pp as $k=>$v){
			$pp[$k]['id']=$v['id'];
			$pp[$k]['name']=$v['cpmmodity_type_name'];
		}
	
		$data=['res'=>'1','data'=>$pp];
		return $data;
	}
	
}
?>