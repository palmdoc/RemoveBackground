# Remove Background Web App

This repo now includes two ways to use the app:

- **Preview-friendly static app**: open `index.html` in a preview/static server and run background removal in the browser.
- **Flask backend app**: run `app.py` and process uploads on the server using `rembg`.

## 1) Preview mode (no backend required)

Open the repo preview on `index.html` and upload an image.

> Note: this mode downloads the model in the browser, so the first run can take longer.

## 2) Flask mode

### Setup

```bash
python -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
```

### Run

```bash
python app.py
```

Then open `http://localhost:5000`.
