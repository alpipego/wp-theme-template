module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      dev: {
        options: {
          outputStyle: 'compressed',
          sourceMap: true,
          includePaths: require('node-bourbon').includePaths
        },
        files: {
          'css/app.css': 'src/scss/app.scss'
        }
      },

      dist: {
        options: {
          outputStyle: 'compressed',
          includePaths: require('node-bourbon').includePaths
        },
        files: {
          'css/app.css': 'src/scss/app.scss'
        }
      }
    },

    uglify : {
      dev: {
        options : {
          sourceMap : true
        },
        files: [
          {
            expand: true,
            cwd: 'src/js',
            src: ['**/*.js'],
            dest: 'js/',
            ext: '.min.js'
          }
        ]
      },
      dist : {
        files: [
          {
            expand: true,
            cwd: 'src/js',
            src: ['**/*.js'],
            dest: 'js/',
            ext: '.min.js'
          }
        ]
      }
    },

    watch: {
      grunt: {
        files: ['Gruntfile.js'],
        tasks: ['sass:dev', 'uglify:dev']
      },
      sass: {
        files: 'src/**/*.scss',
        tasks: ['sass:dev']
      },
      js: {
        files: 'src/**/*.js',
        tasks: ['uglify:dev']
      },
    },
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.registerTask('build', ['sass:dist', 'uglify:dist']);
  grunt.registerTask('default', ['sass:dev', 'uglify:dev', 'watch']);
}
