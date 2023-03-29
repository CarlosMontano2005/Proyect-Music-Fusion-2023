let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e) => {
    let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
    arrowParent.classList.toggle("showMenu");
  });
}

const body = document.querySelector('body');
  toggle = body.querySelector(".toggle");
  searchBtn = body.querySelector(".search-box"),
  modeSwitch = body.querySelector(".toggle-switch");
  modeText = body.querySelector(".mode-text");
  var sidebar = document.querySelector(".sidebar");
  var sidebarBtn = document.querySelector(".bx-menu");
  console.log(sidebarBtn);

sidebarBtn.addEventListener("click", () => {
  sidebar.classList.toggle("close");
});


modeSwitch.addEventListener("click", () => {
  body.classList.toggle("dark");

  if (body.classList.contains("dark")) {
    modeText.innerText = "Light mode";
  } else {
    modeText.innerText = "Dark mode";

  }
});
