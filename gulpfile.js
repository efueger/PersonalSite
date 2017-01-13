var gulp = require('gulp');
var sass = require('gulp-sass');
var clean = require('gulp-clean-css');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

gulp.task('appcss', function () {
    return gulp
        .src('resources/sass/*.scss')
        .pipe(sass())
        .pipe(clean())
        .pipe(gulp.dest('public/css'));
});

gulp.task('vendorcss', function () {
    return gulp
        .src(['node_modules/bootstrap/dist/css/bootstrap.css', 'node_modules/font-awesome/css/font-awesome.css'])
        .pipe(concat('vendor.css'))
        .pipe(clean())
        .pipe(gulp.dest('public/css'));
});

gulp.task('vendorjs', function () {
    return gulp
        .src(['node_modules/jquery/dist/jquery.js', 'node_modules/bootstrap/dist/js/bootstrap.js', 'node_modules/turbolinks/dist/turbolinks.js'])
        .pipe(concat('vendor.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/js'));
});

gulp.task('appjs', function () {
    return gulp
        .src('resources/js/**/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('public/js'))
});

gulp.task('vendorfonts', function () {
    return gulp
        .src(['node_modules/font-awesome/fonts/*', 'node_modules/bootstrap/dist/fonts/*'])
        .pipe(gulp.dest('public/fonts'));
});

gulp.task('watch', ['appcss', 'appjs'], function () {
    gulp.watch(['resources/sass/*.scss'], ['appcss']);
    gulp.watch(['resources/js/*.js'], ['appjs']);
});

gulp.task('default', ['appcss', 'vendorcss', 'vendorjs', 'appjs', 'vendorfonts', 'watch']);