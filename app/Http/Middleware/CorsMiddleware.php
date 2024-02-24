<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Memanggil handler berikutnya dalam rantai middleware        
        $response = $next($request);

        // Menambahkan 'application/json' ke dalam header 'Access-Control-Allow-Headers' jika belum ada
        $supportedContentTypes = [
            'model/gltf-binary',
            'application/json',
            'text/html',
        ];

        // Mendapatkan jenis konten dari request header
        $contentType = $request->header('Content-Type');
        
        // Mendapatkan nilai header 'Access-Control-Allow-Headers' dari response
        $allowedHeaders = $response->headers->get('Access-Control-Allow-Headers');

        // Memeriksa apakah jenis konten yang didukung dan belum ada dalam header 'Access-Control-Allow-Headers'
        if (in_array($contentType, $supportedContentTypes) && !strpos($allowedHeaders, 'application/json')) {
            // Menambahkan 'application/json' ke dalam header 'Access-Control-Allow-Headers'
            $allowedHeaders .= ', application/json';
        }

        // Mengatur kembali header 'Access-Control-Allow-Headers'
        $response->headers->set('Access-Control-Allow-Headers', $allowedHeaders);

        // Mengatur header 'Access-Control-Allow-Origin' dan 'Access-Control-Allow-Methods'
        return $response
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}
