@extends('layouts.app')

@section('title', 'Cômodos – '.$company->name)

@section('content')
    <h1 class="mb-2 text-2xl font-semibold tracking-tight">{{ $company->name }}</h1>
    @include('partials.company-nav', ['company' => $company, 'current' => 'comodos'])

    <div class="mb-4 flex justify-between">
        <h2 class="text-lg font-medium">Cômodos</h2>
        <a href="{{ route('empresas.comodos.create', $company) }}" class="rounded-md bg-teal-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-teal-800">Novo cômodo</a>
    </div>

    <div class="overflow-hidden rounded-lg bg-white ring-1 ring-slate-200">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3 font-medium">Ordem</th>
                    <th class="px-4 py-3 font-medium">Nome</th>
                    <th class="px-4 py-3 font-medium">Medições</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($rooms as $room)
                    <tr>
                        <td class="px-4 py-3">{{ $room->sort_order }}</td>
                        <td class="px-4 py-3 font-medium">{{ $room->name }}</td>
                        <td class="px-4 py-3">{{ $room->measurements_count }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            <a href="{{ route('empresas.comodos.edit', [$company, $room]) }}" class="text-teal-700 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('empresas.comodos.destroy', [$company, $room]) }}" class="inline" onsubmit="return confirm('Remover este cômodo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-slate-500">Nenhum cômodo cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
