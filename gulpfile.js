var gulp = require('gulp'),
      sass = require('gulp-sass'),
      autoprefixer = require('gulp-autoprefixer'),
      zip = require('gulp-zip');

var devFolder = '_dev';
var buildFolder = '_build';
var sourceFiles = ['_dev/assets/css/**/*', '_dev/assets/js/**/*', '_dev/extlb_link-preview.php', '_dev/index.php', '_dev/LICENSE'];


gulp.task('sass', function() {
  return gulp.src(devFolder + '/assets/scss/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(gulp.dest(devFolder + '/assets/css'))
})


gulp.task('scss:prod', function() {
  return gulp.src(devFolder + '/assets/scss/*.scss')
    .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
    .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
    .pipe(gulp.dest(buildFolder + '/assets/css'))
})

gulp.task('copy:prod', function() {
  return gulp.src(sourceFiles, { base: '_dev' })
    .pipe(gulp.dest(buildFolder))
})

gulp.task('zip', function() {
  return gulp.src('_build/**/*')
    .pipe(zip('extlb_link-preview.zip'))
    .pipe(gulp.dest('_compressed'))
})


///////////////////////////////
gulp.task('dev', function () {
  gulp.watch(devFolder + '/assets/scss/*.scss', ['sass']);
})

gulp.task('build', ['scss:prod','copy:prod']);