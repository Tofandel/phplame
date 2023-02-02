<?php

namespace Lame\Encoding;

/**
 * Use Average Bitrate Encoding (ABR) and specify ABR related options
 *
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense
 */
class ABR implements EncodingInterface
{
    /**
     * Average bitrate
     *
     * @var int
     */
    protected int $bitrate = 192;

    /**
     * {@inheritdoc }
     */
    public function getOptions(): array
    {
        return array(
            '--abr' => $this->getBitrate()
        );
    }

    /**
     * Get average bitrate desired
     *
     * @return int
     */
    public function getBitrate(): int
    {
        return $this->bitrate;
    }

    /**
     * Specify average bitrate desired
     *
     * <i>turns on encoding with a targeted average bitrate of n kbps, allowing
     * to use frames of different sizes.  The allowed range of n is 8...320
     * kbps, you can use any integer value within that range.</i>
     *
     * @param int $bitrate average bitrate desired
     */
    public function setBitrate(int $bitrate): static
    {
        $this->bitrate = $bitrate;

        return $this;
    }
}
