<?php
namespace Texsts\Form;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Textarea;
use Texsts\Form\TextFilter;

class Text extends Form{

    public function __construct(){

        parent::__construct();

        $this->add(array(
            'name' => 'title',
        ));

        $this->add(array(
            'name' => 'annotation',
        ));

        $this->add(array(
            'name' => 'text',
        ));


        $filter = new TextFilter();
        $this->setInputFilter($filter->getInputFilter());
    }

    public function getArrayCopy(){
        return array();
    }
}
