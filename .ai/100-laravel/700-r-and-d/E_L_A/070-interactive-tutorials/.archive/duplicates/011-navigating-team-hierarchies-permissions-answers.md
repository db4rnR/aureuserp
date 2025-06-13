<!-- filepath: /Users/s-a-c/nc/PhpstormProjects/ela-docs/docs/E_L_A/070-interactive-tutorials/011-navigating-team-hierarchies-permissions-answers.md -->
# Answers: Navigating Team Hierarchies & Permissions Tutorial

**Version:** 0.1.0
**Date:** 2025-05-22
**Author:** GitHub Copilot
**Status:** Draft
**Progress:** 75%

---

<details>
<summary>Table of Contents</summary>

- Answers: Navigating Team Hierarchies & Permissions Tutorial
    - [1. Answers to Exercises](#1-answers-to-exercises)
        - [1.1. Exercise 1: Create a Root Team](#11-exercise-1-create-a-root-team)
        - [1.2. Exercise 2: Create a Sub-Team](#12-exercise-2-create-a-sub-team)
        - [1.3. Exercise 3: Identify Parent-Child Relationships](#13-exercise-3-identify-parent-child-relationships)
        - [1.4. Exercise 4: Discuss Permission Scope](#14-exercise-4-discuss-permission-scope)
    - [2. Answers to Assessment/Checks](#2-answers-to-assessmentchecks)
        - [2.1. Question 1](#21-question-1)
        - [2.2. Question 2](#22-question-2)
        - [2.3. Question 3](#23-question-3)
    - [3. Related Documents](#3-related-documents)
    - [4. Version History](#4-version-history)

</details>

---

## 1. Answers to Exercises

<div style="background-color: #e0ffff; padding: 15px; border-radius: 5px; border: 1px solid #afeeee; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #008080;">Behold! The Solutions!</h4>
<p style="color: #333;">Were you right? Or did you just skim the tutorial? Let's find out.</p>
</div>

### 1.1. Exercise 1: Create a Root Team

**Task:** Describe the steps you would take to create a new root team named "Finance" with an "Active" status. What key information would you need to provide?

**Answer:**

So, you want to create a "Finance" team, the big cheese of its own little world? Here's how you'd boss that around in the ELA Admin Portal (presumably Filament, as the prophecy foretold):

1. **Navigate to the Lair of Teams:** Log in with your all-powerful admin credentials and find the "Team Management" or "Teams" section in the Filament admin panel. It's probably lurking somewhere obvious.
2. **Summon the Creation Form:** Click the "Create Team" or "New Team" button. Don't be shy.
3. **Bestow the Sacred Details:**
    - **Name:** "Finance" (because creativity is overrated for finance teams).
    - **Slug:** Likely auto-generated as "finance". If not, type that.
    - **Status:** Set to "Active". You want them doing things, not just... forming.
    - **Parent Team:** This is the crucial bit for a *root* team – leave this field gloriously empty, or select "None" if that's an option. "Finance" bows to no one (in this hierarchy, anyway).
4. **Commit the Deed:** Click "Save" or "Create". Marvel at your new root team.

Key information, you ask? Primarily the `Name`, ensuring the `Parent Team` is `NULL` (or not set), and setting the desired `Status`. The `Slug` is important too, but often a loyal sidekick to the `Name`.

### 1.2. Exercise 2: Create a Sub-Team

**Task:** After creating the "Finance" team, describe how you would create a sub-team named "Payroll" under "Finance". What makes this process different from creating a root team?

**Answer:**

Ah, "Payroll," the team that *really* knows where the money goes. To tuck it neatly under "Finance":

1. **Back to the Team Creation Zone:** Same initial steps as creating any team – find that "Create Team" button in the Filament admin panel.
2. **Input the Minion's Details:**
    - **Name:** "Payroll"
    - **Slug:** "payroll" (you get the idea)
    - **Parent Team:** Here's the plot twist! Instead of leaving it empty, you'll select "Finance" from the list of available parent teams. This is what makes it a sub-team. It now has a boss.
    - Other fields as necessary (description, status, etc.).
3. **Seal its Fate:** Click "Save". "Payroll" is now officially a child of "Finance".

**The Big Difference:** The defining characteristic separating a sub-team's creation from a root team's is the **`Parent Team` assignment**.

- **Root Team:** `parent_id` is `NULL` (no parent selected).
- **Sub-Team:** `parent_id` points to an existing team's `id` (you actively pick its parent).

Easy, right? Unless you forget to assign the parent, then you just have another lonely root team.

### 1.3. Exercise 3: Identify Parent-Child Relationships

**Task:** If you have teams "A", "B", and "C", where "A" is the parent of "B", and "B" is the parent of "C":
  a. What would be the `parent_id` of team "B"?
  b. What would be the `parent_id` of team "A"?

**Answer:**

Let's untangle this family tree:

a. **Team "B"s `parent_id`**: Since "A" is the parent of "B", the `parent_id` field for team "B" would contain the unique `id` of team "A". (e.g., if Team A's ID is 1, then Team B's `parent_id` is 1).

b. **Team "A"s `parent_id`**: Team "A" is the matriarch/patriarch at the top of this specific branch. It has no parent mentioned in this scenario. Therefore, its `parent_id` would be `NULL`.

### 1.4. Exercise 4: Discuss Permission Scope

**Task:** User Jane is a member of the "Finance" root team and has permission to view all documents within that team. The "Payroll" sub-team has highly sensitive documents. According to ELA v2.0\'s permission model (no inheritance), does Jane automatically have permission to view documents in the "Payroll" sub-team? Explain your reasoning.

**Answer:**

Poor Jane. She might be a big shot in "Finance," but when it comes to the "Payroll" sub-team's secrets, she's out of luck by default.

**No, Jane does not automatically have permission to view documents in the "Payroll" sub-team.**

**Reasoning (The "No Free Lunch" Policy of ELA v2.0 Permissions):**

The ELA v2.0 approach to team permissions, as per PRD Section 4.3.8, is very explicit: **there is no inheritance of permissions or membership between parent and sub-teams.**

- Jane's membership and permissions are tied to the "Finance" team.
- The "Payroll" team is a separate entity in terms of access control, even though it's a child of "Finance" in the hierarchy.
- To access "Payroll" documents, Jane would need to be *explicitly* added as a member to the "Payroll" team AND be granted the necessary permissions *within the context of the "Payroll" team*.

Think of it as different security badges for different departments. Her "Finance" badge doesn't open "Payroll" doors. This keeps sensitive data in "Payroll" safe from accidental overreach, which is generally a good thing, unless you're Jane and you're feeling nosy.

---

## 2. Answers to Assessment/Checks

<div style="background-color: #fff0f5; padding: 15px; border-radius: 5px; border: 1px solid #ffc0cb; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #c71585;">The Moment of Truth!</h4>
<p style="color: #333;">Did you ace these, or are we sending you back to tutorial school?</p>
</div>

### 2.1. Question 1

**Question:** What attribute in the teams table links a sub-team to its parent?
(a) `child_id` (b) `parent_id` (c) `team_link_id` (d) `hierarchy_id`

**Answer:**
**(b) `parent_id`**

**Explanation:** As lovingly detailed in section 2.6 of the tutorial, the `parent_id` attribute on a team record holds the ID of its parent team. If it's `NULL`, it's a root team, living its best independent life.

### 2.2. Question 2

**Question:** True or False: In ELA v2.0, if you have access to a parent team, you automatically have access to all its sub-teams.

**Answer:**
**False**

**Explanation:** Section 2.7 of the tutorial hammers this home: ELA v2.0 features **no inheritance** for team permissions or membership. Access to a parent team does not magically grant access to its children. Each team is an island, permission-wise.

### 2.3. Question 3

**Question:** Which ELA document section details Team Hierarchy Permission Inheritance?
(a) PRD Section 4.3.3 (b) PRD Section 4.3.8 (c) PRD Section 4.21

**Answer:**
**(b) PRD Section 4.3.8**

**Explanation:** If you were paying attention in section 2.7 of the tutorial (the part with the yellow box!), you'd know that PRD Section 4.3.8 is where the "no inheritance" rule for ELA v2.0 team permissions is laid down. Section 4.3.3 is about the `staudenmeir/laravel-adjacency-list` package for hierarchy, and 4.21 is about configurable hierarchy depth. Close, but no cigar for the other options!

---

## 3. Related Documents

- `[010-navigating-team-hierarchies-permissions.md](./010-navigating-team-hierarchies-permissions.md)` - The tutorial this answer key is for. (You should probably read it *before* this.)
- `[../010-000-ela-prd.md](../010-000-ela-prd.md)` - Product Requirements Document (especially Sections 4.3, 4.3.8).
- `[060-interactive-tutorial-framework.md](./060-interactive-tutorial-framework.md)` - The grand plan for all these tutorials.

---

## 4. Version History

| Version | Date       | Author         | Changes                                     |
|---------|------------|----------------|---------------------------------------------|
| 0.1.0   | 2025-05-22 | GitHub Copilot | Initial draft of answers for exercises and assessment checks. |
