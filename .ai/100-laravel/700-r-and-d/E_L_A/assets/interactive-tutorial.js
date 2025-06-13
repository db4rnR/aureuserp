// JavaScript for interactive tutorial elements

document.addEventListener('DOMContentLoaded', () => {
    console.log('Interactive tutorial JS loaded.');

    // Placeholder for future interactivity logic
    // Example: Find all check answer buttons and add event listeners
    const checkButtons = document.querySelectorAll('.check-answer-btn');

    checkButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const questionElement = event.target.closest('.interactive-question');
            if (questionElement) {
                const userInput = questionElement.querySelector('textarea, input[type=\'text\']');
                const feedbackElement = questionElement.querySelector('.feedback');
                const correctAnswer = questionElement.dataset.correctAnswer; // Assuming answer stored in data attribute

                if (userInput && feedbackElement && correctAnswer) {
                    if (userInput.value.trim().toLowerCase() === correctAnswer.trim().toLowerCase()) {
                        feedbackElement.textContent = 'Correct!';
                        feedbackElement.className = 'feedback correct';
                    } else {
                        feedbackElement.textContent = 'Incorrect. Try again!';
                        feedbackElement.className = 'feedback incorrect';
                    }
                } else {
                    console.error('Could not find all necessary elements for question:', questionElement);
                }
            }
        });
    });
});
