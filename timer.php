<?php
date_default_timezone_set('Asia/Manila');
?>
<style>
body {
  font: 100%/87.5% 'Roboto', Arial, sans-serif;
  margin: 0;
  padding: 0;
}
/* Font Face */

@font-face {
  font-family: 'Roboto';
  font-style: normal;
  font-weight: 400;
  src: url('tpl/css/fonts/roboto.woff2') format('woff2'),
       url('tpl/css/fonts/roboto.woff') format('woff'); 
}

@font-face {
  font-family: 'Roboto Light';
  font-style: normal;
  font-weight: 300;
  src: url('tpl/css/fonts/roboto-light.woff2') format('woff2'); 
}

@font-face {
  font-family: 'Roboto Bold';
  font-style: normal;
  font-weight: 700;
  src: url('tpl/css/fonts/roboto-bold.woff2') format('woff2'); 
}
.clock-div{font-family: "Roboto"; font-weight: bold; font-size: 39px;color:#111;margin-top: 16px;}
.clock-div{width:220px;display:block;text-align:left;}
</style>

<META HTTP-EQUIV="Refresh" CONTENT="900">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
function addZero(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}

  var d = new Date('<?php echo date("M d, Y h:i:s A"); ?>');
  setInterval(function() {
    d.setSeconds(d.getSeconds() + 1);
    var curr_hour = d.getHours();
    var curr_min = d.getMinutes();
    var curr_sec = d.getSeconds();
    if (curr_hour < 12){
      a_p = "AM";
    }else{
      a_p = "PM";
    }if (curr_hour == 0){
      curr_hour = 12;
    }if (curr_hour > 12){
      curr_hour = curr_hour - 12;   
    }

    $('.clock-div').text( addZero(curr_hour) +':' + addZero(curr_min) + ':' + addZero(curr_sec) + " " + a_p );
  }, 1000);
</script>
<span class="clock-div"></span>
