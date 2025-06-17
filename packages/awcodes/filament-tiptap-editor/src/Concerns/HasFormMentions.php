<?php

namespace FilamentTiptapEditor\Concerns;

use FilamentTiptapEditor\TiptapEditor;
use Livewire\Attributes\Renderless;

trait HasFormMentions
{
    #[Renderless]
    public function getMentionsItems(string $statePath, string $search): array
    {
        foreach ($this->getCachedForms() as $form) {
            if ($results = $this->searchFormComponents($form->getComponents(), $statePath, $search)) {
                return $results;
            }
        }

        return [];
    }

    protected function searchFormComponents(array $components, string $statePath, string $search): ?array
    {
        foreach ($components as $component) {
            if ($component instanceof TiptapEditor && $component->getStatePath() === $statePath) {
                return $component->getSearchResults($search);
            }

            // Search within child containers if available
            foreach ($component->getChildComponentContainers() as $container) {
                if ($container->isHidden()) {
                    continue;
                }

                if ($childComponents = $container->getComponents()) {
                    if ($results = $this->searchFormComponents($childComponents, $statePath, $search)) {
                        return $results;
                    }
                }
            }
        }

        return null;
    }
}
