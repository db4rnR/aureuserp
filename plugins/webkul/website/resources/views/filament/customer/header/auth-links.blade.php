@if (! filament()->auth()->check())
    <ul class="me-4 hidden items-center gap-x-4 lg:flex">
        @foreach ($navigationItems as $item)
            @if ($item->isVisible())
                <x-filament-panels::topbar.item
                    :active="$item->isActive()"
                    :active-icon="$item->getActiveIcon()"
                    :badge="$item->getBadge()"
                    :badge-color="$item->getBadgeColor()"
                    :badge-tooltip="$item->getBadgeTooltip()"
                    :icon="$item->getIcon()"
                    :should-open-url-in-new-tab="$item->shouldOpenUrlInNewTab()"
                    :url="$item->getUrl()"
                >
                    {{ $item->getLabel() }}
                </x-filament-panels::topbar.item>
            @endif
        @endforeach
    </ul>
@endif