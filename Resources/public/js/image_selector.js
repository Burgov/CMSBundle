$(document).on('click', '[data-image-selector] a', function(e) {
    e.preventDefault();
    
    var $parent = $(this).parent();
    var $dialog = $("<div data-bind='template: \"image-list\"'>");
    $dialog.appendTo('body');
    $dialog.dialog({ width: '700px' });

    
    $.get(cmf_image.list, function(data) {
        ko.applyBindings(new ImagesViewModel(data, function(image) {
            $dialog.dialog('close').dialog('destroy').remove();
            $parent.data('image').setImage(image);
        }), $dialog.get(0)); 
    });
});

var ImagesViewModel = function(data, handlerCallback) {
    this.images = ko.observableArray([]);
    for (i in data.assets) {
        var image = new Asset(data.assets[i]);
        this.images.push(image);
    };
    
    this.selectImage = handlerCallback;
    this.isUploading = ko.observable(false);
    
    this.errorMessage = ko.observable("");
    this.progress = ko.observable(0);
    this.progress.subscribe(function(newValue) {
        if (self.isUploading()) {
            $("#progress-bar").progressbar({ value: newValue });
        }
    });
    
    this.clearForm = function($row) {
        $row.find('input[name="caption"]').val("");
        $row.find('input[name="file"]').val("");
    };
    
    var self = this;
    this.remove = function(image) {
        if (!confirm('Are you sure you want to delete this image?')) {
            return false;
        }

        $.post(cmf_image['delete'].replace('%25name%25', image.name), function() {
            self.images.remove(image);
        });
    };
    
    this.upload = function(data, e) {
        var $button = $(e.target);
        var $row = $button.closest('tbody');
        var data = new FormData;
        data.append('caption', $row.find('input[name="caption"]').val());
        data.append('file', $row.find('input[name="file"]').get(0).files[0]);

        self.errorMessage("");
        self.isUploading(true);
        
        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(e) {
            self.progress((e.loaded / e.total) * 100);
        }, false);
        xhr.addEventListener("load", function(e) {
            self.isUploading(false);
            var response = JSON.parse(e.target.responseText);
            if (e.target.status != 200) {
                self.errorMessage('Upload failed! '+response[0].message);
            } else {
                self.clearForm($row);
                self.images.push(new Asset(response));
            }
        }, false);
        xhr.open("POST", cmf_image.upload);
        xhr.send(data);
    }
};

var ImageViewModel = function(image) {
    this.image = ko.observable(image);
    
    this.setImage = function(image) {
        this.image(image);
    }
};

var Asset = function(data) {
    this.url = data.url;
    this.alt = data.alt;
    this.id = data.id;
    this.name = this.id.split('/');
    this.name = this.name[this.name.length - 1];
}


var tinymce_button_burgov_image_selector = function(ed) {
    
    var $dialog = $("<div data-bind='template: \"image-list\"'>");
    $dialog.appendTo('body');
    $dialog.dialog({ width: '700px' });
    
    $.get(cmf_image.list, function(data) {
        ko.applyBindings(new ImagesViewModel(data, function(image) {
            $dialog.dialog('close').dialog('destroy').remove();
            ed.focus();console.log(image);
            ed.selection.setContent('<img style="float: left; margin: 0 1em 1em 0" src="'+image.url+'" alt="'+image.alt+'" title="'+image.alt+'">');
        }), $dialog.get(0)); 
    });
};