<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Measurement;
use Illuminate\Support\Collection;

class ReportAggregator
{
    public function __construct(
        private Company $company,
        private Collection $measurements,
        private array $selectedDates,
        private float $threshold24 = 50.0,
        private float $threshold5 = 300.0,
    ) {}

    public static function forCompany(
        Company $company,
        ?array $dates = null,
        float $threshold24 = 50.0,
        float $threshold5 = 300.0,
    ): self {
        $query = $company->measurements()->with('room')->orderBy('measured_on')->orderBy('time_slot');

        $availableDates = $company->measurements()
            ->select('measured_on')
            ->distinct()
            ->orderBy('measured_on')
            ->pluck('measured_on')
            ->map(fn ($d) => $d->format('Y-m-d'))
            ->values()
            ->all();

        $selectedDates = $dates ?: $availableDates;

        if ($selectedDates) {
            $query->where(function ($builder) use ($selectedDates) {
                foreach ($selectedDates as $date) {
                    $builder->orWhereDate('measured_on', $date);
                }
            });
        }

        return new self(
            $company,
            $query->get(),
            $selectedDates,
            $threshold24,
            $threshold5,
        );
    }

    public function availableDates(): array
    {
        return $this->company->measurements()
            ->select('measured_on')
            ->distinct()
            ->orderBy('measured_on')
            ->pluck('measured_on')
            ->map(fn ($d) => $d->format('Y-m-d'))
            ->values()
            ->all();
    }

    public function selectedDates(): array
    {
        return $this->selectedDates;
    }

    public function tablesByDateAndSlot(): array
    {
        $result = [];

        foreach ($this->selectedDates as $date) {
            $result[$date] = [];
            foreach (Measurement::TIME_SLOTS as $slot) {
                $rows = $this->measurements
                    ->filter(fn (Measurement $m) => $m->measured_on->format('Y-m-d') === $date && $m->time_slot === $slot)
                    ->sortBy(fn (Measurement $m) => $m->room->sort_order)
                    ->values()
                    ->map(fn (Measurement $m) => [
                        'room' => $m->room->name,
                        'speed_24' => (float) $m->speed_24_mbps,
                        'speed_5' => (float) $m->speed_5_mbps,
                    ])
                    ->all();

                $result[$date][$slot] = $rows;
            }
        }

        return $result;
    }

    public function dailyAverages(): array
    {
        $result = [];

        foreach ($this->selectedDates as $date) {
            $byRoom = $this->measurements
                ->filter(fn (Measurement $m) => $m->measured_on->format('Y-m-d') === $date)
                ->groupBy('room_id');

            $result[$date] = $byRoom->map(function (Collection $items) {
                $room = $items->first()->room;

                return [
                    'room' => $room->name,
                    'sort_order' => $room->sort_order,
                    'speed_24' => round($items->avg('speed_24_mbps'), 2),
                    'speed_5' => round($items->avg('speed_5_mbps'), 2),
                ];
            })->sortBy('sort_order')->values()->all();
        }

        return $result;
    }

    public function overallAverages(): array
    {
        return $this->measurements
            ->groupBy('room_id')
            ->map(function (Collection $items) {
                $room = $items->first()->room;

                return [
                    'room' => $room->name,
                    'sort_order' => $room->sort_order,
                    'speed_24' => round($items->avg('speed_24_mbps'), 2),
                    'speed_5' => round($items->avg('speed_5_mbps'), 2),
                    'delta' => round($items->avg('speed_5_mbps') - $items->avg('speed_24_mbps'), 2),
                    'ratio' => $items->avg('speed_24_mbps') > 0
                        ? round($items->avg('speed_5_mbps') / $items->avg('speed_24_mbps'), 2)
                        : null,
                ];
            })
            ->sortBy('sort_order')
            ->values()
            ->all();
    }

    public function ranking(): array
    {
        $averages = $this->overallAverages();

        return [
            'best_24' => collect($averages)->sortByDesc('speed_24')->values()->all(),
            'worst_24' => collect($averages)->sortBy('speed_24')->values()->all(),
            'best_5' => collect($averages)->sortByDesc('speed_5')->values()->all(),
            'worst_5' => collect($averages)->sortBy('speed_5')->values()->all(),
        ];
    }

    public function temporalEvolution(): array
    {
        $result = [];

        foreach ($this->selectedDates as $date) {
            $day = $this->measurements->filter(fn (Measurement $m) => $m->measured_on->format('Y-m-d') === $date);
            $result[] = [
                'date' => $date,
                'avg_24' => $day->isEmpty() ? 0 : round($day->avg('speed_24_mbps'), 2),
                'avg_5' => $day->isEmpty() ? 0 : round($day->avg('speed_5_mbps'), 2),
            ];
        }

        return $result;
    }

    public function alerts(): array
    {
        return $this->measurements
            ->filter(fn (Measurement $m) => (float) $m->speed_24_mbps < $this->threshold24
                || (float) $m->speed_5_mbps < $this->threshold5)
            ->sortBy([
                ['measured_on', 'asc'],
                ['time_slot', 'asc'],
            ])
            ->values()
            ->map(fn (Measurement $m) => [
                'room' => $m->room->name,
                'date' => $m->measured_on->format('Y-m-d'),
                'time_slot' => $m->time_slot,
                'speed_24' => (float) $m->speed_24_mbps,
                'speed_5' => (float) $m->speed_5_mbps,
                'below_24' => (float) $m->speed_24_mbps < $this->threshold24,
                'below_5' => (float) $m->speed_5_mbps < $this->threshold5,
            ])
            ->all();
    }

    public function chartByRoom(): array
    {
        $averages = $this->overallAverages();

        return [
            'labels' => array_column($averages, 'room'),
            'speed_24' => array_column($averages, 'speed_24'),
            'speed_5' => array_column($averages, 'speed_5'),
        ];
    }

    public function chartTemporal(): array
    {
        $evolution = $this->temporalEvolution();

        return [
            'labels' => array_map(
                fn ($row) => \Carbon\Carbon::parse($row['date'])->format('d/m/Y'),
                $evolution
            ),
            'avg_24' => array_column($evolution, 'avg_24'),
            'avg_5' => array_column($evolution, 'avg_5'),
        ];
    }

    /**
     * Média geral (todos os cômodos/dias) por horário do dia.
     */
    public function chartByTimeSlot(): array
    {
        $avg24 = [];
        $avg5 = [];

        foreach (Measurement::TIME_SLOTS as $slot) {
            $items = $this->measurements->filter(fn (Measurement $m) => $m->time_slot === $slot);
            $avg24[] = $items->isEmpty() ? 0 : round($items->avg('speed_24_mbps'), 2);
            $avg5[] = $items->isEmpty() ? 0 : round($items->avg('speed_5_mbps'), 2);
        }

        return [
            'labels' => Measurement::TIME_SLOTS,
            'avg_24' => $avg24,
            'avg_5' => $avg5,
        ];
    }

    /**
     * Por cômodo, média em cada horário (08h/13h/17h) ao longo dos dias selecionados.
     */
    public function chartByRoomThroughDay(): array
    {
        $rooms = $this->measurements
            ->groupBy('room_id')
            ->map(fn (Collection $items) => $items->first()->room)
            ->sortBy('sort_order')
            ->values();

        $series24 = [];
        $series5 = [];

        foreach ($rooms as $room) {
            $data24 = [];
            $data5 = [];

            foreach (Measurement::TIME_SLOTS as $slot) {
                $items = $this->measurements->filter(
                    fn (Measurement $m) => $m->room_id === $room->id && $m->time_slot === $slot
                );
                $data24[] = $items->isEmpty() ? null : round($items->avg('speed_24_mbps'), 2);
                $data5[] = $items->isEmpty() ? null : round($items->avg('speed_5_mbps'), 2);
            }

            $series24[] = ['label' => $room->name, 'data' => $data24];
            $series5[] = ['label' => $room->name, 'data' => $data5];
        }

        return [
            'labels' => Measurement::TIME_SLOTS,
            'series_24' => $series24,
            'series_5' => $series5,
        ];
    }

    public function thresholds(): array
    {
        return [
            'threshold_24' => $this->threshold24,
            'threshold_5' => $this->threshold5,
        ];
    }

    public function toArray(): array
    {
        return [
            'tables_by_date' => $this->tablesByDateAndSlot(),
            'daily_averages' => $this->dailyAverages(),
            'overall_averages' => $this->overallAverages(),
            'ranking' => $this->ranking(),
            'temporal' => $this->temporalEvolution(),
            'alerts' => $this->alerts(),
            'chart_by_room' => $this->chartByRoom(),
            'chart_temporal' => $this->chartTemporal(),
            'chart_by_time_slot' => $this->chartByTimeSlot(),
            'chart_by_room_through_day' => $this->chartByRoomThroughDay(),
            'thresholds' => $this->thresholds(),
            'selected_dates' => $this->selectedDates(),
            'available_dates' => $this->availableDates(),
        ];
    }
}
