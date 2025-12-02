let translations = {};
let currentLang = localStorage.getItem("lang") || "en";

fetch("translations.json")
  .then(res => res.json())
  .then(data => {
    translations = data;
    applyTranslations();
  });

document.getElementById("lang-select").value = currentLang;

document.getElementById("lang-select").addEventListener("change", e => {
  currentLang = e.target.value;
  localStorage.setItem("lang", currentLang);
  applyTranslations();
  location.reload();
});

function applyTranslations() {
  document.querySelectorAll("[data-t]").forEach(el => {
    const key = el.getAttribute("data-t");
    
    if (translations[currentLang] && translations[currentLang][key]) {
      if (el.placeholder !== undefined) {
        el.placeholder = translations[currentLang][key];
      } else {
        el.innerHTML = translations[currentLang][key];
      }
    }

    // if (currentLang === "ar") {
    //   document.body.style.direction = "rtl";
    //   document.body.style.textAlign = "right";

    // } else {
    //   document.body.style.direction = "ltr";
    //   document.body.style.textAlign = "left";
    // }
  });
}
