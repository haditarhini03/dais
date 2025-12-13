let translations = window.translations || {};
let currentLang = localStorage.getItem("lang") || "en";

/* Apply translations + direction */
function applyTranslations() {
  document.querySelectorAll("[data-t]").forEach(el => {
    const key = el.getAttribute("data-t");

    if (translations[currentLang] && translations[currentLang][key]) {
      if ("placeholder" in el) {
        el.placeholder = translations[currentLang][key];
      } else {
        el.innerHTML = translations[currentLang][key];
      }
    }
  });

  // RTL for Arabic only
  if (currentLang === "ar") {
    document.documentElement.setAttribute("dir", "rtl");
    document.body.classList.add("rtl");
  } else {
    document.documentElement.setAttribute("dir", "ltr");
    document.body.classList.remove("rtl");
  }
}

/* Highlight active language */
function highlightActiveLang() {
  document.querySelectorAll(".lang-link").forEach(link => {
    link.classList.toggle(
      "active",
      link.dataset.lang === currentLang
    );
  });
}

/* Language click handler */
document.querySelectorAll(".lang-link").forEach(link => {
  link.addEventListener("click", e => {
    e.preventDefault();

    const lang = link.dataset.lang;
    localStorage.setItem("lang", lang);

    // Full reload so all scripts re-run correctly
    location.reload();
  });
});

/* Init on load */
document.addEventListener("DOMContentLoaded", () => {
  applyTranslations();
  highlightActiveLang();
});
