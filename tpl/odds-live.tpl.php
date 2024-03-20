<!DOCTYPE html>
<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <META HTTP-EQUIV="Refresh" CONTENT="43200">
  <title>MSW LIVE Odds</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="tpl/js/jquery.smarticker.min.js"></script>
  <script type="text/javascript" src="tpl/js/jquery.easy-ticker.js"></script>
  <script type="text/javascript" src="tpl/js/newsticker.functions.js"></script>
  <script type="text/javascript" src="tpl/js/functions.js"></script>
  <script type="text/javascript">
   $(document).ready(function(){ 
		  
    (function worker() {
		
      $.ajax({
			
		    type: "POST",
        url: 'load_data.php', 
		    data: 'sport=<?php echo $data['sport']; ?>',
        success: function(data) {
		      
          //console.log(data);

          var start = new Date().getTime();
          if(typeof(data) != "undefined" && data !== null)
          {

            if (data == "NOTFOUND" || data == "NOTREADY")
            {
              displayNotFound(data);
            }
            else
            {
              obj = JSON.parse(data);
              myids = obj.myids; // get all active match ids
              delete obj.myids;  // unset ids in main array

              // check first if exist --------------------
              sport_ctr = 1;
              $.each(obj, function(sport, league)
              {

                sport_id = JSON.stringify(sport).replace(/\W/g, '');
                if(typeof($('#'+sport_id)) != "undefined" && $('#'+sport_id) !== null)
                {
                    // update scores and odds ------
                    if ($('#'+sport_id).length > 0)
                    {
                      updateSportData(sport, league);
                    }
                    else
                    {
                      myobj = {};
                      myobj[sport] = obj[sport];
                      //console.log('building our display');
                      display_sport(myobj, sport_ctr); // append the new data
                    }

                } // end #sport_id not undefined

              sport_ctr++;
              }); // end each per sport

              removeLeagues();
              removeMatches();

            } // end else if SPORTS FOUND
          } // end validate data from ajax

          var end = new Date().getTime();
          var time = end - start;
          //console.log('Execution time: ' + time); 

        },
        complete: function() {
  
          if ($(".no-market").length == 0) 
          {
              var vticker = $('.content-wrapper').easyTicker({
                    direction: 'up',
                    easing: 'easeInOutBack',
                    speed: 'slow',
                    interval: 8000,
                    height: 'auto',
                    visible: 3,
                    mousePause: 0
                });

              var tickObj = vticker.data('easyTicker');
              tickObj.stop();

              if ($(".match-container").length > 6) 
              {
                //console.log('matchA container LENGTH is: ' + $(".match-container").length + ' should run easyticker');
                tickObj.start(); 
              }
            }
          setTimeout(worker, 10000); // ten seconds

        } // end complete
		
		//cache: false,
        }); // end ajax
      })(); // end worker function

  }); // jquery
  </script>
  <link rel="stylesheet" type="text/css" href="tpl/css/jquery.smarticker.min.css">
  <link rel="stylesheet" type="text/css" href="tpl/css/style.css">
</head>
<body>
  <div class="row content-row" id="no_result" style="display:none" align="center">
    <h2><br /> UNRECOGNIZED REQUESTED SPORT </h2>
  </div>
  <div class="row content-row" id="not_ready" style="display:none" align="center">
    <h2><br /> ALL SPORT VIEWING NOT READY YET </h2>
  </div>
  <div class="content-wrapper-sport"></div>
  <div class="content-wrapper"></div>

  <!-- Footer Ticker -->
	<div class="foot-ticker" style="position: fixed;background: #111;">
		<div class="foot-container">
      <div id="clock-ticker">
          <span class="clock-digit">
            <iframe id="frmtimer" src="timer.php" style="border:none"></iframe>
          </span>
      </div>

			<div class="smarticker5">
				<ul></ul>
			</div>
			<div class="foot-ticker-msw"><img src="tpl/img/msw-ticker-logo-new.png" alt=""/>&nbsp;</div>
		</div>
	</div>

</body>
</html>
