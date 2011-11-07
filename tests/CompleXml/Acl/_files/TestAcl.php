<?php
// @codeCoverageIgnoreStart
class TestAclController extends CompleXml_Controller
{
    /**
     * @allow user1
     * @deny user2
     */
    public function methodAction()
    {
        
    }

   /**
    * @allow all
    * @deny user2
    */
    public function methodAllowAllAction()
    {

    }

   /**
    * @deny all
    * @allow user2
    */
    public function methodDenyAllAction()
    {

    }
}
// @codeCoverageIgnoreEnd