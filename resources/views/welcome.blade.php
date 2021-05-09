@extends('layouts.master')
@section('content')
    <div class="jumbotron jumbotron-fluid bg-dark">
        <div class="container-lg">
            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 mx-auto text-white">
                    <h1 class="display-3">Анализатор страниц</h1>
                    <form action="{{ route('urls.store') }}" method="post" class="d-flex justify-content-center">
                        @csrf
                        <input type="text" name="url[name]" value="" class="form-control form-control-lg" placeholder="https://www.example.com">
                        <button type="submit" class="btn btn-lg btn-primary ml-3 px-5 text-uppercase">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
