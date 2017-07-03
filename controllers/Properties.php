<?php namespace Tiipiik\Catalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Properties Back-end Controller
 */
class Properties extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Tiipiik.Catalog', 'catalog', 'properties');
    }
}
