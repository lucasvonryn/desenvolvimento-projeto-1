@php($company = $company ?? null)
<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium">Nome da empresa</label>
        <input type="text" name="name" value="{{ old('name', $company?->name) }}" required class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Responsável</label>
        <input type="text" name="responsible_name" value="{{ old('responsible_name', $company?->responsible_name) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Telefone</label>
        <input type="text" name="responsible_phone" value="{{ old('responsible_phone', $company?->responsible_phone) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium">Endereço</label>
        <textarea name="address" rows="2" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('address', $company?->address) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium">Descrição do layout</label>
        <textarea name="layout_description" rows="3" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('layout_description', $company?->layout_description) }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Notebooks</label>
        <input type="number" min="0" name="notebooks_count" value="{{ old('notebooks_count', $company?->notebooks_count ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Smartphones</label>
        <input type="number" min="0" name="smartphones_count" value="{{ old('smartphones_count', $company?->smartphones_count ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Câmeras</label>
        <input type="number" min="0" name="cameras_count" value="{{ old('cameras_count', $company?->cameras_count ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Outros dispositivos</label>
        <input type="number" min="0" name="other_devices_count" value="{{ old('other_devices_count', $company?->other_devices_count ?? 0) }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium">Observações</label>
        <textarea name="notes" rows="2" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ old('notes', $company?->notes) }}</textarea>
    </div>
</div>
