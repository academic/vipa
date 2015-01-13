<?php
/** 
 * Date: 14.01.15
 * Time: 00:00
 */

namespace Ojs\JournalBundle\Tests\Form;


use Ojs\Common\Tests\BaseTypeTestcase;
use Ojs\JournalBundle\Form\ArticleType;

class ArticleTypeTest extends BaseTypeTestcase {
 public function testSubmitValidData(){
         $this->basicSubmitTest(
             new ArticleType(),
             [

             ],
             '');
     }
}
