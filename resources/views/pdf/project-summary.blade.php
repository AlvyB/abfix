<!doctype html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->title }} – santrauka</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; line-height: 1.4; }
        h1 { font-size: 20px; margin: 0 0 6px; color: #111827; }
        h2 { font-size: 14px; margin: 14px 0 8px; color: #111827; }
        .muted { color: #6b7280; font-size: 11px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; }
        th { background: #f3f4f6; color: #111827; font-size: 11px; text-transform: uppercase; letter-spacing: .02em; }
        .right { text-align: right; }
    </style>
</head>
<body>
    <h1>{{ $project->title }}</h1>
    <div class="muted">Santrauka • {{ $project->created_at->format('Y-m-d H:i') }}</div>

    @php
        $projectTotal = 0;
    @endphp

    <div class="card">
        <h2>Kambarių santrauka</h2>
        <table>
            <thead>
                <tr>
                    <th>Kambarys / zona</th>
                    <th class="right">Suma</th>
                </tr>
            </thead>
            <tbody>
                @foreach($project->rooms as $room)
                    @php
                        $roomTotal = $room->items->sum(function ($item) {
                            $qty = $item->quantity ?? 0;
                            $price = $item->unit_price ?? 0;
                            return $qty * $price;
                        });
                        $projectTotal += $roomTotal;
                    @endphp
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td class="right">{{ number_format($roomTotal, 2, ',', ' ') }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="card" style="text-align: right;">
        <h2 style="margin-bottom: 4px;">Bendra projekto suma</h2>
        <div style="font-size: 18px; font-weight: 700;">{{ number_format($projectTotal, 2, ',', ' ') }} €</div>
    </div>
</body>
</html>
