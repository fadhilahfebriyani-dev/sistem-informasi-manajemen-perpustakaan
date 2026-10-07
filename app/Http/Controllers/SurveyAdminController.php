<?php
namespace App\Http\Controllers;

use App\Models\SurveyKepuasan;
use Illuminate\Http\Request;

class SurveyAdminController extends Controller
{
    public function index(Request $request)
    {
        $fields     = array_keys(SurveyKepuasan::pertanyaan());
        $pertanyaan = SurveyKepuasan::pertanyaan();

        // Filter periode
        $periode = $request->get('periode', 'bulan_ini');
        $query   = SurveyKepuasan::query();

        match($periode) {
            'hari_ini'   => $query->whereDate('created_at', today()),
            'minggu_ini' => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]),
            'bulan_ini'  => $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year),
            'tahun_ini'  => $query->whereYear('created_at', now()->year),
            default      => null,
        };

        $total = $query->count();

        // Rata-rata per pertanyaan
        $rataPerQ = [];
        foreach ($fields as $f) {
            $rataPerQ[$f] = $total ? round($query->avg($f), 2) : 0;
        }

        // Rata-rata keseluruhan
        $rataTotal = $total
            ? round(array_sum($rataPerQ) / count($rataPerQ), 2)
            : 0;

        // Distribusi nilai per pertanyaan (untuk chart)
        $distribusi = [];
        foreach ($fields as $f) {
            for ($i = 1; $i <= 5; $i++) {
                $distribusi[$f][$i] = (clone $query)->where($f, $i)->count();
            }
        }

        // Distribusi jenis responden
        $jenisResp = (clone $query)
            ->selectRaw('jenis_responden, count(*) as total')
            ->groupBy('jenis_responden')
            ->pluck('total', 'jenis_responden');

        // NPS sederhana dari q_rekomendasi
        $promoters  = (clone $query)->whereIn('q_rekomendasi', [5])->count();
        $passives   = (clone $query)->whereIn('q_rekomendasi', [4])->count();
        $detractors = (clone $query)->whereIn('q_rekomendasi', [1,2,3])->count();
        $nps        = $total ? round((($promoters - $detractors) / $total) * 100) : 0;

        // Saran terbaru
        $sarans = SurveyKepuasan::whereNotNull('saran')
            ->where('saran', '!=', '')
            ->latest()
            ->take(10)
            ->get();

        // Tren bulanan (6 bulan terakhir)
        $tren = SurveyKepuasan::selectRaw(
                'MONTH(created_at) as bulan, YEAR(created_at) as tahun, COUNT(*) as total, ' .
                implode(', ', array_map(fn($f) => "AVG($f) as avg_$f", $fields))
            )
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

        return view('admin.survey.index', compact(
            'pertanyaan','rataPerQ','rataTotal','total',
            'distribusi','jenisResp','nps','promoters','passives','detractors',
            'sarans','tren','periode'
        ));
    }

    /** Export CSV sederhana */
    public function export()
    {
        $rows = SurveyKepuasan::latest()->get();
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="survey_kepuasan_' . now()->format('Ymd') . '.csv"',
        ];

        $fields     = array_keys(SurveyKepuasan::pertanyaan());
        $pertanyaan = SurveyKepuasan::pertanyaan();

        $callback = function () use ($rows, $fields, $pertanyaan) {
            $f = fopen('php://output', 'w');
            // Header CSV
            fputcsv($f, array_merge(
                ['Tgl Isi', 'Nama', 'Kelas', 'Jenis'],
                array_values($pertanyaan),
                ['Rata-rata', 'Saran']
            ));
            foreach ($rows as $r) {
                fputcsv($f, array_merge(
                    [$r->created_at->format('d/m/Y'), $r->nama_responden, $r->kelas, $r->jenis_responden],
                    array_map(fn($fld) => $r->$fld, $fields),
                    [$r->rata_rata, $r->saran]
                ));
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}
