<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Index extends Controller
{
    public function index()
    {
			$data['Menu_name'] = '312';
			$data['menu_order'] = '2';
			$data['status'] = '2';
			$data['isfz'] = '1';
			$data['parentid']='1';
			$data=Db::name('xc_menu')->insert($data);	
    }
}
