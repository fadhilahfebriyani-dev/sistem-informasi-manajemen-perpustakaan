<?php
namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\SurveyKepuasan;
use Illuminate\Http\Request;

class PublikController extends Controller
{
    /**
     * Halaman utama publik — landing page sekaligus OPAC
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->where('stok', '>', 0);

        // Pencarian
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('judul',   'like', "%{$q}%")
                   ->orWhere('pengarang', 'like', "%{$q}%")
                   ->orWhere('penerbit',  'like', "%{$q}%")
                   ->orWhereHas('kategori', fn($k) => $k->where('nama', 'like', "%{$q}%"));
            });
        }

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bukus    = $query->latest()->paginate(12)->withQueryString();
        $kategoris = \App\Models\Kategori::orderBy('nama')->get();

        // Statistik ringkas untuk hero
        $stats = [
            'total_buku'    => Buku::count(),
            'total_koleksi' => Buku::sum('stok'),
            'total_judul'   => Buku::distinct('judul')->count('judul'),
        ];

        return view('publik.index', compact('bukus', 'kategoris', 'stats'));
    }

    /**
     * Form survey kepuasan
     */
    public function surveyForm()
    {
        $pertanyaan = SurveyKepuasan::pertanyaan();
        return view('publik.survey', compact('pertanyaan'));
    }

    /**
     * Simpan jawaban survey
     */
    public function surveySimpan(Request $request)
    {
        $request->validate([
            'nama_responden'        => 'nullable|string|max:100',
            'kelas'                 => 'nullable|string|max:20',
            'jenis_responden'       => 'required|in:siswa,guru,umum',
            'q_layanan_petugas'     => 'required|integer|between:1,5',
            'q_kemudahan_akses'     => 'required|integer|between:1,5',
            'q_kelengkapan_koleksi' => 'required|integer|between:1,5',
            'q_kondisi_ruang'       => 'required|integer|between:1,5',
            'q_manfaat'             => 'required|integer|between:1,5',
            'q_rekomendasi'         => 'required|integer|between:1,5',
            'saran'                 => 'nullable|string|max:1000',
        ], [
            'required' => 'Pertanyaan ini wajib dijawab.',
            'between'  => 'Pilih nilai antara 1 sampai 5.',
        ]);

        // Cegah spam: 1 survey per IP per hari
        $sudahIsi = SurveyKepuasan::where('created_at', '>=', now()->startOfDay())
            ->where(function ($q) {
                // Tidak ada kolom IP, pakai session flag saja
            })->exists();

        if (session('survey_submitted_today') === today()->toDateString()) {
            return redirect()->route('publik.index')
                ->with('info', 'Anda sudah mengisi survey hari ini. Terima kasih!');
        }

        SurveyKepuasan::create($request->only([
            'nama_responden','kelas','jenis_responden',
            'q_layanan_petugas','q_kemudahan_akses','q_kelengkapan_koleksi',
            'q_kondisi_ruang','q_manfaat','q_rekomendasi','saran',
        ]));

        session(['survey_submitted_today' => today()->toDateString()]);

        return redirect()->route('publik.index')
            ->with('success', 'Terima kasih! Pendapat Anda sangat berarti bagi kami.');
    }
}
