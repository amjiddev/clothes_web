<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $this->isCompressible($response)) {
            return $response;
        }

        $this->varyByAcceptEncoding($response);

        if (
            $request->isMethod('HEAD')
            || $request->headers->has('Range')
            || $response->getStatusCode() !== Response::HTTP_OK
            || $response->headers->has('Content-Encoding')
            || ! $this->acceptsGzip($request)
        ) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || strlen($content) < 1024) {
            return $response;
        }

        $compressed = gzencode($content, 6);

        if (! is_string($compressed) || strlen($compressed) >= strlen($content)) {
            return $response;
        }

        $response->setContent($compressed);
        $response->headers->set('Content-Encoding', 'gzip');
        $response->headers->set('Content-Length', (string) strlen($compressed));
        $response->headers->remove('Content-MD5');

        return $response;
    }

    private function varyByAcceptEncoding(Response $response): void
    {
        $vary = $response->headers->get('Vary', '');

        if (! preg_match('/(?:^|,)\s*accept-encoding\s*(?:,|$)/i', $vary)) {
            $response->headers->set('Vary', $vary === '' ? 'Accept-Encoding' : $vary . ', Accept-Encoding');
        }
    }

    private function acceptsGzip(Request $request): bool
    {
        foreach (explode(',', $request->headers->get('Accept-Encoding', '')) as $encoding) {
            [$name, $parameters] = array_pad(explode(';', $encoding, 2), 2, '');

            if (strtolower(trim($name)) !== 'gzip') {
                continue;
            }

            if (preg_match('/(?:^|;)\s*q=([0-9.]+)/i', $parameters, $matches)) {
                return (float) $matches[1] > 0;
            }

            return true;
        }

        return false;
    }

    private function isCompressible(Response $response): bool
    {
        $contentType = strtolower(trim(explode(';', $response->headers->get('Content-Type', ''), 2)[0]));

        return str_starts_with($contentType, 'text/')
            || in_array($contentType, [
                'application/javascript',
                'application/json',
                'application/xml',
                'application/xhtml+xml',
                'image/svg+xml',
            ], true);
    }
}
