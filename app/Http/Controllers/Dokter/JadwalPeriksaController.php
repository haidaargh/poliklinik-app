<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    private const HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    private function rules(bool $withStatus = false): array
    {
        $rules = [
            'hari'        => 'required|in:' . implode(',', self::HARI),
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ];

        if ($withStatus) {
            $rules['status'] = 'required|boolean';
        }

        return $rules;
    }

    private function messages(): array
    {
        return [
            'hari.required'          => 'Hari wajib dipilih.',
            'hari.in'                => 'Hari yang dipilih tidak valid.',
            'jam_mulai.required'     => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format'  => 'Format jam mulai harus HH:MM.',
            'jam_selesai.required'   => 'Jam selesai wajib diisi.',
            'jam_selesai.date_format'=> 'Format jam selesai harus HH:MM.',
            'jam_selesai.after'      => 'Jam selesai harus lebih besar dari jam mulai.',
            'status.required'        => 'Status wajib dipilih.',
        ];
    }

    /** Pastikan jadwal hanya bisa diakses oleh dokter pemiliknya. */
    private function authorizeOwner(JadwalPeriksa $jadwalPeriksa): void
    {
        abort_if($jadwalPeriksa->id_dokter !== Auth::id(), 403, 'Anda tidak berhak mengakses jadwal ini.');
    }

    /** Cek apakah jadwal bentrok dengan jadwal lain milik dokter di hari yang sama. */
    private function isOverlap(array $data, ?int $ignoreId = null): bool
    {
        return JadwalPeriksa::where('id_dokter', Auth::id())
            ->where('hari', $data['hari'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->exists();
    }

    /** Nonaktifkan jadwal lain milik dokter (hanya satu yang boleh aktif). */
    private function deactivateOthers(?int $exceptId = null): void
    {
        JadwalPeriksa::where('id_dokter', Auth::id())
            ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
            ->update(['status' => false]);
    }

    public function index()
    {
        $jadwalPeriksas = Auth::user()
            ->jadwalPeriksas()
            ->orderByRaw("FIELD(hari, '" . implode("','", self::HARI) . "')")
            ->orderBy('jam_mulai')
            ->get();

        return view('dokter.jadwal-periksa.index', compact('jadwalPeriksas'));
    }

    public function create()
    {
        return view('dokter.jadwal-periksa.create', ['hariList' => self::HARI]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        if ($this->isOverlap($validated)) {
            return back()->withInput()
                ->withErrors(['jam_mulai' => 'Jadwal bentrok dengan jadwal lain di hari yang sama.']);
        }

        Auth::user()->jadwalPeriksas()->create($validated + ['status' => false]);

        return redirect()->route('jadwal-periksa.index')
            ->with('success', 'Jadwal periksa berhasil ditambahkan.');
    }

    public function edit(JadwalPeriksa $jadwalPeriksa)
    {
        $this->authorizeOwner($jadwalPeriksa);

        return view('dokter.jadwal-periksa.edit', [
            'jadwal'   => $jadwalPeriksa,
            'hariList' => self::HARI,
        ]);
    }

    public function update(Request $request, JadwalPeriksa $jadwalPeriksa)
    {
        $this->authorizeOwner($jadwalPeriksa);

        $validated = $request->validate($this->rules(true), $this->messages());

        if ($this->isOverlap($validated, $jadwalPeriksa->id)) {
            return back()->withInput()
                ->withErrors(['jam_mulai' => 'Jadwal bentrok dengan jadwal lain di hari yang sama.']);
        }

        if ($validated['status']) {
            $this->deactivateOthers($jadwalPeriksa->id);
        }

        $jadwalPeriksa->update($validated);

        return redirect()->route('jadwal-periksa.index')
            ->with('success', 'Jadwal periksa berhasil diperbarui.');
    }

    public function destroy(JadwalPeriksa $jadwalPeriksa)
    {
        $this->authorizeOwner($jadwalPeriksa);

        $jadwalPeriksa->delete();

        return redirect()->route('jadwal-periksa.index')
            ->with('success', 'Jadwal periksa berhasil dihapus.');
    }
}