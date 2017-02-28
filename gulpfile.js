var gulp = require( 'gulp' );
var gutil = require( 'gulp-util' );
var sass = require( 'gulp-sass' );
var postcss = require( 'gulp-postcss' );
var ftp = require( 'vinyl-ftp' );
var secrets = require('./secrets.json');

gulp.task( 'deploy', function () {
    var conn = ftp.create( {
        host:     secrets.host,
        user:     secrets.user,
        password: secrets.pass,
        parallel: 10,
        log:      gutil.log
    } );

    var globs = [
        'plugin/**',
        '!languages/**/*.po',
        '!languages/**/*.mo',
    ];

    // using base = '.' will transfer everything to /public_html correctly 
    // turn off buffering in gulp.src for best performance 

    return gulp.src( globs, { base: './plugin', buffer: false } )
        .pipe( conn.newer( 'dev/wp-content/plugins/wp-booking-engine/' ) ) // only upload newer files
        .pipe( conn.dest( 'dev/wp-content/plugins/wp-booking-engine/' ) );

} );

gulp.task('sass', function(){

       return gulp.src(['src/scss/**/*.scss'])
            .pipe( sass({ outputStyle: 'compressed' }).on('error', sass.logError) )
            .pipe( postcss([
                require('autoprefixer')({ browsers: ['> 1%', '> 1% in my stats', 'ie > 8', 'last 2 versions'], stats: 'browserStats.json' }),
            ]) )
    .pipe( gulp.dest("plugin/assets/css") );

});

gulp.task('watch', function(){
    gulp.watch(['src/**/*.scss'], [ 'sass' ]);
});