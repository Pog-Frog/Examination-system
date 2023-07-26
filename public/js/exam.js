// Select the timer element
const hoursSpan = document.getElementById('hours');
const minutesSpan = document.getElementById('minutes');
const secondsSpan = document.getElementById('seconds');

// Get the exam duration from the database or use a default value
const examDuration = document.getElementById("remaining-time").value;; // in seconds

// Calculate the end time of the exam
const now = new Date();
const endTime = new Date(now.getTime() + examDuration * 1000);

// Initialize the timer state
let remainingTime = examDuration;
let timerInterval = null;

// Listen for changes in page visibility
document.addEventListener("visibilitychange", handleVisibilityChange);

// Start the timer
startTimer();

function startTimer() {
  // Update the timer every second
  timerInterval = setInterval(updateTimer, 1000);
  updateTimer();
}

function stopTimer() {
  // Stop the timer interval
  clearInterval(timerInterval);
}

function handleVisibilityChange() {
  if (document.visibilityState === 'hidden') {
    // Pause the timer when the tab is hidden
    stopTimer();
  } else {
    // Resume the timer when the tab is visible
    startTimer();
  }
}

function updateTimer() {
  // Calculate the remaining time
  remainingTime = calculateRemainingTime();

  // Exit if the timer has expired
  if (remainingTime == 0) {
    clearInterval(timerInterval);
    submitExam();
  }

  // Display the remaining time
  displayRemainingTime(remainingTime);
}

function calculateRemainingTime() {
  const now = new Date();
  return Math.max(0, Math.floor((endTime - now) / 1000));
}

function displayRemainingTime(remainingTime) {
  const hours = Math.floor(remainingTime / 3600);
  const minutes = Math.floor((remainingTime % 3600) / 60);
  const seconds = remainingTime % 60;

  hoursSpan.textContent = `${hours.toString().padStart(2, '0')}`;
  minutesSpan.textContent = `${minutes.toString().padStart(2, '0')}`;
  secondsSpan.textContent = `${seconds.toString().padStart(2, '0')}`;
}

function submitExam() {
  // Get the form element
  const examForm = document.getElementById('exam-form');

  // Set the value of empty fields to '?' when form is submitted automaticlly for grading
  const requiredFields = examForm.querySelectorAll('[required]');
  requiredFields.forEach(field => {
    if (!field.value) {
      field.value = '?';
    }
  });

  // Set the value of unanswered radio buttons to '?'
  const radioButtons = examForm.querySelectorAll('input[type="radio"]');
  const radioGroups = {};
  radioButtons.forEach(button => {
    if (!radioGroups[button.name]) {
      radioGroups[button.name] = [];
    }
    radioGroups[button.name].push(button);
  });

  for (const groupName in radioGroups) {
    const groupButtons = radioGroups[groupName];
    let isAnswered = false;
    groupButtons.forEach(button => {
      if (button.checked) {
        isAnswered = true;
      }
    });

    if (!isAnswered) {
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = groupName;
      hiddenInput.value = '?';
      hiddenInput.required = groupButtons[0].required;
      groupButtons[0].parentNode.insertBefore(hiddenInput, groupButtons[0]);
    }
  }
  // Submit the form
  examForm.submit();
}


let tabSwitchesCount = 0;
const examTitle = document.title;
const tabSwitchesInput = document.getElementById('tabSwitchesInput');

window.addEventListener('blur', function() {
  tabSwitchesCount++;
  document.title = '⚠️ Tab Switch Detected';
  tabSwitchesInput.value = tabSwitchesCount; 
});

window.addEventListener('focus', function() {
  document.title = examTitle; 
});

document.addEventListener('contextmenu', function(event) {
  event.preventDefault();  // prevent student from right click menu
});

document.addEventListener('copy', function(e) {
  e.preventDefault();
});
// prevent student from copying and pasting 
document.addEventListener('paste', function(e) {
  e.preventDefault();
});








