<x-layouts.app>
    <div class="container-fluid">
        <h4 class="mb-3">Edit Jadwal Periksa</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('jadwal-periksa.update', $jadwal) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="hari" class="form-label">Hari</label>
                        <select name="hari" id="hari"
                                class="form-select @error('hari') is-invalid @enderror">
                            @foreach ($hariList as $hari)
                                <option value="{{ $hari }}"
                                    @selected(old('hari', $jadwal->hari) === $hari)>
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
                                   value="{{ old('jam_mulai', \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i')) }}">
                            @error('jam_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jam_selesai" class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai"
                                   class="form-control @error('jam_selesai') is-invalid @enderror"
                                   value="{{ old('jam_selesai', \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i')) }}">
                            @error('jam_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status"
                                class="form-select @error('status') is-invalid @enderror">
                            <option value="1" @selected((string) old('status', (int) $jadwal->status) === '1')>Aktif</option>
                            <option value="0" @selected((string) old('status', (int) $jadwal->status) === '0')>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Mengaktifkan jadwal ini akan menonaktifkan jadwal Anda yang lain.
                        </div>
                    </div>

                    <a href="{{ route('jadwal-periksa.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>