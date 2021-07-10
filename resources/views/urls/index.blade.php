@extends('layouts.master')
@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайты</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Последняя проверка</th>
                    <th>Код ответа</th>
                </tr>
                @foreach($urls as $url)
                    <tr>
                        <th>{{ $url->id  }}</th>
                        <th><a href="{{ route('urls.show', ['url' => $url->id]) }}">{{ $url->name }}</a></th>
                        <th>{{ $last_url_checks[$url->id]['created_at'] ?? '-' }}</th>
                        <th>{{ $last_url_checks[$url->id]['status_code'] ?? '-' }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $urls->links() }}
    </div>
@stop

