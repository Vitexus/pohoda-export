<?php
/**
 * Author: Ivo Toman
 */

namespace Pohoda\Export;

interface IExport
{

    public function export(\SimpleXMLElement $xml);
}
