<?php

namespace Sule\OCR\Service;

/*
 * This file is part of the Sulaeman OCR package.
 *
 * (c) Sulaeman <me@sulaeman.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Tesseract extends Service
{
    /**
     * Path to tesseract executable.
     * Default value assumes it is present in the $PATH.
     *
     * @var string
     */
    private $executable = 'tesseract';

    /**
     * Path to enrich executable.
     * Default value assumes it is present in the $PATH.
     *
     * @var string
     */
    private $enrich = 'enrich';

    /**
     * Path to textcleaner executable.
     * Default value assumes it is present in the $PATH.
     *
     * @var string
     */
    private $textcleaner = 'textcleaner';

    /**
     * Path to the source to be recognized.
     *
     * @var string
     */
    private $source;

    /**
     *  List of tesseract configuration variables.
     *
     * @var array
     */
    private $configs = [];

    /**
     * Path to user words file.
     *
     * @var string
     */
    private $userWords;

    /**
     * Path to user patterns file.
     *
     * @var string
     */
    private $userPatterns;

    /**
     * List of languages.
     *
     * @var string
     */
    private $languages;

    /**
     * Page Segmentation Mode value.
     *
     * @var integer
     */
    private $psm;

    /**
     * Create a new instance.
     *
     * @param  array  $config
     * @return self
     */
    public function __construct(Array $config)
    {
        parent::__construct($config);

        $this->executable  = $config['executable'];
        $this->enrich      = $config['enrich'];
        $this->textcleaner = $config['textcleaner'];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function source($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function option($key, $value)
    {
        switch ($key) {
            case 'user-words':
                $this->userWords($value);
                break;
            
            case 'user-patterns':
                $this->userPatterns($value);
                break;

            case 'lang':
                $this->lang($value);
                break;

            case 'psm':
                $this->psm($value);
                break;

            case 'whitelist':
                $this->whitelist($value);
                break;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function config($key, $value)
    {
        $this->configs[$key] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function parse()
    {
        // Enrich the source image first
        if (file_exists($this->enrich)) {
            exec($this->enrich.' '.$this->source.' '.$this->source, $output, $return);
            if ( ! empty($output)) {
                $this->writeLog('info', 'ENRICH OUTPUT : '.json_encode($output));
                $this->writeLog('info', 'ENRICH RETURN : '.json_encode($return));
            }
        }

        // Clean the source image first
        if (file_exists($this->textcleaner)) {
            exec($this->textcleaner.' -g -e stretch -f 25 -o 5 -s 1 -T -p 20 '.$this->source.' '.$this->source, $output, $return);
            if ( ! empty($output)) {
                $this->writeLog('info', 'TEXTCLEANER OUTPUT : '.json_encode($output));
                $this->writeLog('info', 'TEXTCLEANER RETURN : '.json_encode($return));
            }
        }

        $result = trim(`{$this->buildCommand()}`);

        $this->reset();

        return $result;
    }

    /**
     * Sets user words file path.
     *
     * @param string $filePath
     * @return self
     */
    public function userWords($filePath)
    {
        $this->userWords = $filePath;
        return $this;
    }

    /**
     * Sets user patterns file path.
     *
     * @param string $filePath
     * @return self
     */
    public function userPatterns($filePath)
    {
        $this->userPatterns = $filePath;
        return $this;
    }

    /**
     * Sets the language(s).
     *
     * @param string $languages
     * @return self
     */
    public function lang($languages)
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * Sets the Page Segmentation Mode value.
     *
     * @param integer $psm
     * @return self
     */
    public function psm($psm)
    {
        $this->psm = $psm;
        return $this;
    }

    /**
     * Shortcut to set tessedit_char_whitelist values in a more convenient way.
     * Example:
     *
     *     (new WrapTesseractOCR('image.png'))
     *         ->whitelist(range(0, 9), range('A', 'F'), '-_@')
     *
     * @param mixed ...$charlists
     * @return self
     */
    public function whitelist($charlists)
    {
        $concatenate = function ($carry, $item) {
            return $carry . join('', (array)$item);
        };
        $whitelist = array_reduce($charlists, $concatenate, '');
        $this->config('tessedit_char_whitelist', $whitelist);
        return $this;
    }

    /**
     * Builds the tesseract command with all its options.
     * This method is 'protected' instead of 'private' to make testing easier.
     *
     * @return string
     */
    protected function buildCommand()
    {
        return $this->executable.' '.escapeshellarg($this->source).' stdout'
            .$this->buildUserWordsParam()
            .$this->buildUserPatternsParam()
            .$this->buildLanguagesParam()
            .$this->buildPsmParam()
            .$this->buildConfigurationsParam();
    }

    /**
     * If user words file is defined, return the correspondent command line
     * argument to the tesseract command.
     *
     * @return string
     */
    private function buildUserWordsParam()
    {
        return $this->userWords ? " --user-words $this->userWords" : '';
    }

    /**
     * If user patterns file is defined, return the correspondent command line
     * argument to the tesseract command.
     *
     * @return string
     */
    private function buildUserPatternsParam()
    {
        return $this->userPatterns ? " --user-patterns $this->userPatterns" : '';
    }

    /**
     * If one (or more) languages are defined, return the correspondent command
     * line argument to the tesseract command.
     *
     * @return string
     */
    private function buildLanguagesParam()
    {
        return $this->languages ? " -l $this->languages" : '';
    }

    /**
     * If a page segmentation mode is defined, return the correspondent command
     * line argument to the tesseract command.
     *
     * @return string
     */
    private function buildPsmParam()
    {
        return $this->psm ? " --psm $this->psm" : '';
    }

    /**
     * Return tesseract command line arguments for every custom configuration.
     *
     * @return string
     */
    private function buildConfigurationsParam()
    {
        $buildParam = function ($config, $value) {
            return ' -c '.escapeshellarg("$config=$value");
        };
        return join('', array_map(
            $buildParam,
            array_keys($this->configs),
            array_values($this->configs)
        ));
    }

    /**
     * Reset every custom configuration.
     *
     * @return void
     */
    private function reset()
    {
        $this->userWords = null;
        $this->userPatterns = null;
        $this->languages = null;
        $this->psm = null;
    }
}
