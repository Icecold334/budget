<aside id="sidebar"
  class="fixed top-0 left-0 z-40 w-64 h-screen bg-primary-600 shadow-2xl transition-transform -translate-x-full lg:translate-x-0">
  <div class="h-full  overflow-y-auto">
    <h2 class="text-2xl font-bold mb-6 text-white text-center px-4 py-3">{{ env('APP_NAME') }}</h2>
    <ul class="space-y-2 px-4 py-6">
      <x-layouts.app.sideitem :active="request()->routeIs('dashboard')" href="{{ route('dashboard') }}"
        title="Dashboard" icon="fa-solid fa-gauge-high" />

      <x-layouts.app.sideitem :active="request()->routeIs('budget.*')" href="{{ route('budget.index') }}"
        title="Budgeting" icon="fa-solid fa-wallet" />

      <x-layouts.app.sideitem :active="request()->routeIs('transaction.*')" href="{{ route('transaction.index') }}"
        title="Transaksi" icon="fa-solid fa-receipt" />


      <x-layouts.app.sideitem :active="request()->routeIs('saving.*')" href="{{ route('saving.index') }}"
        title="Tabungan" icon="fa-solid fa-piggy-bank" />


      <x-layouts.app.sideitem :active="request()->routeIs('category.*')" href="{{ route('category.index') }}"
        title="Kategori" icon="fa-solid fa-tags" />

      {{--
      <x-layouts.app.sideitem :active="request()->routeIs('laporan.*')" href="{{ route('laporan.index') }}"
        title="Laporan" icon="fa-solid fa-chart-line" /> --}}

      {{--
      <x-layouts.app.sideitem :active="request()->routeIs('profil')" href="{{ route('profil') }}" title="Profil"
        icon="fa-solid fa-user" /> --}}

      {{--
      <x-layouts.app.sideitem :active="request()->routeIs('pengaturan')" href="{{ route('pengaturan') }}"
        title="Pengaturan" icon="fa-solid fa-gear" /> --}}
    </ul>
  </div>
</aside>