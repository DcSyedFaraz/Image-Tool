<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Marking</title>
    <!-- Include Fabric.js library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.4.0/fabric.min.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 50px;
        }

        canvas {
            border: 2px solid #333;
        }
    </style>
</head>

<body>



    <input type="file" id="imageInput" accept="image/*">
    <button onclick="addRectangle()">Add Rectangle</button>
    {{-- <button onclick="enableDrawingMode()">Enable Drawing Mode</button>
    <button onclick="disableDrawingMode()">Disable Drawing Mode</button> --}}
    <button onclick="saveCanvas()">Save Canvas</button>
    <input type="text" id="overlayImageUrl" placeholder="Overlay Image URL"
        value="https://lumise.com/wp-content/uploads/2021/07/logo-remake-1.png">
    <button onclick="overlayImage()">Overlay Image</button>

    <canvas id="fabricCanvas" width="800" height="600"></canvas>

    <script>
        var canvas = new fabric.Canvas('fabricCanvas');
        var isDrawingMode = false;
        var markedLocation = null;
        var rect = null;

        document.getElementById('imageInput').addEventListener('change', function(event) {
            var file = event.target.files[0];

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var imageUrl = e.target.result;
                    fabric.Image.fromURL(imageUrl, function(img) {
                        canvas.add(img);
                    });
                };
                reader.readAsDataURL(file);
            }
        });


        function enableDrawingMode() {
            isDrawingMode = true;
            canvas.isDrawingMode = true;
        }

        function addRectangle() {
            if (!isDrawingMode) {


                rect = new fabric.Rect({
                    left: 100,
                    top: 100,
                    width: 100,
                    height: 100,
                    fill: 'transparent',
                    stroke: 'red',
                    strokeWidth: 2,
                    selectable: true,
                    hasControls: true,
                    originX: 'left',
                    originY: 'top'
                });

                canvas.add(rect);
            }
        }

        function disableDrawingMode() {
            isDrawingMode = false;
            canvas.isDrawingMode = false;

            // Filter out only the drawn shapes
            var drawnShapes = canvas.getObjects().filter(function(obj) {
                return obj.isType('path') || obj.isType('line');
            });

            // Calculate the bounding box of the drawn shapes
            var group = new fabric.Group(drawnShapes);
            markedLocation = {
                left: group.left,
                top: group.top,
                width: group.width * group.scaleX,
                height: group.height * group.scaleY
            };
            console.log(markedLocation);
            // Remove only the drawn shapes
            canvas.discardActiveObject();
            canvas.remove.apply(canvas, drawnShapes);

            // Add back the background image
            var background = canvas.getObjects().find(obj => !obj.isType('path') && !obj.isType('line'));
            if (background) {
                canvas.add(background);
            }

            alert('Location Marked!');
        }

        function overlayImage() {
            var overlayImageUrl = document.getElementById('overlayImageUrl').value;

            if (overlayImageUrl && rect) {
                fabric.Image.fromURL(overlayImageUrl, function(img) {
                    img.set({
                        left: rect.left,
                        top: rect.top,
                        width: rect.width,
                        height: rect.height
                    });
                    canvas.add(img);
                });
            } else {
                alert('Provide an overlay image URL and mark a location first.');
            }
            // Update overlay image when the rectangle is resized
            rect.on('scaling', function(event) {
                var scaleX = event.target.scaleX;
                var scaleY = event.target.scaleY;

                // Update the overlay image size
                canvas.forEachObject(function(obj) {
                    if (obj !== rect && obj.isType('image')) {
                        obj.set({
                            width: rectWidth * scaleX,
                            height: rectHeight * scaleY
                        });
                    }
                });
            });

        }

        function saveCanvas() {
            // Convert canvas content to a data URL
            var dataURL = canvas.toDataURL('image/png');

            // Create an "a" element
            var link = document.createElement('a');
            link.href = dataURL;
            link.download = 'canvas_image.png';

            // Trigger a click event to download the image
            var clickEvent = new MouseEvent('click', {
                bubbles: true,
                cancelable: true,
                view: window
            });

            link.dispatchEvent(clickEvent);
        }
    </script>
</body>

</html>
