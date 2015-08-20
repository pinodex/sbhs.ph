$(document).foundation({
    equalizer: {
        equalize_on_stack: true
    }
});

var galleryUpload = {
    isUploading: false,
    currentFile: null,
    queue: {}
};

$(document).ready(function() {

    if ($('.write-post-form').length) {
        var editor = $('.write-post-form #editor');

        editor.summernote({
            height: 300,
            onImageUpload: function(files) {
                UI.editorUploadImage(files[0], editor);
            }
        });

        $('.note-help, .note-view [data-name="fullscreen"]').remove();

        $('.write-post-form').submit(function(event) {
            event.preventDefault();

            UI.clearAlertBoxes();

            var postValue = $(this).serializeObject();
            postValue['content'] = editor.code();

            $('[data-submit-form]').attr('disabled', 'disabled');
            
            $.ajax($(this).attr('action'), {
                type: 'post',
                data: JSON.stringify(postValue),
                contentType: 'application/json',
                success: function(response) {
                    window.location = response.data.redirect;
                },
                error: function(xhr, status, error) {
                    response = JSON.parse(xhr.responseText) || {message: 'Error'};
                    UI.addAlertBox('alert', response.message);
                },
                complete: function() {
                    $('[data-submit-form]').removeAttr('disabled');
                }
            });
        });

        $('[data-post-type]').click(function() {
            var type = $(this).data('post-type');

            $('.write-post-form input[name="post_type"]').val(type);
        });
    }

    $('[data-logout-href').click(function(event) {
        event.preventDefault();
        window.location = $(this).data('logout-href');
    });

    $('[data-submit-form]').click(function() {
        var selector = $(this).data('submit-form');
        $(selector).submit();
    });

    $('[data-click-proxy]').click(function() {
        var selector = $(this).data('click-proxy');
        $(selector).click();
    });

    $('.gallery-manager .photo.add').click(function() {
        var fileSelector = $('<input type="file" name="image" accept="image/*" multiple />');
        fileSelector.click();

        fileSelector.bind('change', function() {
            if (!this.files) {
                return;
            }

            var files = this.files;
            var currentFile = 0;

            // do a fake loop
            (function next() {
                if (currentFile >= files.length) {
                    return;
                }

                if (files[currentFile].size > 5120000) {
                    alert('Image must not exceed 512 kb');
                    return;
                }

                var fileReader = new FileReader();
                var galleryPhoto = UI.getImageCardTemplate();

                fileReader.readAsDataURL(files[currentFile]);
                fileReader.onload = function (e) {
                    galleryPhoto.find('.thumb').css('background-image', 'url(' + e.target.result + ')');
                    galleryPhoto.attr('data-temp-id', Util.generateRandomString());
                    
                    UI.addToGalleryManager(galleryPhoto);
                    UI.galleryUploadImage(galleryPhoto, files[currentFile]);

                    currentFile++;
                    next();
                };
            }());
        });
    });

    $('.gallery-manager').on('click', '.photo .delete', function() {
        if (!confirm('Are you sure you want to delete this photo?')) {
            return;
        }

        var photo = $(this).parents('li');

        if (photo.find('.photo').is('.error, .queue')) {
            delete galleryUpload.queue[photo.data('photo')];
            
            photo.fadeOut(function() {
                $(this).remove();
            });

            return;
        }

        $.ajax({
            type: 'delete',
            url: photo.data('delete-url'),
            success: function() {
                photo.fadeOut(function() {
                    $(this).remove();
                });
            },
            error: function() {
                response = JSON.parse(xhr.responseText) || {message: 'Error'};
                UI.addAlertBox('alert', response.message);
            }
        });
    });

});

jQuery.fn.serializeObject = function() {
    var arrayData, objectData;
    arrayData = this.serializeArray();
    objectData = {};

    $.each(arrayData, function() {
        var value = '';

        if (this.value != null) {
            value = this.value;
        }

        if (objectData[this.name] != null) {
            if (!objectData[this.name].push) {
                objectData[this.name] = [objectData[this.name]];
            }

            objectData[this.name].push(value);
        } else {
            objectData[this.name] = value;
        }
    });

    return objectData;
};

var UI = {

    addAlertBox: function(type, message) {
        $('.alert-box-container').append('<div class="alert-box ' + type + '">' + message + '</div>');
    },

    clearAlertBoxes: function() {
        $('.alert-box-container').empty();
    },

    showLoadingOverlay: function() {
        $('.loading-overlay').fadeIn(100);
    },

    hideLoadingOverlay: function() {
        $('.loading-overlay').fadeOut();
    },

    editorUploadImage: function(file, editor) {
        UI.showLoadingOverlay();

        var formData = new FormData();
        formData.append('image', file);

        $.ajax({
            type: 'post',
            url: urls.postUpload,
            data: formData,
            contentType: false,
            processData: false,
            success: function(url) {
                editor.summernote('insertImage', url);
            },
            error: function(xhr, status, error) {
                response = JSON.parse(xhr.responseText) || {message: 'Error'};
                UI.addAlertBox('alert', response.message);
            },
            complete: function() {
                UI.hideLoadingOverlay();
            }
        });
    },

    galleryUploadImage: function(photo, file) {
        if (!galleryUpload.queue[photo.data('temp-id')]) {
            galleryUpload.queue[photo.data('temp-id')] = file;
        }
        
        if (galleryUpload.isUploading) {
            return;
        }

        galleryUpload.isUploading = true;

        var photoEl = photo.find('.photo');
        var formData = new FormData();

        formData.append('gallery', photo.data('gallery'));
        formData.append('image', file);

        photoEl.toggleClass('queue uploading');

        $.ajax({
            type: 'post',
            url: urls.galleryUpload,
            data: formData,
            contentType: false,
            processData: false,
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();

                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function(e) {
                        photoEl.find('.progress-bar div').css('width', Math.floor((e.loaded / e.total) * 100) + '%');
                    }, false);
                }

                return myXhr;
            },
            success: function(photoId) {
                photoEl.toggleClass('uploading success');

                photo.attr('data-delete-url', 
                    photo.attr('data-delete-url')
                        .replace('-', photo.data('gallery'))
                        .replace('--', photoId));
            },
            error: function() {
                photoEl.toggleClass('uploading error');
            },
            complete: function() {
                delete galleryUpload.queue[photo.data('temp-id')];
                galleryUpload.isUploading = false;

                var next = Object.keys(galleryUpload.queue)[0];

                if (next) {
                    UI.galleryUploadImage($('.gallery-manager [data-temp-id="' + next + '"]'), galleryUpload.queue[next]);
                }
            }
        });
    },

    getImageCardTemplate: function() {
        return $($('template#photo-card').html());
    },

    addToGalleryManager: function(element) {
        element.insertBefore('.gallery-manager .selector');
    }

};

var Util = {

    generateRandomString: function() {
        var text = '';
        var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        for(var i = 0; i < 8; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        return text;
    }

};