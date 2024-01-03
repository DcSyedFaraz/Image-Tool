<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Image Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/3.6.3/fabric.min.js"></script>
</head>

<body>
    <div class="container">

        <form action="{{ route('endpoint') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="formFile" class="form-label"> file input </label>
                <input class="form-control" type="file" id="formFile" name="file">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <a href="{{ route('create') }}" class="btn btn-dark">click</a>
        <button onclick="saveCanvas()">Save Canvas</button>
        <canvas id="canvas" width="800" height="600"></canvas>

        <script>
            const canvas = new fabric.Canvas('canvas');

            // Load Image
            fabric.Image.fromURL('{{ asset('default_cropped_1702050495.jpg') }}', (img) => {
                img.scaleToWidth(canvas.width);
                img.set({
                    selectable: false,
                    evented: false
                });
                canvas.add(img);
                const boundary = new fabric.Rect({
                    left: 100,
                    /* Set the left position of the boundary */
                    top: 100,
                    /* Set the top position of the boundary */
                    width: 200,
                    /* Set the width of the boundary */
                    height: 100,
                    /* Set the height of the boundary */
                    stroke: 'red',
                    /* Set the border color */
                    strokeWidth: 2,
                    /* Set the border width */
                    selectable: true,
                    evented: true,
                    /* Make the boundary unselectable */
                    fill: '#fff0',
                });
                canvas.add(boundary);

                // Load Logo
                @if (isset($logoUrl))

                    fabric.Image.fromURL('{{ $logoUrl }}', (logo) => {
                        logo.scaleToWidth(100); // Adjust the logo size as needed
                        const boundaries = {
                            left: 10,
                            top: 10,
                            width: 20,
                            height: 10
                        };

                        logo.set({
                            left: Math.max(boundaries.left, Math.min(boundaries.left + boundaries
                                .width - logo.width * logo.scaleX, logo.left)),
                            top: Math.max(boundaries.top, Math.min(boundaries.top + boundaries.height -
                                logo.height * logo.scaleY, logo.top)),
                            // selectable: false, // Make the logo unselectable
                            // evented: false, // Disable events on the logo
                        });
                        canvas.add(logo);
                        canvas.on('object:moving', function(options) {
                            const obj = options.target;

                            // Restrict movement within the boundary
                            if (obj.left < boundary.left) {
                                obj.left = boundary.left;
                            }
                            if (obj.top < boundary.top) {
                                obj.top = boundary.top;
                            }
                            if (obj.left + obj.width > boundary.left + boundary.width) {
                                obj.left = boundary.left + boundary.width - obj.width;
                            }
                            if (obj.top + obj.height > boundary.top + boundary.height) {
                                obj.top = boundary.top + boundary.height - obj.height;
                            }
                        });
                        canvas.renderAll();
                    }, { crossOrigin: "Anonymous" });
                @endif
            });

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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</body>

</html>
