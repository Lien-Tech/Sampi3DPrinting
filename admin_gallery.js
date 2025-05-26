document.addEventListener('DOMContentLoaded', function () {
  fetch('gallery.json')
    .then(response => response.json())
    .then(data => {
      const cards = document.querySelectorAll('.flip-card');
      cards.forEach((card, index) => {
        const img = card.querySelector('.editable-image');
        const title = card.querySelector('.editable-title');
        const description = card.querySelector('.editable-description');
        if (data[index]) {
          img.src = data[index].image;
          title.textContent = data[index].title;
          description.textContent = data[index].description;
        }
      });
    });
});

document.getElementById('saveButton').addEventListener('click', () => {
  const cards = document.querySelectorAll('.flip-card');
  const updatedData = [];

  cards.forEach(card => {
    const img = card.querySelector('.editable-image');
    const title = card.querySelector('.editable-title');
    const description = card.querySelector('.editable-description');

    updatedData.push({
      image: img.src,
      title: title.textContent,
      description: description.textContent
    });
  });

  fetch('gallery.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(updatedData)
  })
    .then(response => response.text())
    .then(data => alert('Gallery saved successfully!'))
    .catch(error => alert('Error saving gallery.'));
});

document.getElementById('editButton').addEventListener('click', () => {
  const cards = document.querySelectorAll('.flip-card');
  const isEditing = document.body.classList.toggle('editing');

  cards.forEach(card => {
    const img = card.querySelector('.editable-image');
    const title = card.querySelector('.editable-title');
    const description = card.querySelector('.editable-description');

    title.contentEditable = isEditing;
    description.contentEditable = isEditing;

    if (isEditing) {
      img.addEventListener('click', handleImageChange);
      img.style.cursor = 'pointer';
      img.style.border = '2px dashed #ccc';
    } else {
      img.removeEventListener('click', handleImageChange);
      img.style.cursor = 'default';
      img.style.border = 'none';
    }
  });
});

function handleImageChange(event) {
  const fileInput = document.createElement('input');
  fileInput.type = 'file';
  fileInput.accept = 'image/*';

  fileInput.onchange = () => {
    const file = fileInput.files[0];
    const reader = new FileReader();
    reader.onload = () => {
      event.target.src = reader.result;
    };
    if (file) reader.readAsDataURL(file);
  };

  fileInput.click();
}