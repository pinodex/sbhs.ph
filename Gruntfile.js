module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            css: {
                dest: 'public/assets/css/commons.css',
                src: [
                    'public/assets/css/normalize.css',
                    'public/assets/css/foundation.min.css',
                    'public/assets/css/foundation-icons.css'
                ]
            },

            js: {
                dest: 'public/assets/js/commons.js',
                src: [
                    'public/assets/js/modernizr.js',
                    'public/assets/js/jquery.min.js',
                    'public/assets/js/foundation.min.js',
                    'public/assets/js/webfont.js'
                ]
            }
        },

        cssmin: {
            target: {
                files: {
                    'public/assets/css/commons.min.css': 'public/assets/css/commons.css',
                    'public/assets/css/layout.min.css': 'public/assets/css/layout.css',
                    'public/assets/css/dashboard.min.css': 'public/assets/css/dashboard.css',
                }
            }
        },

        uglify: {
            target: {
                files: {
                    'public/assets/js/commons.min.js': 'public/assets/js/commons.js',
                    'public/assets/js/app.min.js': 'public/assets/js/app.js',
                    'public/assets/js/dashboard.min.js': 'public/assets/js/dashboard.js'
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', [
        'concat',
        'cssmin',
        'uglify'
    ]);

};
