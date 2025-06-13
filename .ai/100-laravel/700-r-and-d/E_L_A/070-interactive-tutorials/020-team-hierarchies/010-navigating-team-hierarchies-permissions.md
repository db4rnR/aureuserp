<!-- filepath: /Users/s-a-c/nc/PhpstormProjects/ela-docs/docs/E_L_A/070-interactive-tutorials/020-team-hierarchies/010-navigating-team-hierarchies-permissions.md -->
# Interactive Tutorial: Navigating Team Hierarchies & Permissions

**Version:** 0.2.0
**Date:** 2025-05-22
**Author:** GitHub Copilot
**Status:** Draft
**Progress:** 40%

---

<details>
<summary>Table of Contents</summary>

- Interactive Tutorial: Navigating Team Hierarchies & Permissions
    - [1. Introduction](#1-introduction)
        - [1.1. Learning Objectives](#11-learning-objectives)
        - [1.2. Prerequisites](#12-prerequisites)
        - [1.3. Estimated Time](#13-estimated-time)
    - [2. Step-by-Step Guidance](#2-step-by-step-guidance)
        - [2.1. What are Teams in ELA?](#21-what-are-teams-in-ela)
        - [2.2. Understanding Hierarchical Structure](#22-understanding-hierarchical-structure)
        - [2.3. Accessing Team Management](#23-accessing-team-management)
        - [2.4. Creating a New Team](#24-creating-a-new-team)
            - [2.4.1. Creating a Root Team](#241-creating-a-root-team)
            - [2.4.2. Creating a Sub-Team](#242-creating-a-sub-team)
        - [2.5. Viewing and Navigating Team Hierarchy](#25-viewing-and-navigating-team-hierarchy)
        - [2.6. Key Team Attributes](#26-key-team-attributes)
        - [2.7. Understanding Team Permissions (ELA v2.0 Approach)](#27-understanding-team-permissions-ela-v20-approach)
    - [3. Interactive Elements](#3-interactive-elements)
        - [3.1. Hierarchy Explorer Questions](#31-hierarchy-explorer-questions)
        - [3.2. Scenario Quiz](#32-scenario-quiz)
    - [4. Revision Summary](#4-revision-summary)
    - [5. Exercises](#5-exercises)
        - [5.1. Exercise 1: Create a Root Team](#51-exercise-1-create-a-root-team)
        - [5.2. Exercise 2: Create a Sub-Team](#52-exercise-2-create-a-sub-team)
        - [5.3. Exercise 3: Identify Parent-Child Relationships](#53-exercise-3-identify-parent-child-relationships)
        - [5.4. Exercise 4: Discuss Permission Scope](#54-exercise-4-discuss-permission-scope)
    - [6. Assessment/Checks](#6-assessmentchecks)
    - [7. Overall Summary & Next Steps](#7-overall-summary--next-steps)
    - [8. Related Documents](#8-related-documents)
    - [9. Version History](#9-version-history)

</details>

---

## 1. Introduction

<div style="background-color: #f0f8ff; color: #333; padding: 15px; border-radius: 5px; border: 1px solid #add8e6; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #005a9c;">Welcome!</h4>
<p style="color: #333;">This interactive tutorial will guide you through understanding and managing team hierarchies and permissions within the Enhanced Laravel Application (ELA). Teams are a cornerstone of collaboration in ELA, and knowing how to navigate their structure is key!</p>
</div>

### 1.1. Learning Objectives

Upon completing this tutorial, you will be able to:

- Understand the purpose and importance of Teams in ELA.
- Describe how team hierarchies are structured.
- Locate and navigate the team management interface.
- Create root teams and sub-teams.
- Understand key attributes associated with teams.
- Explain the ELA v2.0 approach to team permission scoping (no inheritance).

### 1.2. Prerequisites

- Basic familiarity with the ELA concept.
- Access to the ELA documentation, particularly the [Product Requirements Document (PRD)](../../040-product-requirements/010-product-requirements.md).
- An understanding of general web application navigation.

### 1.3. Estimated Time

- **Tutorial Content:** 35-50 minutes
- **Interactive Exercises:** 15-25 minutes

---

## 2. Step-by-Step Guidance

<div style="background-color: #e6ffe6; color: #333; padding: 15px; border-radius: 5px; border: 1px solid #99e699; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #006400;">Let's Organize!</h4>
<p style="color: #333;">We'll explore how teams form the backbone of ELA's collaborative environment.</p>
</div>

### 2.1. What are Teams in ELA?

In the Enhanced Laravel Application (ELA), **Teams** serve as primary organizational units. They are designed to:

- **Group Users:** Bring together users who collaborate on specific projects or share common responsibilities.
- **Scope Data:** Act as boundaries for data. For example, resources like `Categories` are often directly associated with a specific Team (as per PRD 4.4).
- **Structure Collaboration:** Provide a clear framework for how users interact and share information.

Think of teams as departments, project groups, or any logical collection of users working towards a common goal within ELA.

<div style="background-color: #e6ffe6; color: #333; padding: 10px; border-radius: 5px; margin-top:10px; margin-bottom:10px;">
💡 **Quick Tip:** Teams are fundamental to how ELA organizes users and data, enabling structured collaboration.
</div>

### 2.2. Understanding Hierarchical Structure

ELA supports **hierarchical teams**. This means teams can have parent-child relationships, forming a tree-like structure.

- **Parent Team:** A team that has one or more sub-teams.
- **Sub-Team (Child Team):** A team that belongs to a parent team.
- **Root Team:** A team at the top of a hierarchy, with no parent.

This structure is typically implemented using a `parent_id` attribute on the team model, which references the ID of its parent team. The ELA PRD (Section 4.3.3) specifies using the `staudenmeir/laravel-adjacency-list` package for managing this.

**Example Hierarchy:**

```text
Marketing (Root Team)
  └── Social Media (Sub-Team of Marketing)
      └── Content Creators (Sub-Team of Social Media)
Engineering (Root Team)
  └── Backend (Sub-Team of Engineering)
  └── Frontend (Sub-Team of Engineering)
```

The depth of these hierarchies can be configured in ELA's application settings (PRD 4.3.4 & 4.21).

### 2.3. Accessing Team Management

Team management functionalities, such as creating, viewing, editing, and deleting teams, are typically handled within the ELA's **Admin Portal**. The PRD (Section 4.20) states that this portal is built with **Filament**.

To manage teams, you would generally:

1. Log in to ELA with an account that has administrative privileges (e.g., an `Admin` or a `Manager` with appropriate permissions).
2. Navigate to the Filament admin panel.
3. Locate the "Teams" or "Team Management" section.

*(Specific navigation steps would depend on the final Filament UI design.)*

### 2.4. Creating a New Team

#### 2.4.1. Creating a Root Team

A root team is a top-level team without a parent.

1. In the Team Management section of the Filament admin panel, look for an option like "Create Team" or "New Team".
2. You'll likely need to provide:
    - **Name:** A descriptive name for the team (e.g., "Sales Department").
    - **Slug:** A URL-friendly version of the name (often auto-generated).
    - Other relevant fields as defined (e.g., description, status).
3. Since this is a root team, you would typically leave the "Parent Team" field empty or select a "None" option.
4. Save the new team.

#### 2.4.2. Creating a Sub-Team

A sub-team is created under an existing parent team.

1. Follow the initial steps for creating a team.
2. When providing the team details, you will need to specify its **Parent Team**. This might be a dropdown list or a search field to select the existing team that will be the parent.
3. Fill in the other details (Name, Slug, etc.).
4. Save the new sub-team. It will now appear as a child of the selected parent team in the hierarchy.

### 2.5. Viewing and Navigating Team Hierarchy

The Filament admin panel should provide a way to visualize and navigate the team hierarchy (PRD 4.3.3 mentions a custom Filament UI for this). This might involve:

- A tree view display.
- A list view with clear indication of parent-child relationships (e.g., indentation, breadcrumbs).
- The ability to expand and collapse parent teams to see their sub-teams.

### 2.6. Key Team Attributes

When working with teams, you'll encounter several important attributes (fields):

- **`id`**: Unique identifier for the team.
- **`name`**: Human-readable name of the team.
- **`slug`**: URL-friendly identifier, often derived from the name.
- **`parent_id`**: The ID of the parent team. This is `NULL` for root teams.
- **`status`**: The current state of the team (e.g., `Forming`, `Active`, `Archived`), managed by a state machine (PRD 4.3.7).
- **`team_avatar`**: A visual identifier for the team (PRD 4.3.2).

### 2.7. Understanding Team Permissions (ELA v2.0 Approach)

A crucial aspect of team management is understanding how permissions relate to the team structure. For ELA version 2.0, the PRD (Section 4.3.8) specifies:

- **Explicit Per-Team Permissions:** Permissions are assigned directly to a team or to a user in the context of a specific team.
- **No Inheritance (for v2.0):** Membership or permissions in a parent team **do not** automatically grant membership or permissions in its sub-teams. Similarly, permissions in a sub-team do not affect the parent.

<div style="background-color: #fffacd; color: #333; padding: 10px; border-radius: 5px; margin-top:10px; margin-bottom:10px;">
🔍 **Important Note:** If User A is a member of "Marketing" (parent team), they are not automatically a member of "Social Media" (sub-team) unless explicitly added to "Social Media". Access to resources within "Social Media" would require specific permissions related to that sub-team.
</div>

This approach ensures clear, explicit control over access within each team, minimizing the risk of unintended access through complex inheritance rules.

---

## 3. Interactive Elements

<div style="background-color: #fffacd; color: #333; padding: 15px; border-radius: 5px; border: 1px solid #ffd700; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #b8860b;">Time to Interact!</h4>
<p style="color: #333;">Let's test your understanding of team structures and permissions with the following scenarios and questions. Refer back to section 2 if you need a refresher!</p>
</div>

### 3.1. Hierarchy Explorer Questions

Consider the following team structure:

```text
Global Corp (ID: 1, parent_id: NULL)
  ├── North America Division (ID: 2, parent_id: 1)
  │   ├── USA Operations (ID: 3, parent_id: 2)
  │   │   ├── East Coast Sales (ID: 4, parent_id: 3)
  │   │   └── West Coast Sales (ID: 5, parent_id: 3)
  │   └── Canada Operations (ID: 6, parent_id: 2)
  └── Europe Division (ID: 7, parent_id: 1)
      ├── UK Operations (ID: 8, parent_id: 7)
      └── Germany Operations (ID: 9, parent_id: 7)
```

Answer the following based on the structure above:

1. What is the `parent_id` of "UK Operations"?
2. List all the direct sub-teams of "North America Division".
3. How many levels deep is "West Coast Sales" from "Global Corp"?
4. If "East Coast Sales" is archived, does this automatically archive "USA Operations"? (Hint: Consider team independence vs. cascading effects, which are not explicitly stated for archiving but think about general data relationships).
5. Which team(s) are at the root level of this hierarchy?

*(Answers will be in the linked answer key document)*

### 3.2. Scenario Quiz

Answer the following questions based on ELA's team and permission concepts:

1. **True or False:** In ELA v2.0, if a user is a member of "North America Division" and has "View Documents" permission for that team, they automatically have "View Documents" permission for "USA Operations".
2. A new team, "Asia Division", needs to be added as a direct sub-team of "Global Corp". What would its `parent_id` be?
3. If a team has a `parent_id` of `NULL`, what does this signify?
4. User Sarah is explicitly added as a member to "East Coast Sales" and "West Coast Sales". Is she automatically a member of "USA Operations"?

*(Answers will be in the linked answer key document)*

---

## 4. Revision Summary

*(To be filled in once the content is more complete. This section will recap the key takeaways.)*

---

## 5. Exercises

<div style="background-color: #e0ffff; color: #333; padding: 15px; border-radius: 5px; border: 1px solid #afeeee; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #008080;">Practice Makes Perfect!</h4>
<p style="color: #333;">Apply your knowledge. Assume you have access to the ELA Admin Portal.</p>
</div>

### 5.1. Exercise 1: Create a Root Team

**Task:** Describe the steps you would take to create a new root team named "Finance" with an "Active" status. What key information would you need to provide?

### 5.2. Exercise 2: Create a Sub-Team

**Task:** After creating the "Finance" team, describe how you would create a sub-team named "Payroll" under "Finance". What makes this process different from creating a root team?

### 5.3. Exercise 3: Identify Parent-Child Relationships

**Task:** If you have teams "A", "B", and "C", where "A" is the parent of "B", and "B" is the parent of "C":

  a. What would be the `parent_id` of team "B"?
  b. What would be the `parent_id` of team "A"?

### 5.4. Exercise 4: Discuss Permission Scope

**Task:** User Jane is a member of the "Finance" root team and has permission to view all documents within that team. The "Payroll" sub-team has highly sensitive documents. According to ELA v2.0's permission model (no inheritance), does Jane automatically have permission to view documents in the "Payroll" sub-team? Explain your reasoning.

<div style="color: #333; padding: 10px; border-radius: 5px; margin-top:10px; margin-bottom:10px;">
🔗 **Answers:** Sample answers will be available in <a href="./020-navigating-team-hierarchies-permissions-answers.md">020-navigating-team-hierarchies-permissions-answers.md</a>.
</div>

---

## 6. Assessment/Checks

<div style="background-color: #fff0f5; color: #333; padding: 15px; border-radius: 5px; border: 1px solid #ffc0cb; margin-bottom: 20px;">
<h4 style="margin-top: 0; color: #c71585;">Knowledge Check!</h4>
<p style="color: #333;">Test your understanding with these questions. The answers can be found in the answer key.</p>
</div>

1. What attribute in the teams table links a sub-team to its parent?
    (a) `child_id` (b) `parent_id` (c) `team_link_id` (d) `hierarchy_id`
2. **True or False:** In ELA v2.0, if you have access to a parent team, you automatically have access to all its sub-teams.
3. Which ELA document section details Team Hierarchy Permission Inheritance (specifically, the lack thereof for v2.0)?
    (a) PRD Section 4.3.3 (b) PRD Section 4.3.8 (c) PRD Section 4.21
4. To create a new root team named "Logistics", what value should its `parent_id` have?
    (a) 0 (b) 1 (c) `NULL` (d) The ID of the "Global Corp" team
5. The `staudenmeir/laravel-adjacency-list` package is mentioned in the PRD for managing:
    (a) User permissions (b) Team avatars (c) Team hierarchies (d) Application settings

*(Answers will be in the linked answer key document)*

---

## 7. Overall Summary & Next Steps

*(To be filled in once the content is more complete.)*

---

## 8. Related Documents

- [../../040-product-requirements/010-product-requirements.md](../../040-product-requirements/010-product-requirements.md) - Product Requirements Document (see Sections 4.3, 4.21)
- [060-interactive-tutorial-framework.md](./060-interactive-tutorial-framework.md) - Interactive Tutorial Framework
- [020-navigating-team-hierarchies-permissions-answers.md](./020-navigating-team-hierarchies-permissions-answers.md)

---

## 9. Version History

| Version | Date       | Author         | Changes                                     |
|---------|------------|----------------|---------------------------------------------|
| 0.1.0   | 2025-05-22 | GitHub Copilot | Initial draft structure and content for sections 1, 2, part of 3, 5, 6, 8, 9. Progress set to 15%. |
| 0.2.0   | 2025-05-22 | GitHub Copilot | Updated interactive elements in section 3, refined assessment in section 6, updated version and progress. |

---
