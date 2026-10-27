<?php

namespace Tests\Unit;

use App\Http\Middleware\CompressResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CompressResponseTest extends TestCase
{
    public function test_it_gzips_large_text_responses_when_accepted(): void
    {
        $content = str_repeat('<p>Invoice details and payment history.</p>', 100);
        $request = Request::create('/receptionist/invoices/1', 'GET', server: [
            'HTTP_ACCEPT_ENCODING' => 'gzip',
        ]);
        $response = new Response($content, Response::HTTP_OK, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);

        $compressedResponse = (new CompressResponse())->handle($request, fn () => $response);

        $this->assertSame('gzip', $compressedResponse->headers->get('Content-Encoding'));
        $this->assertSame('Accept-Encoding', $compressedResponse->headers->get('Vary'));
        $this->assertSame($content, gzdecode($compressedResponse->getContent()));
    }

    public function test_it_leaves_responses_uncompressed_when_gzip_is_not_accepted(): void
    {
        $request = Request::create('/receptionist/invoices/1', 'GET', server: [
            'HTTP_ACCEPT_ENCODING' => 'gzip;q=0, identity',
        ]);
        $response = new Response(str_repeat('invoice details ', 100), Response::HTTP_OK, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);

        $result = (new CompressResponse())->handle($request, fn () => $response);

        $this->assertNull($result->headers->get('Content-Encoding'));
        $this->assertSame('Accept-Encoding', $result->headers->get('Vary'));
    }
}
