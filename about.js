document.getElementById('saveButton').addEventListener('click', () => {
  const editableElements = document.querySelectorAll('.editable');
  const data = Array.from(editableElements).map(el => el.innerHTML);

  fetch('about.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ content: data })
  })
  .then(response => response.text())
  .then(data => {
    alert('Changes saved!');
  })
  .catch(error => console.error('Error:', error));
});