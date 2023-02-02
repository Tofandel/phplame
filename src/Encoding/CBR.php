<?php

namespace Lame\Encoding;

/**
 *  Use Constant Bitrate Encoding (CBR) and specify CBR related options
 *
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense 
 */
class CBR implements EncodingInterface
{
    /**
     * Bitrate in kbps
     * 
     * @var int 
     */
    protected int $bitrate = 128;
    
    /**
     * Get bitrate (8, 16, 24, ..., 320)
     * 
     * @return int 
     */
    public function getBitrate(): int
    {
        return $this->bitrate;
    }
    
    /**
     * Set bitrate in kbps (default 128 kbps)
     * possible values (8, 16, 24, ..., 320)
     */
    public function setBitrate(int $bitrate): static
    {
        $this->bitrate = $bitrate;

        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOptions(): array
    {
        return array(
            '--cbr' => true,
            '-b'    => $this->getBitrate()
        );
    }    
}
