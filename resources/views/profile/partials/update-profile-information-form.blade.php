<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form id="update-profile-info-form" method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 needs-confirmation" data-confirmation-title="Konfirmasi Pembaruan Profil" data-confirmation-message="Apakah Anda yakin ingin menyimpan perubahan pada informasi profil Anda?">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white" :value="old('username', $user->username)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full mt-1 block w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-white" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <!-- Company Name Field -->
        @php
            $companySetting = \App\Models\CompanySetting::first();
            $companyName = $companySetting ? $companySetting->company_name : 'Belum diatur';
            $isSuperUser = $user->hasRole('SuperUser');
        @endphp
        <div>
            <x-input-label for="company_name" :value="__('Nama Perusahaan')" />
            <x-text-input id="company_name" 
                         name="company_name" 
                         type="text" 
                         class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed" 
                         :value="$companyName" 
                         disabled
                         readonly />
            <p class="mt-1 text-xs text-gray-500">Field ini tidak dapat diedit.</p>
        </div>

        <!-- Job Title (Jabatan) Field -->
        @php
            $roleName = $user->getRoleNames()->first() ?? 'User';
            $jabatan = $roleName;
        @endphp
        <div>
            <x-input-label for="jabatan" :value="__('Jabatan')" />
            <x-text-input id="jabatan" 
                         name="jabatan" 
                         type="text" 
                         class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed" 
                         :value="$jabatan" 
                         disabled
                         readonly />
            <p class="mt-1 text-xs text-gray-500">Jabatan ditentukan oleh role akun Anda.</p>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>