<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ProjectPdfController extends Controller
{
    public function __invoke(Project $project, Request $request): Response
    {
        abort_unless($project->user_id === auth()->id(), 403);

        $project->load([
            'rooms.items',
        ]);

        $type = $request->string('type', 'full')->value();
        $view = match ($type) {
            'summary' => 'pdf.project-summary',
            'offer'   => 'pdf.project-offer',
            'client'  => 'pdf.project',
            default   => 'pdf.project',
        };

        $pdf = Pdf::loadView($view, [
            'project' => $project,
        ])->setPaper('a4');

        $date     = Carbon::parse($project->created_at)->format('Y-m-d');
        $title    = Str::slug($project->title, '-');

        $suffix = match ($type) {
            'summary' => 'Santrauka',
            'offer'   => 'Pasiulymas',
            'client'  => 'Samata',
            default   => 'Vidine-Samata',
        };

        $filename = "{$date}_{$title}_{$suffix}.pdf";

        return $pdf->stream($filename);
    }
}
