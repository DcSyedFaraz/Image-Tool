<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Intervention\image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Symfony\Component\DomCrawler\Crawler;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $websiteUrl = 'https://www.linkedin.com';
        $client = new Client();
        $response = $client->get($websiteUrl);
        $html = $response->getBody()->getContents();

        // Use Symfony DomCrawler to extract the logo URL from the HTML
        $crawler = new Crawler($html);
        // dd($crawler);
        $logoUrl = $crawler->filter('link[rel="icon"]')->attr('href');

        // Combine relative URL with the base URL if needed
        $data['logoUrl'] = url($logoUrl, $websiteUrl);
        // dd($logoUrl);
        // Return the logo URL
        return view('newfile',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->input('file'));
        $image = $request->input('file');
        $manager = new ImageManager(new Driver());
        // read image from filesystem
        $images = $manager->read($image);
        $logoUrl = 'https://lumise.com/wp-content/uploads/2021/07/logo-remake-1.png';
        $images->insert($logoUrl, 'center');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
