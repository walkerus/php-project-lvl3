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
                        <th>{{ $url->last_check ?? '-' }}</th>
                        <th>{{ $url->last_check_code ?? '-' }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $urls->links() }}
    </div>
@stop

