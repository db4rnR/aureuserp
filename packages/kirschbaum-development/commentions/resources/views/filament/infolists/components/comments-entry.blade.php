<x-dynamic-component :component="$getEntryWrapperView()">
    <livewire:commentions::comments
        :record="$getRecord()"
        :mentionables="$getMentionables()"
        :polling-interval="$getPollingInterval()"
    />
</x-dynamic-component>
