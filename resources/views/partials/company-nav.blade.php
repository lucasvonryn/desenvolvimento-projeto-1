@php
    $current = $current ?? '';
@endphp
<nav class="mb-6 flex flex-wrap gap-2 text-sm">
    <a href="{{ route('empresas.show', $company) }}"
       class="rounded-md px-3 py-1.5 {{ $current === 'resumo' ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-100' }}">
        Resumo
    </a>
    <a href="{{ route('empresas.comodos.index', $company) }}"
       class="rounded-md px-3 py-1.5 {{ $current === 'comodos' ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-100' }}">
        Cômodos
    </a>
    <a href="{{ route('empresas.equipamento.edit', $company) }}"
       class="rounded-md px-3 py-1.5 {{ $current === 'equipamento' ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-100' }}">
        Equipamento
    </a>
    <a href="{{ route('empresas.medicoes.index', $company) }}"
       class="rounded-md px-3 py-1.5 {{ $current === 'medicoes' ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-100' }}">
        Medições
    </a>
    <a href="{{ route('empresas.relatorios.show', $company) }}"
       class="rounded-md px-3 py-1.5 {{ $current === 'relatorios' ? 'bg-slate-900 text-white' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-100' }}">
        Relatórios
    </a>
</nav>
