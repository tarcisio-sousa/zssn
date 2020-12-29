@extends('layouts.main')

@section('title', 'Zombie Network')

@section('content')

<h1>Sobrevivente! </h1>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    {{ $survivor->name }}
                    <span>, {{ $survivor->age }}</span>
                    @if(!$survivor->infected)
                    <span><ion-icon name="skull"></ion-icon></span>
                    @endif
                    <div>
                        <small>{{ $survivor->gender }}</small>
                    </div>
                </div>
                <a href="/survivors/edit/{{ $survivor->id }}" class="btn btn-warning"><ion-icon name="create-outline"></ion-icon></a>
                <form action="/survivors/{{ $survivor->id }}" method="POST" class="form-inline" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><ion-icon name="close-outline"></ion-icon></button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection