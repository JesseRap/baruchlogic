/**
 * Functions for recording that the logged in user has watched the video
 */

( () => {

// Keep track of the times of the video that the user has seen
let timesRecorded = new Set();

const videoElement = document.querySelector('video');

videoElement.addEventListener('timeupdate', ()=> {
  // Whenever the time of the video changes, add it to the set of times seen
  let newTime = Math.floor(videoElement.currentTime);
  timesRecorded.add(newTime);
  console.log(timesRecorded);
});

videoElement.addEventListener('ended', ()=> {
  // When the video ends, record that the user has watched it
  // if all times are in timesRecorded
  // window.location.href = 'addVideo/' + getSlug(window.location.href);
  if (timesRecorded.size >= videoElement.duration) {
    // CHANGE THIS TO AJAX !!!

    const slug = getSlug(window.location.href) || 'blurred1';

    console.log("SLUG", slug, slug.length);

    $.ajax({
      method: 'POST',
      url: '/phi1600b/public/videos/addVideo',
      beforeSend: () => {
        console.log('sending POST');
      },
      data: {videopath: slug},
    }).done( (msg) => {
      console.log('POST is back ', msg);
      fillInTheCircle(msg);
      // alert(msg);
    }).fail( (msg) => {
      alert('Uh oh... Something went wrong. The .');
    });
  }
})

function getSlug(href) {
  const r = /video\/\w+/;
  return r.exec(href)[0].slice(6);
}

function fillInTheCircle(videoId) {
  console.log("FILL IN", videoId);
  $('.sidebar__content[data-video-id=' + videoId + '] .circle').removeClass('circle--unwatched').addClass('circle--watched');
}


})();
