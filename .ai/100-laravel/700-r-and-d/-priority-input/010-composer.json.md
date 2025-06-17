# composer.json

```json
{
    "name": "devdojo/wave",
    "description": "Wave SaaS Starter Kit",
    "keywords": ["framework", "laravel", "SaaS", "Starter Kit"],
    "license": "MIT",
    "type": "project",
    "repositories": {
        "flux-pro": {
            "type": "composer",
            "url": "https://composer.fluxui.dev"
        },
        "laravel-comments": {
            "type": "composer",
            "url": "https://satis.spatie.be"
        }
    },
    "require": {
        "php":                                          "^8.4",
        "ext-exif":                                     "*",
        "ext-gd":                                       "*",
        "awcodes/filament-curator":                     "^3.7",
        "awcodes/filament-tiptap-editor":               "^3.5",
        "bezhansalleh/filament-google-analytics":       "^2.0",
        "bezhansalleh/filament-shield":                 "^3.3",
        "codeat3/blade-phosphor-icons":                 "^2.0",
        "devdojo/app":                                  "0.11",
        "devdojo/auth":                                 "^1.0",
        "devdojo/themes":                               "0.0.11",
        "dotswan/filament-laravel-pulse":               "^1.1",
        "filament/filament":                            "^3.2",
        "filament/spatie-laravel-media-library-plugin": "^3.3",
        "filament/spatie-laravel-settings-plugin":      "^3.3",
        "filament/spatie-laravel-tags-plugin":          "^3.3",
        "filament/spatie-laravel-translatable-plugin":  "^3.3",
        "gehrisandro/tailwind-merge-laravel":           "^1.2",
        "glhd/bits":                                    "^0.6",
        "guzzlehttp/guzzle":                            "^7.2",
        "hirethunk/verbs":                              "^0.7",
        "inertiajs/inertia-laravel":                    "^2.0",
        "intervention/image":                           "^2.7",
        "lab404/laravel-impersonate":                   "^1.7",
        "laravel/folio":                                "^1.1",
        "laravel/framework":                            "^12.0",
        "laravel/octane":                               "^2.0",
        "laravel/pail":                                 "^1.2",
        "laravel/pennant":                              "^1.16",
        "laravel/pulse":                                "^1.4",
        "laravel/reverb":                               "^1.5",
        "laravel/sanctum":                              "^4.1",
        "laravel/scout":                                "^10.15",
        "laravel/telescope":                            "^5.8",
        "laravel/tinker":                               "^2.10",
        "laravel/ui":                                   "^4.5",
        "laravel/wayfinder":                            "^0.1",
        "league/flysystem-aws-s3-v3":                   "^3.0",
        "livewire/flux":                                "^2.1",
        "livewire/flux-pro":                            "^2.1",
        "livewire/livewire":                            "^3.0",
        "livewire/volt":                                "^1.7",
        "mvenghaus/filament-plugin-schedule-monitor":   "^3.0",
        "nnjeim/world":                                 "^1.1",
        "nunomaduro/essentials":                        "@dev",
        "php-http/curl-client":                         "^2.3",
        "pxlrbt/filament-spotlight":                    "^1.3",
        "ralphjsmit/livewire-urls":                     "^1.4",
        "rmsramos/activitylog":                         "^1.0",
        "saade/filament-adjacency-list":                "^3.2",
        "shuvroroy/filament-spatie-laravel-backup":     "^2.2",
        "shuvroroy/filament-spatie-laravel-health":     "^2.3",
        "spatie/crawler":                               "^8.4",
        "spatie/laravel-activitylog":                   "^4.10",
        "spatie/laravel-backup":                        "^9.3",
        "spatie/laravel-comments":                      "^2.2",
        "spatie/laravel-comments-livewire":             "^3.2",
        "spatie/laravel-data":                          "^4.15",
        "spatie/laravel-event-sourcing":                "^7.0",
        "spatie/laravel-model-states":                  "^2.11",
        "spatie/laravel-model-status":                  "^1.18",
        "spatie/laravel-pdf":                           "^1.5",
        "spatie/laravel-permission":                    "^6.19",
        "spatie/laravel-prometheus":                    "^1.2",
        "spatie/laravel-query-builder":                 "^6.3",
        "spatie/laravel-schedule-monitor":              "^3.10",
        "spatie/laravel-settings":                      "^3.4",
        "spatie/laravel-sitemap":                       "^7.3",
        "spatie/laravel-sluggable":                     "^3.7",
        "spatie/laravel-tags":                          "^4.10",
        "spatie/laravel-translatable":                  "^6.11",
        "spatie/robots-txt":                            "^2.5",
        "statikbe/laravel-cookie-consent":              "^1.10",
        "staudenmeir/laravel-adjacency-list":           "^1.25",
        "stripe/stripe-php":                            "^15.3",
        "symfony/http-client":                          "^7.3",
        "tightenco/parental":                           "^1.4",
        "tightenco/ziggy":                              "^2.5",
        "tymon/jwt-auth":                               "@dev",
        "typesense/typesense-php":                      "^5.1",
        "ueberdosis/tiptap-php":                        "^1.4",
        "z3d0x/filament-fabricator":                    "^2.5"
    },
    "require-dev": {
        "alebatistella/duskapiconf":                    "^1.2",
        "barryvdh/laravel-debugbar":                    "^3.15",
        "barryvdh/laravel-ide-helper":                  "^3.5",
        "brianium/paratest":                            "^7.8",
        "driftingly/rector-laravel":                    "^2.0",
        "ergebnis/composer-normalize":                  "^2.47",
        "fakerphp/faker":                               "^1.24",
        "infection/infection":                          "^0.29",
        "jasonmccreary/laravel-test-assertions":        "^2.8",
        "larastan/larastan":                            "^3.4",
        "laravel-shift/blueprint":                      "^2.12",
        "laravel/dusk":                                 "^8.3",
        "laravel/pint":                                 "^1.22",
        "laravel/sail":                                 "^1.43",
        "laravel/telescope":                            "^5.8",
        "mockery/mockery":                              "^1.6",
        "nunomaduro/collision":                         "^8.8",
        "nunomaduro/phpinsights":                       "^2.13",
        "peckphp/peck":                                 "^0.1",
        "pestphp/pest":                                 "^3.8",
        "pestphp/pest-plugin":                          "^3.x-dev",
        "pestphp/pest-plugin-arch":                     "^3.1",
        "pestphp/pest-plugin-faker":                    "^3.0",
        "pestphp/pest-plugin-laravel":                  "^3.2",
        "pestphp/pest-plugin-livewire":                 "^3.0",
        "pestphp/pest-plugin-stressless":               "^3.1",
        "pestphp/pest-plugin-type-coverage":            "^3.5",
        "php-parallel-lint/php-parallel-lint":          "^1.4",
        "phpmetrics/phpmetrics":                        "^3.0",
        "rector/rector":                                "^2.0",
        "rector/type-perfect":                          "^2.1",
        "roave/security-advisories":                    "dev-latest",
        "soloterm/solo":                                "^0.5",
        "spatie/laravel-blade-comments":                "^1.4",
        "spatie/laravel-horizon-watcher":               "^1.1",
        "spatie/laravel-ray":                           "^1.40",
        "spatie/laravel-web-tinker":                    "^1.10",
        "spatie/pest-plugin-snapshots":                 "^2.2",
        "symfony/polyfill-php84":                       "^1.32",
        "symfony/var-dumper":                           "^7.3"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/",
            "Wave\\": "wave/src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "extra": {
        "laravel": {
            "dont-discover": [],
            "providers": [
                "Wave\\WaveServiceProvider"
            ]
        }
    },
    "scripts": {
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php artisan migrate --graceful --ansi",
            "@php artisan db:seed"
        ],
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover",
            "@php artisan storage:link",
            "@php artisan filament:upgrade",
            "@php artisan livewire:publish --assets"
        ],
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite"
        ],
        "git:hooks":                    "php artisan git:install-hooks",
        "lint":                         [ "./vendor/bin/pint", "npm run lint" ],
        "metrics":                      "./vendor/bin/phpmetrics --config=phpmetrics.json app",
        "monitor:check":                "php artisan pulse:check",
        "monitor:start":                [ "php artisan pulse:install", "php artisan pulse:work" ],
        "monitoring:start":             "cd /opt/docker/monitoring && docker-compose up -d",
        "monitoring:stop":              "cd /opt/docker/monitoring && docker-compose down",
        "monitoring:restart":           "cd /opt/docker/monitoring && docker-compose restart",
        "monitoring:status":            "cd /opt/docker/monitoring && docker-compose ps",
        "monitoring:logs":              "cd /opt/docker/monitoring && docker-compose logs -f",
        "monitoring:test":              "cd /opt/docker/monitoring && ./test-alerts.sh check",
        "monitoring:alerts":            "cd /opt/docker/monitoring && ./test-alerts.sh alerts",
        "monitoring:config":            "cd /opt/docker/monitoring && ./test-alerts.sh config",
        "refactor":                     "./vendor/bin/rector",
        "test:arch":                    "./vendor/bin/pest --parallel --group=arch",
        "test:coverage":                "@test:coverage:pcov",
        "test:coverage:pcov":           [ "@putenv PCOV_ENABLED=1", "php artisan test --coverage" ],
        "test:coverage:xdebug":         [ "@putenv XDEBUG_MODE=coverage", "php artisan test --coverage" ],
        "test:feature":                 "./vendor/bin/pest --parallel --min=90 --filter Feature",
        "test:integration":             "./vendor/bin/pest --parallel --min=90 --filter Integration",
        "test:type-coverage":           "./vendor/bin/pest --type-coverage --min=100",
        "test:typos":                   "./vendor/bin/peck",
        "test:lint":                    [ "./vendor/bin/pint --test", "npm run test:lint" ],
        "test:mutation":                "./vendor/bin/infection --threads=$(@composer detect-cores) --min-msi=85",
        "test:primer":                  [ "php artisan config:clear --ansi", "php artisan test" ],
        "test:unit":                    "vendor/bin/pest --parallel --coverage --exactly=100.0",
        "test:types":                   "./vendor/bin/phpstan",
        "test:refactor":                "./vendor/bin/rector --dry-run",
        "test:security":                [
            "./vendor/bin/pest --parallel --group=security",
            "./vendor/bin/security-checker security:check",
            "@composer audit"
        ],
        "test":                         [
            "@test:lint",
            "@test:typos",
            "@test:refactor",
            "@test:arch",
            "@test:type-coverage",
            "@test:types",
            "@test:mutation",
            "@test:snapshots",
            "@test:security",
            "@test:primer",
            "@test:unit",
            "@test:feature",
            "@test:integration"
        ],
        "validate:deps":                [
            "@composer validate",
            "@composer normalize --dry-run || exit 0",
            "@composer audit --no-dev || echo 'Found abandoned packages'"
        ]
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "dealerdirect/phpcodesniffer-composer-installer": true,
            "ergebnis/composer-normalize": true,
            "infection/extension-installer": true,
            "pestphp/pest-plugin": true,
            "php-http/discovery": true
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```
