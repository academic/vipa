<?php
/** 
 * Date: 13.01.15
 * Time: 17:24
 */

namespace Ojs\Common\Tests;


use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;

class BaseTypeTestcase extends TypeTestCase {


    public $faker;

    public function __construct($name=null,$data=[],$dataName='')
    {
        $this->faker = \Faker\Factory::create();
        return parent::__construct($name,$data,$dataName);
    }
    /**
     * Generate basic form submit test
     * @param  $type object
     * @param  $formData array
     * @param  $objectClass string
     */
    public function basicSubmitTest($type, $formData, $objectClass, $options=[])
    {
        $form = $this->factory->create($type,null,$options);
        $object = $this->createObject($formData, $objectClass);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($this->toArray($object,$formData),$formData);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key,$children);
        }
    }

    /**
     * Create object from array values
     * @param $data array
     * @param $class string
     * @return object
     */
    public function createObject($data, $class)
    {
        $object = new $class();

        foreach ($data as $key => $value) {
            if(method_exists($object,'set'.ucfirst($key)))
                $object->{'set' . ucfirst($key)}($value);
        }

        return $object;
    }

    /**
     * Get array
     * @param $object object
     * @param $refer array
     * @return array
     */
    public function toArray($object,$refer){
        $data =[];
       /* foreach ($refer as $key => $value) {
            $new_key = join('',array_map(function($a){
                return ucfirst($a);
            },explode('_',$key)));
            unset($refer[$key]);
            $refer[$new_key]=$value;
        }
*/
        $attributes = get_class_methods(get_class($object));
        foreach($attributes as $key=>$value){
            $r = new \ReflectionMethod(get_class($object),$value);
            if(preg_match('~^get.*~',$value) && $r->getNumberOfRequiredParameters()<1){
                $key = lcfirst(str_replace('get','',$value));
                if(array_key_exists($key,$refer)){
                    $data[$key]=$object->{$value}();
                }
            }
        }
        return $data;
    }
}