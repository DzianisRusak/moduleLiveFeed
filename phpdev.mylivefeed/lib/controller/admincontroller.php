<?php
namespace Phpdev\Mylivefeed\Controller;

use Bitrix\Main\Engine\Controller,
    Phpdev\Mylivefeed\Model\AdminModel;

class AdminController extends Controller
{
    public function configureActions()
    {
        return [
            'SaveOptions' => [
                'prefilters' => []
            ]
        ];
    }

    public static function SaveOptionsAction( $data = [])
    {
        return  (new AdminModel)->SaveOptions($data);
    }
}