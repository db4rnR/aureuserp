<div class="flex flex-col w-full h-full space-y-4">
    <!-- Actions -->
    <div class="flex justify-between w-full gap-3">
        <div class="flex gap-3">
            @foreach (['messageAction', 'logAction', 'activityAction'] as $action)
                @if ($this->{$action}->isVisible())
                    {{ $this->{$action} }}
                @endif
            @endforeach
        </div>

        <div class="flex gap-3">
            @foreach (['fileAction', 'followerAction'] as $action)
                @if ($this->{$action}->isVisible())
                    {{ $this->{$action} }}
                @endif
            @endforeach
        </div>
    </div>

    @if ($this->activityInfolist->isVisible())
        <!-- Activities -->
        {{ $this->activityInfolist }}
    @endif

    <!-- Messages -->
    {{ $this->chatInfolist }}

    <x-filament-actions::modals />
</div>