<x-filament-panels::page>
    <?php
        $categories = $this->getRecords();

        $posts = $this->getPosts();
    ?>
    
    <div class="flex gap-4">
        <a href="" class="hover:bg-primary-6 font-bold text-primary-500">
            All
        </a>

        @foreach ($categories as $category)
            <a href="{{ self::getResource()::getUrl('view', ['record' => $category->slug]) }}" class="hover:bg-primary-6 font-bold text-gray-500">
                {{ $category->name }}
            </a>
        @endforeach
    </div>
    
    @include('blogs::filament.customer.resources.post.pages.list-records', ['records' => $posts])
</x-filament-panels::page>
