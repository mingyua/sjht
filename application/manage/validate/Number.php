<?php
    namespace app\manage\validate;
    use think\Validate;
    class Number extends Validate{        
        //需要验证的键值
        protected $rule =   [
            'number_tel'               => 'require|unique:number,number_tel^number_code',
        ];
        //验证不符返回msg
        protected $message  =   [
            'number_tel.require'      => '手机必填',    
            'number_tel.unique' => '手机号码已存在',
        ];
        //需要指定验证位置 和字段
        protected $scene = [
        'add'   =>  ['number_tel'],
        'edit'  =>  ['number_tel'],
    ];   
}