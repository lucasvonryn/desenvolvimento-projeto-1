@extends('layouts.app')

@section('title', 'Medições – '.$company->name)

@section('content')
    <h1 class="mb-2 text-2xl font-semibold tracking-tight">{{ $company->name }}</h1>
    @include('partials.company-nav', ['company' => $company, 'current' => 'medicoes'])

    <div class="mb-4 flex justify-between">
        <h2 class="text-lg font-medium">Medições</h2>
        <a href="{{ route('empresas.medicoes.create', $company) }}" class="rounded-md bg-teal-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-teal-800">Nova medição (lote)</a>
    </div>

    <div class="overflow-hidden rounded-lg bg-white ring-1 ring-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3 font-medium">Data</th>
                    <th class="px-4 py-3 font-medium">Horário</th>
                    <th class="px-4 py-3 font-medium">Cômodo</th>
                    <th class="px-4 py-3 font-medium">2,4 GHz</th>
                    <th class="px-4 py-3 font-medium">5 GHz</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($measurements as $measurement)
                    <tr>
                        <td class="px-4 py-3">{{ $measurement->measured_on->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $measurement->time_slot }}</td>
                        <td class="px-4 py-3">{{ $measurement->room->name }}</td>
                        <td class="px-4 py-3">{{ number_format($measurement->speed_24_mbps, 1, ',', '.') }} Mbps</td>
                        <td class="px-4 py-3">{{ number_format($measurement->speed_5_mbps, 1, ',', '.') }} Mbps</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('empresas.medicoes.edit', [$company, $measurement]) }}" class="text-teal-700 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('empresas.medicoes.destroy', [$company, $measurement]) }}" class="inline" onsubmit="return confirm('Remover esta medição?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-500">Nenhuma medição cadastrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $measurements->links() }}</div>
@endsection
