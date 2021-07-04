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
        $latestUrlChecksQuery = DB::table('url_checks')
            ->select(['url_checks.url_id', 'url_checks.status_code', 'url_checks.created_at'])
            ->distinct('url_checks.url_id')
            ->orderBy('url_checks.url_id')
            ->latest();

        return view('urls.index', [
            'urls' => DB::table('urls')
                ->addSelect('urls.id')
                ->addSelect('urls.name')
                ->addSelect('url_checks.created_at AS last_check')
                ->addSelect('url_checks.status_code AS last_check_code')
                ->leftJoinSub($latestUrlChecksQuery, 'url_checks', 'urls.id', '=', 'url_checks.url_id')
                ->paginate()
        ]);
    }

    public function show(int $id): View | ViewFactory
    {
        $url = DB::table('urls')->where('id', $id)->first();

        if (is_null($url)) {
            abort(404);
        }

        $checks = DB::table('url_checks')
            ->where('url_id', $url->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('urls.show', [
            'url' => $url,
            'checks' => $checks,
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
