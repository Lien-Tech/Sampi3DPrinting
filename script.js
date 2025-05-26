// Existing login/register button script
const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

if (registerBtn && loginBtn && container) {
    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
}

// Drag & drop image upload script
const dropZone = document.getElementById('dropZone');
const productImage = document.getElementById('productImage');

if (dropZone && productImage) {
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');

        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (event) {
                productImage.src = event.target.result;
            };
            reader.readAsDataURL(file);

            // Upload to server
            const formData = new FormData();
            formData.append('image', file);

            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                console.log('Upload response:', data);
            })
            .catch(err => {
                console.error('Upload failed:', err);
            });
        } else {
            alert("Please drop a valid image file.");
        }
    });
}