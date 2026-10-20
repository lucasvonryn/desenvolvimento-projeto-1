@extends('layouts.app')

@section('title', 'Relatórios – '.$company->name)

@section('content')
    <h1 class="mb-2 text-2xl font-semibold tracking-tight">{{ $company->name }}</h1>
    @include('partials.company-nav', ['company' => $company, 'current' => 'relatorios'])

    <form method="GET" action="{{ route('empresas.relatorios.show', $company) }}" class="mb-8 rounded-lg bg-white p-5 ring-1 ring-slate-200">
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Filtros</h2>
        <div class="mb-4">
            <p class="mb-2 text-sm font-medium">Dias de medição</p>
            <div class="flex flex-wrap gap-3">
                @forelse ($report['available_dates'] as $date)
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="dates[]" value="{{ $date }}" @checked(in_array($date, $report['selected_dates'], true))>
                        {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                    </label>
                @empty
                    <span class="text-sm text-slate-500">Nenhuma medição disponível.</span>
                @endforelse
            </div>
        </div>
        <div class="mb-4 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">Limiar alerta 2,4 GHz (Mbps)</label>
                <input type="number" step="0.1" name="threshold_24" value="{{ $report['thresholds']['threshold_24'] }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Limiar alerta 5 GHz (Mbps)</label>
                <input type="number" step="0.1" name="threshold_5" value="{{ $report['thresholds']['threshold_5'] }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Atualizar relatório</button>
            <button type="button" id="export-pdf" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-medium text-white hover:bg-teal-800">Exportar PDF</button>
        </div>
    </form>

    @if (empty($report['selected_dates']))
        <p class="text-sm text-slate-500">Selecione ao menos um dia com medições para gerar o relatório.</p>
    @else
        @foreach ($report['tables_by_date'] as $date => $slots)
            <section class="mb-8">
                <h2 class="mb-4 text-lg font-semibold">Medições – {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h2>
                @foreach ($slots as $slot => $rows)
                    <div class="mb-4 overflow-hidden rounded-lg bg-white ring-1 ring-slate-200">
                        <div class="border-b border-slate-100 bg-slate-50 px-4 py-2 text-sm font-medium">{{ $slot }}</div>
                        <table class="min-w-full divide-y divide-slate-100 text-sm">
                            <thead class="text-left text-slate-500">
                                <tr>
                                    <th class="px-4 py-2 font-medium">Local</th>
                                    <th class="px-4 py-2 font-medium">2,4 GHz</th>
                                    <th class="px-4 py-2 font-medium">5 GHz</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($rows as $row)
                                    <tr>
                                        <td class="px-4 py-2">{{ $row['room'] }}</td>
                                        <td class="px-4 py-2">{{ number_format($row['speed_24'], 1, ',', '.') }} Mbps</td>
                                        <td class="px-4 py-2">{{ number_format($row['speed_5'], 1, ',', '.') }} Mbps</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-3 text-slate-500">Sem dados neste horário.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endforeach

                <div class="overflow-hidden rounded-lg bg-white ring-1 ring-slate-200">
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-2 text-sm font-medium">Média do dia</div>
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="text-left text-slate-500">
                            <tr>
                                <th class="px-4 py-2 font-medium">Local</th>
                                <th class="px-4 py-2 font-medium">2,4 GHz</th>
                                <th class="px-4 py-2 font-medium">5 GHz</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($report['daily_averages'][$date] ?? [] as $row)
                                <tr>
                                    <td class="px-4 py-2">{{ $row['room'] }}</td>
                                    <td class="px-4 py-2">{{ number_format($row['speed_24'], 1, ',', '.') }} Mbps</td>
                                    <td class="px-4 py-2">{{ number_format($row['speed_5'], 1, ',', '.') }} Mbps</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endforeach

        <section class="mb-8 overflow-hidden rounded-lg bg-white ring-1 ring-slate-200">
            <div class="border-b border-slate-100 bg-slate-50 px-4 py-2 text-sm font-medium">Média entre os dias selecionados – Comparativo 2,4 vs 5 GHz</div>
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="text-left text-slate-500">
                    <tr>
                        <th class="px-4 py-2 font-medium">Local</th>
                        <th class="px-4 py-2 font-medium">2,4 GHz</th>
                        <th class="px-4 py-2 font-medium">5 GHz</th>
                        <th class="px-4 py-2 font-medium">Delta (5 − 2,4)</th>
                        <th class="px-4 py-2 font-medium">Razão (5 / 2,4)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($report['overall_averages'] as $row)
                        <tr>
                            <td class="px-4 py-2">{{ $row['room'] }}</td>
                            <td class="px-4 py-2">{{ number_format($row['speed_24'], 1, ',', '.') }} Mbps</td>
                            <td class="px-4 py-2">{{ number_format($row['speed_5'], 1, ',', '.') }} Mbps</td>
                            <td class="px-4 py-2">{{ number_format($row['delta'], 1, ',', '.') }} Mbps</td>
                            <td class="px-4 py-2">{{ $row['ratio'] !== null ? number_format($row['ratio'], 2, ',', '.') . 'x' : '–' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="mb-8 grid gap-6 md:grid-cols-2">
            <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
                <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Ranking 2,4 GHz (melhor → pior)</h3>
                <ol class="list-decimal space-y-1 pl-5 text-sm">
                    @foreach ($report['ranking']['best_24'] as $row)
                        <li>{{ $row['room'] }} – {{ number_format($row['speed_24'], 1, ',', '.') }} Mbps</li>
                    @endforeach
                </ol>
            </div>
            <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
                <h3 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-500">Ranking 5 GHz (melhor → pior)</h3>
                <ol class="list-decimal space-y-1 pl-5 text-sm">
                    @foreach ($report['ranking']['best_5'] as $row)
                        <li>{{ $row['room'] }} – {{ number_format($row['speed_5'], 1, ',', '.') }} Mbps</li>
                    @endforeach
                </ol>
            </div>
        </section>

        <section class="mb-8 grid gap-6 md:grid-cols-2">
            <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
                <h3 class="mb-3 text-sm font-semibold">Velocidades médias por cômodo</h3>
                <canvas id="chart-by-room" height="220"></canvas>
            </div>
            <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
                <h3 class="mb-3 text-sm font-semibold">Evolução temporal (média do dia)</h3>
                <canvas id="chart-temporal" height="220"></canvas>
            </div>
        </section>

        <section class="mb-8 grid gap-6 md:grid-cols-2">
            <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
                <h3 class="mb-1 text-sm font-semibold">Velocidade por horário do dia</h3>
                <p class="mb-3 text-xs text-slate-500">Média de todos os cômodos nos dias selecionados (08h / 13h / 17h).</p>
                <canvas id="chart-by-time-slot" height="220"></canvas>
            </div>
            <div class="rounded-lg bg-white p-5 ring-1 ring-slate-200">
                <h3 class="mb-1 text-sm font-semibold">Cômodos ao longo do dia — 5 GHz</h3>
                <p class="mb-3 text-xs text-slate-500">Média por horário em cada cômodo (dias selecionados).</p>
                <canvas id="chart-room-day-5" height="220"></canvas>
            </div>
        </section>

        <section class="mb-8 rounded-lg bg-white p-5 ring-1 ring-slate-200">
            <h3 class="mb-1 text-sm font-semibold">Cômodos ao longo do dia — 2,4 GHz</h3>
            <p class="mb-3 text-xs text-slate-500">Média por horário em cada cômodo (dias selecionados).</p>
            <canvas id="chart-room-day-24" height="180"></canvas>
        </section>

        <section class="mb-8 overflow-hidden rounded-lg bg-white ring-1 ring-slate-200">
            <div class="border-b border-slate-100 bg-slate-50 px-4 py-2 text-sm font-medium">
                Alertas – abaixo de {{ number_format($report['thresholds']['threshold_24'], 1, ',', '.') }} Mbps (2,4 GHz)
                ou {{ number_format($report['thresholds']['threshold_5'], 1, ',', '.') }} Mbps (5 GHz)
            </div>
            @if (empty($report['alerts']))
                <p class="px-4 py-4 text-sm text-slate-500">Nenhuma medição abaixo dos limiares configurados.</p>
            @else
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="text-left text-slate-500">
                        <tr>
                            <th class="px-4 py-2 font-medium">Data</th>
                            <th class="px-4 py-2 font-medium">Horário</th>
                            <th class="px-4 py-2 font-medium">Local</th>
                            <th class="px-4 py-2 font-medium">2,4 GHz</th>
                            <th class="px-4 py-2 font-medium">5 GHz</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($report['alerts'] as $alert)
                            <tr>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($alert['date'])->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $alert['time_slot'] }}</td>
                                <td class="px-4 py-2">{{ $alert['room'] }}</td>
                                <td class="px-4 py-2 {{ $alert['below_24'] ? 'font-semibold text-red-700' : '' }}">{{ number_format($alert['speed_24'], 1, ',', '.') }} Mbps</td>
                                <td class="px-4 py-2 {{ $alert['below_5'] ? 'font-semibold text-red-700' : '' }}">{{ number_format($alert['speed_5'], 1, ',', '.') }} Mbps</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    @endif

    <form id="pdf-form" method="POST" action="{{ route('empresas.relatorios.pdf', $company) }}" class="hidden">
        @csrf
        @foreach ($report['selected_dates'] as $date)
            <input type="hidden" name="dates[]" value="{{ $date }}">
        @endforeach
        <input type="hidden" name="threshold_24" value="{{ $report['thresholds']['threshold_24'] }}">
        <input type="hidden" name="threshold_5" value="{{ $report['thresholds']['threshold_5'] }}">
        <input type="hidden" name="chart_by_room" id="pdf-chart-by-room">
        <input type="hidden" name="chart_temporal" id="pdf-chart-temporal">
        <input type="hidden" name="chart_by_time_slot" id="pdf-chart-by-time-slot">
        <input type="hidden" name="chart_room_day_5" id="pdf-chart-room-day-5">
        <input type="hidden" name="chart_room_day_24" id="pdf-chart-room-day-24">
    </form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
    const chartByRoomData = @json($report['chart_by_room']);
    const chartTemporalData = @json($report['chart_temporal']);
    const chartByTimeSlotData = @json($report['chart_by_time_slot']);
    const chartRoomThroughDay = @json($report['chart_by_room_through_day']);

    const roomColors = ['#0f766e', '#0369a1', '#b45309', '#7c3aed', '#be123c', '#15803d', '#c2410c'];

    const roomCanvas = document.getElementById('chart-by-room');
    const temporalCanvas = document.getElementById('chart-temporal');
    const timeSlotCanvas = document.getElementById('chart-by-time-slot');
    const roomDay5Canvas = document.getElementById('chart-room-day-5');
    const roomDay24Canvas = document.getElementById('chart-room-day-24');

    const chartOpts = {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true, title: { display: true, text: 'Mbps' } } },
    };

    if (roomCanvas && chartByRoomData.labels?.length) {
        new Chart(roomCanvas, {
            type: 'bar',
            data: {
                labels: chartByRoomData.labels,
                datasets: [
                    { label: '2,4 GHz', data: chartByRoomData.speed_24, backgroundColor: '#0f766e' },
                    { label: '5 GHz', data: chartByRoomData.speed_5, backgroundColor: '#0369a1' },
                ],
            },
            options: chartOpts,
        });
    }

    if (temporalCanvas && chartTemporalData.labels?.length) {
        new Chart(temporalCanvas, {
            type: 'line',
            data: {
                labels: chartTemporalData.labels,
                datasets: [
                    { label: 'Média 2,4 GHz', data: chartTemporalData.avg_24, borderColor: '#0f766e', tension: 0.2 },
                    { label: 'Média 5 GHz', data: chartTemporalData.avg_5, borderColor: '#0369a1', tension: 0.2 },
                ],
            },
            options: chartOpts,
        });
    }

    if (timeSlotCanvas && chartByTimeSlotData.labels?.length) {
        new Chart(timeSlotCanvas, {
            type: 'bar',
            data: {
                labels: chartByTimeSlotData.labels,
                datasets: [
                    { label: '2,4 GHz', data: chartByTimeSlotData.avg_24, backgroundColor: '#0f766e' },
                    { label: '5 GHz', data: chartByTimeSlotData.avg_5, backgroundColor: '#0369a1' },
                ],
            },
            options: chartOpts,
        });
    }

    function roomSeriesDatasets(series) {
        return (series || []).map((item, index) => ({
            label: item.label,
            data: item.data,
            borderColor: roomColors[index % roomColors.length],
            backgroundColor: roomColors[index % roomColors.length],
            tension: 0.25,
            spanGaps: true,
        }));
    }

    if (roomDay5Canvas && chartRoomThroughDay.labels?.length) {
        new Chart(roomDay5Canvas, {
            type: 'line',
            data: {
                labels: chartRoomThroughDay.labels,
                datasets: roomSeriesDatasets(chartRoomThroughDay.series_5),
            },
            options: chartOpts,
        });
    }

    if (roomDay24Canvas && chartRoomThroughDay.labels?.length) {
        new Chart(roomDay24Canvas, {
            type: 'line',
            data: {
                labels: chartRoomThroughDay.labels,
                datasets: roomSeriesDatasets(chartRoomThroughDay.series_24),
            },
            options: chartOpts,
        });
    }

    document.getElementById('export-pdf')?.addEventListener('click', () => {
        document.getElementById('pdf-chart-by-room').value = roomCanvas ? roomCanvas.toDataURL('image/png') : '';
        document.getElementById('pdf-chart-temporal').value = temporalCanvas ? temporalCanvas.toDataURL('image/png') : '';
        document.getElementById('pdf-chart-by-time-slot').value = timeSlotCanvas ? timeSlotCanvas.toDataURL('image/png') : '';
        document.getElementById('pdf-chart-room-day-5').value = roomDay5Canvas ? roomDay5Canvas.toDataURL('image/png') : '';
        document.getElementById('pdf-chart-room-day-24').value = roomDay24Canvas ? roomDay24Canvas.toDataURL('image/png') : '';
        document.getElementById('pdf-form').submit();
    });
</script>
@endpush
