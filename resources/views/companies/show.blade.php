@extends('layouts.app')

@section('title', $company->name)

@section('content')
    <div class="mb-4 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">{{ $company->name }}</h1>
            <p class="mt-1 text-sm text-slate-600">{{ $company->responsible_name ?: 'Sem responsável informado' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('empresas.edit', $company) }}" class="rounded-md px-3 py-1.5 text-sm ring-1 ring-slate-200 hover:bg-white">Editar</a>
            <form method="POST" action="{{ route('empresas.destroy', $company) }}" onsubmit="return confirm('Remover esta empresa e todos os dados relacionados?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-md px-3 py-1.5 text-sm text-red-700 ring-1 ring-red-200 hover:bg-red-50">Excluir</button>
            </form>
        </div>
    </div>

    @include('partials.company-nav', ['company' => $company, 'current' => 'resumo'])

    <div class="grid gap-6 md:grid-cols-2">
        <section class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Dados do cliente</h2>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-slate-500">Endereço</dt><dd>{{ $company->address ?: '–' }}</dd></div>
                <div><dt class="text-slate-500">Layout</dt><dd class="whitespace-pre-line">{{ $company->layout_description ?: '–' }}</dd></div>
                <div><dt class="text-slate-500">Observações</dt><dd class="whitespace-pre-line">{{ $company->notes ?: '–' }}</dd></div>
            </dl>
        </section>

        <section class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Dispositivos</h2>
            <ul class="space-y-1 text-sm">
                <li>Notebooks: <strong>{{ $company->notebooks_count }}</strong></li>
                <li>Smartphones: <strong>{{ $company->smartphones_count }}</strong></li>
                <li>Câmeras: <strong>{{ $company->cameras_count }}</strong></li>
                <li>Outros: <strong>{{ $company->other_devices_count }}</strong></li>
                <li class="pt-2 border-t border-slate-100">Total: <strong>{{ $company->totalDevices() }}</strong></li>
            </ul>
            <p class="mt-4 text-sm text-slate-600">{{ $company->measurements_count }} medições – {{ $company->rooms->count() }} cômodos</p>
        </section>

        <section class="rounded-lg bg-white p-5 ring-1 ring-slate-200 md:col-span-2">
            <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Equipamento</h2>
            @if ($company->equipment)
                <p class="text-sm"><strong>{{ $company->equipment->brand }}</strong> – {{ $company->equipment->model }}</p>
                <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($company->equipment->summary, 280) }}</p>
            @else
                <p class="text-sm text-slate-500">Nenhum equipamento cadastrado. <a href="{{ route('empresas.equipamento.edit', $company) }}" class="text-teal-700 hover:underline">Cadastrar</a></p>
            @endif
        </section>
    </div>
@endsection
