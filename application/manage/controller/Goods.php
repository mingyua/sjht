<?php

namespace app\manage\controller;
use think\Controller;
use think\Db;
use \think\Cache;
use think\Loader;
use app\manage\model\Goods as Gods;
class Goods extends Auth{
	public function index(){
		$goodkinds=db('commodity_type')->cache('kinds',0)->select();
		$kinds=json_encode(ztree($goodkinds,0));
		$this->assign('kinds',$kinds);
		//商品列表
    	$data=array_filter(input());
    	$where['store_code']=array('eq',$this->dpid);
    	$where['user_code']=array('eq',$this->uname);
    	$where['statue']=array('eq','1');
    	if(is_array($data)){
    		foreach($data as $k=>$v){
    			
    			if($k=='keyword'){
    				$where['commodity_code|commodity_name']=array('like','%'.trim($v).'%');
    			}
    			unset($where['page']);
    		}
    		
    	}
    	$splist=Gods::where($where)->order('create_date DESC')->paginate(12, false, ['query' => request()->param()]);
		$this->assign('idarr',input('spflid'));
		$this->assign('key',input('keyword'));
		$this->assign('splist',$splist);
		
		return $this->fetch();
	}
	public function addgood(){

	if(input('code')){
		$code=input();
		$map['Store_code']=['eq',$this->dpid];
		$map['commodity_code']=['eq',$code['code']];
		$goods=db('commodity')->where($map)->find();
		if($goods['SPType']==1){
			$goods=db('commodity')->where('Store_code','eq',$this->dpid)->where('commodity_code','eq',$goods['z_commodity_code'])->find();
		}
		if($goods['SPType']==2){
			$good=db('commodity')->where('Store_code','eq',$this->dpid)->where('commodity_code','eq',$goods['z_commodity_code'])->find();
			if($good['z_commodity_code']==0){
				$goods=$good;
			}else{
				$goods=db('commodity')->where('Store_code','eq',$this->dpid)->where('commodity_code','eq',$good['z_commodity_code'])->find();
			}			
		}
		$goods['in_price']=intval($goods['in_price']);
		$goods['sell_price']=intval($goods['sell_price']);
		$goods['production_date']=date('Y-m-d',strtotime($goods['production_date']));
		$allgood=getchildid($this->dpid,$goods['commodity_code']);
		$goo[]=$goods;		
		$arr=array_merge($goo,$allgood);		
		$data1=getTree($arr,'0',$goods['unit_name']);
		//dump($data1);	
		foreach($data1 as $k=>$v){	
			$data1[$k]['sort']=$v['id'];	
			if(is_array($v['child'])){	
			foreach($v['child'] as $kk=>$vv){
				if($vv['SPType']==2 && $vv['level']==2){
					$data1[$k]['child'][$kk]['sort']=$v['id']+0.1;
				}else{
					$data1[$k]['child'][$kk]['sort']=$vv['id'];
				}												
				if(is_array($vv['child'])){
					foreach($vv['child'] as $a=>$b){
						$data1[$k]['child'][$kk]['child'][$a]['sort']=$vv['id']+0.1;
						if(is_array($b['child'])){
							foreach($b['child'] as $c=>$d){
								$data1[$k]['child'][$kk]['child'][$a]['child'][$c]['sort']=$b['id']+0.1;
							}
						}
					}
				}
			}
			}
		}
		$newarr=array();
		foreach($data1 as $a=>$b){
			$newarr[$a]=$b;
			unset($newarr[$a]['child']);
			if(isset($b['child']) && is_array($b['child'])){		
			$newarr=array_merge($newarr,$b['child']);
			foreach($newarr as $c=>$d){
				$newarr[$c]=$d;												
				if(isset($d['child']) && is_array($d['child'])){					
					unset($newarr[$c]['child']);
					$newarr=array_merge($newarr,$d['child']);
				}
			}
			}
		}
		$data=f_order($newarr,'sort',1);
	}else{
			$data=[];
			$goods='';
		}		
		$cate=db('commodity_type')->where('super_id','eq',0)->select();
		$this->assign('cate',$cate);
		$this->assign('data',json_encode($data));
		$this->assign('info',$goods);
		return $this->fetch();
	}


	public function addgoods(){
		if($this->request->post()){
			$post=$this->request->post();
			if(isset($post['old']['edit'])){
				foreach($post['news'] as $k=>$v){
					$post['news'][$k]=array_merge($post['old'],$v);
				}
				foreach($post['news'] as $k=>$v){
					$nuit=explode('=',$v['unit_name_value']);
					$post['news'][$k]['unit_name_value']=intval($nuit[1]);
					$post['news'][$k]['extent_ttribute']='1';
					$post['news'][$k]['lable_print']='1';
					$post['news'][$k]['Commission_flag']='0';
					$post['news'][$k]['Current_price_set']='0';
					$post['news'][$k]['fixed_price_set']='0';
					$post['news'][$k]['create_date']=date('Y-m-d H:i:s');
					$post['news'][$k]['create_user_code']=$this->dpid;
					
				}
				$data=$post['news'];
				
				//店铺商品条码
				$map['Store_code']=['eq',$this->dpid];
				
				$allcode=db('commodity')->where($map)->field('commodity_code')->select();
				$allcode=array_column($allcode, 'commodity_code');
				$newarr=[];$edit=[];
				foreach($data as $k=>$v){
					if(in_array($v['commodity_code'],$allcode)){
						$edit[$k]=$v;
						unset($edit[$k]['edit']);
						unset($edit[$k]['hide']);
						unset($edit[$k]['ROW_NUMBER']);
						unset($edit[$k]['LAY_TABLE_INDEX']);
						unset($edit[$k]['sort']);
						unset($edit[$k]['id']);
						unset($edit[$k]['level']);						
						unset($edit[$k]['child']);						
						
					}else{
						$newarr[$k]=$v;
					}
				}
				
				
				
				
				
				if($newarr){
					$result = model('Goods')->allowField(true)->saveAll($newarr);
				}else{
					$maps['Store_code']=['eq',$this->dpid];
					$i=0;
					foreach($edit as $k=>$v){
						$maps['commodity_code']=$v['commodity_code'];
						$result = model('Goods')->where($maps)->update($v);
						if(false === $result){
							$i;
						}else{
							$i++;
						}
					}
					if($i>0){
						$result=true;
					}else{
						$result=false;
					}
				}
								
				if(false === $result){
				    $back=['msg'=>'修改失败','status'=>2];
				}else{
				    		
	       			$back=['msg'=>'修改成功','status'=>1];;

       			}					
       			
				
				
			}else{
				
			//start
			if(isset($post['news'])){				
				foreach($post['news'] as $k=>$v){
					$post['news'][$k]=array_merge($post['old'],$v);
				}
				foreach($post['news'] as $k=>$v){
					$nuit=explode('=',$v['unit_name_value']);
					$post['news'][$k]['unit_name_value']=intval($nuit[1]);
					$post['news'][$k]['extent_ttribute']='1';
					$post['news'][$k]['lable_print']='1';
					$post['news'][$k]['Commission_flag']='0';
					$post['news'][$k]['Current_price_set']='0';
					$post['news'][$k]['fixed_price_set']='0';
					$post['news'][$k]['create_date']=date('Y-m-d H:i:s');
					$post['news'][$k]['create_user_code']=$this->dpid;
					
				}
				$data=$post['news'];
				$result = model('Goods')->allowField(true)->saveAll($data);
			}else{
				foreach($post['old'] as $vv){

					$post['old']['extent_ttribute']='1';
					$post['old']['lable_print']='1';
					$post['old']['Commission_flag']='0';
					$post['old']['Current_price_set']='0';
					$post['old']['fixed_price_set']='0';
					$post['old']['create_date']=date('Y-m-d H:i:s');
					$post['old']['create_user_code']=$this->dpid;
				}
				
				$data=$post['old'];
				$result = model('Goods')->allowField(true)->save($data);
			}
			
			
			
			//return $data;
       		if(false === $result){
			    $back=['msg'=>'添加失败','status'=>2];
			}else{
				    		
       			$back=['msg'=>'添加成功','status'=>1];

       		}			
		}//end		
			

				
		return $back;
			
		}else{
			
			
		$id=input('id');
	    if(isset($id)){
	    	$info=db('xc_shangpin')->where("id=".$id)->find();
	    }else{
	    	$info="";
	    } 
	    	
	    $this->assign('info',$info);
		
		$cate=db('commodity_type')->where('super_id','eq',0)->select();
		$this->assign('cate',$cate);
		
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
	
	public function spstatus(){
		$input=input();
		
		$allgood=getchildid($this->dpid,$input['code']);
		$code1=array_column($allgood,'commodity_code');
		$code2[]=$input['code'];
		$code=array_merge($code2,$code1);
		
		if($input['statue']==0){
			$status=1;
		}else{
			$status=0;
		}
		$data=[];
		foreach($code as $k=>$v){
 			$map['Store_code']=['eq',$this->dpid];
 			$map['commodity_code']=['eq',$v];
 			$result=db('commodity')->where($map)->update(['statue' =>$status]);						
		}
		return $back=['msg'=>'删除成功','status'=>1];		

		
		
	}
	public function spstatusre(){
	if(input('code')){
		$input=input();
		$map['Store_code']=['eq',$this->dpid];
		$map['commodity_code']=['eq',$input['code']];
		$goods=db('commodity')->where($map)->find();
		if($goods['SPType']==1){
			$goods=db('commodity')->where('Store_code','eq',$this->dpid)->where('commodity_code','eq',$goods['z_commodity_code'])->find();
		}
		if($goods['SPType']==2){
			$good=db('commodity')->where('Store_code','eq',$this->dpid)->where('commodity_code','eq',$goods['z_commodity_code'])->find();
			if($good['z_commodity_code']==0){
				$goods=$good;
			}else{
				$goods=db('commodity')->where('Store_code','eq',$this->dpid)->where('commodity_code','eq',$good['z_commodity_code'])->find();
			}			
		}
		$allgood=getchildid($this->dpid,$goods['commodity_code']);
		$goo[]=$goods;		
		$arr=array_merge($goo,$allgood);
		$ccode=array_column($arr,'commodity_code');		
		$data=[];
		foreach($ccode as $k=>$v){
 			$map1['Store_code']=['eq',$this->dpid];
 			$map1['commodity_code']=['eq',$v];
 			$result=db('commodity')->where($map1)->update(['statue' =>1]);						
		}
		return $back=['msg'=>'还原成功','status'=>1];		

	}	
		
	}
	public function alldel(){
		if($this->request->isPost()) {
            $post = $this->request->post(); 
			$sppp=model('Goods');
			
			$id=$post['id'];
			$map['Store_code']=['eq',$this->dpid];
			if(is_array($id)){
				$i=1;
				foreach($id  as $v){					
					$map['commodity_code']=['eq',$v];
					$sppp->where($map)->delete();
					if($sppp){
						$i=0;
					}else{
						$i++;
					}
				}
				if($i>0){
				    $back=['msg'=>'删除失败','status'=>2];
				}else{
	       			$back=['msg'=>'删除成功','status'=>1];
	       		}				
				
			}else{
				$map['commodity_code']=['eq',$id];				
				$info=db('commodity')->where($map)->find();
				
				$wheres['Store_code']=['eq',$this->dpid];
				$wheres['commodity_name']=['eq',$info['commodity_name']];
				$idarr=db('commodity')->where($wheres)->select();
				$spcode=get_all_child($idarr,$id);	
				if($spcode){								
					$spcode=array_merge($spcode,[$id]);
				}else{
					$spcode=array($id);
				}
				$i=0;
				foreach($spcode as $v){
					$del['Store_code']=$this->dpid;
					$del['commodity_code']=$v;					
					$sppp->where($del)->delete();
					if($sppp===false){
						return ['msg'=>'删除失败','status'=>2];
					}else{
						$i++;
					}
					
				}

				if($i>0){
					return $back=['msg'=>'删除成功','status'=>1];
				}else{
					return $back=['msg'=>'删除失败','status'=>2];
				}
				

			}
			
			
							
					
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
	//获取店铺商品条码
	public function getcode(){
		$map['user_code']=array('eq',$this->uname);
		$map['Store_code']=array('eq',$this->dpid);
		$dpcode=db('commodity')->where($map)->where('commodity_code','egt','99999')->max('commodity_code');
		if($dpcode==0){
		   	$code="10001";
	    }else{
		   	$new=$dpcode+1;
		   	$code=str_pad($new,5,'0',STR_PAD_LEFT);
	   	}
		return $code;
	}
	//店铺是否存在商品条码
	public function getgoods(){
		$map['user_code']=array('eq',$this->uname);
		$map['Store_code']=array('eq',$this->dpid);
		$map['commodity_code']=array('eq',input('code'));
		$spcode=db('commodity')->where($map)->find();
		if($spcode){
			return ['msg'=>'商品已经存在','status'=>2];
	   }else{
	   	
	   		$commgood=db('xc_shangpin')->where('code','eq',input('code'))->find();
	   		if($commgood){
	   			$commgood['danwei']=db('unit')->where('ID','EQ',$commgood['danwei'])->value('unit_name');
	   			$commgood['shangpinppid']=db('xc_shangpinpp')->where('ID','EQ',$commgood['shangpinppid'])->value('name');
	   		}
	   			   	
		    return ['msg'=>'通过','status'=>1,'data'=>$commgood];
	   }
		 
	}
	public function getspcode(){
		$map['user_code']=array('eq',$this->uname);
		$map['Store_code']=array('eq',$this->dpid);
		$map['commodity_code']=array('eq',input('code'));
		$spcode=db('commodity')->where($map)->find();
		if($spcode){
			return ['msg'=>'商品已经存在','status'=>2];
	   }else{
	   		return ['msg'=>'通过','status'=>1];
	   }		
	}
 public function importExcel(){
 	
 	
	    header("content-type:text/html;charset=utf-8");
	    //上传excel文件
	    $file = request()->file('file');
	    //移到/public/uploads/excel/下
	    $info = $file->move(ROOT_PATH.'public'.DS.'uploads'.DS.'excel');
	    //上传文件成功
	    if ($info) {
        	
			Loader::import('excel\PHPExcel', EXTEND_PATH);
			$excel=new \PHPExcel();	
		    $savePath = ROOT_PATH.'public'.DS.'uploads'.DS.'excel'.DS.$info->getSaveName();/*以时间来命名上传的文件*/      
		    $res=$excel->read($savePath,"UTF-8",'xls');//传参,
		    $field=[];$data=[];$newdata=[];
		    foreach($res as $k=>$v){
		    	
		    	if($k==1){    		
		    		$field=$v;  
		    				
		    	}else{
		    		$data[]=$v;   		
		    	}
		    }
		    foreach($data as $k=>$v){
		    	$j=0;
		    	foreach($v as $a=>$b){   		
					$newdata[$k][$field[$j]]=$v[$j]; 
					$j++;   		
		    	}
		    	
		    } 
//		    foreach($newdata as $k=>$v){
//				$fid=db('xc_shangpin')->where('code','eq',$v['commodity_code'])->value('shangpinflid');
//				if($fid){
//					$id=$fid;
//				}else{
//					$id='1210';
//				}
//				$newdata[$k]['user_code']=$this->uid;
//				$newdata[$k]['Store_code']=$this->dpid;
//				$newdata[$k]['create_date']=date('Y-m-d');
//				$newdata[$k]['commodity_type_id']=$id;
//		    	
//		    }
		    
		    
		    return ['msg'=>'上传成功！','status'=>1,'data'=>$newdata];
	    }else{
            // 上传失败获取错误信息
            return ['msg'=>$file->getError(),'status'=>2];
          
        }
        
         
  }
  
  
  public function import(){
  	ini_set('max_execution_time', '0');
	set_time_limit(0);  //在有关数据库的大量数据的时候，可以将其设置为0，表示无限制。  
	ob_end_clean();     //在循环输出前，要关闭输出缓冲区  
  	if($this->request->isPost()) {
            $post = $this->request->post(); 
				$newdata=$post['arr'];
				$code=array_column($newdata,'commodity_code');
				$size=1000;
				$num=count($code);
				$for=ceil($num/$size);
				
				
				for($i=0;$i<$for;$i++){
					
					$limit=$i*$size;
					$one[$i]=array_slice($code,$limit,$size);
				}				
				$data=[];
				foreach($one as $a=>$b){
					
					$join = [
					    ['tbl_unit D','A.danwei=D.ID','FULL'],
					    ['tbl_xc_shangpinpp P','A.shangpinppid=P.ID','FULL']
					];	
					$where['A.code']=['IN',$b];
					$fid=db('xc_shangpin')->alias('A')->join($join)->where($where)->field('A.code,A.shangpinflid,A.shangpingg,A.pinyinm,A.shangpinppid,A.danwei,D.unit_name,P.name as pname')->select();					
					$fcode=[];					
					foreach($fid as $k=>$v){
						$fcode[$v['code']]['fl']=$v['shangpinflid'];
						$fcode[$v['code']]['unit']=$v['unit_name'];
						$fcode[$v['code']]['pp']=$v['pname'];
						$fcode[$v['code']]['gg']=$v['shangpingg'];
						$fcode[$v['code']]['pinyinm']=$v['pinyinm'];
					}
					
					foreach($b as $c){
						foreach($newdata as $k=>$v){
							if($c==$v['commodity_code']){						
								if(isset($fcode[$v['commodity_code']]['fl'])){
									$type=$fcode[$v['commodity_code']]['fl'];
								}else{
									$type=1210;
								}
								if(isset($fcode[$v['commodity_code']]['unit'])){
									$unit=$fcode[$v['commodity_code']]['unit'];
								}else{
									$unit='无';
								}
								
								if(isset($fcode[$v['commodity_code']]['pp'])){
									$pp=$fcode[$v['commodity_code']]['pp'];
								}else{
									$pp='无';
								}
								
								$data[$k]=$v;
								$data[$k]['user_code']=$this->uid;
								$data[$k]['Store_code']=$this->dpid;
								$data[$k]['unit_name']=$unit;								
								$data[$k]['shangpinppID']=$pp;								
								$data[$k]['guige']=isset($fcode[$v['commodity_code']]['gg']) ? $fcode[$v['commodity_code']]['gg']:'';								
								$data[$k]['py_code']=isset($fcode[$v['commodity_code']]['pinyinm']) ? $fcode[$v['commodity_code']]['pinyinm']:'';							
								$data[$k]['commodity_type_id']=$type;								
							}
							 					
						}
					}
					
				}
				
				for($i=0;$i<count($data);$i++){
					$insave=db('commodity')->insert($data[$i]);
			        if ($insave) {
			        	echo "第".($i+1)."条导入成功！<br/>";
					    usleep(100); //1微秒等于百万分之一秒
					    echo  str_pad("", 10000); 
					    flush(); 	
    			    }else{    			        	
			          echo "第".($i+1)."条导入失败！<br/>";
			        }				
			    }
				

    
  	
  		}
  	}

	public function recycle(){
    	$data=array_filter(input());
    	$where['store_code']=array('eq',$this->dpid);
    	$where['user_code']=array('eq',$this->uname);
    	$where['statue']=array('eq','0');
    	if(is_array($data)){
    		foreach($data as $k=>$v){
    			
    			if($k=='keyword'){
    				$where['commodity_code|commodity_name']=array('like','%'.trim($v).'%');
    			}
    			unset($where['page']);
    		}
    		
    	}
    	$splist=Gods::where($where)->order('create_date DESC')->paginate(12, false, ['query' => request()->param()]);
		$this->assign('idarr',input('spflid'));
		$this->assign('key',input('keyword'));
		$this->assign('splist',$splist);		
		return $this->fetch();
	}
	
}
?>