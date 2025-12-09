let translations = {};
let currentLang = localStorage.getItem("lang") || "en";

translations = window.translations;
applyTranslations();

// Highlight selected language in header
document.addEventListener("DOMContentLoaded", () => {
  highlightActiveLang();
});

// Apply translations when language changes
function changeLanguage(lang) {
  currentLang = lang;
  localStorage.setItem("lang", lang);

  applyTranslations();
  highlightActiveLang();

  document.dispatchEvent(new Event("langChanged"));
}


// Apply text replacements
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
  });
}

// Add active class to current language in header
function highlightActiveLang() {
  const current = localStorage.getItem("lang") || "en";

  document.querySelectorAll(".lang-link").forEach(link => {
    link.classList.remove("active");
    if (link.dataset.lang === current) {
      link.classList.add("active");
    }
  });
}

document.querySelectorAll(".lang-link").forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();

    const lang = link.dataset.lang;

    // store language
    localStorage.setItem("lang", lang);

    // refresh page so all scripts reload correctly
    location.reload();
  });

});
