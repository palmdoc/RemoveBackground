from io import BytesIO

from flask import Flask, render_template, request, send_file
from rembg import remove

app = Flask(__name__)


@app.get("/")
def index():
    return render_template("index.html")


@app.get("/<path:path>")
def index_fallback(path: str):
    # Some preview environments request a non-root URL (for example `/preview`).
    # Return the app shell for unknown GET routes so preview still renders.
    return render_template("index.html")


@app.post("/remove-background")
def remove_background():
    if "image" not in request.files:
        return {"error": "No image uploaded."}, 400

    uploaded = request.files["image"]
    if not uploaded or uploaded.filename == "":
        return {"error": "No image selected."}, 400

    input_bytes = uploaded.read()
    if not input_bytes:
        return {"error": "Uploaded image is empty."}, 400

    output_bytes = remove(input_bytes)
    return send_file(
        BytesIO(output_bytes),
        mimetype="image/png",
        as_attachment=True,
        download_name="background-removed.png",
    )


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
