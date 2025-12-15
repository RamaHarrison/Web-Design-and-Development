<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn w-100 rounded-pill']) }}>
    {{ $slot }}
</button>