<x-layouts.app>
    <div class="container-fluid">
        <h4 class="mb-3">Tambah Obat</h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('obat.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nama_obat" class="form-label">Nama Obat</label>
                        <input type="text" name="nama_obat" id="nama_obat"
                               class="form-control @error('nama_obat') is-invalid @enderror"
                               value="{{ old('nama_obat') }}">
                        @error('nama_obat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kemasan" class="form-label">Kemasan</label>
                        <input type="text" name="kemasan" id="kemasan"
                               class="form-control @error('kemasan') is-invalid @enderror"
                               value="{{ old('kemasan') }}"
                               placeholder="Contoh: Strip isi 10 tablet">
                        @error('kemasan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" id="harga" min="0"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   value="{{ old('harga') }}">
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <a href="{{ route('obat.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>