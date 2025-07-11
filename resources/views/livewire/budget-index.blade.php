<div class="space-y-6">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">
            <i class="fa-solid fa-wallet mr-2 text-primary-600"></i>Budgeting
        </h1>
        @if (!$activeBudget)
        <a href="{{ route('budget.create') }}" {{-- <a href="#" --}}
            class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Budget
        </a>
        @endif
    </div>

    {{-- Budget Aktif --}}
    @if ($activeBudget)
    <div class="bg-gradient-to-br from-pink-50 to-primary-100 shadow-md rounded-lg p-6 border border-primary-200">
        <h2 class="text-lg font-semibold mb-2 text-primary-700">Budget Aktif</h2>
        <p>{{ $activeBudget->start_date->translatedFormat('j F Y') }} – {{ $activeBudget->end_date->translatedFormat('j
            F Y') }}</p>
        <p>Total: {{ $activeBudget->amount_rupiah }}</p>
        <p>Target Sisa: {{ $activeBudget->target_remainder_rupiah }}</p>

        <div class="mt-4 flex gap-2">
            {{-- <a href="{{ route('budget.show', $activeBudget->id) }}" class="text-sm text-blue-600 hover:underline">
                --}}
                <a href="#" class="text-sm text-primary-600 hover:underline">
                    <i class="fa-solid fa-eye mr-1"></i>Lihat Detail
                </a>
                <a href="#" wire:click="$dispatch('confirm-cancel', {{ $activeBudget->id }})"
                    class="text-sm text-danger-600 hover:underline cursor-pointer">
                    <i class="fa-solid fa-ban mr-1"></i>Batalkan
                </a>
        </div>
    </div>
    @else
    <div class="text-sm text-gray-500 italic">
        <i class="fa-solid fa-circle-info mr-1"></i>Tidak ada budget aktif saat ini.
    </div>
    @endif

    {{-- Riwayat Budget --}}
    <div>
        <h2 class="text-lg font-semibold mb-3 text-gray-700">
            <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-500"></i>Riwayat Budget
        </h2>
        <div class="space-y-3">
            @forelse ($pastBudgets as $budget)
            <div class="bg-gradient-to-br from-pink-50 to-primary-100 p-4 rounded-lg border">
                <div class="flex justify-between">
                    <div>
                        <p class="font-medium">
                            {{ $budget->start_date->translatedFormat('d F') }} –
                            {{ $budget->end_date->translatedFormat('d F Y') }}
                        </p>
                        <p class="text-sm text-gray-500">
                            Total: {{ $budget->amount_rupiah }},
                            Target: {{$budget->target_remainder_rupiah }}
                        </p>
                    </div>
                    {{-- <a href="{{ route('budget.show', $budget->id) }}" --}} <a href="#"
                        class="text-sm text-blue-500 hover:underline self-center">
                        <i class="fa-solid fa-eye mr-1"></i>Detail
                    </a>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400 italic">Belum ada riwayat budgeting.</p>
            @endforelse
        </div>
    </div>
    @push('scripts')
    <script>
        Livewire.on('confirm-cancel', id => {
            Swal.fire({
                title: 'Batalkan budget ini?',
                text: "Tindakan ini tidak bisa dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                // cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, batalkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('cancelBudget',  {id: id} );
                }
            });
        });
    
Livewire.on('budget-cancelled', () => {
    Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: 'Budget berhasil dibatalkan',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
    });
    });
    </script>
    @endpush

    @if (session()->has('toast'))
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
                const toast = @json(session('toast'));
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: toast.type,
                    title: toast.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
    </script>
    @endpush
    @endif
</div>