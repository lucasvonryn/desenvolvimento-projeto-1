@extends('layouts.app')

@section('title', 'Nova empresa')

@section('content')
    <h1 class="mb-6 text-2xl font-semibold tracking-tight">Nova empresa</h1>
    <form method="POST" action="{{ route('empresas.store') }}" class="rounded-lg bg-white p-6 ring-1 ring-slate-200">
        @csrf
        @include('companies._form')
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Salvar</button>
            <a href="{{ route('empresas.index') }}" class="rounded-md px-4 py-2 text-sm text-slate-600 ring-1 ring-slate-200 hover:bg-slate-50">Cancelar</a>
        </div>
    </form>
@endsection
