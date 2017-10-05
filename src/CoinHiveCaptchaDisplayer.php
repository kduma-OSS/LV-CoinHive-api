<?php
namespace KDuma\CoinHive;


/**
 * Class CoinHiveCaptchaDisplayer
 * @package KDuma\CoinHive
 */
class CoinHiveCaptchaDisplayer
{
    /**
     *
     */
    const CAPTCHA_JAVASCRIPT_URL = 'https://coinhive.com/lib/captcha.min.js';

    /**
     * @var string
     */
    protected $site_key;

    /**
     * @var int
     */
    private $default_required_hashes;

    /**
     * CoinHiveCaptchaDisplayer constructor.
     *
     * @param string $site_key
     * @param int $default_required_hashes
     */
    public function __construct($site_key, $default_required_hashes = 256)
    {
        $this->site_key = $site_key;
        $this->default_required_hashes = $default_required_hashes;
    }

    /**
     * @param null $required_hashes
     * @param array $attributes
     * @return string
     */
    public function display($required_hashes = null, $attributes = [])
    {
        $attributes['data-hashes'] = $required_hashes ?: $this->default_required_hashes;
        $attributes['data-key'] = $this->site_key;

        $html = '<script src="'.self::CAPTCHA_JAVASCRIPT_URL.'" async defer></script>'."\n";

        $html .= '<div class="coinhive-captcha"'.$this->buildAttributes($attributes).'>';
            $html .= '<em>';
                $html .= 'Loading Captcha...';
                $html .= '<br>';
                $html .= 'If it doesn\'t load, please disable Adblock!';
            $html .= '</em>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Build HTML attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function buildAttributes(array $attributes)
    {
        $html = [];
        foreach ($attributes as $key => $value) {
            if($value === false)
                $value = "false";

            if($value === true)
                $value = "true";

            $html[] = $key.'="'.$value.'"';
        }
        return count($html) ? ' '.implode(' ', $html) : '';
    }
}