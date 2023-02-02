<?php

namespace Lame\Encoding;

/**
 * Use Variable Bit Rate Encoding (VBR) and specify VBR related options
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense
 */
class VBR implements EncodingInterface
{
    /**
     * VBR quality setting
     */
    protected ?float $quality = null;
    
    /**
     * Minimum allowed bitrate
     */
    protected ?int $minBitrate = null;
    
    /**
     * Maximum allowed bitrate
     */
    protected ?int $maxBitrate = null;
    
    /**
     * Use old variable bitrate (VBR) routine
     */
    protected ?bool $vbrOld = null;
    
    /**
     * Use new variable bitrate (VBR) routine (default)
     */
    protected ?bool $vbrNew = null;
    
    /**
     * VBR quality setting
     */
    public function getQuality(): ?float
    {
        return $this->quality;
    }
    
    /**
     * Get a minimum allowed bitrate
     */
    public function getMinBitrate(): ?int
    {
        return $this->minBitrate;
    }
    
    /**
     * Get a maximum allowed bitrate
     */
    public function getMaxBitrate(): ?int
    {
        return $this->maxBitrate;
    }
    
    /**
     * Determine if use new variable bitrate (VBR) routine (default)
     */
    public function isVBRNew(): ?bool
    {
        return $this->vbrNew;
    }
    
    /**
     * Determine if use old variable bitrate (VBR) routine
     */
    public function isVBROld(): ?bool
    {
        return $this->vbrOld;
    }
    
    /**
     * Specify a minimum allowed bitrate (8,16,24,...,320)
     */
    public function setMinBitrate(int $minBitrate): static
    {
        $this->minBitrate = $minBitrate;
        
        return $this;
    }
    
    /**
     * Specify a maximum allowed bitrate (8,16,24,...,320)
     */
    public function setMaxBitrate(int $maxBitrate): static
    {
        $this->maxBitrate = $maxBitrate;
        
        return $this;
    }
    
    /**
     * VBR quality setting  (0=highest quality, 9.999=lowest)
     * default is 4
     */
    public function setQuality(float $quality): static
    {
        $this->quality = $quality;
        
        return $this;
    }
    
    
    /**
     * Use new variable bitrate (VBR) routine (default)
     */
    public function setVBRNew(bool $flag): static
    {
        $this->vbrNew = $flag;
        
        return $this;
    }
    
    /**
     * Use old variable bitrate (VBR) routine
     */
    public function setVBROld(bool $flag): static
    {
        $this->vbrOld = $flag;
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getOptions(): array
    {
        $options = array();
        
        if (!is_null($this->getQuality())) {
            $options['-V'] = $this->getQuality();
        }
        
        if (!is_null($this->getMinBitrate())) {
            $options['-b'] = $this->getMinBitrate();
        }
        
        if (!is_null($this->getMaxBitrate())) {
            $options['-B'] = $this->getMaxBitrate();
        }
        
        if (true === $this->isVBRNew()) {
            $options['--vbr-new'] = true;
        }
        
        if (true === $this->isVBROld()) {
            $options['--vbr-old'] = true;
        }
        
        return $options;
    }    
}
