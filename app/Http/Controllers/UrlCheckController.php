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
        $url = $this->findUrlOrFail($urlId);

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

        if ($response->body() !== '') {
            $document = new Document($response->body());

            try {
                $h1 = optional($document->first('h1'))->text();
                if (is_string($h1)) {
                    $h1 = mb_strimwidth($h1, 0, 252, '...');
                }
            } catch (Exception) {
                $h1 = null;
            }

            try {
                $keywords = optional($document->first('meta[name=keywords]'))->getAttribute('content');
                if (is_string($keywords)) {
                    $keywords = mb_strimwidth($keywords, 0, 252, '...');
                }
            } catch (Exception) {
                $keywords = null;
            }

            try {
                $description = optional($document->first('meta[name=description]'))->getAttribute('content');
                if (is_string($description)) {
                    $description = mb_strimwidth($description, 0, 252, '...');
                }
            } catch (Exception) {
                $description = null;
            }
        }

        DB::table('url_checks')->insert([
            'url_id' => $url->id,
            'status_code' => $response->status(),
            'h1' => $h1 ?? null,
            'description' => $description ?? null,
            'keywords' => $keywords ?? null,
        ]);

        return back()->with('success', 'Сайт проверен');
    }
}
