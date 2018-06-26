<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/*
 */
function getdp($id){
	$name=db('store')->where('store_code','EQ',$id)->value('store_name');	
	return $name;
}

function getsptp($spid){
	$name=db('xc_shangpintp')->where('shangpinid','EQ',$spid)->value('dizhi');	
	return $name;
}
function getfl($dwid){
	$name=db('commodity_type')->where('ID','EQ',$dwid)->value('cpmmodity_type_name');	
	return $name;
}
function getpp($dwid){
	$name=db('xc_shangpinpp')->where('id','EQ',$dwid)->value('name');	
	return $name;
}
function getdw($dwid){
	$name=db('unit')->where('ID','EQ',$dwid)->value('unit_name');	
	return $name;
}
function inarr($text,$arr){
	$arr=explode(',',$arr);	
	if(in_array($text,$arr)){
		return 'checked';
	}else{
		return false;
	}
}

//返回所属企业
function getuserinfo($tel,$field){
	if($tel){
		$name=db('user')->where('TEL','EQ',$tel)->value($field);	
		return $name;
	}else{
		return '';
	}
	
}

function times($val='',$type='Y-m-d'){
	if($val){
		return date($type,$val);
	}else{
		return date($type,time());
	}
	
}

 function cate($cate,$name='child',$pid=0){
		
		$arr=array();
		foreach ($cate as $v){
			if($v['ParentID']== $pid){
			   $v[$name]=cate($cate,$name,$v['ID']);
				$arr[]=$v;
				}
			
			}
			return $arr;
		}

function menutree(&$list, $pid = 0, $level = 0, $html = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') {
	static $tree = array();
	foreach ($list as $v) {
		if ($v['ParentID'] == $pid) {
			if ($level == 1) {
				$v['html'] = str_repeat($html, $level) . "└ ";
			}else if ($level == 2) {
				$v['html'] = str_repeat($html, $level + 1) . "└ ";
			}else{
				$v['html'] ='';
			}
			//$v['html'] = str_repeat($html, $level);
			$tree[] = $v;
			menutree($list, $v['ID'], $level + 1);
		}
	}
	return $tree;
}
function getrolename($id){
		$name=db('xc_role')->where("id='$id'")->find();
		return $name['Role_name'];
}
function checked($rid,$mid){
		$name=db('xc_role_menu')->where(array('RoleID'=>$rid,'MenuID'=>$mid))->find();
		if($name){
			$ck='checked';
		}else{
			$ck="";
		}
		return $ck;
}


function ztree($data,$pid='0'){
	static $tree = array();
	foreach ($data as $k=> $v) {
		if ($v['super_id'] == $pid) {			
			$tree[$k]['id'] = $v['id'];
			$tree[$k]['pId'] = $v['super_id'];
			$tree[$k]['name'] = $v['cpmmodity_type_name'];
			if($v['super_id']==0){
			$tree[$k]['open'] = true;
			}
			ztree($data, $v['id']);
		}
	}
	$arr=array(['id'=>0,'pId'=>'-1','name'=>'全部类别','open'=>true]);
	foreach($tree as $k=> $v){
		$arr[]=$v;
	}
			
	return $arr;		
		
}
/*
 * 返回最大ID
 */
function getmaxid(){
	$id=db('commodity_type')->max('id');
	return $id;
}

function getkinds($id){
	$name=db('commodity_type')->where('id','eq',$id)->value('cpmmodity_type_name');
	return $name;
}

function get_all_child($array,$id){
    $arr = array();
    foreach($array as $v){
        if($v['z_commodity_code'] == $id){
            $arr[] = $v['commodity_code'];
            $arr = array_merge($arr,get_all_child($array,$v['commodity_code']));
        };
    };
    return $arr;
}

function getTree($data, $pId,$unit,$level=0)
{
	$tree = '';
	$i=1;
	foreach($data as $k => $v)
	{
	  if($v['z_commodity_code'] == $pId)
	  {  
	   $v['id']=$i;
	     //父亲找到儿子
	   $v['level']=$level+1;

			if($v['SPType']==0){
				$v['hide']='add-main';				
			}else if($v['SPType']==1){
				$v['hide']='add-attr';
			}else{
				$v['hide']='add-unit';
			}
		$v['in_price']=intval($v['in_price']);				
		$v['sell_price']=intval($v['sell_price']);	
		$v['unit_name_value']="1".$v['unit_name']."=".$v['unit_name_value'].$unit;
		
					
	   $v['child'] = getTree($data, $v['commodity_code'],$unit,$level+1);
	   $tree[] = $v;
	   //unset($data[$k]);
	  }
	  $i++;
	}
return $tree;
}
function f_order($arr,$field,$sort){
        $order = array();
        foreach($arr as $kay => $value){
            $order[] = $value[$field];
        }
        if($sort==1){
            array_multisort($order,SORT_ASC,$arr);
        }else{
            array_multisort($order,SORT_DESC,$arr);
        }
        return $arr;
    }
  /*
     * number_price(准备要开始的价格,要保留的小数位数,小数点显示的符号,千分位分隔符)
     * php4,php5
     * 默认将进行四舍五入处理
    */
function toprice($price,$num=2){	  
    return number_format($price,$num,'.',',');    
}

/*
 * 
 * 
 */
function getchildid($dpid,$arrcode){
	$data='';
	$where['Store_code']=['eq',$dpid];
	$where['z_commodity_code']=['IN',$arrcode];
	$allgood=db('commodity')->where($where)->select();
	if($allgood){
		$zcode=array_column($allgood,'commodity_code');
		$data1 = getchildid($dpid, $zcode);
		$data=array_merge($data1,$allgood);
	}else{
		$data=$allgood;
	}
	
	return $data;
}
/** 
 * 数组分页函数  核心函数  array_slice 
 * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中 
 * $count   每页多少条数据 
 * $page   当前第几页 
 * $array   查询出来的所有数组 
 * order 0 - 不变     1- 反序 
 */   
  
function page_array($count,$page,$array,$order){  
    global $countpage; #定全局变量  
    $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面   
       $start=($page-1)*$count; #计算每次分页的开始位置  
    if($order==1){  
      $array=array_reverse($array);  
    }     
    $totals=count($array);    
    $countpage=ceil($totals/$count); #计算总页面数  
    $pagedata=array();  
    $pagedata=array_slice($array,$start,$count);  
    return $pagedata;  #返回查询数据  
} 