<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        DB::table('urls')->insert([
            [
                'id' => 10,
                'name' => 'http://test.ru'
            ],
        ]);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testStoreBadRequest(): void
    {
        $response = $this->post(route('urls.store'), ['url' => ['name' => null]]);
        $response->assertSessionHasErrors(['name' => 'name обязательное поле.']);
        $response->assertRedirect('/');

        $response = $this->post(route('urls.store'), ['url' => ['name' => 'example']]);
        $response->assertSessionHasErrors(['name' => 'name должен быть в формате url.']);
        $response->assertRedirect('/');

        $this->assertDatabaseMissing('urls', ['name' => 'example']);
    }

    public function testStore(): void
    {
        $urlData = ['name' => 'http://abc'];
        $response = $this->post(route('urls.store'), ['url' => $urlData]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('urls', $urlData);
    }

    public function testShowNotFound(): void
    {
        $response = $this->get(route('urls.show', ['url' => 1]));
        $response->assertNotFound();
    }

    public function testShow(): void
    {
        $response = $this->get(route('urls.show', ['url' => 10]));
        $response->assertOk();
    }
}
