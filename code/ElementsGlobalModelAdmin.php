<?php

namespace Arillo\ElementsGlobal;

use ModelAdmin;
use HiddenField;
use ClassInfo;


class ElementsGlobalModelAdmin extends ModelAdmin
{

	private static $menu_title = 'Elements';

	private static $url_segment = 'elements-global';

	private static $menu_icon = 'elements-global/images/ModelAdmin.png';

	private static $menu_priority = 1.5;

	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		$modelclass = $this->sanitiseClassName($this->modelClass);
		$config = $form->Fields()->fieldByName($modelclass)->getConfig();
		
		$config->getComponentByType('GridFieldPaginator')->setItemsPerPage(50);

		$detailForm = $config->getComponentByType('GridFieldDetailForm');
		$detailForm->setItemEditFormCallback(function($form) {
			// $record = $form->getRecord();
			$form->Fields()->removeByName('PageID');
			$form->Fields()->addFieldToTab('Root.Main', HiddenField::create('Global', 'Global', 1));
			// $form->Fields()->addFieldToTab('Root.Reference', TextField::create('Testsadf', 'Test'));
		});

		return $form;
	}

	public function getManagedModels() {

		$models = ClassInfo::implementorsOf("IElementsGlobal");
		if(is_string($models)) {
			$models = array($models);
		}
		if(!count($models)) {
			user_error(
				'ModelAdmin::getManagedModels():
				You need to specify at least one DataObject subclass in public static $managed_models.
				Make sure that this property is defined, and that its visibility is set to "public"',
				E_USER_ERROR
			);
		}

		// Normalize models to have their model class in array key
		foreach($models as $k => $v) {
			if(is_numeric($k)) {
				$models[$v] = array('title' => singleton($v)->i18n_singular_name());
				unset($models[$k]);
			}
		}

		return $models;
	}

	public function getList() {
		$list = parent::getList()
			->filter(array('Global' => 1))
		;
		return $list;
	}
}