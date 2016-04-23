/*
 * ASCII Camera
 * http://idevelop.github.com/ascii-camera/
 *
 * Copyright 2013, Andrei Gheorghe (http://github.com/idevelop)
 * Released under the MIT license
 */

(function() {
	var asciiContainer = document.getElementById("ascii");
	var capturing = false;
	var snippet = document.querySelector(".webcam-snippet");

	camera.init({
		width: 640,
		height: 480,
		fps: 30,
		mirror: true,
		targetCanvas: document.querySelector('canvas'),

		onFrame: function(canvas) {
			ascii.fromCanvas(canvas, {
				 //contrast: 128,
				callback: function(asciiString) {
					asciiContainer.innerHTML = asciiString;
				}
			});
		},

		onSuccess: function() {
			var self = this;
			document.getElementById("info").style.display = "none";
			this.targetCanvas.style.display = "block";

			capturing = true;
			snippet.querySelector("input[name=togglePause]").onclick = function() {
				if (capturing) {
					camera.pause();
					snippet.querySelector("input[name=capture]").style.display = "inline-block";
				} else {
					camera.start();
					snippet.querySelector("input[name=capture]").style.display = "none";
				}
				capturing = !capturing;
			};

			snippet.querySelector("input[name=ascii]").onchange = function() {
				if (this.checked) {
					snippet.querySelector('pre').style.display = 'block';
					snippet.querySelector('canvas').style.display = 'none';
					self.width = 320;
					self.height = 240;
					camera.resize();
					camera.start();
					snippet.querySelector("input[name=capture]").style.display = "none";
				} else {
					snippet.querySelector('pre').style.display = 'none';
					snippet.querySelector('canvas').style.display = 'block';
					self.width = 640;
					self.height = 480;
					camera.resize();
					camera.start();
					snippet.querySelector("input[name=capture]").style.display = "none";
				}
			};

			snippet.querySelector("input[name=capture]").onclick = function() {
				snippet.querySelector("textarea[name=asciiImg]").value = snippet.querySelector("pre").innerText;
				snippet.querySelector("textarea[name=imgBase64]").value = self.targetCanvas.toDataURL();
				snippet.querySelector("form").submit();
			}
		},

		onError: function(error) {
			// TODO: log error
		},

		onNotSupported: function() {
			document.getElementById("info").style.display = "none";
			asciiContainer.style.display = "none";
			document.getElementById("notSupported").style.display = "block";
		}
	});
})();
