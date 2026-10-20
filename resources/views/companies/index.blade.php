@extends('layouts.app')

@section('title', 'Empresas')

@section('content')
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Empresas</h1>
            <p class="mt-1 text-sm text-slate-600">Cadastre clientes e gerencie medições de sinal Wi-Fi.</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg bg-white ring-1 ring-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3 font-medium">Nome</th>
                    <th class="px-4 py-3 font-medium">Responsável</th>
                    <th class="px-4 py-3 font-medium">Cômodos</th>
                    <th class="px-4 py-3 font-medium">Medições</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($companies as $company)
                    <tr>
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('empresas.show', $company) }}" class="text-teal-800 hover:underline">{{ $company->name }}</a>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $company->responsible_name ?: '–' }}</td>
                        <td class="px-4 py-3">{{ $company->rooms_count }}</td>
                        <td class="px-4 py-3">{{ $company->measurements_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('empresas.relatorios.show', $company) }}" class="text-teal-700 hover:underline">Relatórios</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">Nenhuma empresa cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
