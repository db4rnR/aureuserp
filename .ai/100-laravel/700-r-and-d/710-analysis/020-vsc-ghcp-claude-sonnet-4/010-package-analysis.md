~~~markdown
# 2. Package Dependency Analysis

## 2.1. Current vs. Documented Dependencies

### 2.1.1. PHP Dependencies (composer.json)

**Current State** (5 production packages):
```json
{
    "require": {
        "php": "^8.4",
        "laravel/framework": "^12.0",
        "livewire/livewire": "^3.0",
        "livewire/volt": "^1.0",
        "livewire/flux": "^1.0"
    }
}
```

**Documented Target** (60+ packages):

#### 2.1.2. Core Framework (5 packages)
```
├── php: ^8.4
├── laravel/framework: ^12.0
├── laravel/octane: ^2.0
├── laravel/reverb: ^1.5
└── laravel/sanctum: ^4.1
```

#### 2.1.3. Event Sourcing (2 packages)
```
├── hirethunk/verbs: ^0.7
└── spatie/laravel-event-sourcing: ^7.0
```

#### 2.1.4. State Management (2 packages)
```
├── spatie/laravel-model-states: ^2.11
└── spatie/laravel-model-status: ^1.18
```

#### 2.1.5. UI Framework (4 packages)
```
├── livewire/livewire: ^3.0
├── livewire/volt: ^1.7
├── livewire/flux: ^2.1
└── livewire/flux-pro: ^2.1
```

#### 2.1.6. FilamentPHP Ecosystem (15 packages)
```
├── filament/filament: ^3.2
├── awcodes/filament-curator: ^3.7
├── awcodes/filament-tiptap-editor: ^3.5
├── bezhansalleh/filament-shield: ^3.3
├── dotswan/filament-laravel-pulse: ^1.1
├── mvenghaus/filament-plugin-schedule-monitor: ^3.0
├── pxlrbt/filament-spotlight: ^1.3
├── rmsramos/activitylog: ^1.0
├── saade/filament-adjacency-list: ^3.2
├── shuvroroy/filament-spatie-laravel-backup: ^2.2
├── shuvroroy/filament-spatie-laravel-health: ^2.3
├── z3d0x/filament-fabricator: ^2.5
├── filament/spatie-laravel-media-library-plugin: ^3.3
├── filament/spatie-laravel-settings-plugin: ^3.3
└── filament/spatie-laravel-tags-plugin: ^3.3
```

## 2.2. Critical Package Conflicts

### 2.2.1. Event Sourcing Conflict ⚠️
**Issue**: Uses BOTH `hirethunk/verbs` AND `spatie/laravel-event-sourcing`
**Risk**: Competing event sourcing implementations
**Recommendation**: Choose `hirethunk/verbs` for PHP 8.4+ modern approach

### 2.2.2. UI Framework Overlap ⚠️
**Issue**: Uses `livewire/flux`, `livewire/flux-pro`, AND `inertiajs/inertia-laravel`
**Risk**: Competing client-side rendering approaches
**Recommendation**: Standardize on Livewire + Alpine for server-side rendering

### 2.2.3. Authentication Redundancy ⚠️
**Issue**: Has `laravel/sanctum`, `tymon/jwt-auth`, AND `devdojo/auth`
**Risk**: Multiple auth systems causing conflicts
**Recommendation**: Use Laravel Sanctum + custom 2FA implementation

### 2.2.4. Search Engine Duplication ⚠️
**Issue**: Includes BOTH `laravel/scout` AND `typesense/typesense-php`
**Risk**: Competing search implementations
**Recommendation**: Use Scout with Typesense driver

## 2.3. JavaScript Dependencies Analysis

**Current State** (7 dependencies):
```json
{
    "dependencies": {
        "@vitejs/plugin-vue": "^5.0.0",
        "axios": "^1.6.0",
        "laravel-vite-plugin": "^1.0.0",
        "vite": "^5.0.0",
        "alpinejs": "^3.13.0",
        "tailwindcss": "^3.4.0",
        "@tailwindcss/forms": "^0.5.0"
    }
}
```

**Documented Target** (40+ packages):

### 2.3.1. AlpineJS Ecosystem (9 packages)
```
├── @alpinejs/anchor: ^3.14.9
├── @alpinejs/collapse: ^3.14.9
├── @alpinejs/focus: ^3.14.9
├── @alpinejs/intersect: ^3.14.9
├── @alpinejs/mask: ^3.14.9
├── @alpinejs/morph: ^3.14.9
├── @alpinejs/persist: ^3.14.9
├── @alpinejs/resize: ^3.14.9
└── @alpinejs/sort: ^3.14.9
```

### 2.3.2. Vue.js Ecosystem (4 packages)
```
├── vue: ^3.5.13
├── @inertiajs/vue3: ^2.0.0
├── @vueuse/core: ^13.3.0
└── reka-ui: ^2.2.0
```

### 2.3.3. Styling & Animation (6 packages)
```
├── tailwindcss: ^4.1.6
├── tailwind-merge: ^3.3.0
├── tailwindcss-animate: ^1.0.7
├── tw-animate-css: ^1.2.5
├── class-variance-authority: ^0.7.1
└── clsx: ^2.1.1
```

## 2.4. Package Installation Roadmap

### 2.4.1. Phase 1: Core Infrastructure (Week 1)
```bash
# Event sourcing (choose ONE)
composer require hirethunk/verbs

# State management
composer require spatie/laravel-model-states spatie/laravel-model-status

# Core utilities
composer require glhd/bits tightenco/parental

# Performance
composer require laravel/octane laravel/reverb laravel/sanctum
```

### 2.4.2. Phase 2: UI Framework (Week 2)
```bash
# Livewire ecosystem
composer require livewire/flux-pro livewire/volt
composer require gehrisandro/tailwind-merge-laravel

# AlpineJS plugins
npm install @alpinejs/anchor @alpinejs/collapse @alpinejs/focus
npm install @alpinejs/intersect @alpinejs/mask @alpinejs/morph
npm install @alpinejs/persist @alpinejs/resize @alpinejs/sort
npm install @imacrayon/alpine-ajax
```

---

**Next**: [Architecture Patterns](015-architectural-patterns.md) | [Dependency Tree](025-dependency-tree.md)
~~~
