<?php

declare(strict_types=1);

namespace Webkul\Support\Http\Controllers;

use Exception;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Cache;

final class ImageCacheController
{
    /**
     * Logo
     */
    public const string AUREUS_LOGO = 'https://updates.aureuserp.com/aureus.png';

    /**
     * Get HTTP response of template applied image file
     */
    public function getImage(): Illuminate\Http\Response
    {
        try {
            $content = Cache::remember('aureus-logo', 10080, fn (): string => base64_encode($this->getImageFromUrl(self::AUREUS_LOGO)));
        } catch (Exception) {
            $content = '';
        }

        return $this->buildResponse($content);
    }

    /**
     * Init from given URL
     */
    public function getImageFromUrl(string $url): string
    {
        $domain = config('app.url');

        $options = [
            'http' => [
                'method' => 'GET',
                'protocol_version' => 1.1, // force use HTTP 1.1 for service mesh environment with envoy
                'header' => "Accept-language: en\r\n".
                "Domain: $domain\r\n".
                "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36\r\n",
            ],
        ];

        $context = stream_context_create($options);

        if ($data = @file_get_contents($url, false, $context)) {
            return $data;
        }

        throw new Exception(
            'Unable to init from given url ('.$url.').'
        );
    }

    /**
     * Builds HTTP response from given image data
     *
     * @param  string  $content
     */
    private function buildResponse($content): Illuminate\Http\Response
    {
        $decodedContent = base64_decode($content, true);

        /**
         * Define mime type
         */
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $decodedContent);

        /**
         * Respond with 304 not modified if browser has the image cached
         */
        $eTag = md5($decodedContent);

        $notModified = isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === $eTag;

        $responseContent = $notModified ? null : $decodedContent;

        $statusCode = $notModified ? 304 : 200;

        /**
         * Return http response
         */
        return new IlluminateResponse($responseContent, $statusCode, [
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age=10080, public',
            'Content-Length' => mb_strlen($responseContent),
            'Etag' => $eTag,
        ]);
    }
}
