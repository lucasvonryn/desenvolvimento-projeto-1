@extends('layouts.app')

@section('title', 'Nova medição')

@section('content')
    <h1 class="mb-2 text-2xl font-semibold tracking-tight">{{ $company->name }}</h1>
    @include('partials.company-nav', ['company' => $company, 'current' => 'medicoes'])

    @if ($rooms->isEmpty())
        <p class="rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
            Cadastre cômodos antes de lançar medições.
            <a href="{{ route('empresas.comodos.create', $company) }}" class="underline">Criar cômodo</a>
        </p>
    @else
        <form method="POST" action="{{ route('empresas.medicoes.store', $company) }}" class="rounded-lg bg-white p-6 ring-1 ring-slate-200">
            @csrf
            <div class="mb-6 grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium">Data</label>
                    <input type="date" name="measured_on" value="{{ old('measured_on', now()->toDateString()) }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium">Horário</label>
                    <select name="time_slot" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        @foreach ($timeSlots as $slot)
                            <option value="{{ $slot }}" @selected(old('time_slot') === $slot)>{{ $slot }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Velocidades por cômodo (Mbps)</h3>
            <div class="overflow-hidden rounded-md ring-1 ring-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left">
                        <tr>
                            <th class="px-3 py-2 font-medium">Cômodo</th>
                            <th class="px-3 py-2 font-medium">2,4 GHz</th>
                            <th class="px-3 py-2 font-medium">5 GHz</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($rooms as $index => $room)
                            <tr>
                                <td class="px-3 py-2">
                                    {{ $room->name }}
                                    <input type="hidden" name="speeds[{{ $index }}][room_id]" value="{{ $room->id }}">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.1" min="0" name="speeds[{{ $index }}][speed_24_mbps]" value="{{ old('speeds.'.$index.'.speed_24_mbps') }}" class="w-full rounded-md border border-slate-300 px-2 py-1.5">
                                </td>
                                <td class="px-3 py-2">
                                    <input type="number" step="0.1" min="0" name="speeds[{{ $index }}][speed_5_mbps]" value="{{ old('speeds.'.$index.'.speed_5_mbps') }}" class="w-full rounded-md border border-slate-300 px-2 py-1.5">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="mt-3 text-xs text-slate-500">Deixe em branco os cômodos sem medição. Se já existir medição no mesmo dia/horário/cômodo, ela será atualizada.</p>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Salvar</button>
                <a href="{{ route('empresas.medicoes.index', $company) }}" class="rounded-md px-4 py-2 text-sm ring-1 ring-slate-200">Cancelar</a>
            </div>
        </form>
    @endif
@endsection
