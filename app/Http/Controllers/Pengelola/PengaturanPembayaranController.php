<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PengaturanPembayaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaturanPembayaranController extends Controller
{
    /**
     * Tampilkan formulir pengaturan rekening & QRIS
     */
    public function edit(): View
    {
        $setting = PengaturanPembayaran::getSetting();

        return view('pengelola.pembayaran.pengaturan', compact('setting'));
    }

    /**
     * Simpan pembaruan pengaturan rekening & QRIS
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bank_utama' => ['required', 'string', 'max:50'],
            'nomor_rekening_utama' => ['required', 'string', 'max:50'],
            'atas_nama_utama' => ['required', 'string', 'max:100'],
            'nama_bank_sekunder' => ['nullable', 'string', 'max:50'],
            'nomor_rekening_sekunder' => ['nullable', 'string', 'max:50'],
            'atas_nama_sekunder' => ['nullable', 'string', 'max:100'],
            'nama_qris' => ['required', 'string', 'max:100'],
            'nmid_qris' => ['nullable', 'string', 'max:50'],
            'petunjuk_transfer' => ['nullable', 'string', 'max:1000'],
            'qris_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'hapus_qris' => ['nullable', 'boolean'],
        ], [
            'nama_bank_utama.required' => 'Nama bank utama wajib diisi.',
            'nomor_rekening_utama.required' => 'Nomor rekening utama wajib diisi.',
            'atas_nama_utama.required' => 'Atas nama pemilik rekening utama wajib diisi.',
            'nama_qris.required' => 'Nama instansi/merchant QRIS wajib diisi.',
            'qris_image.image' => 'Berkas QRIS harus berupa gambar.',
            'qris_image.max' => 'Ukuran gambar QRIS maksimal 3 MB.',
        ]);

        $setting = PengaturanPembayaran::getSetting();

        // Opsi hapus gambar QRIS saat ini
        if ($request->boolean('hapus_qris')) {
            if ($setting->qris_image && Storage::disk('public')->exists($setting->qris_image)) {
                Storage::disk('public')->delete($setting->qris_image);
            }
            $setting->qris_image = null;
        }

        // Upload berkas gambar QRIS baru jika ada
        if ($request->hasFile('qris_image')) {
            if ($setting->qris_image && Storage::disk('public')->exists($setting->qris_image)) {
                Storage::disk('public')->delete($setting->qris_image);
            }
            $path = $request->file('qris_image')->store('pengaturan-pembayaran', 'public');
            $setting->qris_image = $path;
        }

        $setting->update([
            'nama_bank_utama' => strtoupper(trim($validated['nama_bank_utama'])),
            'nomor_rekening_utama' => trim($validated['nomor_rekening_utama']),
            'atas_nama_utama' => strtoupper(trim($validated['atas_nama_utama'])),
            'nama_bank_sekunder' => ! empty($validated['nama_bank_sekunder']) ? strtoupper(trim($validated['nama_bank_sekunder'])) : null,
            'nomor_rekening_sekunder' => ! empty($validated['nomor_rekening_sekunder']) ? trim($validated['nomor_rekening_sekunder']) : null,
            'atas_nama_sekunder' => ! empty($validated['atas_nama_sekunder']) ? strtoupper(trim($validated['atas_nama_sekunder'])) : null,
            'nama_qris' => trim($validated['nama_qris']),
            'nmid_qris' => ! empty($validated['nmid_qris']) ? trim($validated['nmid_qris']) : null,
            'petunjuk_transfer' => ! empty($validated['petunjuk_transfer']) ? trim($validated['petunjuk_transfer']) : null,
        ]);

        ActivityLog::log(
            aksi: 'UPDATE_PENGATURAN_PEMBAYARAN',
            entitasTipe: 'PengaturanPembayaran',
            entitasId: $setting->id,
            keterangan: [
                'bank_utama' => $setting->nama_bank_utama,
                'no_rekening_utama' => $setting->nomor_rekening_utama,
                'user' => auth()->user()->name,
            ],
            userId: auth()->id()
        );

        return back()->with('success', 'Pengaturan rekening bank dan QRIS pembayaran berhasil diperbarui.');
    }
}
