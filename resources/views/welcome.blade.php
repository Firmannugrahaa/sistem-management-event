<x-guest-layout>
    <x-landing.header />

    <main>
        <x-landing.hero />
        <x-landing.vendors :vendors="$vendors" />
        <x-landing.portfolio :events="$events" />
        <x-landing.packages :packages="$packages" />
        <x-landing.cta />
    </main>

    <x-landing.footer />

    {{-- Modals --}}
    <x-landing.registration-modal />
    <x-landing.vendor-detail-modal />

</x-guest-layout>
