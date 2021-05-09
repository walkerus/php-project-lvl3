<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller
{
    public function index(): View
    {
        return view('urls.index', [
            'urls' => DB::table('urls')->paginate(15)
        ]);
    }

    public function show(int $id): View | Response
    {
        $urls = DB::table('urls')->where('id', $id)->get();

        if ($urls->isEmpty()) {
            abort(404);
        }

        return view('urls.show', [
            'url' => $urls->first()
        ]);
    }

    public function store(Request $request): Response
    {
        $request->validate([
            'url.name' => 'required|url',
        ]);

        $urlParts = parse_url($request->request->get('url')['name']);
        $url = ($urlParts['scheme'] ?? 'http') . '://' . $urlParts['host'];

        DB::table('urls')->insertOrIgnore([
            'name' => $url,
        ]);

        return back()->with('success', 'Сайт успешно добавлен');
    }
}
