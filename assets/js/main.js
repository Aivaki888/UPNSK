(() => {
  const root = document.documentElement;
  const themeToggle = document.querySelector("[data-theme-toggle]");
  const navToggle = document.querySelector("[data-nav-toggle]");
  const siteNav = document.querySelector("[data-site-nav]");

  function resolveTheme(pref) {
    if (pref === "dark" || pref === "light") return pref;
    return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
  }

  let themeTimer = null;

  function applyTheme(pref) {
    const theme = resolveTheme(pref);
    root.classList.add("theme-animating");
    root.setAttribute("data-theme", theme);
    root.setAttribute("data-theme-pref", pref === "dark" || pref === "light" ? pref : "system");
    localStorage.setItem("dari-theme", pref === "dark" || pref === "light" ? pref : "system");
    document.cookie = `theme=${encodeURIComponent(root.getAttribute("data-theme-pref"))};path=/;max-age=31536000;SameSite=Lax`;
    if (themeTimer) clearTimeout(themeTimer);
    themeTimer = setTimeout(() => root.classList.remove("theme-animating"), 600);
  }

  if (themeToggle) {
    themeToggle.addEventListener("click", (event) => {
      event.preventDefault();
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
    frame: document.querySelector("[data-preview-frame]"),
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
      const thumb = card?.querySelector(".thumb");
      if (preview.design) preview.design.textContent = title;
      if (preview.frame && thumb) {
        preview.frame.innerHTML = thumb.innerHTML;
      }
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

  function showCopyToast(text) {
    let toast = document.querySelector(".copy-toast");
    if (!toast) {
      toast = document.createElement("div");
      toast.className = "copy-toast";
      document.body.appendChild(toast);
    }
    toast.textContent = text;
    toast.classList.add("is-on");
    clearTimeout(showCopyToast._timer);
    showCopyToast._timer = setTimeout(() => toast.classList.remove("is-on"), 1600);
  }

  document.querySelectorAll("[data-copy]").forEach((button) => {
    button.addEventListener("click", async () => {
      const value = button.getAttribute("data-copy") || "";
      try {
        await navigator.clipboard.writeText(value);
        showCopyToast("Код скопирован");
      } catch (err) {
        showCopyToast("Не удалось скопировать");
      }
    });
  });

  if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    const markStagger = (selector) => {
      document.querySelectorAll(selector).forEach((group) => {
        const kids = [...group.children].filter((child) => {
          if (child.matches("input[type='hidden']")) return false;
          const style = window.getComputedStyle(child);
          return style.display !== "none";
        });
        if (kids.length > 1) {
          group.classList.add("stagger");
          kids.forEach((child, index) => {
            child.style.setProperty("--stagger-index", String(index));
          });
        }
      });
    };

    markStagger(".design-grid, .feature-grid, .choice-grid, .cert-list, .cta-row, .footer-grid, .footer-links, .footer-contacts, .preview-list, .site-nav, .stagger-layout, .stagger-form, .stagger-copy, .stack-sm, .profile-layout, .contact-info, .contact-actions");

    const soloReveal = document.querySelectorAll(
      ".section-head, .page-hero, .auth-card, .auth-side, .panel, .order-preview, .profile-summary, .hero-copy, .hero-visual, .empty-state, .about-visual"
    );

    soloReveal.forEach((el) => {
      if (!el.classList.contains("stagger") && !el.parentElement?.classList.contains("stagger")) {
        el.classList.add("reveal");
      }
    });

    // Nested stagger inside reveal parents: observe nested groups too.
    const animated = document.querySelectorAll(".stagger, .reveal");
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            entry.target.querySelectorAll(".stagger").forEach((nested) => {
              nested.classList.add("is-visible");
            });
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.08, rootMargin: "0px 0px -20px 0px" }
    );

    animated.forEach((el) => observer.observe(el));

    const showNow = () => {
      document.querySelectorAll(
        ".hero .stagger, .hero .reveal, .site-nav.stagger, .auth-layout.stagger, .auth-layout .stagger, .auth-layout .reveal, .order-layout.stagger, .order-layout .stagger, .order-layout .reveal, .about-story.stagger, .about-story .stagger, .page-hero.stagger, .page-hero.reveal, .page-hero.stagger-copy"
      ).forEach((el) => {
        el.classList.add("is-visible");
        el.querySelectorAll(".stagger").forEach((nested) => nested.classList.add("is-visible"));
      });
    };

    requestAnimationFrame(showNow);
    setTimeout(showNow, 50);
  }
})();
