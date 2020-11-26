<?php
namespace Phpdev\Mylivefeed\Settings;

use Bitrix\Main\Application;
use Phpdev\Mylivefeed\Settings\AdminSettings;

class AdminSettings {
    const settings = ['tabs'=>[
            [
                'tab_name'=> 'Default', 'active'=> false,
                'tab_options'=> [
                    ['name'=> "Type line",'type'=>'line','sort'=>"head"],
                    ['name'=> "При добавлении к типу _n_save запись в БД производиться не будет",'type'=>'line','sort'=>"head"],
                    ['name'=>"Type input", 'option_str'=> "input", 'value'=> "",'type'=>'input'],
                    ['name'=> "Type checkbox", 'option_str'=> "check", 'value'=> false,'type'=>'checkbox'],
                    ['name'=> "Type list", 'option_str'=> "list", 'value'=> "",'list'=> ['1','2'],'type'=>'list'],
                    ['name'=> "Type textarea", 'option_str'=> "textarea", 'value'=> "",'type'=>'textarea'],
                ],
                'buttons'=>[
                    [
                        'button_name'=>"Сохранить",'button_function'=>'SaveOptions','class'=>'adm-btn-save'
                    ],
                    [
                         'button_name'=>"Обновить",'button_function'=>'UpdateOptions','class'=>'adm-btn'
                     ]
                ]
            ],
            [
                'tab_name'=> 'Default2', 'active'=> false,
                'tab_options'=> [

                ],
                'buttons'=>[
                    [
                        'button_name'=>"Сохранить",'button_function'=>'SaveOptions','class'=>'adm-btn-save'
                    ]
                ]
            ]
        ],
    ];
    const TableName ='b_option';
    const ModuleID = 'phpdev.mylivefeed';
    var $setting = false;
    private static $_instance = null;

    private function __construct() {
    }
    protected function __clone() {
    }
    static public function getInstance() {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    private static function GetDBOptions(){
        global $DB;
        $Sql='SELECT `name`,`value` FROM '.self::TableName." WHERE `module_id`='".self::ModuleID."'";
        $res = $DB->Query($Sql, false, $err_mess.__LINE__);
        $setting = self::settings;
        while ($row = $res->Fetch())
        {
            foreach ($setting['tabs'] as $k => $tab) {
                foreach ( $tab['tab_options'] as $index => $option) {
                    if($row['name']===$option['option_str']){
                        if($option['type']=='checkbox')
                            $setting['tabs'][$k]['tab_options'][$index]['value']=($row['value']=='false')?false:true;
                        else
                            $setting['tabs'][$k]['tab_options'][$index]['value']=$row['value'];
                    }
                }
            }
        }
        return $setting;
    }
    public function getSettings() {
        return json_encode(self::GetDBOptions());
    }
}