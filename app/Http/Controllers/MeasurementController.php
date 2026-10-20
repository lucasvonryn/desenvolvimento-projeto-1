<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Measurement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MeasurementController extends Controller
{
    public function index(Company $empresa): View
    {
        $measurements = $empresa->measurements()
            ->with('room')
            ->orderByDesc('measured_on')
            ->orderBy('time_slot')
            ->orderBy('room_id')
            ->paginate(30);

        return view('measurements.index', [
            'company' => $empresa,
            'measurements' => $measurements,
        ]);
    }

    public function create(Company $empresa): View
    {
        $rooms = $empresa->rooms;

        return view('measurements.create', [
            'company' => $empresa,
            'rooms' => $rooms,
            'timeSlots' => Measurement::TIME_SLOTS,
        ]);
    }

    public function store(Request $request, Company $empresa): RedirectResponse
    {
        $data = $request->validate([
            'measured_on' => ['required', 'date'],
            'time_slot' => ['required', Rule::in(Measurement::TIME_SLOTS)],
            'speeds' => ['required', 'array'],
            'speeds.*.room_id' => ['required', 'integer', Rule::exists('rooms', 'id')->where('company_id', $empresa->id)],
            'speeds.*.speed_24_mbps' => ['nullable', 'numeric', 'min:0'],
            'speeds.*.speed_5_mbps' => ['nullable', 'numeric', 'min:0'],
        ]);

        foreach ($data['speeds'] as $row) {
            if ($row['speed_24_mbps'] === null && $row['speed_5_mbps'] === null) {
                continue;
            }

            if ($row['speed_24_mbps'] === null || $row['speed_5_mbps'] === null) {
                return back()
                    ->withInput()
                    ->withErrors(['speeds' => 'Informe as duas bandas (2,4 e 5 GHz) para cada cômodo preenchido.']);
            }

            Measurement::query()->updateOrCreate(
                [
                    'room_id' => $row['room_id'],
                    'measured_on' => $data['measured_on'],
                    'time_slot' => $data['time_slot'],
                ],
                [
                    'company_id' => $empresa->id,
                    'speed_24_mbps' => $row['speed_24_mbps'],
                    'speed_5_mbps' => $row['speed_5_mbps'],
                ]
            );
        }

        return redirect()
            ->route('empresas.medicoes.index', $empresa)
            ->with('success', 'Medições salvas com sucesso.');
    }

    public function edit(Company $empresa, Measurement $medicao): View
    {
        abort_unless($medicao->company_id === $empresa->id, 404);

        return view('measurements.edit', [
            'company' => $empresa,
            'measurement' => $medicao,
            'rooms' => $empresa->rooms,
            'timeSlots' => Measurement::TIME_SLOTS,
        ]);
    }

    public function update(Request $request, Company $empresa, Measurement $medicao): RedirectResponse
    {
        abort_unless($medicao->company_id === $empresa->id, 404);

        $data = $request->validate([
            'room_id' => ['required', 'integer', Rule::exists('rooms', 'id')->where('company_id', $empresa->id)],
            'measured_on' => ['required', 'date'],
            'time_slot' => ['required', Rule::in(Measurement::TIME_SLOTS)],
            'speed_24_mbps' => ['required', 'numeric', 'min:0'],
            'speed_5_mbps' => ['required', 'numeric', 'min:0'],
        ]);

        $medicao->update($data);

        return redirect()
            ->route('empresas.medicoes.index', $empresa)
            ->with('success', 'Medição atualizada com sucesso.');
    }

    public function destroy(Company $empresa, Measurement $medicao): RedirectResponse
    {
        abort_unless($medicao->company_id === $empresa->id, 404);

        $medicao->delete();

        return redirect()
            ->route('empresas.medicoes.index', $empresa)
            ->with('success', 'Medição removida com sucesso.');
    }
}
