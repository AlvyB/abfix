<!doctype html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Pasiūlymas – {{ $project->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; line-height: 1.5; }
        h1 { font-size: 22px; margin: 0 0 6px; color: #0b1220; }
        h2 { font-size: 14px; margin: 14px 0 8px; color: #0b1220; }
        .muted { color: #6b7280; font-size: 11px; }
        .card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 14px; background: #f8fafc; }
        .row { display: flex; justify-content: space-between; gap: 12px; }
        .col { flex: 1; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; background: #e0f2fe; color: #0ea5e9; font-size: 11px; font-weight: 600; }
        .total { font-size: 20px; font-weight: 700; color: #0b1220; }
    </style>
</head>
<body>
    <h1>{{ $project->title }}</h1>
    <div class="muted">Oficialus pasiūlymas</div>

    @php
        $projectTotal = 0;
        $statusLabels = [
            'draft' => 'Juodraštis',
            'sent' => 'Pateikta',
            'approved' => 'Patvirtinta',
            'finished' => 'Užbaigta',
        ];
    @endphp

    <div class="card">
        <div class="row">
            <div class="col">
                <h2>Klientas</h2>
                <div>{{ $project->client_name ?: '—' }}</div>
                <div class="muted">{{ $project->address ?: '—' }}</div>
            </div>
            <div class="col" style="text-align: right;">
                <span class="badge">{{ $statusLabels[$project->status] ?? ucfirst($project->status) }}</span>
                <div class="muted" style="margin-top: 6px;">Terminas: {{ $project->due_date ? \Illuminate\Support\Carbon::parse($project->due_date)->format('Y-m-d') : '—' }}</div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col">
                <div class="muted">Kontaktai:</div>
                <div>{{ $project->contact_phone ?: '—' }}</div>
                <div>{{ $project->contact_email ?: '' }}</div>
            </div>
            <div class="col">
                <div class="muted">Pastabos:</div>
                <div>{{ $project->notes ?: '—' }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Kambarių apžvalga</h2>
        <ul style="margin: 0; padding-left: 16px;">
            @foreach($project->rooms as $room)
                @php
                    $roomTotal = $room->items->sum(function ($item) {
                        $qty = $item->quantity ?? 0;
                        $price = $item->unit_price ?? 0;
                        return $qty * $price;
                    });
                    $projectTotal += $roomTotal;
                @endphp
                <li style="margin-bottom: 6px;">
                    <strong>{{ $room->name }}</strong> – {{ number_format($roomTotal, 2, ',', ' ') }} €
                </li>
            @endforeach
        </ul>
    </div>

    <div class="card" style="text-align: right;">
        <div class="muted">Bendra projekto suma</div>
        <div class="total">{{ number_format($projectTotal, 2, ',', ' ') }} €</div>
    </div>
</body>
</html>
