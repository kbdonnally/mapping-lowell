// gulpfile.js

// --------------------------------------------------
// 				 Import modules:
// --------------------------------------------------

var gulp 		 = require('gulp'),
	clean 		 = require('gulp-clean'),
	concat 		 = require('gulp-concat'),
	uglify 		 = require('gulp-uglify-es').default,
	insert 		 = require('gulp-insert');
	sass 		 = require('gulp-sass'),
	autoprefixer = require('gulp-autoprefixer'),
	replace 	 = require('gulp-replace'),
	paths 		 = require('./_assets/gulp_config/paths');


// --------------------------------------------------
// 				 Tasks: JavaScript
// --------------------------------------------------

// ------ format JS: --------------------------------
// ------ concatenate + minify + wrap in IIFE -------

// define using functions
var js_main_format = (done) => {
	gulp.src('_assets/js/_partials/*.js')
		.pipe(concat('main.js'))
		.pipe(uglify({ keep_fnames: true }))
		.pipe(insert.wrap('(function() {', '\n})();'))
		.pipe(gulp.dest('_site/assets/js'))
		.pipe(gulp.dest('assets/js'));
	done();
}

var js_lib_format = (done) => {
	gulp.src('_assets/js/lib/*.min.js')
		.pipe(gulp.dest('_site/assets/js/lib'))
		.pipe(gulp.dest('assets/js/lib'));
	done();
};

// make tasks for each
gulp.task('js_main_format', js_main_format);
gulp.task('js_lib_format', js_lib_format);

// result:
gulp.task('js_format', gulp.parallel('js_main_format', 'js_lib_format'));


// ------ clean JS: ---------------------------------

// delete compiled file
var js_main_clean = (done) => {
	gulp.src('assets/js/*.js', { allowEmpty: true })
		.pipe(clean());
	done();
};

var js_site_clean = (done) => {
	gulp.src('_site/assets/js/*.js', { allowEmpty: true })
		.pipe(clean());
	done();
};

// tasks for each location
gulp.task('js_main_clean', js_main_clean);
gulp.task('js_site_clean', js_main_clean);

// result:
gulp.task('js_clean', gulp.parallel('js_main_clean', 'js_site_clean'));


// ---------------- JS: Watching --------------------

// rewrite if changes
gulp.task('js_main_watch', () => {
	return gulp.watch('_assets/js/_partials/*.js', js_main_format);
});

/* NB: lib doesn't change -> no cleaning or watching */

// ---------------- JS: Final -----------------------

// delete, reformat, rewrite if changes
gulp.task('js', gulp.series('js_clean', 'js_format', 'js_main_watch'));


// --------------------------------------------------
// 					Tasks: CSS
// --------------------------------------------------

// --------------- CSS: Compiling -------------------

// compile + minify + autoprefix
var css_main_format = (done) => {
	gulp.src('_assets/css/style.scss')
		.pipe(sass({
			includePaths: ['./_assets/css/_sass'],
			outputStyle: 'compressed'
		}).on('error', sass.logError))
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			grid: true
		}))
		.pipe(gulp.dest('_site/assets/css'))
		.pipe(gulp.dest('assets/css'));
	done();
};

// pass along unchanged
var css_lib_format = (done) => {
	gulp.src('_assets/css/lib/*.css')
		.pipe(gulp.dest('_site/assets/css/lib'))
		.pipe(gulp.dest('assets/css/lib'));
	done();
};

// make into tasks
gulp.task('css_main_format', css_main_format);
gulp.task('css_lib_format', css_lib_format);

// result:
gulp.task('css_format', gulp.parallel('css_main_format', 'css_lib_format'));

// --------------- CSS: Cleaning --------------------

// delete compiled file
var css_main_clean = (done) => {
	gulp.src('assets/css/style.css', { allowEmpty: true })
		.pipe(clean());
	done();
};

var css_site_clean = (done) => {
	gulp.src('_site/assets/css/style.css', { allowEmpty: true })
		.pipe(clean());
	done();
};

// make into tasks
gulp.task('css_main_clean', css_main_clean);
gulp.task('css_site_clean', css_site_clean);

// result:
gulp.task('css_clean', gulp.parallel('css_main_clean', 'css_site_clean'));

// ----------------- CSS: Watch ---------------------

gulp.task('css_main_watch', () => {
	return gulp.watch('_assets/css/**/*.scss', css_main_format);
});

// ----------------- CSS: Final ---------------------

gulp.task('css', gulp.series('css_clean', 'css_format', 'css_main_watch'));


// --------------------------------------------------
// 					Tasks: Images
// --------------------------------------------------

// not processing the images rn, so just
// passing them to the right directory
var img_all_format = () => {
	return gulp.src(paths.imgSrc)
		.pipe(gulp.dest('_site/assets/img'))
		.pipe(gulp.dest('assets/img'));
};

gulp.task('img_format', img_all_format);

// watch -> change -> send files
gulp.task('img_watch', () => {
	return gulp.watch(paths.imgSrc, img_all_format);
});

// delete folder the site reads
gulp.task('img_clean', () => {
	return gulp.src('assets/img', { allowEmpty: true })
		.pipe(clean());
});
/* NB: not bothering to completely rewrite rn */

// clear site-read folder -> rewrite when images change
gulp.task('img', gulp.series('img_clean', 'img_format', 'img_watch'));

// --------------------------------------------------
// 			  Tasks: Build & watch assets
// --------------------------------------------------

gulp.task('assets', gulp.parallel('js', 'css', 'img'));

/* NB: add explanatory notes on a copy of this file &
 * turn that into docs; delete most of the notes on the
 * version you'll use in 'production'
 *
 * Eg: these are prob separated into sub-optimal # of tasks
 * rn, but I'd prefer the code to be more readable than a couple
 * milliseconds faster; in addition to being able to make more
 * surgical changes to what the tasks do.
 */