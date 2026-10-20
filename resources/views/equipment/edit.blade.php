@extends('layouts.app')

@section('title', 'Equipamento – '.$company->name)

@section('content')
    <h1 class="mb-2 text-2xl font-semibold tracking-tight">{{ $company->name }}</h1>
    @include('partials.company-nav', ['company' => $company, 'current' => 'equipamento'])

    <form method="POST" action="{{ route('empresas.equipamento.update', $company) }}" class="rounded-lg bg-white p-6 ring-1 ring-slate-200">
        @csrf
        @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">Marca</label>
                <input type="text" name="brand" value="{{ old('brand', $equipment?->brand) }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Modelo</label>
                <input type="text" name="model" value="{{ old('model', $equipment?->model) }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Resumo</label>
                <textarea name="summary" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('summary', $equipment?->summary) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Especificações técnicas</label>
                <textarea name="technical_specs" rows="5" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('technical_specs', $equipment?->technical_specs) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Especificações Wi-Fi</label>
                <textarea name="wifi_specs" rows="5" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('wifi_specs', $equipment?->wifi_specs) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-medium">Segurança e criptografia</label>
                <textarea name="security_specs" rows="4" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('security_specs', $equipment?->security_specs) }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Máx. dispositivos Wi-Fi</label>
                <input type="number" min="0" name="max_wifi_devices" value="{{ old('max_wifi_devices', $equipment?->max_wifi_devices) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Recomendado (alto desempenho)</label>
                <input type="number" min="0" name="recommended_devices" value="{{ old('recommended_devices', $equipment?->recommended_devices) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <div class="mt-6">
            <button type="submit" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Salvar equipamento</button>
        </div>
    </form>
@endsection
