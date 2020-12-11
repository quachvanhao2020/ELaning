<?php
use Stichoza\GoogleTranslate\GoogleTranslate as BGoogleTranslate;
use GuzzleHttp\Client;
use Stichoza\GoogleTranslate\Tokens\GoogleTokenGenerator;
use Stichoza\GoogleTranslate\Tokens\TokenProviderInterface;
require_once __DIR__."/../vendor/autoload.php";
class GoogleTranslate extends BGoogleTranslate {
    /**
     * Class constructor.
     *
     * For more information about HTTP client configuration options, see "Request Options" in
     * GuzzleHttp docs: http://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @param string $target Target language
     * @param string|null $source Source language
     * @param array|null $options Associative array of http client configuration options
     * @param TokenProviderInterface|null $tokenProvider
     */
    public function __construct(string $target = 'en', string $source = null, array $options = null, TokenProviderInterface $tokenProvider = null)
    {
        parent::__construct($target,$source,$options,$tokenProvider);
        $this->client = new Client([
            "curl" => [
                CURLOPT_SSL_VERIFYPEER => false
            ]
        ]);
    }

    
    /**
     * Override translate method for static call.
     *
     * @param string $string
     * @param string $target
     * @param string|null $source
     * @param array $options
     * @param TokenProviderInterface|null $tokenProvider
     * @return null|string
     * @throws ErrorException If the HTTP request fails
     * @throws UnexpectedValueException If received data cannot be decoded
     */
    public static function trans(string $string, string $target = 'en', string $source = null, array $options = [], TokenProviderInterface $tokenProvider = null): ?string
    {
        return (new self)
            ->setTokenProvider($tokenProvider ?? new GoogleTokenGenerator)
            ->setOptions($options) // Options are already set in client constructor tho.
            ->setSource($source)
            ->setTarget($target)
            ->translate($string);
    }
}
echo GoogleTranslate::trans('Hello again', 'vi', 'en');