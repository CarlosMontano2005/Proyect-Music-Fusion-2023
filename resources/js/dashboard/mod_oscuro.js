let body = document.querySelector('body');

let toggle = body.querySelector(".toggle");
let searchBtn = body.querySelector(".search-box");
let modeSwitch = body.querySelector(".toggle-switch");
let modeText = body.querySelector(".mode-text");



modeSwitch.addEventListener("click", () => {
body.classList.toggle("dark");

if (body.classList.contains("dark")) {
  modeText.innerText = "Light mode";
} else {
  modeText.innerText = "Dark mode";
}
});
