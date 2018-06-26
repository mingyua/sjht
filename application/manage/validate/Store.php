<?php
    namespace app\manage\validate;
    use think\Validate;
    class Store extends Validate{        
        //需要验证的键值
        protected $rule =   [
            'TEL'               => 'require|unique:store',
        ];
        //验证不符返回msg
        protected $message  =   [
            'TEL.require'      => '手机必填',    
            'TEL.unique' => '手机号码已存在',
        ];
        //需要指定验证位置 和字段
        protected $scene = [
        'add'   =>  ['TEL'],
        'edit'  =>  ['TEL'],
    ];   
}