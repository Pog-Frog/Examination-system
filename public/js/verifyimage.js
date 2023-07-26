// Set up video stream
navigator.mediaDevices.getUserMedia({ video: true })
    .then(function (stream) {
        var video = document.getElementById('video');
        video.srcObject = stream;
        video.play();
    })
    .catch(function (err) {
        console.log('Error: ' + err);
    });

// capture image button
var startbutton = document.getElementById('startbutton');
var canvas = document.getElementById('canvas');
var video = document.getElementById('video');

startbutton.addEventListener('click', function (ev) {
    takePicture();
    ev.preventDefault();
});

// Take picture function
function takePicture() {
    //console.log('Button clicked!');
    var context = canvas.getContext('2d');
    var width = video.videoWidth;
    var height = video.videoHeight;
    if (width && height) {
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0, width, height);

        var imageData = canvas.toDataURL('image/png');

        var profile_picture = $.ajax({
            type: 'GET',
            url: 'http://127.0.0.1:8000/student/get_profile_image',
            async: false,
            success: function (response) {
                return response;
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });

        var profilePicture = profile_picture.responseJSON.image;

        $.ajax({
            type: 'POST',
            url: 'http://127.0.0.1:7000/verify',
            data: JSON.stringify({ 'img1': imageData, 'img2': profilePicture, 'model_name' : 'VGG-Face'}),
            contentType: 'application/json',
            success: function (response) {
                if (response.success) {
                    console.log('verfied = ', response.result.verified);
                    if(response.result.verified == true){
                        document.cookie = "imageVerified=true";
                        setTimeout(function () {
                            window.location.href = startbutton.getAttribute('data-url');
                        }, 10000);
                        
                    }
                } else {
                    window.alert("face not verified");
                    console.log('error = ', response.error);
                }  
                // document.cookie = "imageVerified=true"; 
                window.location.href = startbutton.getAttribute('data-url');
            },
            error: function (xhr, status, error) {
                console.log(error);
                //console.log the request 
                console.log(xhr.responseText);
                window.alert("face not verified");
            }
        });
    }
}





