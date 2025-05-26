document.addEventListener("DOMContentLoaded", () => {
  const editButton = document.getElementById("editButton");
  let editing = false;

  const page = window.location.pathname;

  // Shared function to make elements editable
  function toggleEditable(selectors, state) {
    selectors.forEach(sel => {
      const elements = document.querySelectorAll(sel);
      elements.forEach(el => el.contentEditable = state);
    });
  }

  editButton?.addEventListener("click", async () => {
    editing = !editing;
    editButton.textContent = editing ? "Save Content" : "Edit Content";

    if (page.includes("adsampi")) {
      toggleEditable([".description", ".product-content"], editing);

      if (!editing) {
        try {
          const productArray = [];
          const uploadPromises = [];

          for (let i = 1; i <= 9; i++) {
            const box = document.querySelector(`#productImage${i}`).closest('.box');
            const titleElement = box.querySelector('.product-content h3');
            const descElement = box.querySelector('.description');
            const imageElement = document.getElementById(`productImage${i}`);

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = imageElement.naturalWidth;
            canvas.height = imageElement.naturalHeight;
            ctx.drawImage(imageElement, 0, 0);
            const imageBase64 = canvas.toDataURL("image/jpeg");

            const uploadPromise = fetch('save_edits.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ id: i, image: imageBase64 })
            }).then(res => res.json());

            uploadPromises.push(uploadPromise);

            productArray.push({
              id: i,
              title: titleElement.textContent.trim(),
              description: descElement.textContent.trim(),
              image_path: `uploads/product${i}.jpg`
            });
          }

          const uploadResults = await Promise.all(uploadPromises);

          if (uploadResults.some(res => res.status !== "success")) {
            alert("One or more images failed to upload.");
            return;
          }

          const saveResponse = await fetch('save_products.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(productArray)
          });

          const result = await saveResponse.json();

          alert(result.status === "success" ? "All changes saved!" : "Saving product data failed.");
        } catch (error) {
          console.error("Error saving data:", error);
          alert("Something went wrong while saving.");
        }
      }
    } else if (page.includes("about")) {
      const aboutTitle = document.querySelector("#aboutTitle");
      const aboutContent = document.querySelector("#aboutContent");
      aboutTitle.contentEditable = editing;
      aboutContent.contentEditable = editing;

      if (!editing) {
        const data = {
          title: aboutTitle.textContent.trim(),
          content: aboutContent.textContent.trim()
        };

        try {
          const res = await fetch("save_about.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
          });

          const result = await res.json();
          alert(result.status === "success" ? "About Us saved!" : "Save failed.");
        } catch (err) {
          console.error(err);
          alert("Failed to save About Us content.");
        }
      }
    } else if (page.includes("contacts")) {
      const contactFields = ["phone", "email", "address"];
      const data = {};

      contactFields.forEach(field => {
        const el = document.querySelector(`#${field}`);
        el.contentEditable = editing;
        if (!editing) data[field] = el.textContent.trim();
      });

      if (!editing) {
        try {
          const res = await fetch("save_contacts.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
          });

          const result = await res.json();
          alert(result.status === "success" ? "Contacts saved!" : "Save failed.");
        } catch (err) {
          console.error(err);
          alert("Failed to save Contacts.");
        }
      }
    } else if (page.includes("faqs")) {
      const faqs = document.querySelectorAll(".faq-item");
      const faqData = [];

      faqs.forEach(faq => {
        const q = faq.querySelector(".question");
        const a = faq.querySelector(".answer");
        q.contentEditable = editing;
        a.contentEditable = editing;
        if (!editing) faqData.push({ question: q.textContent.trim(), answer: a.textContent.trim() });
      });

      if (!editing) {
        try {
          const res = await fetch("save_faqs.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(faqData)
          });

          const result = await res.json();
          alert(result.status === "success" ? "FAQs saved!" : "Save failed.");
        } catch (err) {
          console.error(err);
          alert("Failed to save FAQs.");
        }
      }
    }
  });

  function enableDragAndDrop(dropZoneId, imgId) {
    const dropZone = document.getElementById(dropZoneId);
    const img = document.getElementById(imgId);

    dropZone?.addEventListener("dragover", e => {
      e.preventDefault();
      dropZone.classList.add("drag-over");
    });

    dropZone?.addEventListener("dragleave", () => {
      dropZone.classList.remove("drag-over");
    });

    dropZone?.addEventListener("drop", e => {
      e.preventDefault();
      dropZone.classList.remove("drag-over");

      const file = e.dataTransfer.files[0];
      if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = ev => {
          img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        alert("Please drop a valid image file.");
      }
    });
  }

  if (page.includes("adsampi")) {
    for (let i = 1; i <= 9; i++) {
      enableDragAndDrop(`dropZone${i}`, `productImage${i}`);
    }
  }
});