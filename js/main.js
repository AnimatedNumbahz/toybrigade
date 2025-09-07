//submenu functionality for navbar
document.querySelectorAll(".dropdown-submenu").forEach(function (el) {
  el.addEventListener("mouseleave", function () {
    const submenu = el.querySelector(".dropdown-menu");
    if (submenu) {
      setTimeout(() => (submenu.style.display = "none"), 150); // small delay before hiding
    }
  });

  el.addEventListener("mouseenter", function () {
    const submenu = el.querySelector(".dropdown-menu");
    if (submenu) {
      submenu.style.display = "block";
    }
  });
});

//login state management
document.addEventListener("DOMContentLoaded", function () {
  // Simulate a login state (replace with your actual auth check)
  const isLoggedIn = localStorage.getItem("loggedIn") === "true"; // Example: check localStorage

  const authButtons = document.getElementById("authButtons");
  const userMenu = document.getElementById("userMenu");

  if (isLoggedIn) {
    authButtons.classList.add("d-none");
    userMenu.classList.remove("d-none");
    document.getElementById("usernameDisplay").textContent =
      localStorage.getItem("username") || "User";
  } else {
    authButtons.classList.remove("d-none");
    userMenu.classList.add("d-none");
  }

  // Logout button
  const logoutBtn = document.getElementById("logoutBtn");
  logoutBtn?.addEventListener("click", function () {
    localStorage.setItem("loggedIn", "false");
    location.reload();
  });
});

//form slider functionality
document.addEventListener("DOMContentLoaded", () => {
  const slider = document.querySelector(".form-slider");
  const showSignup = document.getElementById("showSignup");
  const showLogin = document.getElementById("showLogin");
  const accountDropdownMenu = document.getElementById("accountDropdownMenu");

  // Prevent dropdown from closing on inside clicks
  if (accountDropdownMenu) {
    accountDropdownMenu.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }

  if (showSignup) {
    showSignup.addEventListener("click", (e) => {
      e.preventDefault();
      slider.style.transform = "translateX(-50%)"; // slide left to show signup
    });
  }

  if (showLogin) {
    showLogin.addEventListener("click", (e) => {
      e.preventDefault();
      slider.style.transform = "translateX(0%)"; // slide back to login
    });
  }
});

// form button loading state
document.addEventListener("DOMContentLoaded", () => {
  function setLoadingState(button, isLoading) {
    const defaultText = button.querySelector(".default-text");
    const loadingText = button.querySelector(".loading-text");

    if (isLoading) {
      defaultText.classList.add("d-none");
      loadingText.classList.remove("d-none");
      button.disabled = true;
    } else {
      defaultText.classList.remove("d-none");
      loadingText.classList.add("d-none");
      button.disabled = false;
    }
  }

  const loginForm = document.getElementById("loginForm");
  const signupForm = document.getElementById("signupForm");

  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const btn = this.querySelector("button[type=submit]");
      setLoadingState(btn, true);

      // TODO: replace with real backend call
      setTimeout(() => {
        setLoadingState(btn, false);
        alert("Logged in! (simulate backend)");
      }, 2000);
    });
  }

  if (signupForm) {
    signupForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const btn = this.querySelector("button[type=submit]");
      setLoadingState(btn, true);

      // TODO: replace with real backend call
      setTimeout(() => {
        setLoadingState(btn, false);
        alert("Account created! (simulate backend)");
      }, 2000);
    });
  }
});

// Page loading spinner
document.addEventListener("DOMContentLoaded", function () {
  const spinner = document.getElementById("spinner");
  setTimeout(() => {
    spinner.classList.add("hidden");
  }, 500); // adjust delay as you like
});

document.addEventListener("DOMContentLoaded", () => {
  const searchToggle = document.getElementById("searchToggle");
  const searchInput = document.getElementById("navbarSearchInput");

  if (searchToggle && searchInput) {
    searchToggle.addEventListener("click", () => {
      searchInput.classList.toggle("show");
      if (searchInput.classList.contains("show")) {
        searchInput.focus();
      }
    });
  }
});
