document.addEventListener("DOMContentLoaded", () => {
  // 👁 Toggle password visibility
  const toggleButtons = document.querySelectorAll(".toggle-password");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const targetId = button.getAttribute("data-target");
      const passwordInput = document.getElementById(targetId);

      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);

      const icon = button.querySelector("i");
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
    });
  });

  // 📝 Handle signup form validation
  const signupForm = document.getElementById("signup-form");

  signupForm.addEventListener("submit", (e) => {
    const password = document.getElementById("password").value.trim();
    const confirmPassword = document.getElementById("confirm-password").value.trim();
    const terms = document.getElementById("terms").checked;

    // client-side validation
    if (password !== confirmPassword) {
      e.preventDefault();
      alert("Passwords do not match!");
      return;
    }

    if (!terms) {
      e.preventDefault();
      alert("Please agree to the Terms & Conditions");
      return;
    }

  });
});
