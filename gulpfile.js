var gulp = require('gulp');
var sass = require('gulp-sass');
var clean = require('gulp-clean-css');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

gulp.task('appcss', function () {
    return gulp
        .src('resources/sass/**/*.scss')
        .pipe(sass())
        .pipe(clean())
        .pipe(gulp.dest('public/css'));
});

gulp.task('vendorcss', function () {
    return gulp
        .src(['node_modules/bootstrap/dist/css/bootstrap.css'])
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
        .src('resources/js/app.js')
        .pipe(uglify())
        .pipe(gulp.dest('public/js'))
});

gulp.task('watch', ['appcss', 'appjs'], function () {
    gulp.watch(['resources/sass/**/*.scss'], ['appcss']);
    gulp.watch(['resources/js/app.js'], ['appjs']);
});

gulp.task('default', ['appcss', 'vendorcss', 'vendorjs', 'appjs', 'watch']);