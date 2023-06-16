@extends('layouts.app')
@push('styles')
<style>
  body {
    margin: 0;
    padding: 0;
  }

  .origin_container {
    width: 80%;
  }

  #result {
    width: 20%;
    margin-left: 50px;
    margin-top: 12px;
  }

  .test-wrapper {
    margin-left: 260px;
  }

  .incorrect_char {
    background-color: red;
    text-decoration: underline;
    color: white;
  }

  .correct_char {
    color: #bbb;
  }


  #origin-text {
    margin: 1em 0;
    padding: 1em 1em 0;
    background-color: #ededed;
  }

   #origin-text {
    font-size: 20px;
    border: 3px solid #FFB400;
    padding: 10px;
    margin-bottom: 5px;
    min-height: 150px;
  }

  #origin-text p {
    margin: 0;
  }

  .test-wrapper {
    display: flex;
  }

  .test-wrapper textarea {
    flex: 1;
  }

  .meta {
    margin-top: 1em;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
  }

  .text-area-inner {
    display: flex;
  }

  .timer {
    font-size: 3em;
    font-weight: bold;
  }

  .test-area2 {
    border: 1px solid #aaa;
    color: #777;
    width: 65%;
    margin-bottom: 5px;
    text-align: center;
    padding: 0 10%;
    font-size: 16px;
    margin-left: -30px;
  }

  #reset {
    padding: 0.5em 1em;
    font-size: 1.2em;
    font-weight: bold;
    color: #e95d0f;
    background: white;
    border: 10px solid #e95d0f;
  }



  #reset:hover {
    color: white;
    background-color: #e95d0f;
  }

 

  .right_word_color {
    background-color: #ffffff;
  }

  .wrong_word_color {
    background-color: #ffeeee;

  }

  #NWPM {
    background-color: #f8f9fa;
    font-size: 15px;
  }

  #accuracy {
    background-color: #f8f9fa;
    font-size: 15px;

  }

  #h_timer {
    background-color: #fcf8e3;

  }

  .flex-canvas {
    display: flex;
    justify-content: space-between;
  }

  .inner_box {
    width: 50%;
  }
  .startButton, .startButton.disabled:hover, .btn-primary:disabled:hover {
  background-color: #FFB400 !important;
  border: 1px solid #FFB400 !important;
}
.startButton {
  background-color: #1d82f5 !important;
  border: 1px solid #1d82f5 !important;
  color: #fff !important;
  padding: 9px 11px;
  position: relative;
  text-transform: capitalize;
}
  .noselect {
    -webkit-touch-callout: none;
    /* iOS Safari */
    -webkit-user-select: none;
    /* Safari */
    -khtml-user-select: none;
    /* Konqueror HTML */
    -moz-user-select: none;
    /* Old versions of Firefox */
    -ms-user-select: none;
    /* Internet Explorer/Edge */
    user-select: none;
    /* Non-prefixed version, currently
                                        supported by Chrome, Edge, Opera and Firefox */
  }
</style>
@endpush
@section('content')
<!-- CONTENT WRAPPER START -->
<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">

              <body oncopy="return false" oncut="return false" onpaste="return false">
                <form method="POST" enctype="multipart/from-data">
                  @csrf
                  <input type='hidden' id="h_NWPM" value="" />
                  <input type='hidden' id="h_Accuracy" value="" />
                  <audio id="myAudio">
                    <source src="{{__DIR__}}'\storage\alert.mp3" type="audio/mpeg">
                  </audio>
                  <main class="main">
                    <section class="test-area">
                      <div class='text-area-inner'>
                        <div class="origin_container">
                          <div id="origin-text" class="noselect">
                            <p id='per1' style='color:#333333; ' hidden>{{$sentence}}</p>
                            <p id="per2" class='quote1' style='font-size: 15px;'>
                              Press <b style="background-color: #333333; color: white; font-size: 20px;">Esc</b> to being the test.</p>
                            <p id="per" class='quote' style='font-size: 15px;'></p>
                          </div>
                          <!-- #origin-text -->
                          <div class="test-wrapper">
                            <input type="text" id="test-area" class='test-area2 input_area' name="textarea" autocomplete="off" oninput="processCurrentText()" rows="6" placeholder="Type the words here" onClick="this.onclick=null;" style='display: inline-block;'></input>
                          </div>
                          <!-- .test-wrapper -->
                          <p id="count"></p>
                          <div class="meta">
                            <section id="clock">
                              <div><span class="timer" id="timer" hidden>00:00:00</span></div>
                            </section>
                          </div>
                        </div>
                        <div id="result">
                          <a style="color: #39e500"><u>View High Scores</u></a>
                          <p id="NWPM"><b>Speed:</b> <span id='span_wpm'>0.00 </span> WPM</p>
                          <p id="accuracy"><b>Accuracy:</b> <span id='span_acc'>0.00 </span> %</p>
                          <p id="h_timer"><b>Time:</b> <span id='span_time'>00:00:00 </span></p>
                        </div>
                      </div>
                      
                      <span class="startButton" onclick='return change_paragraph()'><span id='text_change'>Start</span> Test</span>
                      <span style="margin-left: 20px;" id='span_sound' class="glyphicon glyphicon-volume-off"></span>
                  </main>
                </form>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <div class="flex-canvas">
                <div class="inner_box">
                  <p id="re_speed" style="text-align:center;">Recent speed scores</p>
                  <div id="graph1">
                    <canvas id="myChart" style="max-height: 400px;"></canvas>
                  </div>
                </div>
                <div class="inner_box">
                  <p id="re_accu" style="text-align:center;">Recent accurecy scores</p>
                  <div id="graph2">
                    <canvas id="myChart2" style="max-height: 400px;"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
  @endsection

  @push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script>
    const testWrapper = document.querySelector(".test-wrapper");
    const testArea = document.querySelector("#test-area");
    const resetButton = document.querySelector("#reset");
    const theTimer = document.querySelector(".timer");
    const text = document.querySelector("#per");

    var incorrect_word_count = 0
    var incorrect_spell_count = 0
    var timer = [0, 0, 0, 0];
    var interval;
    var timerRunning = false;
    var strokeCount = 0;

    $('#re_speed').hide();
    $('#re_accu').hide();
    $('#h_timer').hide()

    //audio on-off button

    $('#span_sound').click(function() {
      $(this).toggleClass('glyphicon-volume-off glyphicon');
      $(this).toggleClass('glyphicon-volume-up glyphicon');
      $('.input_area').focus();
    });

    // Add leading zero to numbers 9 or below (purely for aesthetics):
    function leadingZero(time) {
      if (time <= 9) {
        time = "0" + time;
      }
      return time;
    }

    // Run a standard minute/second/hundredths timer:
    function runTimer() {
      let currentTime = leadingZero(timer[0]) + ":" + leadingZero(timer[1]) + ":" + leadingZero(timer[2]);
      theTimer.innerHTML = currentTime;
      timer[3]++;
      timer[0] = Math.floor((timer[3] / 100) / 60);
      timer[1] = Math.floor((timer[3] / 100) - (timer[0] * 60));
      timer[2] = Math.floor(timer[3] - (timer[1] * 100) - (timer[0] * 6000));
    }
    // Match the text entered with the provided text on the page:
    function spellCheck() {
      var originText = document.getElementById("per1").innerHTML;
      let data = {
        strcount: 0,
        incorrect_word_count: 0
      }
      let textEntered = testArea.value;
      var count = 0
      let originTextMatch = originText.substring(0, textEntered.length);
      var key = event.keyCode
      strokeCount++
      var strcount = strokeCount
      data.strcount = strokeCount
      if (textEntered == originText) {
        clearInterval(interval);
        $('input').prop('disabled', true);
        data.incorrect_word_count = incorrect_spell_count
        testWrapper.style.borderColor = "#429890";
        timerRunning = false;
        $('#h_timer').show()
        setTimeout(() => {
          let s = $('#h_NWPM').val();

          let a = $('#h_Accuracy').val();

          let time = $('#h_timer').val();
          $("#span_time").text(time);

          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

          $.ajax({
            url: '/test_data',
            type: 'POST',
            data: {
              'NWPM': s,
              'Accuracy': a
            },
            success: function(data) {
              //console.log("accurecy",data.list_accuracy)
              var speed = data.list_speed.reverse();
              var accuracy = data.list_accuracy.reverse();
              if (speed.length > 0) {
                $('#re_speed').show();
                $('#re_accu').show();

                $("canvas#myChart").remove();
                $("#graph1").append('<canvas id="myChart" style="max-height: 400px;"></canvas>');
                graphScript(speed);
                $("canvas#myChart2").remove();
                $("#graph2").append('<canvas id="myChart2" style="max-height: 400px;"></canvas>');
                graphScript2(accuracy);
              }
              speed
            },

          });
        }, 100)

      } else {
        if (textEntered == originTextMatch) {
          data.incorrect_word_count = incorrect_spell_count
          //$("#origin-text").css("background-color", "##ffffff");
          $("#origin-text").removeClass("wrong_word_color");
          $("#origin-text").addClass("right_word_color");

        } else {
          if (key == 8 && key == 27) {} else {

            incorrect_spell_count += 1
            incorrect_word_count = incorrect_spell_count
            data.incorrect_word_count = incorrect_spell_count
            $("#origin-text").removeClass("right_word_color");
            $("#origin-text").addClass("wrong_word_color");

            var sound_class = document.getElementsByClassName('glyphicon-volume-up glyphicon')

            if (sound_class.length == 1) {

              let sound_id = document.getElementById("span_sound");
              const play_sound = document.getElementById("myAudio");

              play_sound.play();
            } else {
              //pass
            }

          }
        }
      }
      WPM(data)
    }
    // Start the timer:
    function start() {
      let textEnterdLength = testArea.value.length;
      if (textEnterdLength === 0 && !timerRunning) {
        timerRunning = true;
        interval = setInterval(runTimer, 10);
      }
    }

    // start_test_button
    function change_paragraph() {

      $('#text_change').text('Next');
      NWPM = 0.00;
      Accuracy = 0.00;
      correct_word = 0
      time = 0
      GWMP = 0.00
      strokeCount = 0
      incorrect_spell_count = 0
      timer = [0, 0, 0, 0]
      var timerRunning = false;

      var value_0_fix = (0.00).toFixed(2)
      $("#span_wpm").text(value_0_fix);
      $("#span_acc").text(value_0_fix);
      start()
      timerRunning = false;
      $('#h_timer').hide()

      $('#h_NWPM').val('');
      $('#h_Accuracy').val('');
      $('#h_timer').val('');
      document.getElementById('per').innerHTML = '';
      $('.input_area').val('');
      $('#origin-text').addClass('right_word_color')
      $('#origin-text').removeClass('wrong_word_color')
      $('.input_area').show()
      $('input').prop('disabled', false);
      $('.input_area').focus()
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url: '/test2',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
          document.getElementById('per1').innerHTML = data.sentence;
          var quote_text = document.querySelector(".quote");
          var input_area = document.querySelector(".input_area");
          var quoteNo = 0;
          quotes_array = []
          quotes_array.push(data.sentence)
          document.getElementById('per2').style.display = 'none';
          originText.textContent = null;
          current_quote = quotes_array[quoteNo];
          current_quote.split('').forEach(char => {
            const charSpan = document.createElement('span')
            charSpan.innerText = char
            quote_text.appendChild(charSpan)
          })
          if (quoteNo < quotes_array.length - 1) {
            quoteNo++;
          } else {
            quoteNo = 0;
          }
        },
      });
    }
    //Find Speed and Accuracy
    var NWPM = 0.00;
    var Accuracy = 0.00;
    var correct_word = 0
    var time = 0.00
    var GWMP = 0.00

    $('#h_NWPM').val('');
    $('#h_Accuracy').val('');
    $('#h_timer').val('');

    function WPM(data) {
      correct_word = data.strcount - data.incorrect_word_count

      let speed_time = $('#timer').text();

      var split_time = timer

      var key = event.keyCode

      let text = testArea.value.split(" ");
      //Get percentage timing
      time = Number(split_time[0]) + (Number(parseFloat(split_time[1] / 60).toFixed(2)))

      GWMP = (data.strcount / time).toFixed(2)

      NWPM = (correct_word / 5 / time).toFixed(2)

      $("#span_wpm").text(NWPM);

      Accuracy = (((correct_word / time) * 100) / GWMP).toFixed(2)

      $("#span_acc").text(Accuracy);

      $('#h_NWPM').val(NWPM);
      $('#h_Accuracy').val(Accuracy);
      $('#h_timer').val(speed_time);

    }

    var quote_text = document.querySelector(".quote");
    var input_area = document.querySelector(".input_area");
    var quoteNo = 0;
    let originText = document.getElementById("per1").innerHTML;
    quotes_array = []
    quotes_array.push(originText)

    function updateQuote() {
      document.getElementById('per2').style.display = 'none';
      originText.textContent = null;
      current_quote = quotes_array[quoteNo];
      current_quote.split('').forEach(char => {
        const charSpan = document.createElement('span')
        charSpan.innerText = char
        quote_text.appendChild(charSpan)
      })
      if (quoteNo < quotes_array.length - 1) {
        quoteNo++;
      } else {
        quoteNo = 0;
      }

    }

    function processCurrentText() {
      curr_input = input_area.value;
      curr_input_array = curr_input.split('');
      quoteSpanArray = quote_text.querySelectorAll('span');
      quoteSpanArray.forEach((char, index) => {
        let typedChar = curr_input_array[index]

        if (typedChar == null) {
          char.classList.remove('correct_char');
          char.classList.remove('incorrect_char');
        } else if (typedChar === char.innerText) {
          char.classList.add('correct_char');
          char.classList.remove('incorrect_char');
        } else {
          char.classList.add('incorrect_char');
          char.classList.remove('correct_char');
        }
      });

    }

    function startGame() {
      updateQuote();

    }
    window.addEventListener("keydown", function(e) {
      if (["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].indexOf(e.code) > -1) {
        e.preventDefault();
      }
    }, false);

    $('.input_area').hide();
    setTimeout(function() {
      $('.input_area').focus()
    }, 3000);

    $(document).on('keyup', function(e) {

      if (e.key == "Escape") {

        change_paragraph()

      }
    });
    // Event listeners for keyboard input and the reset
    testArea.addEventListener("keypress", start, WPM, false);
    testArea.addEventListener("keyup", spellCheck, false);
  </script>
  <script>
    $('#re_speed').show();
    $('#re_accu').show();
    var speed_data = <?php echo json_encode($list_speed); ?>;
    var accuracy_data = <?php echo json_encode($list_accuracy); ?>;

    var speed = speed_data.reverse();
    var accuracy = accuracy_data.reverse();

    graphScript(speed);
    graphScript2(accuracy);

    function graphScript(array) {

      const ctx = document.getElementById('myChart');
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
          datasets: [{
            label: 'speed',
            data: array,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
          }]
        },
        options: {
          scales: {
            x: {
              display: true,
              title: {
                display: true,
                text: 'Last 10 Tests'
              }
            },
            y: {
              beginAtZero: true,
              display: true,
              title: {
                display: true,
                text: 'speed'
              }
            },

          }
        }
      });

    }
    // this is second chart for acccurecy

    function graphScript2(array2) {

      const ctx = document.getElementById('myChart2');
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
          datasets: [{
            label: 'accuracy',
            data: array2,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
          }]
        },
        options: {
          scales: {
            x: {
              display: true,
              title: {
                display: true,
                text: 'Last 10 Tests'
              }
            },
            y: {
              beginAtZero: true,
              display: true,
              title: {
                display: true,
                text: 'acccurecy'
              }
            },

          }
        }
      });
    }
  </script>
  @endpush