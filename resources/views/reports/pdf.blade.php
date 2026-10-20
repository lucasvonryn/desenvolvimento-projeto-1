<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatório Wi-Fi – {{ $company->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; margin: 28px; }
        h1 { font-size: 18px; margin: 0 0 6px; }
        h2 { font-size: 13px; margin: 18px 0 8px; border-bottom: 1px solid #333; padding-bottom: 3px; }
        h3 { font-size: 12px; margin: 12px 0 6px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #111; padding-bottom: 12px; }
        .header small { display: block; font-size: 10px; line-height: 1.4; }
        .meta { margin-bottom: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #555; padding: 4px 6px; text-align: left; }
        th { background: #eee; }
        .muted { color: #444; }
        .chart { margin: 10px 0 16px; text-align: center; }
        .chart img { max-width: 100%; height: auto; }
        .page-break { page-break-before: always; }
        ul { margin: 4px 0 10px 16px; padding: 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Análise da rede Wi-Fi – {{ $company->name }}</h1>
        <small>Relatório gerado em {{ now()->format('d/m/Y H:i') }}</small>
    </div>

    <h2>1. Dados do cliente</h2>
    <div class="meta">
        <p><strong>Empresa:</strong> {{ $company->name }}</p>
        <p><strong>Responsável:</strong> {{ $company->responsible_name ?: '–' }}</p>
        <p><strong>Endereço:</strong> {{ $company->address ?: '–' }}</p>
        <p><strong>Layout:</strong> {{ $company->layout_description ?: '–' }}</p>
        <p><strong>Dispositivos:</strong>
            {{ $company->notebooks_count }} notebooks,
            {{ $company->smartphones_count }} smartphones,
            {{ $company->cameras_count }} câmeras,
            {{ $company->other_devices_count }} outros
            (total {{ $company->totalDevices() }}).
        </p>
        @if ($company->notes)
            <p><strong>Observações:</strong> {{ $company->notes }}</p>
        @endif
    </div>

    <h2>2. Equipamento</h2>
    @if ($company->equipment)
        <p><strong>{{ $company->equipment->brand }}</strong> – {{ $company->equipment->model }}</p>
        <p class="muted">{{ $company->equipment->summary }}</p>
        @if ($company->equipment->technical_specs)
            <h3>Especificações técnicas</h3>
            <p style="white-space: pre-line;">{{ $company->equipment->technical_specs }}</p>
        @endif
        @if ($company->equipment->wifi_specs)
            <h3>Wi-Fi</h3>
            <p style="white-space: pre-line;">{{ $company->equipment->wifi_specs }}</p>
        @endif
        @if ($company->equipment->security_specs)
            <h3>Segurança</h3>
            <p style="white-space: pre-line;">{{ $company->equipment->security_specs }}</p>
        @endif
        <p>
            Capacidade máxima: {{ $company->equipment->max_wifi_devices ?: '–' }} dispositivos.
            Recomendado (alto desempenho): {{ $company->equipment->recommended_devices ?: '–' }}.
        </p>
    @else
        <p class="muted">Equipamento não cadastrado.</p>
    @endif

    <div class="page-break"></div>
    <h2>3. Medição da velocidade (Mbps)</h2>

    @foreach ($report['tables_by_date'] as $date => $slots)
        <h3>{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</h3>
        @foreach ($slots as $slot => $rows)
            <p><strong>{{ $slot }}</strong></p>
            <table>
                <thead>
                    <tr>
                        <th>Local</th>
                        <th>Velocidade 2,4 GHz</th>
                        <th>Velocidade 5 GHz</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td>{{ $row['room'] }}</td>
                            <td>{{ number_format($row['speed_24'], 1, ',', '.') }} Mbps</td>
                            <td>{{ number_format($row['speed_5'], 1, ',', '.') }} Mbps</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <p><strong>Média do dia</strong></p>
        <table>
            <thead>
                <tr>
                    <th>Local</th>
                    <th>2,4 GHz</th>
                    <th>5 GHz</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report['daily_averages'][$date] ?? [] as $row)
                    <tr>
                        <td>{{ $row['room'] }}</td>
                        <td>{{ number_format($row['speed_24'], 1, ',', '.') }} Mbps</td>
                        <td>{{ number_format($row['speed_5'], 1, ',', '.') }} Mbps</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <h2>4. Média entre os dias selecionados</h2>
    <table>
        <thead>
            <tr>
                <th>Local</th>
                <th>2,4 GHz</th>
                <th>5 GHz</th>
                <th>Delta</th>
                <th>Razão</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report['overall_averages'] as $row)
                <tr>
                    <td>{{ $row['room'] }}</td>
                    <td>{{ number_format($row['speed_24'], 1, ',', '.') }} Mbps</td>
                    <td>{{ number_format($row['speed_5'], 1, ',', '.') }} Mbps</td>
                    <td>{{ number_format($row['delta'], 1, ',', '.') }} Mbps</td>
                    <td>{{ $row['ratio'] !== null ? number_format($row['ratio'], 2, ',', '.') . 'x' : '–' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>5. Ranking de cômodos</h2>
    <p><strong>2,4 GHz (melhor → pior)</strong></p>
    <ul>
        @foreach ($report['ranking']['best_24'] as $row)
            <li>{{ $row['room'] }} – {{ number_format($row['speed_24'], 1, ',', '.') }} Mbps</li>
        @endforeach
    </ul>
    <p><strong>5 GHz (melhor → pior)</strong></p>
    <ul>
        @foreach ($report['ranking']['best_5'] as $row)
            <li>{{ $row['room'] }} – {{ number_format($row['speed_5'], 1, ',', '.') }} Mbps</li>
        @endforeach
    </ul>

    <h2>6. Evolução temporal</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Média 2,4 GHz</th>
                <th>Média 5 GHz</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report['temporal'] as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row['date'])->format('d/m/Y') }}</td>
                    <td>{{ number_format($row['avg_24'], 1, ',', '.') }} Mbps</td>
                    <td>{{ number_format($row['avg_5'], 1, ',', '.') }} Mbps</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!empty($chartByRoom))
        <div class="chart">
            <h3>Gráfico – médias por cômodo</h3>
            <img src="{{ $chartByRoom }}" alt="Gráfico por cômodo">
        </div>
    @endif

    @if (!empty($chartTemporal))
        <div class="chart">
            <h3>Gráfico – evolução temporal</h3>
            <img src="{{ $chartTemporal }}" alt="Gráfico temporal">
        </div>
    @endif

    @if (!empty($chartByTimeSlot))
        <div class="chart">
            <h3>Gráfico – velocidade por horário do dia</h3>
            <img src="{{ $chartByTimeSlot }}" alt="Gráfico por horário">
        </div>
    @endif

    @if (!empty($chartRoomDay5))
        <div class="chart">
            <h3>Gráfico – cômodos ao longo do dia (5 GHz)</h3>
            <img src="{{ $chartRoomDay5 }}" alt="Cômodos ao longo do dia 5 GHz">
        </div>
    @endif

    @if (!empty($chartRoomDay24))
        <div class="chart">
            <h3>Gráfico – cômodos ao longo do dia (2,4 GHz)</h3>
            <img src="{{ $chartRoomDay24 }}" alt="Cômodos ao longo do dia 2,4 GHz">
        </div>
    @endif

    <h2>7. Alertas</h2>
    <p class="muted">
        Limiares: 2,4 GHz &lt; {{ number_format($report['thresholds']['threshold_24'], 1, ',', '.') }} Mbps
        ou 5 GHz &lt; {{ number_format($report['thresholds']['threshold_5'], 1, ',', '.') }} Mbps.
    </p>
    @if (empty($report['alerts']))
        <p>Nenhuma medição abaixo dos limiares.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Local</th>
                    <th>2,4 GHz</th>
                    <th>5 GHz</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report['alerts'] as $alert)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($alert['date'])->format('d/m/Y') }}</td>
                        <td>{{ $alert['time_slot'] }}</td>
                        <td>{{ $alert['room'] }}</td>
                        <td>{{ number_format($alert['speed_24'], 1, ',', '.') }} Mbps</td>
                        <td>{{ number_format($alert['speed_5'], 1, ',', '.') }} Mbps</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
