document.getElementById('saveButton').addEventListener('click', () => {
  const editableElements = document.querySelectorAll('.editable');
  const content = Array.from(editableElements).map(el => el.innerHTML);

  fetch('contacts.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ content })
  })
  .then(res => res.text())
  .then(res => alert('Saved!'))
  .catch(err => console.error('Error:', err));
});