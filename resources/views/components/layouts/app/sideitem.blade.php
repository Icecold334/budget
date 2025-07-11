<li>
  <a href="{{ $href }}"
    class="flex gap-3 items-center p-2 text-primary-50 hover:bg-primary-100 {{ $active ?'bg-primary-100 text-primary-600':'' }} hover:text-primary-600 transition duration-200 rounded-lg">
    <i class="{{ $icon }}"></i>
    <span class="font-semibold">{{ $title }}</span>
  </a>
</li>