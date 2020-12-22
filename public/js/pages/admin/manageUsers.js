let activeUser;
let usersSet;

const renderResults = function (users, actUser, page = 1, resPerPage = 10) {
  clearResults();
  // render results of current page
  activeUser = actUser;
  usersSet = users;
  const start = (page - 1) * resPerPage;
  const end = page * resPerPage;

  users.slice(start, end).forEach(renderUser);

  // document
  //   .querySelector(".search__results-container")
  //   .insertAdjacentHTML("beforeend", `<div class="btn__container"></div>`);
  // render pagination buttons
  renderButtons(page, users.length, resPerPage);
};

const renderUser = function (user) {
  let markup = `
    <form class='search__result-card' id='form-card' method='get'>
        <div class='search__result-user-mainbox search__result-mainbox'>
            <div class='user-mainbox__img-box'>
                <img src='/utrance-railway/public/img/uploads/${
                  user.user_image
                }.jpg' alt='profile-avatar' class='profile__avatar'/>
            </div>
            <div class='user-mainbox__other'>
                <div class ='user-mainbox__other-name'>${user.first_name}</div>
                <div class ='user-mainbox__other-id'>
                    <span>#</span>
                    <span class='user__id'>${user.id}</span>
                </div>
            </div>
        </div>
        <div class='search__result-user-emailbox'>
       
            ${user.user_active_status==1 ? "Active" : "Deactivated"}
        </div>
        <div class='search__result-user-rolebox'>
            ${
              user.user_role === "admin"
                ? "Admin"
                : user.user_role === "user"
                ? "User"
                : "Details Provider"
            }
        </div>`;

  if (user.user_role === "admin" && user.first_name === activeUser.first_name) {
    markup += `<a href='/utrance-railway/settings' class='btn btn-box-white margin-r-s'>View</a>`;
  } else {
    markup += `<a href='/utrance-railway/users/view?id=${user.id}' class='btn btn-box-white margin-r-s'>View</a>`;
  }

  if (user.user_active_status==1) {
    if (user.user_role !== "admin") {
      markup += `<a href='/utrance-railway/users/deactivate?id=${user.id}&user_active_status=${user.user_active_status}' class='btn btn-box-white btn-box-white--delete'>Deactivate</a>`;
    } else {
      markup += `<a style='visibility: hidden'></a>`;
    }
  } else {
    if (user.user_role !== "admin") {
      markup += `<a href='/utrance-railway/users/activate?id=${user.id}&user_active_status=${user.user_active_status}' class='btn btn-box-white btn-box-white--activate'>Activate</a>`;
    } else {
      markup += `<a style='visibility: hidden'></a>`;
    }
  }

  markup += `</form>`;

  // console.log(markup);
  document
    .querySelector(".search__results-container")
    .insertAdjacentHTML("beforeend", markup);
};

const clearResults = function () {
  document.querySelector(".search__results-container").innerHTML = "";
  document.querySelector(".btn__container").innerHTML = "";
};

// EVENT LISTNERS

document
  .querySelector(".btn__container")
  .addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-round-pagination");
    if (btn) {
      clearResults();
      const goToPage = parseInt(btn.dataset.goto, 10);
      renderResults(usersSet, activeUser, goToPage);
    }
  });


