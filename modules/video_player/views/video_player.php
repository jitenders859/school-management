<div id="video-container">

  <video id="my-video" class="video-js vjs-big-play-centered" controls width="100%"
  poster="<?= BASE_URL ?>video_player_module/images/blackcurtains.jpg" data-setup='{"aspectRatio":"640:360", "playbackRates": [0.25, 0.5, 0.75, 1, 1.5, 2]}'>
    <source src="<?= $video_path ?>" type='video/mp4'>

    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a web browser that
      <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
  </video>

  <?php
  if(isset($video_id) && isset($token)) { ?>
    <script>
      var vid = document.getElementById('my-video');

        vid.addEventListener('ended', (ev) => {
          register_video_watched();
        })

        function register_video_watched() {
          var token = '<?= $token ?>';
          var videoId = '<?= $video_id ?>';
          var target_url = '<?= BASE_URL ?>members/register_video_watched/' + videoId;

          const http = new XMLHttpRequest();
          http.open('GET', target_url);
          http.setRequestHeader("Content-type", "application/json");
          http.setRequestHeader("trongateToken", token);
          http.send();
        }
    </script>
  <?php }
  ?>


<style>
  @import "<?= BASE_URL ?>video_player_module/css/video-js.css";

  #video-container {
    text-align: center; width: 90%; margin-left: auto; margin-right: auto;
    -webkit-box-shadow: -1px 14px 15px -7px rgba(0,0,0,0.5);
    -moz-box-shadow: -1px 14px 15px -7px rgba(0,0,0,0.5);
    box-shadow: -1px 14px 15px -7px rgba(0,0,0,0.5);
    margin-bottom: 5em;
  }
</style>


</div>
<script type="text/javascript" src="<?= BASE_URL ?>video_player_module/js/video.js"></script>