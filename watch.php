<?php
//open connection to the database
	class MyDB extends SQLite3
   {
      function __construct()
      {
         $this->open('../../db/CoM.db');
      }
   }
?>

<html>
    <head>
        <title>Colour Timelines - Watch</title>
        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
        <link type='text/css' rel='stylesheet' href='colours.css'/>
        <script type="text/javascript" src="color/color-thief.js"></script>
        <script type="text/javascript" src="color/quantize.js"></script>
        <script type="text/javascript" src="color/jquery-3.1.1.js"></script>
        <script src="https://html2canvas.hertzen.com/build/html2canvas.js"></script>
        <script type='text/javascript'>
 
            //execute when the page loads
            window.onload = function (){
 

                var video = document.getElementById('my_video');
                var thecanvas = document.getElementById('thecanvas');
                var img = document.getElementById('frame');
                document.getElementById('my_video').addEventListener('ended', myHandler);

                var i = 0;

                //as the video plays, this method continually executes, calling the draw() method once every 10 loops
				        video.addEventListener('timeupdate', function()
                {
                  document.getElementById('pressplay').style.display = "none";
					        i += 1;

					        if (i % 10 == 0)
   					        {

                      draw( video, thecanvas, img );

                    }
 
                }, false);
 
            };


            //creates a coloured block in the timeline
            function createDiv(color)
            {
              var elem = document.createElement('div');
              elem.style.backgroundColor = "rgb(" + color + ")";
              elem.style.width = "299px";
              elem.style.height = "12px";
              elem.style.marginLeft = "2px";
              elem.style.marginRight = "2px";
              elem.style.marginTop = "0px";
              elem.style.marginBottom = "0px";
              elem.style.float = "left";
              document.getElementById('list').appendChild(elem);
            }
 
 
            //draws the current frame to a canvas, uses Color Thief to get the 3 dominant colours,
            //then updates the blocks under the captured frame as well as draws those 3 blocks on the end of the timeline
            function draw( video, thecanvas, img )
            {

              var colorThief = new ColorThief();
 
              var context = thecanvas.getContext('2d');
 
              context.drawImage( video, 0, 0, thecanvas.width, thecanvas.height);
 
              var dataURL = thecanvas.toDataURL();
 
              img.setAttribute('src', dataURL);

              var container = document.getElementById("content");
              var height = container.offsetHeight;
              var newHeight = height + 12;
              container.style.height = newHeight + 'px';

              var container2 = document.getElementById("list");
              var height2 = container2.offsetHeight;
              var newHeight2 = height2 + 12;
              container2.style.height = newHeight2 + 'px';

              var color = colorThief.getPalette(img, 3);


              var cur = document.getElementById('current');
              var cur2 = document.getElementById('current2');
              var cur3 = document.getElementById('current3');
              cur.style.backgroundColor = "rgb(" + color[1] + ")";
              cur2.style.backgroundColor = "rgb(" + color[0] + ")";
              cur3.style.backgroundColor = "rgb(" + color[2] + ")";

              createDiv(color[1]);
              createDiv(color[0]);
              createDiv(color[2]);

              document.getElementById('bottom').scrollIntoView();
            }

            var data;

            //triggers when the video finishes, converting the timeline to an image and displaying it for the user to download
            function myHandler(e) 
            {
              html2canvas($('#list'), {
                onrendered: function (canvas) {
                  data = canvas.toDataURL('image/png');

                  document.getElementById('toupload').setAttribute('src', data);
                  document.getElementById('form').style.display = "inline";
                  document.getElementById('bottom').scrollIntoView();
                }
              });
            }

        </script>

    </head>
  <body>



    
    <?php
      $video = $_GET['video'];
    ?>

    <div id="last">

    <center>

          <video id="my_video" width="640" controls>
              <source src="<?php echo $video; ?>" type="video/mp4" />
          </video>
    <br />

    <div id="dont">
          <canvas id="thecanvas" width="480" height="270">
          </canvas>
    </div>

    <div id="line"></div>

    <div id="pressplay"><h3>press play on the video to begin</h3></div>

    <img id="frame" />

    <div id="sample">
      <div id= "current"></div>
      <div id= "current2"></div>
      <div id= "current3"></div>
    </div>

    </div>

    </center>

<div id="content">
<div id="inner">    
<center>
    <br />
    <h2>COLOUR TIMELINE</h2>
    <br />

      <div id="list"></div>

    <br />
    </center>

    <br><br>

    <center><div id="form">
    <p>right-click to save</p>
    <img id="toupload"  width="200px" height="200px" text-align="center" title="Right-Click to Save"/>
    </div></center>

    <div id="bottom"></div>

  

</div>
</div>


  </body>
</html>