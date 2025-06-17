<!-- filepath: /Users/s-a-c/nc/PhpstormProjects/ela-docs/docs/E_L_A/070-interactive-tutorials/040-real-time-collaboration/020-real-time-collaboration-chat-answers.md -->
# Answer Key: Real-Time Collaboration: Understanding ELA Chat

**Version:** 0.1.0
**Date:** 2025-05-22
**Author:** GitHub Copilot
**Status:** Draft

---

<details>
<summary>Table of Contents</summary>

- [Answer Key: Real-Time Collaboration: Understanding ELA Chat](#answer-key-real-time-collaboration-understanding-ela-chat)
  - [1. Exercise 1: Core Chat Features](#1-exercise-1-core-chat-features)
  - [2. Exercise 2: Team Communication Dynamics](#2-exercise-2-team-communication-dynamics)
  - [3. Exercise 3: Staying Informed](#3-exercise-3-staying-informed)
  - [4. Quiz Answers](#4-quiz-answers)
    - [4.1. Question 1](#41-question-1)
    - [4.2. Question 2](#42-question-2)
    - [4.3. Question 3](#43-question-3)
  - [5. Related Documents](#5-related-documents)
  - [6. Version History](#6-version-history)

</details>

## 1. Exercise 1: Core Chat Features

**Task:** Based on the ELA PRD (Section 3.2.4 "Real-time Communication"), list three core functionalities provided by the ELA chat system for users.

**Solution:**
1.  **Direct Messaging:** Allows users to have one-on-one private conversations.
2.  **Team/Group Channels:** Facilitates discussions within specific teams or project groups.
3.  **File Sharing:** Enables users to share documents and other files directly within chat conversations.

## 2. Exercise 2: Team Communication Dynamics

**Task:** According to the ELA PRD (Section 3.1.2 "Team Structure & Roles" and 3.2.4 "Real-time Communication"):
1.  When a new team is created in ELA, is a dedicated chat channel automatically provisioned for that team?
2.  Can users be part of multiple team chat channels simultaneously?

**Solution:**
1.  Yes, the PRD implies that team creation includes associated communication channels: "Each team will have a dedicated space... including chat channels." (derived from intent of 3.1.2 and 3.2.4).
2.  Yes, users can be members of multiple teams, and therefore would have access to the chat channels of all teams they belong to.

## 3. Exercise 3: Staying Informed

**Task:** Based on the ELA PRD (Section 3.2.4 "Real-time Communication"):
1.  How does ELA inform users of new chat messages?
2.  What feature helps users determine if a colleague is currently active in ELA?

**Solution:**
1.  ELA uses **real-time notifications** (e.g., visual cues, possibly sound alerts – though specific alert types might be user-configurable) to inform users of new messages.
2.  **User presence indicators** (e.g., online, offline, away statuses) help users see if colleagues are active.

## 4. Quiz Answers

### 4.1. Question 1

Which of the following is NOT explicitly mentioned as a core chat functionality in the PRD's "Real-time Communication" section?

**Answer:** (b) Video Conferencing

**Explanation:** While direct messaging, team channels, and file sharing are mentioned (PRD 3.2.4), video conferencing is not listed as a core feature of the chat system in that section. It might be a separate feature or a future consideration, but it's not part of the specified core chat functionalities.

### 4.2. Question 2

True or False: In ELA, a user can only be a member of one team chat channel at a time.

**Answer:** False

**Explanation:** Users can be part of multiple teams (PRD 3.1.2), and each team has its own communication channels. Therefore, users can participate in multiple team chat channels.

### 4.3. Question 3

What is the primary purpose of user presence indicators in ELA chat?

**Answer:** (b) To show if a user is available or away, aiding in communication timing.

**Explanation:** User presence indicators (PRD 3.2.4) are designed to provide visibility into colleagues' availability, helping users decide the best time to initiate contact and manage communication expectations.

## 5. Related Documents

- [010-real-time-collaboration-chat.md](./010-real-time-collaboration-chat.md) - The main tutorial document.
- [../../040-product-requirements/010-product-requirements.md](../../040-product-requirements/010-product-requirements.md) - Product Requirements Document (especially Sections 3.1.2 and 3.2.4).

---

## 6. Version History

| Version | Date       | Changes                                      | Author          |
|---------|------------|----------------------------------------------|-----------------|
| 0.1.0   | 2025-05-22 | Initial draft of the answer key.             | GitHub Copilot  |
