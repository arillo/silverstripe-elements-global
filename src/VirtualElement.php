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
    private static $extensions = [Versioned::class];

    private static $db = [
        'ReferenceClass' => 'Varchar',
    ];
    private static $has_one = [
        'ReferenceElement' => ElementBase::class,
    ];

    private static $owns = ['ReferenceElement'];

    public static function global_elements_filter()
    {
        return [
            'PageID' => 0,
            'ElementID' => 0,
            // 'Global' => 1,
        ];
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $labels = $this->fieldLabels();
        $availableClasses = ElementsExtension::map_classnames(
            ClassInfo::implementorsOf(IElementsGlobal::class)
        );
        $allowedClasses = Config::inst()->get(
            $this->getHolder()->ClassName,
            'virtual_elements'
        );
        $relationName = Controller::curr()->request->param('FieldName');

        if (isset($allowedClasses[$relationName])) {
            $filterClasses = $allowedClasses[$relationName];
            $availableClasses = array_filter(
                $availableClasses,
                function ($key) use ($filterClasses) {
                    return in_array($key, $filterClasses);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        $availableElements = function ($val) {
            return $val
                ::get()
                ->filter(self::global_elements_filter())
                ->map('ID', 'Title');
        };

        $classesDropdown = DropdownField::create(
            'ReferenceClass',
            $labels['Type'],
            $availableClasses
        );

        $elementsDropdown = DependentDropdownField::create(
            'ReferenceElementID',
            $labels['ReferenceElementID'],
            $availableElements
        )->setDepends($classesDropdown);

        $fields->addFieldToTab('Root.Main', $classesDropdown);
        $fields->addFieldToTab('Root.Main', $elementsDropdown);

        return $fields;
    }

    public function getType()
    {
        if ($ref = $this->ReferenceClass) {
            return $this->i18n_singular_name() .
                ' (' .
                $ref::singleton()->i18n_singular_name() .
                ')';
        }
        return $this->i18n_singular_name();
    }

    public function previewRender()
    {
        return $this->Render();
    }

    public function Render(
        $IsPos = null,
        $IsFirst = null,
        $IsLast = null,
        $IsEvenOdd = null
    ) {
        $el = $this->ReferenceElement();
        if ($el->hasMethod('setVirtualHolderElement')) {
            $el->setVirtualHolderElement($this);
        }
        return $el->Render($IsPos, $IsFirst, $IsLast, $IsEvenOdd);
    }

    public function getCMSSummary()
    {
        if (($ref = $this->ReferenceElement()) && $ref->exists()) {
            return $ref->getCMSSummary();
        }
        return $this->getCMSSummary();
    }

    public function getCacheKey()
    {
        $key = [];
        if (($ref = $this->ReferenceElement()) && $ref->exists()) {
            $key = [
                $ref->ID,
                $ref->obj('LastEdited')->format('y-MM-dd-HH-mm-ss'),
            ];
        }

        return implode(
            '-_-',
            array_merge([parent::getCacheKey()], array_filter($key, 'strlen'))
        );
    }

    public function fieldLabels($includerelations = true)
    {
        return array_merge(parent::fieldLabels($includerelations), [
            'Type' => _t(__CLASS__ . '.Type', 'Type'),
            'ReferenceElementID' => _t(
                __CLASS__ . '.ReferenceElementID',
                'Element'
            ),
        ]);
    }
}
