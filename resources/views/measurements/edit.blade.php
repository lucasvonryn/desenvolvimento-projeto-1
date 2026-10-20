@extends('layouts.app')

@section('title', 'Editar medição')

@section('content')
    <h1 class="mb-2 text-2xl font-semibold tracking-tight">{{ $company->name }}</h1>
    @include('partials.company-nav', ['company' => $company, 'current' => 'medicoes'])

    <form method="POST" action="{{ route('empresas.medicoes.update', [$company, $measurement]) }}" class="max-w-lg rounded-lg bg-white p-6 ring-1 ring-slate-200">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium">Cômodo</label>
            <select name="room_id" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected(old('room_id', $measurement->room_id) == $room->id)>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium">Data</label>
            <input type="date" name="measured_on" value="{{ old('measured_on', $measurement->measured_on->format('Y-m-d')) }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
        </div>
        <div class="mb-4">
            <label class="mb-1 block text-sm font-medium">Horário</label>
            <select name="time_slot" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                @foreach ($timeSlots as $slot)
                    <option value="{{ $slot }}" @selected(old('time_slot', $measurement->time_slot) === $slot)>{{ $slot }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium">2,4 GHz (Mbps)</label>
                <input type="number" step="0.1" min="0" name="speed_24_mbps" value="{{ old('speed_24_mbps', $measurement->speed_24_mbps) }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">5 GHz (Mbps)</label>
                <input type="number" step="0.1" min="0" name="speed_5_mbps" value="{{ old('speed_5_mbps', $measurement->speed_5_mbps) }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Atualizar</button>
            <a href="{{ route('empresas.medicoes.index', $company) }}" class="rounded-md px-4 py-2 text-sm ring-1 ring-slate-200">Cancelar</a>
        </div>
    </form>
@endsection
