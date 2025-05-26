document.addEventListener("DOMContentLoaded", () => {
  fetch("faq.php")
    .then(res => res.json())
    .then(data => {
      // Example: Set intro texts
      document.querySelector(".introfaq p:nth-child(1)").textContent = data.intro.heading;
      document.querySelector(".introfaq p:nth-child(2)").textContent = data.intro.subheading;
      document.querySelector(".sub p").textContent = data.intro.description;

      // Save to a global variable so we can update it on save
      window.faqData = data;
    });

  document.getElementById("editButton").addEventListener("click", () => {
    const editableElements = document.querySelectorAll(".editable");

    if (!window.isEditing) {
      editableElements.forEach(el => {
        el.contentEditable = true;
        el.style.border = "1px dashed #999";
      });
    } else {
      editableElements.forEach(el => {
        el.contentEditable = false;
        el.style.border = "none";
      });

      // You must update window.faqData with current DOM content here before saving
      // For example:
      window.faqData.intro.heading = document.querySelector(".introfaq p:nth-child(1)").textContent;
      window.faqData.intro.subheading = document.querySelector(".introfaq p:nth-child(2)").textContent;
      window.faqData.intro.description = document.querySelector(".sub p").textContent;

      // Do this for all sections...

      fetch("faq.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(window.faqData)
      })
        .then(res => res.json())
        .then(result => {
          if (result.status === "success") alert("Changes saved successfully.");
        });
    }

    window.isEditing = !window.isEditing;
  });
});