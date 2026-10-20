<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    public function edit(Company $empresa): View
    {
        $equipment = $empresa->equipment;

        return view('equipment.edit', [
            'company' => $empresa,
            'equipment' => $equipment,
        ]);
    }

    public function update(Request $request, Company $empresa): RedirectResponse
    {
        $data = $request->validate([
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'technical_specs' => ['nullable', 'string'],
            'wifi_specs' => ['nullable', 'string'],
            'security_specs' => ['nullable', 'string'],
            'max_wifi_devices' => ['nullable', 'integer', 'min:0'],
            'recommended_devices' => ['nullable', 'integer', 'min:0'],
        ]);

        $empresa->equipment()->updateOrCreate(
            ['company_id' => $empresa->id],
            $data
        );

        return redirect()
            ->route('empresas.equipamento.edit', $empresa)
            ->with('success', 'Equipamento salvo com sucesso.');
    }
}
