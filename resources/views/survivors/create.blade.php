@extends('layouts.main')

@section('title', 'Cadastrar sobrevivente')

@section('content')

<h1>Crie um sobrevivente</h1>

<form action="/survivors" method="POST" class="form">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" name="name" id="name" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="age">Idade</label>
                <input type="text" name="age" id="age" class="form-control">
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="gender">Sexo</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="">Selecione o gênero</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-control">
            </div>
        </div>
    </div>

    <hr>

    <h4>Inventário</h4>
    <ul class="list-group">
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-10">
                </div>
                <div class="col-md-2">
                    Quantidade
                </div>
            </div>
        @foreach($inventory as $resource)
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-10">
                    <label for="resource-{{ $loop->index }}">{{ $resource->item }} - {{ $resource->points }} Pontos</label>
                </div>
                <div class="col-md-1">
                    <input type="number" value="0" name="resource[{{$resource->id}}]" class="form-control" />
                </div>
            </div>
        </li>
        @endforeach
    </ul>

    <hr>

    <button type="submit" class="btn btn-primary">Cadastrar</button>
</form>

@endsection