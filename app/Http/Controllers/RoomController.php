<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Company $empresa): View
    {
        $rooms = $empresa->rooms()->withCount('measurements')->get();

        return view('rooms.index', ['company' => $empresa, 'rooms' => $rooms]);
    }

    public function create(Company $empresa): View
    {
        return view('rooms.create', ['company' => $empresa]);
    }

    public function store(Request $request, Company $empresa): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $empresa->rooms()->create([
            'name' => $data['name'],
            'sort_order' => $data['sort_order'] ?? (($empresa->rooms()->max('sort_order') ?? 0) + 1),
        ]);

        return redirect()
            ->route('empresas.comodos.index', $empresa)
            ->with('success', 'Cômodo cadastrado com sucesso.');
    }

    public function edit(Company $empresa, Room $comodo): View
    {
        abort_unless($comodo->company_id === $empresa->id, 404);

        return view('rooms.edit', ['company' => $empresa, 'room' => $comodo]);
    }

    public function update(Request $request, Company $empresa, Room $comodo): RedirectResponse
    {
        abort_unless($comodo->company_id === $empresa->id, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $comodo->update([
            'name' => $data['name'],
            'sort_order' => $data['sort_order'] ?? $comodo->sort_order,
        ]);

        return redirect()
            ->route('empresas.comodos.index', $empresa)
            ->with('success', 'Cômodo atualizado com sucesso.');
    }

    public function destroy(Company $empresa, Room $comodo): RedirectResponse
    {
        abort_unless($comodo->company_id === $empresa->id, 404);

        $comodo->delete();

        return redirect()
            ->route('empresas.comodos.index', $empresa)
            ->with('success', 'Cômodo removido com sucesso.');
    }
}
