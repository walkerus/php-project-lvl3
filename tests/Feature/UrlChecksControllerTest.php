<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UrlChecksControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('urls')->insert([
            [
                'id' => 1,
                'name' => 'http://test.ru'
            ],
            [
                'id' => 3,
                'name' => 'http://test.su'
            ],
            [
                'id' => 4,
                'name' => 'http://test.ua'
            ],
        ]);
    }

    public function testStoreNotFound(): void
    {
        $response = $this->post(route('urls.checks.store', ['url' => 2]), []);
        $response->assertNotFound();

        $this->assertDatabaseMissing('url_checks', ['url_id' => 2]);
    }

    public function testStore(): void
    {
        Http::fake([
            'http://test.ru' => Http::response(),
            'http://test.su' => Http::response(null, 404),
            'http://test.ua' => Http::response(
                implode('', [
                    '<h1>Headline</h1>',
                    '<meta name="keywords" content="keywords">',
                    '<meta name="description" content="description">'
                ]),
            ),
        ]);

        $response = $this->post(route('urls.checks.store', ['url' => 1]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');

        $this->assertDatabaseHas('url_checks', ['url_id' => 1, 'status_code' => 200]);

        $response = $this->post(route('urls.checks.store', ['url' => 3]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');

        $this->assertDatabaseHas('url_checks', ['url_id' => 3, 'status_code' => 404]);

        $response = $this->post(route('urls.checks.store', ['url' => 4]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/');

        $this->assertDatabaseHas('url_checks', [
            'url_id' => 4,
            'status_code' => 200,
            'h1' => 'Headline',
            'description' => 'description',
            'keywords' => 'keywords'
        ]);
    }
}
