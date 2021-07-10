<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller
{
    public function index(): View | ViewFactory
    {
        $urls = DB::table('urls')
            ->oldest()
            ->paginate();

        $lastChecks = DB::table('url_checks')
            ->distinct('url_id')
            ->whereIn('url_id', array_column($urls->items(), 'id'))
            ->get()
            ->keyBy('url_id');

        return view('urls.index', [
            'urls' => $urls,
            'last_url_checks' => $lastChecks
        ]);
    }

    public function show(int $id): View | ViewFactory
    {
        $url = $this->findUrlOrFail($id);

        $checks = DB::table('url_checks')
            ->where('url_id', $url->id)
            ->latest()
            ->get();

        return view('urls.show', [
            'url' => $url,
            'checks' => $checks,
        ]);
    }

    public function store(Request $request): Response
    {
        $formData = $request->input('url');
        $validator = app('validator')->make($formData, [
            'name' => 'required|url|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $urlParts = parse_url($formData['name']);
        $url = ($urlParts['scheme'] ?? 'http') . '://' . $urlParts['host'];

        $urlId = DB::table('urls')->select('id')
            ->where('name', '=', $url)
            ->value('id');

        if (is_null($urlId)) {
            $urlId = DB::table('urls')->insertGetId([
                'name' => $url,
            ]);
        }

        return redirect()->route('urls.show', ['url' => $urlId])->with('success', 'Сайт успешно добавлен');
    }
}
