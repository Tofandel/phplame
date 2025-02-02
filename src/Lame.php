<?php
namespace Lame;

use Closure;
use InvalidArgumentException;

/**
 * Lame wrapper
 * 
 * @package Lame
 * @author Bernard Baltrusaitis <bernard@runawaylover.info>
 * @license http://unlicense.org/UNLICENSE Unlicense 
 */
class Lame
{
    /**
     * LAME binary file path
     */
    protected ?string $binary = null;
    
    /**
     * LAME settings
     */
    protected ?Settings $settings = null;

    /**
     * Whether to perform is_executable and is_readable checks on lame binary.
     * On some PHP configs these checks fails but command can be executed nevertheless.
     */
    protected bool $executableChecks = true;

    /**
     * Create new instance of LAME wrapper
     * 
     * @param string $binary lame binary file location
     * @param Settings $settings instance of lame settings
     */
    public function __construct(string $binary, Settings $settings)
    {
        $this->settings = $settings;
        $this->binary = $binary;
    }
    
    /**
     * LAME binary file path
     */
    public function getBinary(): ?string
    {
        return $this->binary;
    }
    
    /**
     * Get Lame settings
     */
    public function getSettings(): ?Settings
    {
        return $this->settings;
    }

    /**
     * @param bool $executableChecks Whether to perform is_executable and is_readable checks on lame binary.
     */
    public function setExecutableChecks(bool $executableChecks)
    {
        $this->executableChecks = $executableChecks;
    }

    public function getExecutableChecks(): bool
    {
        return $this->executableChecks;
    }

    /**
     * Encode given wav file(s) into mp3 file(s).
     * $inputfile can be a location to a single file or a pattern
     * $outputfile can be a file name or a directory name
     * <b>if $inputfile is a pattern then $outputfile should be an existing
     * directory.</b>
     * Callback function should have the following structure:
     * <code>
     * function ($inputfile, $outputfile) {
     *  /\* do someting with $inputfile or $outputfile file *\/
     * }
     * </code>
     *
     * @param string $inputfile input file location or pattern
     * @param string $outputfile output file location or directory name
     * @param Closure|null $callback callback function to be called after encoding
     */
    public function encode(string $inputfile, string $outputfile, Closure $callback = null): void
    { 
        $files = $this->getFilenames($inputfile, $outputfile);
        
        foreach ($files as $inputfile => $outputfile) {
            $this->executeCommand($this->prepareCommand($inputfile, $outputfile));
            
            if (is_null($callback)) {
                continue;
            }
            
            call_user_func_array($callback, array($inputfile, $outputfile));
        } 
    }
    
    /**
     * Prepare LAME command to be executed
     * 
     * @param string $inputfile input file name
     * @param string $outputfile output file name
     * @return string LAME command
     * @throws InvalidArgumentException
     */
    protected function prepareCommand(string $inputfile, string $outputfile): string
    {
        $binary = $this->getBinary();

        if ($this->executableChecks) {
            if (!is_executable($binary)) {
                throw new InvalidArgumentException(
                    sprintf('LAME binary path: `%s` is invalid or not executable', $binary));
            }

            if (!is_readable($inputfile)) {
                throw new InvalidArgumentException(
                    sprintf('Input file `%s` is not readable', $inputfile));
            }
        }
        $command = sprintf('%s ', $binary);
        $command .= $this->getSettings()->buildOptions();
        $command .= sprintf(' %s %s', escapeshellarg($inputfile), 
            escapeshellarg($outputfile));
        
        return $command;
    }
    
    /**
     * Execute given command
     * 
     * @param string $command command to be executed
     * @return boolean
     * @throws \RuntimeException 
     */
    protected function executeCommand(string $command): bool
    {
        $output = '';
        $handle = popen("$command 2>&1", 'r');
        
        while (!feof($handle)) {
            $output .= fgets($handle);
        }

        $returnCode = pclose($handle);
        
        if (0 !== $returnCode) {
            throw new \RuntimeException(
                sprintf('LAME execution error! command: `%s`, error: `%s`, code: %d', 
                    $command, $output, $returnCode));
        }
        
        return true;
    }
    
    /**
     * Get source/destination file names
     * 
     * @param string $inputfile input file name or pattern
     * @param string $outputfile output file name or directory
     * @return array assoc. array of input and output files to be processed
     * @throws InvalidArgumentException
     */
    protected function getFilenames(string $inputfile, string $outputfile): array
    {
        $filenames  = array();
        $inputfiles = glob($inputfile, GLOB_BRACE);
        
        if (!is_array($inputfiles)) {
            throw new InvalidArgumentException(
                sprintf('`%s` is invalid input file location or pattern', 
                    $inputfile));
        }
        
        if ((1 < sizeof($inputfiles)) && !is_dir($outputfile)) {
            throw new InvalidArgumentException(
                sprintf('If input file is a pattern, output file should be 
                    an existing directory, `%s` given', $outputfile));
        }
        
        if (is_dir($outputfile)) {
            $outputfile = rtrim($outputfile, DIRECTORY_SEPARATOR);
        }
        
        foreach ($inputfiles as $inputfile) {
            
            if (is_dir($outputfile)) {
                $filename = pathinfo($inputfile, PATHINFO_FILENAME);
                $filenames[$inputfile] = sprintf('%s/%s.mp3', $outputfile, $filename);
            } else {
                $filenames[$inputfile] = $outputfile;
            }
        }
        
        return $filenames;
    }    
}
