<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait Url
{
    public function findUrlOrFail(int $id): object
    {
        $url = DB::table('urls')->find($id);
        abort_if(is_null($url), 404);

        return $url;
    }
}
