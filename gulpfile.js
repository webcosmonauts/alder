
 const gulp       = require('gulp');
 const sass       = require('gulp-sass');
 const sourcemaps = require('gulp-sourcemaps');
 const concat     = require('gulp-concat');
 const cleanCss   = require('gulp-clean-css');
 const rename     = require('gulp-rename');
 const uglify     = require('gulp-uglifyjs');
 const cached     = require('gulp-cached');
 const filter     = require('gulp-filter');


const dir = {
	resources: './resources/',
	src: {
		vendor: './resources/vendor/',
		js: './resources/js/',
		sass: './resources/sass/'
	},
	target: {
		js: './public/js/',
		css: './public/css/'
	}
};

/*********************************************/

gulp.task('sass', function (done) {
	return gulp.src(dir.src.sass + '*.scss')
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(sass())
		.pipe(cleanCss({compatibility: '*'}))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(dir.target.css));
});

gulp.task('styles', gulp.series('sass'));


gulp.task('scripts-admin', function () {
	return gulp.src([
		'jquery3-4-1.min.js',
		'popper.min.js',
		'bootstrap4.min.js',

		'all.min.js',
		'brands.js',
		'Chart.bundle.js',
		'Chart.js',
		'fontawesome.js',
		'regular.js',
		'sb-admin-2.js',
		'solid.js',
		'v4-shims.js',

		'datepicker.min.js',

		'contact-form/contact-form.js',
		'contact-form/contact-form-parser.js',

		'file-manager/file-manager.js',
		'app.js',
		'admin.js'
		

	].map(path => dir.src.js + path))
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(uglify())
		.pipe(cached('js-admin-uglify'))
		.pipe(concat("admin.js"))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(dir.target.js));
});



gulp.task('scripts', gulp.parallel('scripts-admin'));

// Watch
gulp.task('watch', gulp.parallel('styles', 'scripts', function () {
	gulp.watch(dir.src.sass + '**/*.scss', gulp.series('styles')); // Наблюдение за sass файлами
	gulp.watch(dir.src.js + '**/*.js', gulp.series('scripts')); // Наблюдение за JS файлами в папке js
}));

// Build
gulp.task('build', gulp.parallel('styles', 'scripts'));


// Default task to be run with `gulp`
gulp.task('default', gulp.series('watch'));
