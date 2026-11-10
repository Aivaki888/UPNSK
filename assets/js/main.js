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
    localStorage.setItem("playgift-theme", pref === "dark" || pref === "light" ? pref : "system");
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
    const pref = root.getAttribute("data-theme-pref") || localStorage.getItem("playgift-theme") || localStorage.getItem("dari-theme") || "system";
    if (pref === "system") applyTheme("system");
  });

  const savedAccent = localStorage.getItem("playgift-accent") || "ps";
  root.setAttribute("data-accent", savedAccent);
  document.querySelectorAll("[data-accent-switch] [data-accent]").forEach((button) => {
    if (button.getAttribute("data-accent") === savedAccent) button.classList.add("is-active");
    button.addEventListener("click", () => {
      const accent = button.getAttribute("data-accent") || "ps";
      root.setAttribute("data-accent", accent);
      localStorage.setItem("playgift-accent", accent);
      document.querySelectorAll("[data-accent-switch] [data-accent]").forEach((el) => {
        el.classList.toggle("is-active", el === button);
      });
    });
  });

  if (navToggle && siteNav) {
    const setNavOpen = (open) => {
      siteNav.classList.toggle("is-open", open);
      navToggle.setAttribute("aria-expanded", open ? "true" : "false");
      document.body.classList.toggle("nav-open", open);
    };

    navToggle.addEventListener("click", (event) => {
      event.preventDefault();
      event.stopPropagation();
      setNavOpen(!siteNav.classList.contains("is-open"));
    });

    siteNav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => setNavOpen(false));
    });

    document.addEventListener("click", (event) => {
      if (!siteNav.classList.contains("is-open")) return;
      const target = event.target;
      if (target instanceof Node && (siteNav.contains(target) || navToggle.contains(target))) return;
      setNavOpen(false);
    });

    window.addEventListener("keydown", (event) => {
      if (event.key === "Escape") setNavOpen(false);
    });
  }

  const preview = {
    design: document.querySelector("[data-preview-design]"),
    amount: document.querySelector("[data-preview-amount]"),
    occasion: document.querySelector("[data-preview-occasion]"),
    recipient: document.querySelector("[data-preview-recipient]"),
    accent: document.querySelector("[data-preview-accent]"),
    delivery: document.querySelector("[data-preview-delivery]"),
    platform: document.querySelector("[data-preview-platform]"),
    frame: document.querySelector("[data-preview-frame]"),
    card: document.querySelector("[data-voucher-card]"),
  };

  const occasionLabels = {
    birthday: "День рождения",
    new_release: "Релиз игры",
    tournament: "Турнир / победа",
    friend_gift: "Подарок другу",
    thankyou: "Спасибо за кооператив",
    justbecause: "Просто так",
  };

  const accentLabels = {
    ps_blue: "PlayStation",
    xbox_green: "Xbox",
    pc_cyan: "PC",
    neon: "Неон",
    carbon: "Карбон",
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
    if (!preview.amount && !preview.card) return;

    const designInput = document.querySelector('input[name="design_id"]:checked');
    const amountInput = document.querySelector('input[name="amount"]:checked, select[name="amount"]');
    const occasionInput = document.querySelector('select[name="occasion"]');
    const recipientInput = document.querySelector('input[name="recipient_name"]');
    const accentInput = document.querySelector('input[name="accent_color"]:checked, select[name="accent_color"]');
    const deliveryInput = document.querySelector('input[name="delivery_method"]:checked, select[name="delivery_method"]');

    if (designInput) {
      const card = designInput.closest(".design-choice");
      const label = card?.querySelector("label");
      const title = card?.querySelector(".caption span:last-child")?.textContent?.trim()
        || card?.querySelector(".caption")?.textContent?.trim()
        || "Дизайн";
      const thumb = card?.querySelector(".thumb");
      if (preview.design) preview.design.textContent = title;
      if (preview.frame && thumb) preview.frame.innerHTML = thumb.innerHTML;
      if (preview.platform && label?.dataset.platform) preview.platform.textContent = label.dataset.platform;
      if (label?.dataset.accentHint && accentInput && accentInput.tagName === "SELECT" && !accentInput.dataset.userTouched) {
        accentInput.value = label.dataset.accentHint;
      }
    }

    if (amountInput && preview.amount) preview.amount.textContent = money(amountInput.value);
    if (occasionInput && preview.occasion) preview.occasion.textContent = occasionLabels[occasionInput.value] || occasionInput.value;
    if (recipientInput && preview.recipient) {
      const name = recipientInput.value.trim();
      preview.recipient.textContent = name || (preview.card ? "получателя" : "—");
    }
    if (accentInput && preview.accent) preview.accent.textContent = accentLabels[accentInput.value] || accentInput.value;
    if (deliveryInput && preview.delivery) preview.delivery.textContent = deliveryLabels[deliveryInput.value] || deliveryInput.value;
    if (preview.card && accentInput) {
      preview.card.className = `voucher-card accent-${accentInput.value}`;
    }
  }

  const accentSelect = document.querySelector('select[name="accent_color"]');
  if (accentSelect) {
    accentSelect.addEventListener("change", () => {
      accentSelect.dataset.userTouched = "1";
    });
  }

  document.querySelectorAll("[data-order-form] input, [data-order-form] select, [data-order-form] textarea")
    .forEach((el) => el.addEventListener("input", updateOrderPreview));

  document.querySelectorAll("[data-order-form] input, [data-order-form] select")
    .forEach((el) => el.addEventListener("change", updateOrderPreview));

  updateOrderPreview();

  document.querySelector("[data-surprise-design]")?.addEventListener("click", () => {
    const radios = [...document.querySelectorAll('input[name="design_id"]')];
    if (!radios.length) return;
    const pick = radios[Math.floor(Math.random() * radios.length)];
    pick.checked = true;
    pick.dispatchEvent(new Event("change", { bubbles: true }));
    pick.closest(".design-choice")?.scrollIntoView({ behavior: "smooth", block: "center" });
    burstConfetti(18);
  });

  document.querySelector("[data-surprise-home]")?.addEventListener("click", (event) => {
    const ids = window.__PLAYGIFT_DESIGNS__ || [];
    if (!ids.length) return;
    event.preventDefault();
    const id = ids[Math.floor(Math.random() * ids.length)];
    window.location.href = `order.php?design=${id}`;
  });

  document.querySelectorAll("[data-template]").forEach((button) => {
    button.addEventListener("click", () => {
      const field = document.querySelector("[data-message-field]");
      if (!field) return;
      field.value = button.getAttribute("data-template") || "";
      field.dispatchEvent(new Event("input", { bubbles: true }));
      field.focus();
    });
  });

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
        showCopyToast("Скопировано");
      } catch (err) {
        showCopyToast("Не удалось скопировать");
      }
    });
  });

  document.querySelectorAll("[data-count-up]").forEach((el) => {
    const target = Number(el.getAttribute("data-count-up") || 0);
    const start = performance.now();
    const duration = 1100;
    const tick = (now) => {
      const p = Math.min(1, (now - start) / duration);
      const eased = 1 - Math.pow(1 - p, 3);
      el.textContent = String(Math.round(target * eased));
      if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
  });

  function burstConfetti(count = 80) {
    const canvas = document.getElementById("confetti-canvas");
    if (!canvas || window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
    const ctx = canvas.getContext("2d");
    if (!ctx) return;
    const dpr = window.devicePixelRatio || 1;
    canvas.width = window.innerWidth * dpr;
    canvas.height = window.innerHeight * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    const colors = ["#0068d9", "#107c10", "#0f8f9f", "#3aa0ff", "#ffffff"];
    const pieces = Array.from({ length: count }, () => ({
      x: Math.random() * window.innerWidth,
      y: -20 - Math.random() * 80,
      r: 3 + Math.random() * 5,
      c: colors[Math.floor(Math.random() * colors.length)],
      vy: 2 + Math.random() * 4,
      vx: -2 + Math.random() * 4,
      a: Math.random() * Math.PI,
    }));
    let frame = 0;
    const draw = () => {
      ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
      pieces.forEach((p) => {
        p.x += p.vx;
        p.y += p.vy;
        p.a += 0.1;
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.a);
        ctx.fillStyle = p.c;
        ctx.fillRect(-p.r, -p.r, p.r * 2, p.r * 1.2);
        ctx.restore();
      });
      frame += 1;
      if (frame < 90) requestAnimationFrame(draw);
      else ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
    };
    requestAnimationFrame(draw);
  }

  if (document.querySelector(".flash-success") || document.querySelector("[data-confetti-on-show]")) {
    burstConfetti(110);
  }

  window.addEventListener("keydown", (event) => {
    if (event.key === "/" && !event.metaKey && !event.ctrlKey && !event.altKey) {
      const tag = (event.target && event.target.tagName) || "";
      if (["INPUT", "TEXTAREA", "SELECT"].includes(tag)) return;
      const search = document.querySelector("#q");
      if (search) {
        event.preventDefault();
        search.focus();
      }
    }
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

    markStagger(".design-grid, .feature-grid, .choice-grid, .cert-list, .cta-row, .footer-grid, .footer-links, .footer-contacts, .preview-list, .site-nav, .stagger-layout, .stagger-form, .stagger-copy, .stack-sm, .profile-layout, .contact-info, .contact-actions, .counter-grid, .pulse-grid, .idea-grid, .quiz-layout");

    const soloReveal = document.querySelectorAll(
      ".section-head, .page-hero, .auth-card, .auth-side, .panel, .order-preview, .profile-summary, .hero-copy, .hero-visual, .empty-state, .about-visual, .voucher-card, .quiz-result"
    );

    soloReveal.forEach((el) => {
      if (!el.classList.contains("stagger") && !el.parentElement?.classList.contains("stagger")) {
        el.classList.add("reveal");
      }
    });

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
