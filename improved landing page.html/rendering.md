To view the static HTML page rendering directly from GitHub, you have a few options:

1. **Use GitHub Pages**
This is the best solution to see the rendered page:

```bash
# Enable GitHub Pages for your repository
# Go to repository Settings > Pages
# Select 'main' branch and '/static' folder as source
```

After enabling, your page will be available at:
`https://nordeim.github.io/The-Scent-gpt6/static/index.html`

2. **Add Preview to README.md**
For the README.md in the /static directory, you can add:

````markdown name=static/README.md
# Static Landing Page Preview

## 🖼️ Preview
![Landing Page Preview](../images/screenshots/landing-preview.png)

## 🔗 View Live Preview
- [View on GitHub Pages](https://nordeim.github.io/The-Scent-gpt6/static/index.html)
- [Raw HTML Source](./index.html)

## 📝 Notes
This is a static HTML rendering of the main landing page. Features demonstrated:
- Responsive design
- Animations (AOS.js)
- Particle effects
- Video background
- Glass-morphism UI

## 🎯 Preview Instructions
To view this page locally:
1. Clone the repository
2. Open `/static/index.html` in a web browser
3. Or use a local server:
```bash
cd The-Scent-gpt6/static
python -m http.server 8000
# Then visit http://localhost:8000
```

## 🔍 Missing Resources
Note: Some resources (images, videos) may not display in the preview as they require:
- `/videos/hero.mp4`
- `/images/products/*.jpg`
- `/images/about.jpg`

Please ensure these assets are added to the appropriate directories.
````

3. **Add Preview Section to Main README.md**
Add this section to your main README.md:

````markdown
## 🎨 Landing Page Preview

<details>
<summary>Click to see landing page preview</summary>

![Landing Page Preview](images/screenshots/landing-preview.png)

### 🔗 View Live Demo
- [View Static Preview](https://nordeim.github.io/The-Scent-gpt6/static/index.html)
- [Source Code](static/index.html)

</details>
````

Would you like me to:
1. Create the GitHub Pages configuration instructions?
2. Generate the README.md for the /static directory?
3. Create the preview section for the main README.md?

The key is to enable GitHub Pages for your repository, as this will allow direct viewing of the HTML page. Without GitHub Pages, users can only see the raw HTML code when viewing the file on GitHub.
