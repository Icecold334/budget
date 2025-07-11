<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-800 flex items-center justify-between">
        <span><i class="fa-solid fa-money-bill-wave text-primary-600 mr-2"></i>Transaksi</span>
    </h1>

    @if ($activeBudget)
    {{-- Ringkasan Budget --}}
    <div class="bg-white p-4 rounded-lg border shadow-sm space-y-1">
        <p>Periode:
            <strong>
                {{ $activeBudget->start_date->translatedFormat('j F Y') }} –
                {{ $activeBudget->end_date->translatedFormat('j F Y') }}
            </strong>
        </p>

        <p>Total Budget:
            <strong>Rp{{ number_format($activeBudget->income_amount, 0, ',', '.') }}</strong>
        </p>

        <p>Target Tabungan:
            <strong class="text-amber-600">Rp{{ number_format($activeBudget->target_savings, 0, ',', '.') }}</strong>
        </p>

        <p>Total Terpakai:
            <strong class="text-red-600">Rp{{ number_format($totalSpent, 0, ',', '.') }}</strong>
        </p>

        <p>Sisa Budget (Setelah Tabungan):
            <strong class="text-green-600">Rp{{ number_format($budgetLeft, 0, ',', '.') }}</strong>
        </p>

        <p>Sisa Maksimum Pengeluaran Hari Ini:
            <strong class="text-blue-600">Rp{{ number_format($limitToday, 0, ',', '.') }}</strong>
        </p>
    </div>

    <div class="grid grid-cols-2 gap-6">
        {{-- Form Transaksi --}}
        <div class="bg-primary-50 p-4 rounded-lg border border-primary-200 shadow-sm space-y-3">
            <h2 class="font-semibold text-primary-800">Tambah Transaksi</h2>
            <form wire:submit.prevent="saveTransaction" class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-700">Tanggal</label>
                    <input type="date" wire:model.live="date"
                        class="w-full rounded border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
                    @error('date') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Jumlah (Rp)</label>
                    <input type="text" wire:model.live="amount"
                        class="w-full rounded border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Contoh: 100000" />
                    @error('amount') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-700">Deskripsi</label>
                    <input type="text" wire:model.live="description"
                        class="w-full rounded border-gray-300 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Misalnya: Belanja harian, Bensin, dll." />
                    @error('description') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-700">Kategori</label>
                    <select wire:model.live="category_id"
                        class="w-full rounded border-gray-300 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Tanpa Kategori</option>
                        @foreach (\App\Models\Category::where('user_id',auth()->id())->orderBy('name')->get() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 shadow">
                        <i class="fa-solid fa-save mr-1"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>

        {{-- List Transaksi --}}
        <div class="bg-primary-50 p-4 rounded-lg border border-primary-200 shadow-sm space-y-3">
            <h2 class="font-semibold text-primary-800">Daftar Transaksi</h2>
            @forelse ($transactions as $trx)
            <div class="bg-white p-3 rounded-lg border text-sm flex justify-between items-start">
                <div>
                    <p class="font-medium">{{ $trx->date->translatedFormat('l, j F Y') }} - {{ $trx->description ??
                        '(Tanpa
                        Deskripsi)' }}</p>
                    <p class="text-gray-500">{{ $trx->category?->name ?? 'Tanpa Kategori' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-red-600 font-semibold">
                        - Rp{{ number_format($trx->amount, 0, ',', '.') }}
                    </p>
                    <button type="button" onclick="confirmDelete({{ $trx->id }})"
                        class="text-xs text-red-500 hover:text-red-700 mt-1">
                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                    </button>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 italic">Belum ada transaksi.</p>
            @endforelse
        </div>
    </div>
    @else
    <div class="text-sm text-gray-500 italic">
        <i class="fa-solid fa-circle-info mr-1"></i>Tidak ada budget aktif. Buat budget terlebih dahulu.
    </div>
    @endif

    @push('scripts')
    <script>
        Livewire.on('toast', (e) => {
            const { type, message } = e[0]
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        });
    
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus transaksi ini?',
                text: "Tindakan ini tidak bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteTransaction', id);
                }
            });
        }
    </script>
    @endpush
</div>