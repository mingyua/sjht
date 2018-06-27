<?php
namespace app\manage\controller;
use think\Controller;
use think\Db;
class Login extends Controller
{
    public function index()
    {
    $input=input();	
    if(isset($input['usercode'])){
				$map['TEL']=$input['usercode'];
				$map['Store_pass']=$input['pwd'];				
				$map1['TEL']=$input['usercode'];
				$map1['Cashier_pass']=$input['pwd'];				
				$admin=Db::name('user')->where($map)->find();
				$store=Db::name('store')->where($map)->find();
				$syy=Db::name('cashier')->where($map1)->find();				
				if($admin){
					$stcode=db('store')->where('user_code','EQ',$input['usercode'])->value('store_code');
					session('uid',$admin['TEL']);
					session('stcode',$stcode);
					session('uname',$admin['user_code']);
					session('sjname',$admin['store_name']);
					session('access',1);
				   $this->redirect('index/index',302); 
				}else if($store){
					session('uid',$store['TEL']);
					session('uname',$store['user_code']);
					session('stcode',$store['store_code']);
					session('sjname',$store['store_name']);
					session('access',2);
					$this->redirect('index/index',302); 
				}else if($syy){
					$m['Store_code']=$syy['Store_code'];
					$stinfo=Db::name('store')->where($m)->find();
					session('uid',$syy['TEL']);
					session('uname',$syy['user_code']);
					session('stcode',$syy['Store_code']);
					session('sjname',$stinfo['store_name']);				
					session('access',0);						
					$this->redirect('index/index',302); 
				}

		die();
    }
    
   
    $admin=Db::name('admin_user')->select();    	
	$this->view->engine->layout(false); 
	return $this->fetch();
    }
    public function login(){
    	$this->view->engine->layout(false); 
		if($this->request->isPost()) {
            $post = $this->request->post();           
			$rule = [
			    ['username','require|length:11','手机必须|手机格式有误！'],
			    ['password','require|length:6,12','密码不能为空！|密码长度为6-12字符'],
			    ['code','require|captcha','验证码不能为空|验证码错误']
			];            
			$validate = new \think\Validate($rule);
			$result   = $validate->check($post);
			if(!$result){
				return ['msg'=>'提交失败：' . $validate->getError(),'code'=>2]; 
			}else{
				$map['TEL']=$_POST['username'];
				$map['Store_pass']=md5($_POST['password']);				
				$map1['TEL']=$_POST['username'];
				$map1['Cashier_pass']=md5($_POST['password']);				
				$admin=Db::name('user')->where($map)->find();
				$store=Db::name('store')->where($map)->find();
				$syy=Db::name('cashier')->where($map1)->find();
				
				if($admin){
					if($admin['Statue']==0){
						return ['msg'=>'用户已经禁用，请联系管理员！','code'=>2];die();
					}
					$stcode=db('store')->where('user_code','EQ',$_POST['username'])->value('store_code');
					session('uid',$admin['TEL']);
					session('stcode',$stcode);
					session('uname',$admin['user_code']);
					session('sjname',$admin['store_name']);
					session('access',1);
					return ['msg'=>'商家登录成功！正在跳转...','code'=>1,'url'=>url('index/index')]; 			
				}else if($store){
					if($store['Statue']==0){
						return ['msg'=>'用户已经禁用，请联系管理员！','code'=>2];die();
					}					
					session('uid',$store['TEL']);
					session('uname',$store['user_code']);
					session('stcode',$store['store_code']);
					session('sjname',$store['store_name']);
					session('access',2);
					return ['msg'=>'店铺登录成功！正在跳转...','code'=>1,'url'=>url('index/index')]; 			
				}else if($syy){
					if($syy['statue']==1){
						return ['msg'=>'用户已经禁用，请联系管理员！','code'=>2];die();
					}
					
					$m['Store_code']=$syy['Store_code'];
					$stinfo=Db::name('store')->where($m)->find();
					session('uid',$syy['TEL']);
					session('uname',$syy['user_code']);
					session('stcode',$syy['Store_code']);
					session('sjname',$stinfo['store_name']);				
					session('access',0);						
					return ['msg'=>'营业员登录成功！正在跳转...','code'=>1,'url'=>url('index/index')]; 
				}else{
					return ['msg'=>'用户不存在或密码错误,请重新输入！','code'=>2]; 		
				}
				
				

			}            
            
           
        }
	}

	public function logout(){
		header("Content-type: text/html; charset=utf-8");
		session(null);
		return ['msg'=>'退出成功！正在跳转...','status'=>1,'url'=>url('login/index')]; 	
	}
    
}
