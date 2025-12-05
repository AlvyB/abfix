<!doctype html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; line-height: 1.4; }
        h1 { font-size: 20px; margin: 0 0 6px; color: #111827; }
        h2 { font-size: 14px; margin: 18px 0 8px; color: #111827; }
        .muted { color: #6b7280; font-size: 11px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; color: #111827; font-size: 11px; text-transform: uppercase; letter-spacing: .02em; }
        .right { text-align: right; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; border: 1px solid #d1d5db; font-size: 11px; color: #374151; }
    </style>
</head>
<body>
    <h1>{{ $project->title }}</h1>
    <div class="muted">Sukurta: {{ $project->created_at->format('Y-m-d H:i') }}</div>

    @php
        $statusLabels = [
            'draft' => 'Juodraštis',
            'sent' => 'Pateikta',
            'approved' => 'Patvirtinta',
            'finished' => 'Užbaigta',
        ];

        $projectTotal = 0;
    @endphp

    <div class="card">
        <h2>Projekto informacija</h2>
        <table style="margin: 0;">
            <tr>
                <th>Klientas</th>
                <td>{{ $project->client_name ?: '—' }}</td>
                <th>Adresas</th>
                <td>{{ $project->address ?: '—' }}</td>
            </tr>
            <tr>
                <th>Statusas</th>
                <td><span class="badge">{{ $statusLabels[$project->status] ?? ucfirst($project->status) }}</span></td>
                <th>Terminas</th>
                <td>{{ $project->due_date ? \Illuminate\Support\Carbon::parse($project->due_date)->format('Y-m-d') : '—' }}</td>
            </tr>
            <tr>
                <th>Kontaktai</th>
                <td colspan="3">
                    {{ $project->contact_phone ?: '—' }}
                    @if($project->contact_email)
                        <span class="muted"> / </span>{{ $project->contact_email }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Pastabos</th>
                <td colspan="3">{{ $project->notes ?: '—' }}</td>
            </tr>
        </table>
    </div>

    @foreach($project->rooms as $room)
        @php
            $roomTotal = $room->items->sum(function ($item) {
                $qty = $item->quantity ?? 0;
                $price = $item->unit_price ?? 0;
                return $qty * $price;
            });
            $projectTotal += $roomTotal;
        @endphp
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0;">{{ $room->name }}</h2>
                <div class="badge">Suma: {{ number_format($roomTotal, 2, ',', ' ') }} €</div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Pozicija</th>
                        <th>Kiekis</th>
                        <th>Vnt</th>
                        <th class="right">Kaina/vnt</th>
                        <th class="right">Suma</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($room->items as $item)
                        @php
                            $lineTotal = ($item->quantity ?? 0) * ($item->unit_price ?? 0);
                        @endphp
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity !== null ? number_format($item->quantity, 2, ',', ' ') : '—' }}</td>
                            <td>{{ $item->unit ?: '—' }}</td>
                            <td class="right">{{ $item->unit_price !== null ? number_format($item->unit_price, 2, ',', ' ') . ' €' : '—' }}</td>
                            <td class="right">{{ number_format($lineTotal, 2, ',', ' ') }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="muted">Kambaryje pozicijų nėra.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="card" style="text-align: right;">
        <h2 style="margin-bottom: 4px;">Bendra projekto suma</h2>
        <div style="font-size: 18px; font-weight: 700;">{{ number_format($projectTotal, 2, ',', ' ') }} €</div>
    </div>
</body>
</html>
