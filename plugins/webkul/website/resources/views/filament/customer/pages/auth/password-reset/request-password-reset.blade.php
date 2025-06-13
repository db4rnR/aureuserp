<x-filament-panels::page>
    <main class="fi-simple-main w-full max-w-lg place-self-center bg-white px-6 py-12 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sm:rounded-xl sm:px-12">
        @if (filament()->hasRegistration())
            <header class="fi-simple-header mb-6 flex flex-col items-center">
                <h1 class="fi-simple-header-heading text-center text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                    {{ __('filament-panels::pages/auth/password-reset/request-password-reset.heading') }}
                </h1>

                <p class="fi-simple-header-subheading mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
                    <a href="http://127.0.0.1:8000/login" class="fi-link group/link fi-size-md fi-link-size-md fi-color-custom fi-color-primary fi-ac-action fi-ac-link-action relative inline-flex items-center justify-center gap-1.5 outline-none">
                        <span class="text-sm font-semibold text-custom-600 group-hover/link:underline group-focus-visible/link:underline dark:text-custom-400" style="--c-400:var(--primary-400);--c-600:var(--primary-600);">
                            {{ $this->loginAction }}
                        </span>
                    </a>
                </p>
            </header>
        @endif

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_PASSWORD_RESET_REQUEST_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

        <x-filament-panels::form id="form" wire:submit="request">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="$this->hasFullWidthFormActions()"
            />
        </x-filament-panels::form>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_PASSWORD_RESET_REQUEST_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
    </main>
</x-filament-panels::page>
