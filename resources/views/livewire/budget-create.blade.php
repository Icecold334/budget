<div class="bg-gradient-to-br from-primary-50 to-primary-100 p-4 rounded-lg shadow">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">
        <i class="fa-solid fa-wallet mr-2 text-primary-600"></i>Tambah Budget
    </h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" wire:model.live="start_date"
                class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
            @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-1 text-sm font-medium text-gray-700">Tanggal Akhir</label>
            <input type="date" wire:model.live="end_date"
                class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
            @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div x-data>
            <label class="block mb-1 text-sm font-medium text-gray-700">Total Pemasukan (Rp)</label>
            <input type="text" wire:model.live="income_amount" x-ref="inputIncome"
                x-on:input="$refs.inputIncome.value = formatRupiah($refs.inputIncome.value)"
                class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
            @error('income_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div x-data>
            <label class="block mb-1 text-sm font-medium text-gray-700">Target Sisa/Tabungan (Rp)</label>
            <input type="text" wire:model.live="target_savings" x-ref="inputTarget"
                x-on:input="$refs.inputTarget.value = formatRupiah($refs.inputTarget.value)"
                class="w-full rounded-lg border-gray-300 focus:ring-primary-500 focus:border-primary-500" />
            @error('target_savings') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('budget.index') }}"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow">
                <i class="fa-solid fa-xmark mr-1"></i> Kembali
            </a>
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow">
                <i class="fa-solid fa-save mr-1"></i> Simpan
            </button>
        </div>
    </form>
    @push('scripts')
    <script>
        Livewire.on('toast', ({ type, message }) => {
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
    </script>
    @endpush

    @push('scripts')
    <script>
        function formatRupiah(value) {
            let numberString = value.replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
    
            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return 'Rp ' + rupiah;
        }
    
        Livewire.on('toast', ({ type, message }) => {
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
    </script>
    @endpush
</div>