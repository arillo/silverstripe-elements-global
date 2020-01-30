<?php

namespace Arillo\ElementsGlobal;

use Arillo\Elements\ElementBase;
use SilverStripe\Core\ClassInfo;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Versioned\Versioned;
use Arillo\Elements\ElementsExtension;
use Arillo\ElementsGlobal\IElementsGlobal;
use Sheadawson\DependentDropdown\Forms\DependentDropdownField;

class VirtualElement extends ElementBase
{
	private static $table_name = 'Arillo_ElementGlobal_VirtualElement';
	private static $singular_name = 'VirtualElement';
	private static $extensions = [ 
		Versioned::class
	];
	// . '.versioned' 

	private static $db = array(
		'ReferenceClass' => 'Varchar'
	);
	private static $has_one = array(
        'ReferenceElement' => ElementBase::class
    );


    public function getCMSFields() {
		$fields = parent::getCMSFields();

		$availableClasses = ElementsExtension::map_classnames(ClassInfo::implementorsOf(IElementsGlobal::class));
		$allowedClasses = Config::inst()->get($this->getHolder()->ClassName,'virtual_elements');
		$relationName = Controller::curr()->request->param('FieldName');

		if(isset($allowedClasses[$relationName])){
			$filterClasses = $allowedClasses[$relationName];
			$availableClasses = array_filter(
				$availableClasses,
				function ($key) use ($filterClasses) {
					return in_array($key, $filterClasses);
				},
				ARRAY_FILTER_USE_KEY
			);
		}

		$availableElements = function($val) {
			return Versioned::get_by_stage($val, Versioned::LIVE)
				->filter([
					'PageID' => 0,
					'ElementID' => 0
					// 'Global' => 1,
				])
				->map('ID','Title')
			;
		};

		$classesDropdown = DropdownField::create('ReferenceClass', 'Type', $availableClasses);
		$elementsDropdown = DependentDropdownField::create('ReferenceElementID', 'Element', $availableElements)->setDepends($classesDropdown);

		$fields->addFieldToTab('Root.Main', $classesDropdown);
		$fields->addFieldToTab('Root.Main', $elementsDropdown);


		return $fields;
	}

	public function getType(){
		if($ref = $this->ReferenceClass){
			return $this->i18n_singular_name() . ' :: ' . $ref;
		}
		return $this->i18n_singular_name();
	}

	public function previewRender(){
		return $this->Render();
	}

	public function Render($IsPos = null, $IsFirst = null, $IsLast = null, $IsEvenOdd = null)
    {
    	return $this->ReferenceElement()->Render($IsPos, $IsFirst, $IsLast, $IsEvenOdd);
    }

}