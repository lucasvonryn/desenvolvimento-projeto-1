<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(): View
    {
        $companies = Company::query()
            ->withCount(['rooms', 'measurements'])
            ->orderBy('name')
            ->get();

        return view('companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $company = Company::query()->create($this->validated($request));

        return redirect()
            ->route('empresas.show', $company)
            ->with('success', 'Empresa cadastrada com sucesso.');
    }

    public function show(Company $empresa): View
    {
        $empresa->load(['rooms', 'equipment']);
        $empresa->loadCount('measurements');

        return view('companies.show', ['company' => $empresa]);
    }

    public function edit(Company $empresa): View
    {
        return view('companies.edit', ['company' => $empresa]);
    }

    public function update(Request $request, Company $empresa): RedirectResponse
    {
        $empresa->update($this->validated($request));

        return redirect()
            ->route('empresas.show', $empresa)
            ->with('success', 'Empresa atualizada com sucesso.');
    }

    public function destroy(Company $empresa): RedirectResponse
    {
        $empresa->delete();

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa removida com sucesso.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'responsible_name' => ['nullable', 'string', 'max:255'],
            'responsible_phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'layout_description' => ['nullable', 'string'],
            'notebooks_count' => ['nullable', 'integer', 'min:0'],
            'smartphones_count' => ['nullable', 'integer', 'min:0'],
            'cameras_count' => ['nullable', 'integer', 'min:0'],
            'other_devices_count' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['notebooks_count'] = (int) ($data['notebooks_count'] ?? 0);
        $data['smartphones_count'] = (int) ($data['smartphones_count'] ?? 0);
        $data['cameras_count'] = (int) ($data['cameras_count'] ?? 0);
        $data['other_devices_count'] = (int) ($data['other_devices_count'] ?? 0);

        return $data;
    }
}
