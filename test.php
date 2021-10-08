<!DOCTYPE html>
<html lang="en">
<head>
  <title>How to show web designer and developer skills set on the website</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<style type="text/css">
.skills-bar {
    width: 500px;
    background-color: #fbfbfb;
    margin: 50px auto;
    padding: 20px 20px 10px;
    font-family: arial;
    border-radius: 15px;
    border: 1px solid #eee;
}
.skills-bar h2 {
    text-align: center;
    padding: 0px 0px 25px;
    margin: 10px 0px 40px;
    font-size: 30px;
    font-weight: normal;
    border-bottom: 1px solid #eaeaea;
}
.skills-bar label {
    font-size: 16px;
    text-transform: uppercase;
    font-weight: bold;
    color: #333;
}
.skills-bar label i {
    font-size: 25px;
    margin-right: 5px;
    color: #4caf4f;
}
.bar {
    height: 10px;
    background-color: #eee;
    margin: 15px 0px 30px;
    border-radius: 10px;
}  
.ui-progressbar-value{
  height: 10px;
  background-color: #4CAF50;
  border-radius: 10px;
}
/*--Responsive CSS--*/
@media (max-width: 767px){
.skills-bar{
  width: 85%;
}
}
</style>
<body>

<div class="skills-bar">
  <h2>My Skills Set</h2>
  <label><i class="fa fa-css3" aria-hidden="true"></i> CSS</label>
  <div id="cssbar" class="bar"></div>
  <label><i class="fa fa-html5" aria-hidden="true"></i> HTML</label>
  <div id="htmlbar" class="bar"></div>
  <label><i class="fa fa-wordpress" aria-hidden="true"></i> WordPress</label>
  <div id="wordpressbar" class="bar"></div>
  <label><i class="fa fa-paint-brush" aria-hidden="true"></i> Photoshop</label>
  <div id="photoshopbar" class="bar"></div>
  <label><i class="fa fa-code" aria-hidden="true"></i> PHP</label>
  <div id="phpbar" class="bar"></div>
</div>
</body>
<script type="text/javascript">
  $( function() {
    $( "#cssbar" ).progressbar({
      value: 85
    });
    $( "#htmlbar" ).progressbar({
      value: 67
    });
    $( "#photoshopbar" ).progressbar({
      value: 80
    });
    $( "#wordpressbar" ).progressbar({
      value: 50
    });
    $( "#phpbar" ).progressbar({
      value: 63
    });
  });
</script>
</html>