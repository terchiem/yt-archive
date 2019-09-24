var gulp = require('gulp');
var bs = require('browser-sync').create(); // create a browser sync instance.

gulp.task('browser-sync', function() {
  bs.init({
    files: ["**/*.css", "**/*.php", '**/*.js'],
    proxy: "localhost/yt-classic",
    port: 80
  });
});

// gulp.task('watch', ['browser-sync'], function () {
//   // gulp.watch("scss/*.scss", ['sass']);
//   gulp.watch("**/*.css").on('change', bs.reload);
// });