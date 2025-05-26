// helpcenter.js

const editButton = document.getElementById('editButton');
const saveButton = document.getElementById('saveButton');
const faqsContainer = document.getElementById('faqs-container');

let isEditing = false;
let faqsData = [];

// Load FAQs from JSON file
async function loadFaqs() {
  try {
    const response = await fetch('helpcenter.json');
    faqsData = await response.json();
    renderFaqs();
  } catch (error) {
    faqsContainer.innerHTML = '<p>Error loading FAQs data.</p>';
    console.error('Error fetching FAQs:', error);
  }
}

// Render FAQs into the container
function renderFaqs() {
  faqsContainer.innerHTML = '';
  faqsData.forEach((faq, index) => {
    const faqItem = document.createElement('div');
    faqItem.classList.add('faq-item', 'mb-3');

    faqItem.innerHTML = `
      <h5 contenteditable="${isEditing}" class="faq-question border ${isEditing ? 'border-secondary rounded p-2' : 'border-0'}" data-index="${index}">${faq.question}</h5>
      <p contenteditable="${isEditing}" class="faq-answer border ${isEditing ? 'border-secondary rounded p-2' : 'border-0'}" data-index="${index}">${faq.answer}</p>
    `;

    faqsContainer.appendChild(faqItem);
  });
}

// Toggle edit mode
editButton.addEventListener('click', () => {
  isEditing = !isEditing;
  if (isEditing) {
    editButton.textContent = 'Cancel Edit';
    saveButton.style.display = 'inline-block';
  } else {
    editButton.textContent = 'Toggle Edit';
    saveButton.style.display = 'none';
    // Reload original data to discard changes if canceled
    loadFaqs();
  }
  renderFaqs();
});

// Save changes to JSON file via POST to PHP
saveButton.addEventListener('click', async () => {
  // Collect edited data
  const questions = document.querySelectorAll('.faq-question');
  const answers = document.querySelectorAll('.faq-answer');

  let updatedFaqs = [];
  for (let i = 0; i < questions.length; i++) {
    updatedFaqs.push({
      question: questions[i].textContent.trim(),
      answer: answers[i].textContent.trim(),
    });
  }

  try {
    const response = await fetch('helpcenter.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(updatedFaqs),
    });

    if (response.ok) {
      alert('FAQs saved successfully!');
      isEditing = false;
      editButton.textContent = 'Toggle Edit';
      saveButton.style.display = 'none';
      loadFaqs();
    } else {
      alert('Failed to save FAQs.');
    }
  } catch (error) {
    alert('Error saving FAQs.');
    console.error(error);
  }
});

// Initialize
saveButton.style.display = 'none';
loadFaqs();