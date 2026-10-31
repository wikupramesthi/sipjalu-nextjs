<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-soft btn-soft-primary']) }}>
    {{ $slot }}
</button>
