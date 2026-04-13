<li class="nav-item navbar-dropdown dropdown" style=" list-style: none;">

    <form id="lang-form" action="{{ route('lang.switch') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="locale" id="locale-input">
    </form>

    <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
        <x-dynamic-component
            :component="'ui.flags.' . current_language()"
            size="2.2rem"
        />
    </a>

    <ul class="dropdown-menu dropdown-menu-end" style="min-inline-size: 14rem;">

        @foreach (['en', 'pt', 'tl'] as $lang)
            <li>
                <a class="dropdown-item d-flex align-items-center gap-3 {{ is_language($lang) ? 'active bg-light' : '' }}"
                   href="javascript:void(0);"
                   onclick="submitLang('{{ $lang }}')">

                    <x-dynamic-component :component="'ui.flags.' . $lang" />

                    <span class="align-middle">{{ language_name($lang) }}</span>

                    @if(is_language($lang))
                        <i class="ri ri-check-line ms-auto text-primary"></i>
                    @endif
                </a>
            </li>
        @endforeach

    </ul>
</li>

<script>
    function submitLang(locale) {
        document.getElementById('locale-input').value = locale;
        document.getElementById('lang-form').submit();
    }
</script>
