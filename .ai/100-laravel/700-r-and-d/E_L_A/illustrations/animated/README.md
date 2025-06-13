# Animated Diagrams

This directory contains animated versions of key diagrams for the Enhanced Laravel Application (ELA) documentation. These animations help visualize processes and state transitions in a step-by-step manner.

## Available Animated Diagrams

| Diagram Name | Description | Link |
|--------------|-------------|------|
| Event Sourcing Flow | Animated flow of events through an event-sourced system | [View Diagram](/_root/docs/E_L_A/illustrations/animated/event-sourcing-flow-animated.html) |
| Team Aggregate States | Animated state transitions for Team aggregate | [View Diagram](/_root/docs/E_L_A/illustrations/animated/team-aggregate-states-animated.html) |
| Post Aggregate States | Animated state transitions for Post aggregate | [View Diagram](/_root/docs/E_L_A/illustrations/animated/post-aggregate-states-animated.html) |
| Todo Aggregate States | Animated state transitions for Todo aggregate | [View Diagram](/_root/docs/E_L_A/illustrations/animated/todo-aggregate-states-animated.html) |

## Usage Instructions

### Animation Controls

Each animated diagram includes the following controls:

- **Play Animation**: Starts the animation from the current step
- **Pause**: Pauses the animation at the current step
- **Reset**: Resets the animation to the beginning
- **Step Forward**: Advances the animation by one step
- **Toggle Dark/Light Mode**: Switches between dark and light themes

### Step Descriptions

As the animation progresses, a description panel updates to explain each step in detail. This helps understand what's happening at each stage of the process or state transition.

### Keyboard Shortcuts

For easier navigation, the following keyboard shortcuts are available:

- **Space**: Play/Pause animation
- **R**: Reset animation
- **→** (Right Arrow): Step forward
- **T**: Toggle dark/light mode
- **A**: Toggle animations on/off

## Features

### Step-by-Step Animation

The animations break down complex processes into discrete steps, making it easier to understand how components interact or how states transition.

#### Event Sourcing Flow Animation

The Event Sourcing Flow animation illustrates:

1. User action triggering a command
2. Command being processed by a command handler
3. Aggregate being updated
4. Event being generated
5. Event being stored in the event store
6. Projector processing the event to update read models
7. Process manager generating new commands
8. Reactor executing side effects

#### Aggregate State Animations

The Aggregate State animations illustrate:

1. Initial state creation
2. Transitions between states
3. Events that trigger state changes
4. Available actions in each state

### State Information Tables

Each aggregate state diagram includes a table with:

- State names
- Descriptions of each state
- Color coding for visual identification

### Accessibility Features

The animated diagrams include several accessibility features:

- **Font Size Controls**: Buttons to increase or decrease text size
- **Animation Disabling**: Option to disable animations for users who prefer static content
- **Keyboard Navigation**: Full keyboard control for all features
- **Reduced Motion Support**: Respects the user's system preference for reduced motion
- **ARIA Labels**: Proper labeling for screen readers

### Theme Support

All animated diagrams support both light and dark themes:

- **Light Theme**: Default theme with light background and dark text
- **Dark Theme**: Alternative theme with dark background and light text

## Troubleshooting

### Animation Not Working

If the animation doesn't start when you click "Play Animation":

1. Check that JavaScript is enabled in your browser
2. Try refreshing the page
3. Use the "Step Forward" button to advance manually

### Diagram Not Rendering

If the diagram doesn't appear:

1. Make sure you're using a modern browser (Chrome, Firefox, Safari, Edge)
2. Check your internet connection (the diagrams use Mermaid.js loaded from a CDN)
3. Try the static versions linked at the bottom of each page

## Related Resources

- [Static Diagrams](/_root/docs/E_L_A/illustrations/mermaid): Non-animated versions of these diagrams
- [Interactive Diagrams](/_root/docs/E_L_A/illustrations/interactive): Interactive versions with component highlighting
- [Mermaid Documentation](https:/mermaid.js.org): Documentation for the diagramming tool used

## Technical Implementation

The animated diagrams are implemented using:

- **Mermaid.js**: For rendering the base diagrams
- **CSS Animations**: For animating diagram elements
- **JavaScript**: For controlling the animations and handling user interactions

The animations use a step-by-step approach where each step reveals and animates specific elements of the diagram. This helps focus attention on the relevant parts of the process at each stage.

## Feedback and Contributions

If you have suggestions for improving these animated diagrams or would like to contribute new ones, please follow the [Contributing New Diagrams](/_root/docs/E_L_A/illustrations/index.md#contributing-new-diagrams) guidelines in the main illustrations index.
