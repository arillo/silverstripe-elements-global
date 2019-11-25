<?php

namespace Arillo\ElementsGlobal;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\HiddenField;

class ElementsGlobalModelAdminExtension extends Extension
{
    // TODO: check why Global = 1 is not being set on creation of dataobject
    public function updateEditForm(&$form) 
    {
        $form->Fields()->removeByName('PageID');
		$form->Fields()->push(HiddenField::create('Global', 'Global', 1));
    }
}