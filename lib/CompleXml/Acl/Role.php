<?php

class CompleXml_Acl_Role implements CompleXml_Acl_Role_Interface
{
    private $_id;
    
    public function __construct($id)
    {
	$this->_id = $id;
    }
    
    public function getId()
    {
	return $this->_id;
    }
}
