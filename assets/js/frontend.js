jQuery(document).ready(function() {

  function fillCell(x, sum) {
      var d = new Date();
      var currentDay = d.getDate();

      // if local storage empty OR its another day
      if (!localStorage.getItem("number" + x) || localStorage.getItem("setupDay" + x) != currentDay || php_vars.debug != "") {
          //set up number and save it
          localStorage.setItem("number" + x, sum);
          localStorage.setItem("setupDay" + x, currentDay);
      }

      document.getElementById("number" + x).innerHTML = localStorage.getItem("number" + x);
  }

  function sumFind(x) {
      var sum = 0;

      //foreach numeric cell value check its nature (static \ dynamic)
      if (php_vars['num_nature_' + x] != true) { //if its static
        sum = php_vars['static_' + x];
      } else {
          var min = +php_vars['dynamic_min_' + x] || 0;
          var max = +php_vars['dynamic_max_' + x] || 0;

          var step = +php_vars['increase_step_' + x] || 1;
          var reset = php_vars['reset_' + x] || "never";

          if (reset < step || max < min) { //validation
              sum = "error";
              return sum;
          }

          // random value from set interval
          if (reset == step) {
              sum += parseInt(Math.floor(Math.random() * (max - min + 1)) + min, 10);
          } else {
              // dynamic formula
              var now = new Date();
              var oneDay = 1000 * 60 * 60 * 24;

              var currentDay;

              //reset logic
              if (reset == "30") {
                  currentDay = now.getDate(); //number from 1 to 31
              }

              if (reset == "365") {
                  var start = new Date(now.getFullYear(), 0, 0);
                  var diff = now - start;

                  currentDay = Math.ceil(diff / oneDay); //number from 1 to 365
              }

              if (reset == "never") {
                  // aka start date, takes param from options array
                  var firstDate = php_vars['start_date_' + x];
                  currentDay = parseInt(Math.round(Math.abs((firstDate.getTime() - now.getTime()) / (oneDay))), 10);
              }

              //formula
              for (var i = 0; i <= currentDay; i += step) {
                  sum += parseInt(Math.round(Math.random() * (max - min + 1)) + min, 10);
              }

          }
      }

      return sum;
  }

  var number = parseInt((php_vars.number), 10) || 4;
  for (var i = 1; i <= number; i++) {
    fillCell(i, sumFind(i));
  }

  //console.log(php_vars);
    
});
