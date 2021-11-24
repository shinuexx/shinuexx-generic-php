<?php

namespace ShInUeXx\Generic;

interface IXmlSerializable
{
    public function toSimpleXml(SimpleXmlElement $thisElement = null): SimpleXmlElement;
}
