<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArrayResoruce;
use Illuminate\Http\Request;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class MainController extends Controller
{
    public function popular(){
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Edge/91.0.864.64',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Firefox/89.0'
        ];
        $randomUserAgent = $userAgents[array_rand($userAgents)];
        $browser = new HttpBrowser(HttpClient::create());
        $crawler = $browser->request('GET', 'https://komikindo2.com/');
        $data = $crawler->filter('.post-show.mangapopuler .odadingslider .animposx');

        $response= [];
        $result = $data->each(function(Crawler $v){
            return [
                'link' => $v->children('a')->attr('href'),
                'img' => $v->filter('.limit')->children('img')->attr('src'),
                'title' => $v->filter('.tt')->text(),
                'jenis' => $v->filter('.warnalabel')->text(),
                'type' => $v->filter('.typeflag ')->attr('class'),
                'chapter' => $v->filter('.lsch')->text(),
                'last_update' =>$v->filter('.datech')->text(),
            ];
        });
        return new ArrayResoruce(true, '', $result);
    }
}
