const path = require("path");

module.exports = {
  reactStrictMode: true,
  images: {
    domains: process.env.NEXT_ALLOWED_IMAGE_DOMAINS.split(", "),
  },
  sassOptions: {
    includePaths: [path.join(__dirname, "styles/scss")],
  },
};
