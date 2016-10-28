var gulp = require( 'gulp' );
var gutil = require( 'gulp-util' );
var ftp = require( 'vinyl-ftp' );
var secrets = require('./secrets.json')

gulp.task( 'deploy', function () {
    var conn = ftp.create( {
        host:     secrets.host,
        user:     secrets.user,
        password: secrets.pass,
        parallel: 10,
        log:      gutil.log
    } );

    var globs = [
        'assets/**',
        'includes/**',
        'languages/**',
        'themes/**',
        'iw_booking.php',
        'LICENSE',
        'README.md',
        'uninstall.php',
        'wp-booking-engine.iml',
        'wp-booking-engine.php',
    ];

    // using base = '.' will transfer everything to /public_html correctly 
    // turn off buffering in gulp.src for best performance 

    return gulp.src( globs, { base: '.', buffer: false } )
        .pipe( conn.newer( 'dev/wp-content/plugins/wp-booking-engine/' ) ) // only upload newer files
        .pipe( conn.dest( 'dev/wp-content/plugins/wp-booking-engine/' ) );

} );