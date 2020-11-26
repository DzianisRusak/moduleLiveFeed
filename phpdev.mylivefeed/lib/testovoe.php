<?php

namespace Phpdev\Mylivefeed;

use Bitrix\Main\Diag\Debug;


class Testovoe
{

    function addDataPickerOnliveFide()
    {
        $arJsLibs = [
            'liveFideTest' => [
                "js" => '/local/modules/phpdev.mylivefeed/script.js',
                'rel' => array('jquery')
            ],
        ];

        foreach ($arJsLibs as $jsLibName => $options) {
            \CJSCore::RegisterExt($jsLibName, $options);
        }


        if ($_SERVER['SCRIPT_URL'] == "/stream/") {
            \CUtil::InitJSCore(array("liveFideTest"));
        }
    }

    function OnBeforePostAddHandler(&$arFields)
    {
        $str = $arFields['TITLE'];

        $start = strpos($str, '{');
        if ($start !== false) {

            $end = strpos($str, '}', $start + 1);
            $length = $end - $start;
            $date = substr($str, $start + 1, $length - 1);
            $title = substr($str, 0, $start);

            $arFields['TITLE'] = $title;
            $arFields['DATE_PUBLISH'] = $date;
            $arFields['PUBLISH_STATUS'] = 'R';
        }
    }

    function OnPostAddHandler(&$ID)
    {
        if (\CModule::IncludeModule("blog")) {
            $arPost = \CBlogPost::GetByID($ID);
            if ($arPost['PUBLISH_STATUS'] === "R") {
                \CAgent::AddAgent(
                    '\Phpdev\Mylivefeed\Testovoe::publickBlog(' . $arPost['ID'] . ');',
                    'phpdev.mylivefeed',
                    "Y",                     // агент не критичен к кол-ву запусков
                    1,                      // интервал запуска - 1 сутки
                    "",                   // дата первой проверки - текущее
                    "Y",                     // агент активен
                    $arPost['DATE_PUBLISH']        // дата первого запуска - текущее
                );
            }
        }
    }


    function publickBlog($ID)
    {
        if (\CModule::IncludeModule("blog")) {
            $res = \CBlogPost::Update($ID, array('PUBLISH_STATUS' => 'P'));
            //Debug::writeToFile($ID);
            //Debug::writeToFile($res);
        }
    }
}