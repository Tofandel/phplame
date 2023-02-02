<?php

namespace Lame\Encoding;

/**
 * Null Encoding, use this encoding when lame 
 * options will be specified manually
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense 
 */
class NullEncoding implements EncodingInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        return array();
    }    
}
