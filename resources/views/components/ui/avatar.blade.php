@props([
    'user',
    'size' => 40,
])

<div class="avatar" style="width: {{ $size }}rem; height: {{ $size }}rem; position: relative;">
    @if ($user->avatar_url)
        <img
            src="{{ $user->avatar_url }}"
            alt="Avatar"
            class="rounded-circle object-cover"
            width="{{ $size }}"
            height="{{ $size }}"
            onerror="this.style.display='none';
                     this.nextElementSibling.style.display='flex';"
        >
    @endif

    <span
        class="avatar-initial rounded-circle bg-label-primary d-flex align-items-center justify-content-center"
        style="
            width: {{ $size }}rem;
            height: {{ $size }}rem;
            display: {{ $user->avatar_url ? 'none' : 'flex' }};
            font-weight: bold;
            font-size: {{ $size / 2.5 }}rem;
        "
    >
        {{ $user->initials }}
    </span>
</div>
