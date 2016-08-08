module.exports = function(grunt) {

  require('load-grunt-tasks')(grunt);
  require('time-grunt')(grunt);

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      default: {
        files: [{
          expand: true,
          cwd: 'src/scss',
          src: '*.scss',
          dest: 'src/tmp',
          ext: '.css',
          extDot: 'last'
        }]
      }
    },

    postcss: {
      default: {
        options: {
          processors: [
            require('autoprefixer')({
              browsers: ['> 1%', 'Last 2 versions']
            })
          ],
          map: {
            prev: 'tmp',
            inline: false
          }
        },
        files: {
          'css/app.css': 'src/tmp/app.css'
        }
      },
      dist: {
        options: {
          processors: [
            require('autoprefixer')({
              browsers: ['> 1%', 'Last 2 versions']
            }),
            require('css-mqpacker'),
            require('cssnano')
          ],
          map: {
            prev: 'tmp',
            inline: false
          }
        },
        files: [{
          expand: true,
          cwd: 'src/tmp',
          src: '*.css',
          dest: 'css',
          ext: '.css',
          extDot: 'last'
        }]
      }
    },

    concat: {
        options: {
            sourceMap: true,
            separator: ' '
        },
        // add js files to concat, e.g.
        app: {
            src: [
                'src/js/app.js'
            ],
            dest: 'src/tmp/app.js'
        }
    },

    uglify: {
      options: {
        sourceMap: true,
        sourceMapIncludeSources: true,
        sourceMapIn: 'src/tmp/app.js.map'
      },
      dist: {
        files: [
          {
            expand: true,
            cwd: 'src/tmp',
            src: ['**/*.js'],
            dest: 'js/',
            ext: '.min.js'
          }
        ]
      }
    },

    // https://modernizr.com/download?flexbox-flexboxlegacy-history-localstorage-sessionstorage-setclasses
    modernizr: {
      dist: {
        "crawl": false,
        "customTests": [],
        "dest": "js/modernizr.min.js",
        "tests": [
          "history",
          "flexbox",
          "flexboxlegacy",
          "localstorage",
          'sessionstorage'
        ],
        "options": [
          "setClasses"
        ],
        'uglify': true
      }
    },

    pot: {
      default: {
        options: {
          text_domain: 'wp-theme-template',
          dest: 'languages/',
          language: 'PHP',
          encoding: 'utf-8',
          keywords: [ //WordPress localisation functions
            '__:1',
            '_e:1',
            '_x:1,2c',
            'esc_html__:1',
            'esc_html_e:1',
            'esc_html_x:1,2c',
            'esc_attr__:1',
            'esc_attr_e:1',
            'esc_attr_x:1,2c',
            '_ex:1,2c',
            '_n:1,2',
            '_nx:1,2,4c',
            '_n_noop:1,2',
            '_nx_noop:1,2,3c'
          ],
          msgid_bugs_address: 'alpipego@gmail.com'
        },
        files: [{
          expand: true,
          src: ['**/*.php', '!node_modules/**']
        }]
      }
    },

    watch: {
      grunt: {
        files: ['Gruntfile.js'],
        tasks: ['styles', 'js', 'modernizr']
      },
      sass: {
        files: 'src/scss/**/*.scss',
        tasks: ['styles']
      },
      js: {
        files: 'src/js/**/*.js',
        tasks: ['js']
      }
    }
  });

  grunt.registerTask('styles', ['sass', 'postcss:default']);
  grunt.registerTask('js', ['concat', 'uglify']);
  grunt.registerTask('default', ['styles', 'js', 'watch', 'modernizr']);
  grunt.registerTask('build', ['sass', 'postcss:dist', 'js', 'pot', 'modernizr']);
};
