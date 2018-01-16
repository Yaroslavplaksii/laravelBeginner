@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Добавить категорию
                <small>приятные слова..</small>
            </h1>
        </section>
        <section class="content">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Добавляем категорию</h3>
                </div>
                {!! Form::open(['route' => 'categories.store']) !!}
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Название</label>
                           @include('admin.errors')
                            <input type="text" name="title" class="form-control" id="exampleInputEmail1" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button class="btn btn-default">Назад</button>
                    <button class="btn btn-success pull-right">Добавить</button>
                </div>
                {!! Form::close() !!}
            </div>
        </section>
    </div>
@endsection