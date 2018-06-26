<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
class Sale extends Auth{
	public function index(){
		$time=input('keyword');
		if(isset($time) || $time==''){
			$time=date('Y-m-d 00:00:00').' 到 '.date('Y-m-d 00:00:00',strtotime('+1 day'));
		}
		
		if(isset($time)){
		 	$times=explode('到',$time);
		 	$map['cashier_date']=['between',[$times[0],$times[1]]];
		}else{
			$map['cashier_date']=['between',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]];
		}
		
		$map['store_code']=['eq',$this->dpid];
		//$map['type_flag']=['eq',1];
		
		$sales=db('Sales')->where($map)->select();
		
		$scode=array_column($sales,'selas_code');
		$join = [
		    ['tbl_commodity g','a.commodity_code=g.commodity_code']
		];	
		$where['g.store_code']=['eq',$this->dpid];
		$where['a.selas_code']=['IN',$scode];
		$field='a.subtotal,a.amount,g.in_price,(a.price - g.in_price)*a.amount as lirui,(a.price - g.in_price)/(a.price) as maoli';	
		$saledetail=db('Sales_detail')->alias('a')->join($join)->where($where)->field($field)->select();
		//dump($saledetail);
		if(count($saledetail)==0){
			$info=[
				'lirui'=>0,
				'total_money'=>0,
				'cash'=>0,
				'bank_cash'=>0,
				'alipay'=>0,
				'wechat'=>0,
				'maoli'=>0,
				'counts'=>0
				];
				
		}else{
			$info=[
				'lirui'=>array_sum(array_column($saledetail,'lirui')),
				'total_money'=>array_sum(array_column($sales,'total_money')),
				'cash'=>array_sum(array_column($sales,'cash')),
				'bank_cash'=>array_sum(array_column($sales,'bank_cash')),
				'alipay'=>array_sum(array_column($sales,'alipay')),
				'wechat'=>array_sum(array_column($sales,'wechat')),
				'maoli'=>round(array_sum(array_column($saledetail,'maoli'))/count($saledetail),4) * 100 ."%",
				'counts'=>count($sales)
				];
			
		}		
		$this->assign('times',$time);
		$this->assign('info',$info);
		return $this->fetch();
	}
	public function change(){
		$time=input('keyword');
		if(!isset($time) || $time==''){
			$time=date('Y-m-d 00:00:00').' 到 '.date('Y-m-d 00:00:00',strtotime('+1 day'));
		}		
		if(isset($time)){
		 	$times=explode('到',$time);
		 	$map['shift_datetime']=['between',[$times[0],$times[1]]];
		}else{
			$map['shift_datetime']=['between',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]];
		}
		
		$map['Store_code']=['eq',$this->dpid];
		$changelist=db('shift')->where($map)->order('end_datetime DESC')->paginate(12, false, ['query' => request()->param()])->each(function($item, $key){			
			$where['Store_code']=['eq',$this->dpid];
			$where['shift_datetime']=['between',[$item['begin_datetime'],$item['end_datetime']]];			
			$item['arr']=db('shift_payment_money')->where($where)->select();
				
		return $item;
		});
		//dump($changelist);
		$this->assign('times',$time);
		$this->assign('changelist',$changelist);
		return $this->fetch();
	}
	
	public function counts(){
		$cate=db('commodity_type')->where('')->select();
		$this->assign('cate',$cate);
		$pp=db('xc_shangpinpp')->where('')->select();
		$this->assign('pp',$pp);
		return $this->fetch();
	}
	public function countdata(){
		$get=input();
		$map1['C.Store_code']=['eq',$this->dpid];
		if(isset($get['data'])){
			$isget=array_filter($get['data']);
				if(isset($isget['time'])){
					$time=explode('到',$isget['time']);					
					$map1['C.cashier_date']=['between',[$time[0],$time[1]]];	
				}else{
					$map1['C.cashier_date']=['between',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]];					
				}				
				if(isset($isget['keyword'])){
					$map2['A.commodity_code|A.commodity_name']=['like','%'.trim($isget['keyword']).'%'];
				}
				if($isget['status']==1){
					$status['W.amount']=['gt',0];
				}else{
					$status="W.amount is null";
				}
				
			unset($isget['time']);			
			unset($isget['status']);			
			unset($isget['keyword']);					
			foreach($isget as $k=>$v){				
				$map2["A.".$k]=$v;							
			}			
		}else{
			$status['W.amount']=['gt',0];
			$map1['C.cashier_date']=['between',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]];	
		}
		$join=[
			 ['tbl_sales_detail B','C.selas_code=B.selas_code','right']
		];	
		$subQuery = db('sales')->alias('C')->where($map1)->join($join)->field('B.selas_code,B.commodity_code,C.cashier_date,B.price,B.subtotal,isnull(B.amount,0) as amount')->buildSql();
		
		$data=db('commodity')->alias('A')->join([$subQuery=> 'W'],'A.commodity_code = W.commodity_code','left')->where($status)->field('A.commodity_code,sum(W.amount) as num,sum(W.subtotal) as total')->group('A.commodity_code')->select();
		
		$spcode=array_column($data,'commodity_code');
		$join2=[
			 ['tbl_commodity_type C','A.commodity_type_id=C.id','left']
		];	

		$map2['A.Store_code']=['eq',$this->dpid];
		$info=db('commodity')->alias('A')->join($join2)->where($map2)->whereIn('A.commodity_code',$spcode)->field('A.commodity_name,A.commodity_code,A.sell_price,A.in_price,A.Store_amount,A.commodity_type_id,C.cpmmodity_type_name as cate')->select();


		$newdata=[];	
		$i=1;	
		foreach($info as $a=>$b){
			foreach($data as $k=>$v){	
				if(isset($b['in_price']) && $v['num']>0){
					$p=toprice(($v['num']*($b['sell_price']-$b['in_price'])/($b['in_price']*$v['num'])),2)."%";
				}else{
					$p='-';
				}
				if($b['commodity_code']==$v['commodity_code']){	
					$newdata[$k]=$b;
					$newdata[$k]['amount']=$v['num'];
					$newdata[$k]['price']=toprice($b['sell_price']*$v['num'],2);//商品总价
					$newdata[$k]['totalprice']=toprice($v['total'],2);//实收总价
					$newdata[$k]['lirui']=toprice($v['num']*($b['sell_price']-$b['in_price']),2);
					$newdata[$k]['liruil']=$p;
					$newdata[$k]['id']=$i;
				}else if(!isset($get['data'])){
					if($v['commodity_code']==null){						
						$newdata[$k]['commodity_name']='无名称';
						$newdata[$k]['commodity_code']='无码商品';
						$newdata[$k]['sell_price']='-';
						$newdata[$k]['in_price']='-';
						$newdata[$k]['Store_amount']='-';
						$newdata[$k]['commodity_type_id']='-';
						$newdata[$k]['cate']='-';
						$newdata[$k]['price']=toprice($v['total'],2);//商品总价
						$newdata[$k]['amount']=$v['num'];
						$newdata[$k]['totalprice']=toprice($v['total'],2);
						$newdata[$k]['lirui']='-';
						$newdata[$k]['liruil']='-';
						$newdata[$k]['id']=$i;
					}
				}
					
			}
			
			$i++;
													
		}		
		$pagedata=page_array($get['limit'],$get['page'],$newdata,'');
		$jsondata=['code'=>0,'msg'=>$get,'count'=>count($newdata),'data'=>$pagedata];
		echo  json_encode($jsondata);
	}
	
	public function analyse(){
		$input=input();
		$commmap['A.Store_code']=['eq',$this->dpid];
		$map1['C.Store_code']=['eq',$this->dpid];
		$get=array_filter($input);
		if(empty($get)){
			$get['cate']=1;
		}
		
		if(isset($get['time'])){
			$time=explode('到',$get['time']);
			$map1['C.cashier_date']=['between',[$time[0],$time[1]]];	
		}else{
			$map1['C.cashier_date']=['between',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]];	
		}
		
		$status['W.amount']=['gt',0];
		$join=[
			 ['tbl_sales C','C.selas_code=B.selas_code','right']
		];	
		$subQuery = db('sales_detail')->alias('B')->where($map1)->join($join)->field('B.selas_code,B.commodity_code,B.price,B.subtotal,B.amount,C.cashier_date,C.cashier_code,C.number_code')->buildSql();				
		//$data=db('commodity')->alias('A')->join([$subQuery=> 'W'],'A.commodity_code = W.commodity_code','left')->where($commmap)->where($status)->field('A.commodity_code,A.sell_price,A.commodity_type_id,W.price,W.subtotal,W.amount,W.cashier_date,W.number_code')->order('commodity_type_id asc')->select();
		//A.commodity_code,A.sell_price,A.commodity_type_id,W.price,W.subtotal,W.amount,W.cashier_date,W.number_code
		
		
		//dump($data);
		
		$tabledata=[];
		if(isset($get['cate'])){
			if($get['cate']==1){
				$title="商品分类";
				$group='commodity_type_id';	
				$datas=db('commodity')->alias('A')->join([$subQuery=> 'W'],'A.commodity_code = W.commodity_code','left')->where($commmap)->where($status)->field('A.commodity_type_id,sum(W.subtotal) as total_money')->group($group)->select();
				
				if(!empty($datas)){
					$typeid=array_column($datas,'commodity_type_id');
					$name=[];
					$type=db('commodity_type')->where('id','in',$typeid)->field('id,cpmmodity_type_name')->select();								
					foreach($datas as $k=>$v){
						foreach($type as $a=>$b){
							if($v['commodity_type_id']==$b['id']){
								$name[]=preg_replace('/ /', '',$b['cpmmodity_type_name']);
								$tabledata[$a]['value']=$v['total_money'];
								$tabledata[$a]['name']=preg_replace('/ /', '',$b['cpmmodity_type_name']);
	
							}
						}
						
					}
				}else{
					$name=['无数据'];
					$tabledata[]=['value'=>0,'name'=>'无数据'];
				}
				
				//dump($datas);
							
			}else if($get['cate']==2){
				$title="商品品牌";
				$group='shangpinppID';
				$datas=db('commodity')->alias('A')->join([$subQuery=> 'W'],'A.commodity_code = W.commodity_code','left')->where($commmap)->where($status)->field('A.shangpinppID,sum(W.subtotal) as total_money')->group($group)->select();
				if(!empty($datas)){
					$name=[];
					foreach($datas as $k=>$v){
						$name[$k]=preg_replace('/ /', '',$v['shangpinppID']);
						$tabledata[$k]['value']=$v['total_money'];
						$tabledata[$k]['name']=preg_replace('/ /', '',$v['shangpinppID']);					
						
					}
				}else{
					$name=['无数据'];
					$tabledata[]=['value'=>0,'name'=>'无数据'];
				}

			}else if($get['cate']==3){
				$title="收银员";
				$sales=db('sales')->alias('C')->where($map1)->field('sum(C.total_money) as price,C.cashier_code')->group('C.cashier_code')->select();
				if(!empty($sales)){
					$name=[];$tabledata=[];
					foreach($sales as $k=>$v){
						$name[$k]=$v['cashier_code'];
						$tabledata[$k]['value']=$v['price'];
						$tabledata[$k]['name']=$v['cashier_code'];
					}
				}else{
					$name=['无数据'];
					$tabledata[]=['value'=>0,'name'=>'无数据'];
				}
				//dump($sales);
			}else if($get['cate']==4){
				$pay=db('sales')->alias('C')->where($map1)->select();
				$title="支付方式";				
				$name=['现金支付','银行卡支付','微信支付','支付宝支付','会员卡支付'];
				
				foreach($name as $k=>$v){
					if($v=='现金支付'){$field='cash';}else 
					if($v=='银行卡支付'){$field='bank_cash';}else 
					if($v=='微信支付'){$field='wechat';}else 
					if($v=='支付宝支付'){$field='alipay';}else 
					if($v=='会员卡支付'){$field='number_cash';}
					$tabledata[$k]['value']=array_sum(array_column($pay,$field));
					$tabledata[$k]['name']=$v;
				}
			}else if($get['cate']==5){
				$title="是否会员";
				$name=['会员','非会员'];
				
				$hy=db('sales')->alias('C')->where($map1)->where("LEN(number_code) > 1")->select();
				$fhy=db('sales')->alias('C')->where($map1)->where("LEN(number_code) < 1 or number_code is null")->select();
				if(is_array($hy)){
					$value1=array_sum(array_column($hy,'total_money'));
					
				}else{
					$value1=0;
				}
				if(is_array($fhy)){
					$value2=array_sum(array_column($fhy,'total_money'));
					
				}else{
					$value2=0;
				}

				$tabledata[]=['value'=>$value1,'name'=>'会员'];
				$tabledata[]=['value'=>$value2,'name'=>'非会员'];
			}	
		
		}
		//dump(count($input));
		$this->assign('get',count($input));
		$this->assign('time',isset($get['time'])?$get['time']:date('Y-m-d 00:00:00')." 到 ".date('Y-m-d 23:59:59'));
		$this->assign('cate',isset($get['cate'])?$get['cate']:'1');
		$this->assign('title',$title);
		$this->assign('name',json_encode($name));
		$this->assign('data',json_encode($tabledata));
		return $this->fetch();
	}

	
}

?>