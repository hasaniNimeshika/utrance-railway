<div class="load-content-container">
    <div class="load-content">
        <div class="load-content--manage-trains">
              <form class="dashboard-searchbar--container" method='POST' action="/utrance-railway/trains" >
                <input type="text" class="dashboard-searchbar" placeholder="Search trains by name or id" name="searchTrain"/>
                <button>
                <svg class="search-icon__btn">
                  <use xlink:href="/utrance-railway/public/img/svg/sprite.svg#icon-magnifying-glass"></use>
                </svg>
                </button>
              </form>

              <a href="/utrance-railway/trains/add" class="btn btn-square-blue">
                <div class="addbtn-text">Add Train</div>
                <svg class="addbtn-img">
                  <use xlink:href="/utrance-railway/public/img/svg/sprite.svg#icon-circle-with-plus"></use>
                </svg> 
              </a>
              <div class="filters__container margin-t-s">
                <div class="filter__item">
                  <label for="train__type" class="margin-r-s">Train Type &colon;</label>
                  <select name="train__type" id="train__type" class="form__input">
                    <option value="all">All</option>
                    <option value="express">Express</option>
                    <option value="slow">Slow</option>
                    <option value="Intercity">Intercity</option>
                  </select>
                </div>
                <div class="filter__item">
                  <label for="active__status" class="margin-r-s">Active Status &colon;</label>
                  <select name="active__status" id="active__status" class="form__input">
                    <option value="active">All</option>
                    <option value="active">Active</option>
                    <option value="deactivated">Deactivated</option>
                  </select>
                </div>
              </div>
              <div class="search__results-container"></div>  
              <div class="btn__container"></div>  
        </div>
    </div>
</div>
</div>

<script type="text/javascript" src="../../../utrance-railway/public/js/components/pagination.js"></script>
      <script type="text/javascript" src="../../../utrance-railway/public/js/pages/admin/manageTrains.js"></script>
      <?php if (isset($trains)): ?>
        <script>
   
          renderResults(<?php echo json_encode($trains); ?>);
          renderButtons();
        </script>
      <?php endif;?>
























  
