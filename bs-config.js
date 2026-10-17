module.exports = {
  proxy: "http://localhost/wordpress",
  port: 3000,
  ui: false,
  open: true,
  notify: false,
  ghostMode: false,
  reloadDelay: 150,
  files: [
    "*.php",
    "template-parts/**/*.php",
    "companion-plugin/**/*.php",
    "companion-plugin/**/*.css",
    "companion-plugin/**/*.js",
    "style.css",
    "assets/js/**/*.js",
    "assets/images/**/*"
  ],
  ignore: ["node_modules"]
};
