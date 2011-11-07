<?php

/**
 * CompleXml_Cache_Item
 *
 * @author Andrey Kucehrenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Cache_Item {

    /**
     * @var mixed
     */
    private $_data;
    /**
     * @var array
     */
    private $_criterias = array();
    /**
     * @var string
     */
    private $_id;
    /**
     * @param string $id
     * @param mixed $data
     */
    public function  __construct($id, $data) {
        $this->_id = $id;
        $this->_data = $data;
    }
    public function setId($id) {
        $this->_data = $id;
    }

    public function getId() {
        return $this->_id;
    }
    public function setData($data) {
        $this->_data = $data;
    }

    public function getData() {
        return $this->_data;
    }

    public function addCriteria(CompleXml_Cache_Criteria_Interface $Criteria) {
        $this->_criterias[] = $Criteria;
    }
    public function isValid() {
        foreach ($this->_criterias as $Criteria) {
            if (!$Criteria->isValid($this)) {
                return false;
            }
        }
        return true;
    }
}

