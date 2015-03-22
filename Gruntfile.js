module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      dev: {
        options: {
          outputStyle: 'expanded',
          includePaths: require('node-bourbon').includePaths,
        },
        files: {
          'css/app.css': 'src/scss/app.scss',
          'css/normalize.css': 'src/scss/normalize.scss',
          'css/ie.css': 'src/scss/ie.scss',
        },
      },
      dist: {
        options: {
          outputStyle: 'compress',
          includePaths: require('node-bourbon').includePaths,
        },
        files: {
          'css/app.css': 'src/scss/app.scss',
          'css/normalize.css': 'src/scss/normalize.scss',
          'css/ie.css': 'src/scss/ie.scss',
        },
      },
    },

    uglify: {
      dist: {
        files: [
          {
            expand: true,
            cwd: 'src/js',
            src: ['**/*.js'],
            dest: 'js/',
            ext: '.min.js',
            extDot: 'first'
          },
        ],
      }
    },

    sync: {
      dev: {
        files: [
          {
            expand: true,
            cwd: 'src/js',
            src: ['**/*.js'], 
            dest: 'js/',
            ext: '.min.js'
          },
        ],
      }
    },

    watch: {
      grunt: { 
        files: ['Gruntfile.js'] 
      },
      sass: {
        files: 'sass/**/*.scss',
        tasks: ['sass:dev'],
      },
      js: {
        files: 'src/**/*.js',
        tasks: ['sync:dev'],
      },
    },
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-sync');

  grunt.registerTask('build', ['sass:dist', 'uglify:dist']);
  grunt.registerTask('default', ['sass:dev', 'sync:dev', 'watch']);
}