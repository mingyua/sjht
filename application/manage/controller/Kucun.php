<?php
namespace app\manage\controller;
use think\Controller;
use app\manage\model\Goods as Gods;
class Kucun extends Auth{
	public function index(){
		//$map['Store_code']=['eq',$this->dpid];
		//$map['Inventory_code']=['eq','2018060610351700067'];
		//$detail=model('Indetail')->has('goods',['Store_code'=>$this->dpid])->select();
		
		//$list = model('Goods')->hasWhere('kinds',['super_id'=>0])->select();
		
		//DUMP($detail);
    	$data=array_filter(input());
    	$where['Store_code']=['eq',$this->dpid];
    	if(is_array($data)){
    		foreach($data as $k=>$v){
    			
    			if($k=='keyword'){
    				$where['commodity_code|commodity_name']=array('like','%'.trim($v).'%');
    			}
    			unset($where['page']);
    		}
    		
    	}				
		$where['Store_code']=['eq',$this->dpid];
		$commdity=model('Goods')->with('kinds')->where($where)->order('Store_amount ASC')->paginate(16, false, ['query' => request()->param()]);		
		$this->assign('kclist',$commdity);
		return $this->fetch();
	}
	
	public function pandian(){
    	$data=array_filter(input());
    	$where['Store_code']=['eq',$this->dpid];
    	
    	
    	
    	if(is_array($data)){
    		$time=explode('到',input('keyword'));
    		foreach($data as $k=>$v){
    			
    			if($k=='keyword'){
    				$where['Inventory_date']=['between',[$time[0],$time[1]]];
    			}
    			unset($where['page']);
    		}
    		
    	}						
		
		$pandian=model('Inventory')->with('indetail')->where($where)->order('create_date DESC')->paginate(12, false, ['query' => request()->param()])->each(function($item, $key){
		      $item['count']=0;
		      foreach($item['indetail'] as $k=>$v){
		      	 $item['count']+=$v['actual_amount']-$v['now_amount'];
		      }
			return $item;
		});
		
		//dump($pandian);		
		$this->assign('timearr',input('keyword'));
		$this->assign('pdlist',$pandian);
		
		return $this->fetch();
	}
	
	public function details(){
		$id=input();
		$map['Store_code']=['eq',$id['dpid']];
		$map['Inventory_code']=['eq',$id['id']];
		
		$detail=db('Inventory_detail')->where($map)->order('commodity_code ASC')->select();
		$code=implode(',',array_column($detail,'commodity_code'));
		$where['store_code']=['EQ',$id['dpid']];
		$where['commodity_code']=['IN',$code];
		$goods=db('commodity')->where($where)->order('commodity_code ASC')->select();
		foreach($detail as $k=>$v){			
			foreach($goods as $a=>$b){
				if($v['commodity_code']==$b['commodity_code']){
					$detail[$k]['commodity_name']=$b['commodity_name'];
					$detail[$k]['in_price']=$b['in_price'];
				}
			}
		}
		
		$this->assign('pdmx',$detail);
		return $this->fetch();
	}
	
	public function changedetail(){
		$input=array_filter(input());
		if(!isset($input['time'])){		
			$map['A.in_out_date']=['between',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]];				
			$timearr=date('Y-m-d 00:00:00')." 到 ".date('Y-m-d 23:59:59');					
		}else{
			$time=explode('到',$input['time']);
			$map['A.in_out_date']=['between',[$time[0],$time[1]]];					
			$timearr=$time[0]." 到 ".$time[1];					
			
		}
		$map['A.store_code']=['eq','000024'];//$this->dpid
		if(is_array($input)){
			foreach($input as $k=>$v){
					$map["A.".$k]=['eq',$v];	
					unset($map['A.time']);						
					unset($map['A.page']);						
			}
		}
		$join = [
		    ['tbl_commodity C','A.commodity_code=C.commodity_code'],
		];
		$data=db('in_out_log')->alias('A')->where($map)->join($join)->where('C.Store_code','eq','000024')->field('A.*,C.commodity_name')->paginate(14, false, ['query' => request()->param()]);
		$status=['1'=>'进货单','2'=>'调货单出库','3'=>'调货单入库','4'=>'给供应商退货单','5'=>'报损单','6'=>'销售单','7'=>'前台退货单','8'=>'前台作废单','9'=>'盘点调整减少','10'=>'盘点调整增加'];
		$this->assign('status',$status);
		$this->assign('timearr',$timearr);
		$this->assign('sele',isset($input['statue'])?$input['statue']:'');
		$this->assign('code',isset($input['commodity_code']) ? $input['commodity_code']:'');
		
		$this->assign('bdlist',$data);
	return $this->fetch();
	}
	
}

?>