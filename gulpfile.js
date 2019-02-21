var gulp = require('gulp'),
      sass = require('gulp-sass'),
      autoprefixer = require('gulp-autoprefixer'),
      minify = require('gulp-minify'),
      zip = require('gulp-zip');

var projectName = 'extlb_link-preview';
var buildFolder = '_build';
var sourceFiles = ['assets/css/**', 'assets/js/' + projectName + '.min.js', projectName + '.php', 'index.php', 'includes/**','languages/**', 'LICENSE'];


gulp.task('sass', function() {
  return gulp.src('scss/*.scss')
    .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(gulp.dest('assets/css'))
})

gulp.task('minify:prod', function () {
  gulp.src('assets/js/' + projectName + '.js')
    .pipe(minify({
      ext: {
        min: '.min.js'
      },
      ignoreFiles: ['.min.js'],
      noSource: true
    }))
    .pipe(gulp.dest('assets/js'))
});

gulp.task('zip', function() {
  return gulp.src(sourceFiles, { base: './' })
    .pipe(zip(projectName + '.zip'))
    .pipe(gulp.dest(buildFolder))
})

gulp.task('dev', function () {
  gulp.watch('scss/*.scss', ['sass']);
})

gulp.task('build', ['minify:prod']);