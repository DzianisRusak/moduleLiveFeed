<?
namespace Phpdev\Mylivefeed;

use Bitrix\Main\Config\Option;
use Phpdev\Mylivefeed\Settings\AdminSettings;

class Loader
{
    public function IncludeFile(){

        require_once $_SERVER['DOCUMENT_ROOT'] . '/local/modules/phpdev.mylivefeed/lib/testovoe.php';

    }
}