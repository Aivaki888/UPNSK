(() => {
  const root = document.documentElement;
  const themeToggle = document.querySelector("[data-theme-toggle]");
  const navToggle = document.querySelector("[data-nav-toggle]");
  const siteNav = document.querySelector("[data-site-nav]");

  function resolveTheme(pref) {
    if (pref === "dark" || pref === "light") return pref;
    return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
  }

  function applyTheme(pref) {
    const theme = resolveTheme(pref);
    root.setAttribute("data-theme", theme);
    root.setAttribute("data-theme-pref", pref);
    localStorage.setItem("dari-theme", pref);
    document.cookie = `theme=${encodeURIComponent(pref)};path=/;max-age=31536000;SameSite=Lax`;
  }

  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      const current = root.getAttribute("data-theme") === "dark" ? "dark" : "light";
      applyTheme(current === "dark" ? "light" : "dark");
    });
  }

  window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", () => {
    const pref = root.getAttribute("data-theme-pref") || localStorage.getItem("dari-theme") || "system";
    if (pref === "system") applyTheme("system");
  });

  if (navToggle && siteNav) {
    navToggle.addEventListener("click", () => {
      const open = siteNav.classList.toggle("is-open");
      navToggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
  }

  const preview = {
    design: document.querySelector("[data-preview-design]"),
    amount: document.querySelector("[data-preview-amount]"),
    occasion: document.querySelector("[data-preview-occasion]"),
    recipient: document.querySelector("[data-preview-recipient]"),
    accent: document.querySelector("[data-preview-accent]"),
    delivery: document.querySelector("[data-preview-delivery]"),
    image: document.querySelector("[data-preview-image]"),
  };

  const occasionLabels = {
    birthday: "День рождения",
    wedding: "Свадьба",
    newyear: "Новый год",
    corporate: "Корпоратив",
    thankyou: "Благодарность",
    justbecause: "Просто так",
  };

  const accentLabels = {
    forest: "Лесной",
    champagne: "Шампань",
    ink: "Чернила",
    berry: "Ягодный",
    sky: "Небесный",
  };

  const deliveryLabels = {
    email: "На email",
    print: "Для печати",
  };

  function money(value) {
    const n = Number(value || 0);
    return `${n.toLocaleString("ru-RU")} ₽`;
  }

  function updateOrderPreview() {
    if (!preview.amount) return;

    const designInput = document.querySelector('input[name="design_id"]:checked');
    const amountInput = document.querySelector('input[name="amount"]:checked, select[name="amount"]');
    const occasionInput = document.querySelector('select[name="occasion"]');
    const recipientInput = document.querySelector('input[name="recipient_name"]');
    const accentInput = document.querySelector('input[name="accent_color"]:checked, select[name="accent_color"]');
    const deliveryInput = document.querySelector('input[name="delivery_method"]:checked, select[name="delivery_method"]');

    if (designInput) {
      const card = designInput.closest(".design-choice");
      const title = card?.querySelector(".caption")?.textContent?.trim() || "Дизайн";
      const src = card?.querySelector("img")?.getAttribute("src");
      if (preview.design) preview.design.textContent = title;
      if (preview.image && src) preview.image.setAttribute("src", src);
    }

    if (amountInput && preview.amount) preview.amount.textContent = money(amountInput.value);
    if (occasionInput && preview.occasion) preview.occasion.textContent = occasionLabels[occasionInput.value] || occasionInput.value;
    if (recipientInput && preview.recipient) preview.recipient.textContent = recipientInput.value.trim() || "—";
    if (accentInput && preview.accent) preview.accent.textContent = accentLabels[accentInput.value] || accentInput.value;
    if (deliveryInput && preview.delivery) preview.delivery.textContent = deliveryLabels[deliveryInput.value] || deliveryInput.value;
  }

  document.querySelectorAll("[data-order-form] input, [data-order-form] select, [data-order-form] textarea")
    .forEach((el) => el.addEventListener("input", updateOrderPreview));

  document.querySelectorAll("[data-order-form] input, [data-order-form] select")
    .forEach((el) => el.addEventListener("change", updateOrderPreview));

  updateOrderPreview();
})();
