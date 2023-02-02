<?php
namespace Lame\Encoding;

/**
 * Encoding interface
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense  
 */
interface EncodingInterface
{
    /**
     * Get options specified for given encoding
     */
    public function getOptions(): array;
}
