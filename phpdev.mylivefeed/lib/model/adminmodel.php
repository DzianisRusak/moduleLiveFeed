<?
namespace Phpdev\Mylivefeed\Model;

use Phpdev\Mylivefeed\Settings\AdminSettings;
use Bitrix\Main\Config\Option;

class AdminModel
{

    public static function SaveOptions(array $data){
        $ModuleID = AdminSettings::ModuleID;
        foreach ($data['tab_options'] as $option){
           if($option['type'] !== 'line')
                if(strpos($option['type'], '_n_') === false) {
                    Option::set($ModuleID, $option['option_str'], $option['value']);
                    $data['state'] = 'Save';
                }
        }
        return $data;
    }
}

?>