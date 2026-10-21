<x-layouts.app>
    <div class="container-fluid">
        <h4 class="mb-3">Tambah Jadwal Periksa</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('jadwal-periksa.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari"
                                class="form-select @error('hari') is-invalid @enderror">
                            <option value="">-- Pilih Hari --</option>
                            @foreach ($hariList as $hari)
                                <option value="{{ $hari }}" @selected(old('hari') === $hari)>
                                    {{ $hari }}
                                </option>
                            @endforeach
                        </select>
                        @error('hari')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai"
                                   class="form-control @error('jam_mulai') is-invalid @enderror"
                                   value="{{ old('jam_mulai') }}">
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai"
                                   class="form-control @error('jam_selesai') is-invalid @enderror"
                                   value="{{ old('jam_selesai') }}">
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <p class="text-muted small">
                        Jadwal baru berstatus Tidak Aktif. Aktifkan lewat menu Edit.
                    </p>

                    <a href="{{ route('jadwal-periksa.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>