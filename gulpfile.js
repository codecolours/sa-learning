const gulp = require('gulp');
const browserSync = require('browser-sync').create();
const dartSass = require('sass');
const gulpSass = require('gulp-sass');
const sass = gulpSass(dartSass);
const cleanCSS = require('gulp-clean-css');
const uglify = require('gulp-uglify');
const webpack = require('webpack-stream');
const named = require('vinyl-named');

gulp.task('scss', function() {
  return gulp.src('assets/scss/**/*.scss')
    .pipe(sass.sync({
      outputStyle: 'compressed',
      implementation: dartSass,
      quietDeps: true,
      logger: {
        warn: function(message) {
          console.log('SCSS Warning:', message);
        }
      }
    }).on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(gulp.dest('assets/dist/css'))
    .pipe(browserSync.reload({
      stream: true
    }));
});

gulp.task('site-images', function() {
  return gulp.src(['assets/images/**/*.{gif,jpg,png,svg}'])
    .pipe(gulp.dest('assets/dist/img'));
});

gulp.task('site-js', function() {
  return gulp.src(['assets/js/main.js'])
    .pipe(named())
    .pipe(webpack({
      mode: 'production',
      output: {
        filename: '[name].js'
      },
      module: {
        rules: [
          {
            test: /\.css$/,
            use: ['style-loader', 'css-loader']
          },
          {
            test: /\.js$/,
            exclude: /node_modules/,
            use: {
              loader: 'babel-loader',
              options: {
                presets: ['@babel/preset-env']
              }
            }
          }
        ]
      }
    }))
    .pipe(gulp.dest('assets/dist/js'));
});

gulp.task('serve', function(){
  gulp.watch('assets/scss/**/*.scss', gulp.series('scss')); 
  gulp.watch('assets/images/**/*.{gif,jpg,png,svg}', gulp.series('site-images'));
  gulp.watch('assets/js/**/*.js', gulp.series('site-js'));
});

// Default task
gulp.task('default', gulp.series('scss', 'site-js', 'site-images', 'serve'));