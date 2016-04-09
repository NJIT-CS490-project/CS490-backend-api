'use strict';

const realgulp = require('gulp');
const gulp     = require('gulp-help')(realgulp);
const clean    = require('gulp-clean');
const changed  = require('gulp-changed');
const path     = require('path');
const sequence = require('run-sequence');

const paths = {
	'build':   path.resolve('build'),
	'source':  path.resolve('source'),
	'public':  path.resolve('public'),
	'project': path.resolve('.')
};

function cleanDirectory (target) {
	return () => {
		return gulp.src(target, { read: false })
			.pipe(clean({ force: true }));
	}
}

function move (source, target) {
	return () => {
		return gulp.src(source)
			.pipe(changed(target))
			.pipe(gulp.dest(target));
	}
}

function moveDirectory (source, target) {
	return move(`${source}/**/*`, target);
}
gulp.task('clean', 'Cleans the build directory', 
	cleanDirectory(paths.build)
);

gulp.task('build', 'Builds the project into the build directory', [
	'build-source',
	'build-config',
	'build-autoload',
	'build-public',
	'build-app'
]);

gulp.task('rebuild', 'Rebuilds the project.', ['clean'], (callback) => {
	sequence('clean', 'build', callback);
});

gulp.task('watch', 'Watches the project and builds when changes occur', () => {
	gulp.watch(`${paths.source}/**/*`,                ['build-source']);
	gulp.watch(`${paths.public}/**/*`,                ['build-public']);
	gulp.watch(`${paths.project}/autoload.php`,       ['build-autoload']);
	gulp.watch(`${paths.project}/app.php`,            ['build-app']);
	gulp.watch(`${paths.project}/config.+(php|json)`, ['build-config']);
});

gulp.task('build-source', false, 
	moveDirectory(paths.source, `${paths.build}/source`)
);

gulp.task('build-autoload', false,
	move(`${paths.project}/autoload.php`, paths.build)
);

gulp.task('build-app', false,
	move(`${paths.project}/app.php`, paths.build)
);

gulp.task('build-public', false,
	moveDirectory(paths.public, `${paths.build}/public`)
);

gulp.task('build-config', false,
	move(`${paths.project}/config.+(json|php)`, paths.build)
);
