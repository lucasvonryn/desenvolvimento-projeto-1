@extends('layouts.app')

@section('title', 'Novo cômodo')

@section('content')
    <h1 class="mb-2 text-2xl font-semibold tracking-tight">{{ $company->name }}</h1>
    @include('partials.company-nav', ['company' => $company, 'current' => 'comodos'])

    <form method="POST" action="{{ route('empresas.comodos.store', $company) }}" class="max-w-lg rounded-lg bg-white p-6 ring-1 ring-slate-200">
        @csrf
        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium">Nome</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium">Ordem</label>
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order') }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div class="flex gap-3">
            <button type="submit" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Salvar</button>
            <a href="{{ route('empresas.comodos.index', $company) }}" class="rounded-md px-4 py-2 text-sm ring-1 ring-slate-200">Cancelar</a>
        </div>
    </form>
@endsection
