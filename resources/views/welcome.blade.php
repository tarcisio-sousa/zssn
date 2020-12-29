@extends('layouts.main')

@section('title', 'Zombie Network')

@section('content')

<h1>Zombie Survival Social Network! </h1>
<div class="text-end">
    <a href="/survivors/create" class="btn btn-primary btn-sm">
        <ion-icon name="add"></ion-icon> Adicionar
    </a>
</div>
<hr>
<div class="row">
    @foreach($survivors as $survivor)
    <div class="col-md-3">
        <div class="card mb-3 {{ $survivor->infected == 3 ? 'alert-warning' : '' }}">
            <div class="card-body">
                <div class="text-center">
                    <a href="/survivors/{{ $survivor->id }}">{{ $survivor->name }}</a>
                    <span>, {{ $survivor->age }}</span>
                    @if($survivor->infected == 3)
                    <span><ion-icon name="skull"></ion-icon></span>
                    @endif
                    <div>
                        <small>{{ $survivor->gender }}</small>
                    </div>
                </div>
                @if($survivor->infected != 3)
                <a href="/survivors/edit/{{ $survivor->id }}" title="Editar" class="btn btn-info btn-sm"><ion-icon name="create"></ion-icon></a>
                <a href="/survivors/edit/location/{{ $survivor->id }}" title="Atualizar localização" class="btn btn-info btn-sm"><ion-icon name="location-sharp"></ion-icon></a>
                <a href="/survivors/trade/{{ $survivor->id }}" title="Negociar recursos" class="btn btn-info btn-sm"><ion-icon name="cube"></ion-icon></a>
                <a href="/survivors/mark/{{ $survivor->id }}" title="Marcar como infectado" class="btn btn-warning btn-sm"><ion-icon name="skull"></ion-icon></a>
                @endif
                <form action="/survivors/{{ $survivor->id }}" method="POST" class="form-inline" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="x_x" class="btn btn-danger btn-sm"><ion-icon name="close-sharp"></ion-icon></button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection