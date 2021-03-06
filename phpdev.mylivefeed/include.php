<?

Class CPhpdevMylivefeed
{
	function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu){
		if($GLOBALS['APPLICATION']->GetGroupRight("main") < "R")
			return;

		$MODULE_ID = basename(dirname(__FILE__));
		$aMenu = array(
			//"parent_menu" => "global_menu_services",
			"parent_menu" => "global_menu_settings",
			"section" => $MODULE_ID,
			"sort" => 50,
			"text" => $MODULE_ID,
			"title" => '',
			"icon" => "",
			"page_icon" => "",
			"items_id" => $MODULE_ID."_items",
			"more_url" => array(),
			"items" => array()
		);

		if (file_exists($path = dirname(__FILE__).'/admin')){

			if ($dir = opendir($path)){
				$arFiles = array();

				while(false !== $item = readdir($dir)){
					if (in_array($item,array('.','..','menu.php')))
						continue;
					if (!file_exists($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.$MODULE_ID.'_'.$item))
						file_put_contents($file,'<'.'? require($_SERVER["DOCUMENT_ROOT"]."/local/modules/'.$MODULE_ID.'/admin/'.$item.'");?'.'>');
					$arFiles[] = $item;
				}
				sort($arFiles);
				foreach($arFiles as $item) {
					$lines = file($_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$MODULE_ID.'/admin/'.$item);
					if($lines[1]){
						if (strpos($lines[1], '//') === false) {
							$text = $item;
						}
						else {
							$text = str_replace('/','',$lines[1]);
						}
					}
					else $text = $item;
					$aMenu['items'][] = array(
						'text' => $text,
						'url' => $MODULE_ID . "_" . $item,
						'module_id' => $MODULE_ID,
						"title" => "",
					);
				}
			}
		}
		$aModuleMenu[] = $aMenu;
	}
}

?>