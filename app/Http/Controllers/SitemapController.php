<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        // Buat konten sitemap
        $content = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            <url>
                <loc>' . url('/') . '</loc>
                <lastmod>' . date('Y-m-d') . '</lastmod>
                <changefreq>daily</changefreq>
                <priority>1.0</priority>
            </url>
            <!-- Tambahkan URL lain sesuai kebutuhan -->
        </urlset>';

        // Return response dengan content type XML
        return response($content)
            ->header('Content-Type', 'application/xml');
    }
} 