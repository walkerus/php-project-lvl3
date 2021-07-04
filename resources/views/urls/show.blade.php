@extends('layouts.master')
@section('content')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайт: {{ $url->name }}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody>
                <tr>
                    <td>ID</td>
                    <td>{{ $url->id }}</td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>{{ $url->name }}</td>
                </tr>
                <tr>
                    <td>Кот ответа последней проверки</td>
                    <td>{{ $checks->last()?->status_code ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Дата создания</td>
                    <td>{{ $url->created_at }}</td>
                </tr>
                <tr>
                    <td>Дата обновления</td>
                    <td>{{ $url->updated_at }}</td>
                </tr>
                </tbody>
            </table>
        </div>


        <h1 class="mt-5 mb-3">Проверки</h1>
        <form action="{{ route('urls.checks.store', ['url' => $url->id]) }}" method="post">
            @csrf
            <button type="submit" class="btn btn-primary">Запустить проверку</button>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Код ответа</th>
                    <th>h1</th>
                    <th>keywords</th>
                    <th>description</th>
                    <th>Дата создания</th>
                </tr>
                @foreach($checks ?? [] as $check)
                    <tr>
                        <th>{{ $check->id }}</th>
                        <th>{{ $check->status_code }}</th>
                        <th>{{ $check->h1 ?? '-' }}</th>
                        <th>{{ $check->keywords ?? '-' }}</th>
                        <th>{{ $check->description ?? '-' }}</th>
                        <th>{{ $check->created_at }}</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

