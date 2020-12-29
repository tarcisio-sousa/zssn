@extends('layouts.main')

@section('title', 'Cadastrar sobrevivente')

@section('content')

<h1>Editando um sobrevivente</h1>

<form action="/survivors/update/{{ $survivor->id }}" method="POST" class="form">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" name="name" value="{{$survivor->name}}" id="name" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="age">Idade</label>
                <input type="text" name="age" value="{{$survivor->age}}" id="age" class="form-control">
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="gender">Sexo</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="">Selecione o gênero</option>
                    <option value="masculino" {{$survivor->gender == 'masculino' ? "selected='selected'" : ''}}>Masculino</option>
                    <option value="feminino" {{$survivor->gender == 'feminino' ? "selected='selected'" : ''}}>Feminino</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" name="longitude" value="{{$survivor->longitude}}" id="longitude" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" name="latitude" value="{{$survivor->latitude}}" id="latitude" class="form-control">
            </div>
        </div>
    </div>
    <hr>
    <button type="submit" class="btn btn-primary">Editar</button>
</form>

@endsection