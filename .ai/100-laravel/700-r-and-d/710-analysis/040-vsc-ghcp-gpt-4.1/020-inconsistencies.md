~~~markdown
// .ai/100-laravel/710-analysis/040-vsc-ghcp-gpt-4.1/020-inconsistencies.md

# 4. Inconsistencies, Gotchas & "What Could Possibly Go Wrong?"


## 4.1. Architectural Pattern Mismatches

- **Event Sourcing Everywhere?**
  - UME (User Model Enhancements) doesn’t fully embrace event sourcing/CQRS, while LSF/LFS do. If you want a single event store for everything, you’ll need to refactor UME to play along.
- **STI Implementation**
  - All models want to be polymorphic, but only some are. Check that your User and Organisation models are using `tightenco/parental` consistently, or you’ll get the dreaded “why is this column always null?” bug.
- **Enums**
  - PHP-native enums are everywhere, but make sure all your states/statuses are actually using them (and not just old-school constants).


## 4.2. Package/Install Inconsistencies

- **@dev Packages**
  - `nunomaduro/essentials` and `tymon/jwt-auth` are on `@dev`. Expect the occasional “it worked yesterday” moment.
- **Filament Plugin Versions**
  - All plugins are v3+, but Filament is a fast-moving target. Double-check for breaking changes between minor versions.
- **Alpine.js + Vue + Inertia**
  - You’re mixing Alpine.js, Vue, and Inertia. This is fine, but don’t try to use Alpine directives inside Vue SFCs unless you enjoy debugging invisible bugs.
- **Livewire/Volt/Flux**
  - Volt SFCs for non-admin, Filament SPA for admin. Don’t cross the streams.


## 4.3. Dependency Tree Warnings

- **Sheer Volume**
  - You have a lot of packages. If you see “out of memory” during install, don’t panic (much).
- **Multiple Event Sourcing Packages**
  - `hirethunk/verbs` and `spatie/laravel-event-sourcing` are both present. Make sure you’re not duplicating event stores or projections.


## 4.4. Visual: "Inconsistency Bingo"

![Inconsistency Bingo](https://fakeimg.pl/600x300/ff0000,ffff00,00ff00,00bfff/?text=Pattern+Mismatch+%2B+Version+Hell+%2B+Dev+Dependencies&font=lobster)


---

**Reason for this chunk:**
- Flags mismatches and install issues before you spend a weekend debugging them.
- Helps you plan refactors and avoid the most common pitfalls.

**Confidence: 90%**
- Most inconsistencies are based on explicit evidence in your files. Some only show up at runtime, so keep your debugger handy.

~~~
