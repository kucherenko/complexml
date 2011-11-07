<?php

/**
 * Класс для создания XML, расширяет возможности стандартного XMLWriter
 */
class CompleXml_View_XmlWriter extends XMLWriter
{
    
    private $_is_included = false;
    /**
     * Default name for uncorect node name
     *
     * @var string
     */
    private $_default_node_name = 'row';
    /**
     * Default name for uncorect attribute name
     *
     * @var string
     */
    private $_default_attribute_name = 'attr';
    
    public function __construct($encoding = 'utf-8')
    {
        $this->openMemory();
        $this->startDocument('1.0', $encoding);
    }
    
    public function __set($name, $value)
    {
        $this->writeElement($name, $value);
    }
    
    public function writeElement($name, $value = null, $attributes = null)
    {
        if ($value instanceof Iterator || is_array($value) || $value instanceof IteratorAggregate) {
            $this->startElement($name);
            if (!is_null($attributes)) {
                $this->writeAttribute('attr', $attributes);
            } elseif (isset($value['__attributes'])) {
                $this->writeAttribute('attr', $value['__attributes']);
                unset($value['__attributes']);
            }
            $this->_createXml($value);
            $this->endElement();
        } else {
            if (!is_null($value)) {
                if (empty($attributes)) {
                    parent::writeElement($name, $value);
                } else {
                    parent::startElement($name);
                    $this->writeAttribute('attr', $attributes);
                    parent::text($value);
                    parent::endElement();
                }
            }
        }
    }
    
    public function writeElementNs($prefix, $name, $uri, $value = null, $attributes = null)
    {
        if ($value instanceof Iterator || is_array($value) || $value instanceof IteratorAggregate) {
            $this->startElementNs($prefix, $name, $uri);
            if (!is_null($attributes)) {
                $this->writeAttribute('attr', $attributes);
            } elseif (isset($value['__attributes'])) {
                $this->writeAttribute('attr', $value['__attributes']);
                unset($value['__attributes']);
            }
            $this->_createXml($value);
            $this->endElement();
        } else {
            parent::writeElementNs($prefix, $name, $uri, $value);
        }
    }
    
    public function writeAttribute($name, $value)
    {
        if ($value instanceof Iterator || is_array($value) || $value instanceof IteratorAggregate) {
            $i = 0;
            foreach ($value as $key => $val) {
                if (!is_string($key[ 0 ])) {
                    parent::writeAttribute($this->getDefaultAttributeName() . $i, $val);
                } else {
                    parent::writeAttribute($key, $val);
                }
                $i ++;
            }
        } else {
            parent::writeAttribute($name, $value);
        }
    }

    /**
     * Generate xi:include instruction
     *
     * @param string $path
     */
    public function includeXML($path)
    {
        $this->startElementNs('xi', 'include', 'http://www.w3.org/2001/XInclude');
        $this->writeAttribute('href', $path);
        $this->writeAttribute('parse', 'xml');
        $this->startElementNs('xi', 'fallback', 'http://www.w3.org/2001/XInclude');
        $this->writeElement('error', 'xinclude: ' . $path . ' not found');
        $this->endElement();
        $this->endElement();
        $this->_is_included = true;
    }
    
    protected function _createXml($object)
    {
        $i = 0;
        foreach ($object as $key => $value) {
            if (!is_string($key[ 0 ])) {
                $this->writeElement($this->getDefaultNodeName(), $value, array(
                                    $this->getDefaultNodeName() . '_id' => $i
                ));
            } else {
                $this->writeElement($key, $value);
            }
            $i ++;
        }
    }
    
    public function writeRawXml($html)
    {
        $xhtml = new DOMDocument('1.0', 'utf-8');
        @$xhtml->loadHTML('<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>' . $html . '</body></html>');
        $string = $xhtml->saveXML();
        $pattern = '%<body>(.*?)<\/body>%is';
        preg_match($pattern, $string, $matches, PREG_OFFSET_CAPTURE);
        $result = $matches[1][0];
        $result = str_replace('&#13;', '', $result);
        $this->writeRaw($result);
    }

    /**
     * @return string
     */
    public function getDefaultAttributeName()
    {
        return $this->_default_attribute_name;
    }

    /**
     * @return string
     */
    public function getDefaultNodeName()
    {
        return $this->_default_node_name;
    }

    /**
     * @param string $default_attribute_name
     */
    public function setDefaultAttributeName($default_attribute_name)
    {
        $this->_default_attribute_name = $default_attribute_name;
    }

    /**
     * @param string $default_node_name
     */
    public function setDefaultNodeName($default_node_name)
    {
        $this->_default_node_name = $default_node_name;
    }
    
    public function hasIncludedXml()
    {
        return $this->_is_included;
    }
}