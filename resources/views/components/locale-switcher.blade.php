<div id="locale-group" class="btn-group ml-auto" role="group">
    @foreach(config('translatable.locales') as $locale)
        <a href="/setlocale/{{ $locale }}" class="btn btn-{{ $style ?? 'primary' }} {{ $locale == session('locale') ? 'disabled' : '' }}">
            <span class="text">{{ strtoupper($locale) }}</span>
        </a>
    @endforeach
</div>
