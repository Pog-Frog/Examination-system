import './bootstrap';
import axios from 'axios';

document.getElementById("snap").addEventListener("click", function() {
    axios({
        method: 'post',
        url: 'http://127.0.0.1:5656/plagiarism/predict',
        headers: {}, 
        data: {
          'essays_dict' : {"1":"Hello World", "2":"Hello Worldsddds", "3":"hi world there"},
            "cased": "False" // This is the body part
        }
      }).then(res => console.log(res.data));
    context.drawImage(video, 0, 0, 640, 480);
});

