module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      dev: {
        options: {
          outputStyle: 'expanded'
        },
        files: {
          'css/app.css': 'scss/app.scss',
          'css/normalize.css': 'scss/normalize.scss',
          'css/ie.css': 'scss/ie.scss',
        },
      },
      dist: {
        options: {
          outputStyle: 'compress'
        },
        files: {
          'css/app.css': 'scss/app.scss',
          'css/normalize.css': 'scss/normalize.scss',
          'css/ie.css': 'scss/ie.scss',
        },
      },
    },

    uglify: {
      dist: {
        files: [
          {
            expand: true,     // Enable dynamic expansion.
            cwd: 'src/',      // Src matches are relative to this path.
            src: ['**/*.js'], // Actual pattern(s) to match.
            dest: 'js/',   // Destination path prefix.
            ext: '.min.js',   // Dest filepaths will have this extension.
            extDot: 'first',   // Extensions in filenames begin after the first dot
          },
        ],
      }
    },

    copy: {
      dev: {
        files: [
          {
            expand: true,     // Enable dynamic expansion.
            cwd: 'src/',      // Src matches are relative to this path.
            src: ['**/*.js'], // Actual pattern(s) to match.
            dest: 'js/',   // Destination path prefix.
            ext: '.min.js',   // Dest filepaths will have this extension.
            extDot: 'first',   // Extensions in filenames begin after the first dot
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
        tasks: ['copy:dev'],
      },
    },
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-copy');

  grunt.registerTask('build', ['sass:dist', 'uglify:dist']);
  grunt.registerTask('default', ['sass:dev', 'copy:dev', 'watch']);
}