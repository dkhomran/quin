// show an hide placeholder
function focusPlace(target) {
  "use strict";
  target.setAttribute("data-place", target.getAttribute("placeholder"));
  target.setAttribute("placeholder", "");
}
function blurPlace(target) {
  "use strict";
  target.setAttribute("placeholder", target.getAttribute("data-place"));
}

// stars for required

let input = document.querySelectorAll("input");
input.forEach((el) => {
  if (el.hasAttribute("required")) {
    let span = document.createElement("span");
    span.textContent = "*";
    span.className = "stars";
    el.after(span);
  }
});

// show and hide password
{
  input.forEach((el) => {
    if (el.title == "pass") {
      let i = document.createElement("i");
      i.classList = "fa-solid fa-eye eye-pass";
      el.after(i);

      i.addEventListener("click", (eo) => {
        if (el.type == "password") {
          el.type = "text";
          i.classList = "fa-solid fa-eye-slash eye-pass";
        } else {
          el.type = "password";
          i.classList = "fa-solid fa-eye eye-pass";
        }
      });
    }
  });
}

// confirm delete member
{
  let confBtns = document.querySelectorAll(".confirm");
  confBtns.forEach((confBtn) => {
    if (confBtn) {
      confBtn.addEventListener("click", (first) => {
        let reslt = window.confirm(
          "are you sure ? do you want to delete this user ?"
        );
        if (reslt == false) {
          event.preventDefault();
        }
      });
    }
  });
}
