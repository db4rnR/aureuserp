# 5. Implementation Phases & Capability Roadmap

## 5.1. Phase 1: Foundation ("Let's Not Trip Over Our Own Feet")
- Refactor models for consistent event sourcing, STI, and enums.
- Align package versions and reduce @dev dependencies.
- Set up static analysis, code style, and CI/CD.

**Capabilities:**
- Stable, predictable codebase. Fewer “it worked yesterday” moments.
- All models play nicely together. No more polymorphic identity crises.

---

## 5.2. Phase 2: Feature Enablement ("Now With Extra Colour")
- Enable all core features (admin, UI, business modules).
- Integrate performance/monitoring tools.
- Ensure all testing/quality tools are running and reporting.

**Capabilities:**
- Feature-complete platform. Admins can admin, users can user, and the app mostly works.
- Monitoring and testing keep you honest (and awake).

---

## 5.3. Phase 3: Polish & Optimisation ("Shiny, Fast, and Less Likely to Explode")
- Optimise for performance, scalability, and maintainability.
- Address any remaining inconsistencies or technical debt.
- Add more diagrams, documentation, and colourful illustrations (because you asked for it).

**Capabilities:**
- Fast, robust, and maintainable. You can finally show it off without apologising.

---

## 5.4. Next Steps
- Prioritise Phase 1 tasks: audit models, align dependencies, set up CI/CD.
- Schedule regular code audits and quality reports.
- Start a "visual documentation" drive (more diagrams, less text).

---

## 5.5. Outstanding Questions, Decisions & Inconsistencies

- **Event Sourcing in UME?**
  - UME doesn’t fully embrace event sourcing. Refactor or leave as-is?
  - *Recommendation:* Refactor for consistency. **Confidence: 85%** (based on architectural goals, but may require more effort than expected).

- **STI Usage Consistency?**
  - Are all models using `tightenco/parental`? If not, why?
  - *Recommendation:* Standardise. **Confidence: 90%** (most models can be migrated easily).

- **@dev Dependencies?**
  - Is it worth the risk to keep bleeding-edge packages?
  - *Recommendation:* Replace with stable versions where possible. **Confidence: 95%** (unless a feature is only in @dev).

- **Alpine.js + Vue + Inertia Mix?**
  - Is this cocktail maintainable long-term?
  - *Recommendation:* Keep, but document integration patterns and avoid mixing directives. **Confidence: 80%** (works now, but could be a future headache).

- **Multiple Event Sourcing Packages?**
  - `hirethunk/verbs` and `spatie/laravel-event-sourcing` both present. Is this necessary?
  - *Recommendation:* Consolidate if possible. **Confidence: 75%** (depends on feature overlap and migration pain).

---

## 5.6. Summary

- The roadmap is clear(ish): refactor, enable, polish, and document. 
- Next up: start with the model and dependency audits, then move to feature enablement and documentation.
- Outstanding questions are mostly about consistency and risk tolerance. 
- If in doubt, add more diagrams and colour.
