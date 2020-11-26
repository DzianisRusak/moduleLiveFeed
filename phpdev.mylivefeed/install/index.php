<?php

use Bitrix\Main\Localization\Loc;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

class phpdev_mylivefeed extends CModule
{

    var $MODULE_ID,
		$MODULE_VERSION,
		$MODULE_VERSION_DATE,
		$MODULE_NAME,
		$MODULE_DESCRIPTION,
		$PARTNER_NAME,
	 	$PARTNER_URI;

	public function __construct(){
    $arModuleVersion=array();
    include(__DIR__."/version.php");
    $this->MODULE_ID = 'phpdev.mylivefeed';
    $this->MODULE_VERSION = $arModuleVersion["VERSION"];
    $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
    $this->MODULE_NAME = Loc::GetMessage("phpdev.mylivefeed_MODULE_NAME");
    $this->MODULE_DESCRIPTION = Loc::GetMessage("phpdev.mylivefeed_MODULE_DESC");
    $this->PARTNER_NAME = Loc::GetMessage("phpdev.mylivefeed_PARTNER_NAME");
    $this->PARTNER_URI = Loc::GetMessage("phpdev.mylivefeed_PARTNER_URI");
}

	function isVersionD7(){
        return CheckVersion( \Bitrix\Main\ModuleManager::getVersion('main'),'14.00.00');

    }

	function InstallEvents(){
        RegisterModule($this->MODULE_ID);
        RegisterModuleDependences('main', 'OnBuildGlobalMenu', $this->MODULE_ID, 'CPhpdevMylivefeed', 'OnBuildGlobalMenu');
        RegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, '\Phpdev\Mylivefeed\Loader', "IncludeFile");

        RegisterModuleDependences('blog', 'OnBeforePostAdd', $this->MODULE_ID, '\Phpdev\Mylivefeed\Testovoe', 'OnBeforePostAddHandler');
        RegisterModuleDependences('blog', 'OnPostAdd', $this->MODULE_ID, '\Phpdev\Mylivefeed\Testovoe', 'OnPostAddHandler');

        RegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, '\Phpdev\Mylivefeed\Testovoe', 'addDataPickerOnliveFide');
        return true;
    }

	function UnInstallEvents(){
        UnRegisterModule($this->MODULE_ID);
        UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', $this->MODULE_ID, 'CPhpdevMylivefeed', 'OnBuildGlobalMenu');
        UnRegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, '\Phpdev\Mylivefeed\Loader', "IncludeFile");

        UnRegisterModuleDependences('blog', 'OnBeforePostAdd', $this->MODULE_ID, '\Phpdev\Mylivefeed\Testovoe', 'OnBeforePostAddHandler');
        UnRegisterModuleDependences('blog', 'OnPostAdd', $this->MODULE_ID, '\Phpdev\Mylivefeed\Testovoe', 'OnPostAddHandler');

        UnRegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, '\Phpdev\Mylivefeed\Testovoe', 'addDataPickerOnliveFide');
        return true;
    }

	function InstallFiles(){
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/admin'))
        {
            if ($dir = opendir($p))
            {
                while (false !== $item = readdir($dir))
                {
                    if ($item == '..' || $item == '.' || $item == 'menu.php')
                        continue;
                    file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$this->MODULE_ID.'_'.$item,
                        '<'.'? require($_SERVER["DOCUMENT_ROOT"]."/local/modules/'.$this->MODULE_ID.'/admin/'.$item.'");?'.'>');
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID.'/install/files')){
            CopyDirFiles(__DIR__ . "/files",
                $_SERVER["DOCUMENT_ROOT"]."/local", true, true);
            return true;
        }
    }

	function UnInstallFiles(){
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID.'/admin')){
            if ($dir = opendir($p)){
                while (false !== $item = readdir($dir)){
                    if ($item == '..' || $item == '.')
                        continue;
                    unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$this->MODULE_ID.'_'.$item);
                }
                closedir($dir);
            }
        }
        if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$this->MODULE_ID.'/install/files')){
            DeleteDirFiles(__DIR__ . "/files",
                $_SERVER["DOCUMENT_ROOT"]."/local", true, true);
            return true;
        }
    }

	public function doInstall(){
    $this->InstallFiles();
    $this->InstallEvents();
}

	public function doUninstall(){
    $this->UnInstallFiles();
    $this->UnInstallEvents();
}

}
?>