<?php

class VirtualElement extends ElementBase
{

	private static $has_one = array(
        'ReferenceElement' => 'ElementBase'
    );


    public function getCMSFields() {
		$fields = parent::getCMSFields();

		// $fields->addFieldToTab('Root.Main', );
		
		return $fields;
	}

	public function Render($IsPos = null, $IsFirst = null, $IsLast = null, $IsEvenOdd = null)
    {
    	return $this->ReferenceElement()->Render($IsPos, $IsFirst, $IsLast, $IsEvenOdd);
        
        // $this->IsPos = $IsPos;
        // $this->IsFirst = $IsFirst;
        // $this->IsLast = $IsLast;
        // $this->IsEvenOdd = $IsEvenOdd;
        // $controller = Controller::curr();
        // return $controller
        //     ->customise($this)
        //     ->renderWith($this->ReferenceElement()->ClassName)
        // ;
    }
}