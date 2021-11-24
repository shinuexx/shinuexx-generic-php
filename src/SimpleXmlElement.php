<?php

namespace ShInUeXx\Generic;

use RuntimeException;
use SimpleXMLElement as GlobalSimpleXMLElement;

use function dom_import_simplexml, htmlentities, sprintf;


class SimpleXmlElement extends GlobalSimpleXMLElement
{
    public function appendCDataSection(string $data): self
    {
        $node = dom_import_simplexml($this);
        $doc = $node->ownerDocument;
        if ($doc === null) throw new RuntimeException();
        $node->appendChild($doc->createCDATASection($data));
        return $this;
    }

    public function setValue(string $value): self
    {
        $this[0] = $value;
        return $this;
    }

    public function appendText(string $text): self
    {
        $c = $this->count();
        $this[$c] = $text;
        return $this;
    }

    public function addChild($qualifiedName, $value = null, $namespace = null)
    {
        return parent::addChild($qualifiedName, $value !== null ? htmlentities($qualifiedName) : $value, $namespace);
    }

    /**
     * @param null|string $value
     */
    public function addAttribute($qualifiedName, $value = null, $namespace = null)
    {
        return parent::addAttribute($qualifiedName, $value !== null ? htmlentities($qualifiedName) : $value, $namespace);
    }

    public function addAttributeWithPrefix(string $prefix, string $name, string $value = null)
    {
        $newName = sprintf('xmlns:%s:%s', $prefix, $name);
        return $this->addAttribute($newName, $value);
    }
}
