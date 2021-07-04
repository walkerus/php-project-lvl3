<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use DiDom\Document;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UrlCheckController extends Controller
{
    public function store(int $urlId): Response
    {
        $url = DB::table('urls')->where('id', $urlId)->first();

        if (is_null($url)) {
            abort(404);
        }

        try {
            $response = Http::get($url->name);
        } catch (ConnectionException) {
            return back()->with('error', 'Не удалось определить хост');
        } catch (Exception $ex) {
            Log::error('UrlCheckController: store', [
                'exp' => $ex
            ]);

            return back()->with('error', 'Ошибка при попытке получить ресурс');
        }

        if (!empty($response->body())) {
            $document = new Document($response->body());

            try {
                $headline = $document->first('h1')?->innerHtml();
            } catch (Exception) {
                $headline = null;
            }

            try {
                $keywords = $document->first('meta[name="keywords"]::attr(content)');
            } catch (Exception) {
                $keywords = null;
            }

            try {
                $description = $document->first('meta[name="description"]::attr(content)');
            } catch (Exception) {
                $description = null;
            }
        }

        DB::table('url_checks')->insert([
            'url_id' => $url->id,
            'status_code' => $response->status(),
            'h1' => $headline ?? null,
            'description' => $description ?? null,
            'keywords' => $keywords ?? null,
        ]);

        return back()->with('success', 'Сайт проверен');
    }
}
