<?php

class CompleXml_Acl_Resource implements CompleXml_Acl_Resource_Interface
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