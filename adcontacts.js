document.addEventListener("DOMContentLoaded", function () {
  const fields = {
    title1: document.getElementById("title1"),
    title2: document.getElementById("title2"),
    getInTouch: document.getElementById("getInTouch"),
    phone: document.getElementById("phone"),
    email: document.getElementById("email"),
    location: document.getElementById("location"),
    facebook: document.getElementById("facebook")
  };

  const saveButton = document.getElementById("saveChanges");

  fetch("contacts.php")
    .then((res) => res.json())
    .then((data) => {
      fields.title1.textContent = data.description.title1;
      fields.title2.textContent = data.description.title2;
      fields.getInTouch.textContent = data.description.getInTouch;
      fields.phone.textContent = data.contactInfo.phone;
      fields.email.textContent = data.contactInfo.email;
      fields.location.textContent = data.location;
      fields.facebook.href = data.social.facebook;
    });

  saveButton.addEventListener("click", function () {
    const updatedData = {
      contactInfo: {
        phone: fields.phone.textContent,
        email: fields.email.textContent
      },
      location: fields.location.textContent,
      social: {
        facebook: fields.facebook.href
      },
      description: {
        title1: fields.title1.textContent,
        title2: fields.title2.textContent,
        getInTouch: fields.getInTouch.textContent
      }
    };

    fetch("contacts.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(updatedData)
    })
      .then((res) => res.json())
      .then((result) => {
        alert(result.message);
      });
  });
});