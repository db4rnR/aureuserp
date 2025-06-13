# Interactive Diagrams

This directory contains interactive versions of the diagrams used in the Enhanced Laravel Application (ELA) documentation. These interactive diagrams provide a more engaging and informative experience compared to static diagrams.

## Available Interactive Diagrams

The following interactive diagrams are available:

1. [Event Sourcing Flow](/_root/docs/E_L_A/illustrations/interactive/event-sourcing-flow-interactive.html) - Interactive diagram showing the flow of commands and events through an event-sourced system
2. [Team Aggregate States](/_root/docs/E_L_A/illustrations/interactive/team-aggregate-states-interactive.html) - Interactive diagram showing the possible states of a Team aggregate
3. [Post Aggregate States](/_root/docs/E_L_A/illustrations/interactive/post-aggregate-states-interactive.html) - Interactive diagram showing the possible states of a Post aggregate
4. [Todo Aggregate States](/_root/docs/E_L_A/illustrations/interactive/todo-aggregate-states-interactive.html) - Interactive diagram showing the possible states of a Todo aggregate

## Features

All interactive diagrams include the following features:

### Component/State Highlighting

- Click on buttons to highlight specific components or states
- See detailed information about the highlighted component or state
- Reset highlighting with a single click

### Transition Visualization

- For state diagrams, visualize transitions from specific states
- See all possible transitions for each state
- Understand the events that trigger state transitions

### Dark/Light Mode Toggle

- Switch between dark and light mode with a single click
- Consistent styling across all diagrams
- Preserves the current state of the diagram when switching modes

### Accessibility Features

- Keyboard shortcuts for all interactive features
- Font size controls for better readability
- High contrast mode for improved visibility
- Screen reader support with ARIA attributes

### Tooltips and Information Panels

- Hover over components or states to see tooltips with brief descriptions
- Click on components or states to see detailed information in the information panel
- Information panels include descriptions, purposes, and relationships

### Static Diagram Links

- Links to static versions of the diagrams for users who prefer non-interactive content
- Both light and dark mode static diagrams are available

## How to Use

### Basic Navigation

1. Open any of the interactive diagrams in a web browser
2. Use the buttons at the top to highlight specific components or states
3. Click on components or states in the diagram to see detailed information
4. Use the "Reset" button to clear all highlights

### Keyboard Shortcuts

Each diagram includes keyboard shortcuts for common actions:

- **Event Sourcing Flow**:
  - `W` - Highlight Write Side
  - `S` - Highlight Storage
  - `R` - Highlight Read Side
  - `P` - Highlight Process Management
  - `E` - Highlight Side Effects
  - `Esc` - Reset Highlights
  - `T` - Toggle Dark/Light Mode

- **Team Aggregate States**:
  - `F` - Highlight Forming
  - `A` - Highlight Active
  - `R` - Highlight Archived
  - `D` - Highlight Deleted
  - `Esc` - Reset Highlights
  - `T` - Toggle Dark/Light Mode

- **Post Aggregate States**:
  - `D` - Highlight Draft
  - `R` - Highlight Pending Review
  - `P` - Highlight Published
  - `S` - Highlight Scheduled
  - `A` - Highlight Archived
  - `X` - Highlight Deleted
  - `Esc` - Reset Highlights
  - `T` - Toggle Dark/Light Mode

- **Todo Aggregate States**:
  - `P` - Highlight Pending
  - `I` - Highlight In Progress
  - `C` - Highlight Completed
  - `X` - Highlight Cancelled
  - `D` - Highlight Deleted
  - `Esc` - Reset Highlights
  - `T` - Toggle Dark/Light Mode

### Accessibility Options

Each diagram includes accessibility controls:

1. **Increase Font Size** - Makes all text larger for better readability
2. **Decrease Font Size** - Makes all text smaller if the increased size is too large
3. **Toggle High Contrast** - Increases contrast for better visibility

## Technical Details

### Implementation

The interactive diagrams are implemented using:

- **HTML** - For structure and content
- **CSS** - For styling and visual effects
- **JavaScript** - For interactivity and dynamic behavior
- **Mermaid.js** - For rendering the base diagrams

### Browser Compatibility

The interactive diagrams are compatible with:

- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)

### Accessibility Compliance

The interactive diagrams are designed to be accessible to all users, including those with disabilities. They comply with:

- WCAG 2.1 Level AA
- Section 508 requirements
- Keyboard accessibility guidelines
- Screen reader compatibility

## Troubleshooting

If you encounter any issues with the interactive diagrams:

1. **Diagram Not Rendering** - Try refreshing the page or using a different browser
2. **Interactivity Not Working** - Ensure JavaScript is enabled in your browser
3. **Dark Mode Not Working** - Check if your browser supports CSS variables
4. **Accessibility Features Not Working** - Ensure your browser is up to date

## Feedback and Contributions

If you have feedback or suggestions for improving the interactive diagrams, please:

1. Open an issue in the project repository
2. Include the name of the diagram and a description of your feedback
3. If applicable, include steps to reproduce any issues you encountered

## Related Resources

- [Static Diagrams](/_root/docs/E_L_A/illustrations/mermaid) - Static versions of all diagrams
- [Documentation Style Guide](/_root/docs/E_L_A/220-ela-documentation-style-guide-v1.2.0.md) - Guidelines for creating and using diagrams
- [Event Sourcing Implementation](/_root/docs/E_L_A/100-implementation-plan/100-350-event-sourcing/010-overview.md) - Documentation on event sourcing implementation
