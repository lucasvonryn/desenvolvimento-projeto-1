<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\ReportAggregator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function show(Request $request, Company $empresa): View
    {
        $empresa->load('equipment');

        $dates = $request->input('dates');
        if (is_string($dates)) {
            $dates = array_filter(explode(',', $dates));
        }

        $threshold24 = (float) $request->input('threshold_24', 50);
        $threshold5 = (float) $request->input('threshold_5', 300);

        $report = ReportAggregator::forCompany(
            $empresa,
            $dates ?: null,
            $threshold24,
            $threshold5,
        )->toArray();

        return view('reports.show', [
            'company' => $empresa,
            'report' => $report,
        ]);
    }

    public function pdf(Request $request, Company $empresa): Response
    {
        $empresa->load('equipment');

        $dates = $request->input('dates');
        if (is_string($dates)) {
            $dates = array_filter(explode(',', $dates));
        }

        $threshold24 = (float) $request->input('threshold_24', 50);
        $threshold5 = (float) $request->input('threshold_5', 300);

        $report = ReportAggregator::forCompany(
            $empresa,
            $dates ?: null,
            $threshold24,
            $threshold5,
        )->toArray();

        $pdf = Pdf::loadView('reports.pdf', [
            'company' => $empresa,
            'report' => $report,
            'chartByRoom' => $request->input('chart_by_room'),
            'chartTemporal' => $request->input('chart_temporal'),
            'chartByTimeSlot' => $request->input('chart_by_time_slot'),
            'chartRoomDay5' => $request->input('chart_room_day_5'),
            'chartRoomDay24' => $request->input('chart_room_day_24'),
        ])->setPaper('a4');

        $filename = 'relatorio-wifi-'.str($empresa->name)->slug().'.pdf';

        return $pdf->download($filename);
    }
}
