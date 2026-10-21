<x-layouts.app>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Jadwal Periksa</h4>
            <a href="{{ route('jadwal-periksa.create') }}" class="btn btn-primary">
                + Tambah Jadwal
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalPeriksas as $jadwal)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $jadwal->hari }}</td>
                                <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                                <td>
                                    @if ($jadwal->status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('jadwal-periksa.edit', $jadwal) }}"
                                       class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('jadwal-periksa.destroy', $jadwal) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus jadwal hari {{ $jadwal->hari }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada jadwal periksa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>