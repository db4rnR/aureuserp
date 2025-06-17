<!-- filepath: /Users/s-a-c/nc/PhpstormProjects/ela-docs/docs/E_L_A/070-interactive-tutorials/030-content-management/020-mastering-content-management-answers.md -->
# Answer Key: Mastering Content Management: Posts and Categories

**Version:** 0.1.0
**Date:** 2025-05-22
**Author:** GitHub Copilot
**Status:** Draft

---

<details>
<summary>Table of Contents</summary>

- [Answer Key: 3. Mastering Content Management: Posts and Categories](#answer-key-3-mastering-content-management-posts-and-categories)
  - [3.1. Exercise 1: Key Features of Post Creation](#31-exercise-1-key-features-of-post-creation)
  - [3.2. Exercise 2: Managing and Assigning Categories](#32-exercise-2-managing-and-assigning-categories)
  - [3.3. Exercise 3: A Contributor's Submission Process](#33-exercise-3-a-contributors-submission-process)
  - [3.4. Quiz Answers](#34-quiz-answers)
    - [3.4.1. Question 1](#341-question-1)
    - [3.4.2. Question 2](#342-question-2)
    - [3.4.3. Question 3](#343-question-3)
  - [3.5. Version History](#35-version-history)

</details>

## 1. Exercise 1: Key Features of Post Creation

**Task:** Based on the ELA PRD (Section 3.2.1 "Content Creation & Management"), list three primary features ELA offers to users when they are creating or editing a post.

**Solution:**
1.  **WYSIWYG Editor:** Allows for rich text formatting and an intuitive content creation experience.
2.  **Version History:** Tracks changes to posts, enabling users to revert to previous versions if needed.
3.  **Scheduling:** Provides the ability to schedule posts for future publication.

## 2. Exercise 2: Managing and Assigning Categories

**Task:** An "Author" user has written a new blog post. According to the ELA PRD (Sections 3.2.2 "Categorization & Tagging" and 3.2.6 "User Roles and Permissions"):
1.  Which user role(s) can create a new category (e.g., "Product Updates")?
2.  Can the "Author" assign their post to an *existing* category?

**Solution:**
1.  **"Administrator"** and **"Editor"** roles can manage categories, which includes creating new ones.
2.  Yes, an **"Author"** can typically assign their posts to existing categories. The PRD states Editors manage categories, implying creation and structural changes, while Authors focus on their content and its placement within the existing structure.

## 3. Exercise 3: A Contributor's Submission Process

**Task:** A "Contributor" user has just finished writing an insightful article. According to the ELA PRD (Section 3.2.6 "User Roles and Permissions"), what is the immediate next step for their article? Can they publish it themselves?

**Solution:** A "Contributor" cannot publish the article themselves. The immediate next step is to **submit the post for review**. An "Editor" or "Administrator" will then need to review and approve/publish the article.

## 4. Quiz Answers

### 4.1. Question 1

Which ELA user role can publish and manage posts and pages, *including those created by other users*?

**Answer:** (c) Editor

**Explanation:** The PRD (Section 3.2.6) specifies that Editors can manage all content, including that of other users.

### 4.2. Question 2

True or False: The "Version History" feature in ELA is primarily for scheduling when posts go live.

**Answer:** False

**Explanation:** Version History (PRD Section 3.2.1) is for tracking changes and reverting to previous versions. Scheduling is a separate feature.

### 4.3. Question 3

If a user wants to create a new top-level category for blog posts, which of these roles is explicitly stated in the PRD as being able to manage (and thus create) categories?

**Answer:** (b) Editor

**Explanation:** The PRD (Section 3.2.2 and 3.2.6) indicates that Editors (and Administrators) are responsible for managing categories.

## 5. Related Documents

- [010-mastering-content-management.md](./010-mastering-content-management.md) - The main tutorial document.
- [../../040-product-requirements/010-product-requirements.md](../../040-product-requirements/010-product-requirements.md) - Product Requirements Document (especially Sections 3.2.1, 3.2.2, and 3.2.6).

---

## 6. Version History

| Version | Date       | Changes                                      | Author          |
|---------|------------|----------------------------------------------|-----------------|
| 0.1.0   | 2025-05-22 | Initial draft of the answer key.             | GitHub Copilot  |
