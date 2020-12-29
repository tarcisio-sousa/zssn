@extends('layouts.main')

@section('title', 'Localização sobrevivente')

@section('content')

<h1>Editando localização do sobrevivente</h1>

<form action="/survivors/update/{{ $survivor->id }}" method="POST" class="form">
    @csrf
    @method('PUT')
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