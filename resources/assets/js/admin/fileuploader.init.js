$(function () {

// Fileuploader initialization
    $('.avatar-uploader[data-fileuploader-listinput]').fileuploader({
        limit: 1,
        fileMaxSize: 1,
        extensions: ['jpg', 'jpeg', 'png', 'bmp'],
        changeInput: ' ',
        theme: 'thumbnails',
        enableApi: true,
        addMore: true,
        editor: {
            cropper: {
                ratio: '1:1',
                minWidth: 128,
                minHeight: 128,
                showGrid: true
            }
        },
        thumbnails: {
            item: '<li class="fileuploader-item file-has-popup w-100 m-0" style="top: 8px; left: 8px;">' +
                '<div class="fileuploader-item-inner">' +
                '<div class="type-holder">${extension}</div>' +
                '<div class="actions-holder">' +
                // '<a class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' +
                '</div>' +
                '<div class="thumbnail-holder">' +
                '${image}' +
                '<span class="fileuploader-action-popup"></span>' +
                '</div>' +
                '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                '<div class="progress-holder">${progressBar}</div>' +
                '</div>' +
                '<input type="text" name="filesTitles[]" class="fileuploader-item-caption form-control" placeholder="Title..." value="">' +
                '</li>',

            box: '<div class="fileuploader-items">' +
                '<ul class="fileuploader-items-list">' +
                '<li class="fileuploader-thumbnails-input w-100 m-0" style="top: 8px; left: 8px;"><div class="fileuploader-thumbnails-input-inner"><i>+</i></div></li>' +
                '</ul>' +
                '</div>',

            item2: '<li class="fileuploader-item file-has-popup w-100 m-0" style="top: 8px; left: 8px;">' +
                '<div class="fileuploader-item-inner">' +
                '<div class="type-holder">${data.extension}</div>' +
                '<div class="actions-holder">' +
                '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i></i></a>' +
                // '<a class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' +
                '</div>' +
                '<div class="thumbnail-holder">' +
                '${image}' +
                '<span class="fileuploader-action-popup"></span>' +
                '</div>' +
                '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                '<div class="progress-holder">${progressBar}</div>' +
                '</div>' +
                '<input type="text" class="fileuploader-item-caption form-control" placeholder="Title..." value="${name}" disabled>' +
                '</li>',

            startImageRenderer: true,
            canvasImage: false,

            _selectors: {
                list: '.fileuploader-items-list',
                item: '.fileuploader-item',
                start: '.fileuploader-action-start',
                retry: '.fileuploader-action-retry',
                remove: '.fileuploader-action-remove'
            },
            onItemShow: function (item, listEl, parentEl, newInputEl, inputEl) {
                let plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                if (item.format == 'image') {
                    item.html.find('.fileuploader-item-icon').hide();
                }
            },
        },
        dragDrop: {
            container: '.fileuploader-thumbnails-input'
        },
        afterRender: function (listEl, parentEl, newInputEl, inputEl) {
            let plusInput = listEl.find('.fileuploader-thumbnails-input'),
                api = $.fileuploader.getInstance(inputEl.get(0));
            if (api.getFileList().length > 0) {
                plusInput.css('display', 'none');
            }
            plusInput.on('click', function () {
                api.open();
            });
        },
        onRemove: function (item, listEl, parentEl, newInputEl, inputEl) {
            let listProps = item.data.listProps;
            if (listProps) {
                let $deletedMedia;
                let inputFileElement = $(parentEl).find('[type="file"]');
                let inputName = inputFileElement.attr('name') + '-deleted-' + $(inputEl).attr('dropzone');

                $deletedMedia = $(parentEl).find('[name="' + inputName + '"]');
                if (!$deletedMedia.length) {
                    $deletedMedia = $('<input type="hidden" name="' + inputName + '" value="[]">');
                    $(parentEl).prepend($deletedMedia);
                }

                let items = JSON.parse($deletedMedia.val());
                items.push(listProps.id);

                $deletedMedia.val(JSON.stringify(items));
            }
            // Display the Add Box again if the limit is reached and the user removed  one or more of the uploaded items
            listEl.children('.fileuploader-thumbnails-input').show();
        },
    })


    $('.cover-uploader[data-fileuploader-listinput]').fileuploader({
        limit: 1,
        fileMaxSize: 5,
        extensions: ['jpg', 'jpeg', 'png', 'bmp'],
        changeInput: ' ',
        theme: 'thumbnails',
        enableApi: true,
        addMore: true,
        editor: {
            cropper: {
                ratio: '16:9',
                minWidth: 1280,
                minHeight: 720,
                showGrid: true
            }
        },
        thumbnails: {
            item: '<li class="fileuploader-item file-has-popup w-100 m-0" style="top: 8px; left: 8px;">' +
                '<div class="fileuploader-item-inner">' +
                '<div class="type-holder">${extension}</div>' +
                '<div class="actions-holder">' +
                // '<a class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' +
                '</div>' +
                '<div class="thumbnail-holder">' +
                '${image}' +
                '<span class="fileuploader-action-popup"></span>' +
                '</div>' +
                '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                '<div class="progress-holder">${progressBar}</div>' +
                '</div>' +
                '<input type="text" name="filesTitles[]" class="fileuploader-item-caption form-control" placeholder="Title..." value="">' +
                '</li>',

            box: '<div class="fileuploader-items">' +
                '<ul class="fileuploader-items-list">' +
                '<li class="fileuploader-thumbnails-input w-100 m-0" style="top: 8px; left: 8px;"><div class="fileuploader-thumbnails-input-inner"><i>+</i></div></li>' +
                '</ul>' +
                '</div>',

            item2: '<li class="fileuploader-item file-has-popup w-100 m-0" style="top: 8px; left: 8px;">' +
                '<div class="fileuploader-item-inner">' +
                '<div class="type-holder">${data.extension}</div>' +
                '<div class="actions-holder">' +
                '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i></i></a>' +
                // '<a class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' +
                '</div>' +
                '<div class="thumbnail-holder">' +
                '${image}' +
                '<span class="fileuploader-action-popup"></span>' +
                '</div>' +
                '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                '<div class="progress-holder">${progressBar}</div>' +
                '</div>' +
                '<input type="text" class="fileuploader-item-caption form-control" placeholder="Title..." value="${name}" disabled>' +
                '</li>',

            startImageRenderer: true,
            canvasImage: false,

            _selectors: {
                list: '.fileuploader-items-list',
                item: '.fileuploader-item',
                start: '.fileuploader-action-start',
                retry: '.fileuploader-action-retry',
                remove: '.fileuploader-action-remove'
            },
            onItemShow: function (item, listEl, parentEl, newInputEl, inputEl) {
                let plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                if (item.format == 'image') {
                    item.html.find('.fileuploader-item-icon').hide();
                }
            },
        },
        dragDrop: {
            container: '.fileuploader-thumbnails-input'
        },
        afterRender: function (listEl, parentEl, newInputEl, inputEl) {
            let plusInput = listEl.find('.fileuploader-thumbnails-input'),
                api = $.fileuploader.getInstance(inputEl.get(0));
            if (api.getFileList().length > 0) {
                plusInput.css('display', 'none');
            }
            plusInput.on('click', function () {
                api.open();
            });
        },
        onRemove: function (item, listEl, parentEl, newInputEl, inputEl) {
            let listProps = item.data.listProps;
            if (listProps) {
                let $deletedMedia;
                let inputFileElement = $(parentEl).find('[type="file"]');
                let inputName = inputFileElement.attr('name') + '-deleted-' + $(inputEl).attr('dropzone');

                $deletedMedia = $(parentEl).find('[name="' + inputName + '"]');
                if (!$deletedMedia.length) {
                    $deletedMedia = $('<input type="hidden" name="' + inputName + '" value="[]">');
                    $(parentEl).prepend($deletedMedia);
                }

                let items = JSON.parse($deletedMedia.val());
                items.push(listProps.id);

                $deletedMedia.val(JSON.stringify(items));
            }
            // Display the Add Box again if the limit is reached and the user removed  one or more of the uploaded items
            listEl.children('.fileuploader-thumbnails-input').show();
        },
    });


    let imagesUploaderElement = $('.images-uploader[data-fileuploader-listinput]');
    const imagesDataLimit = imagesUploaderElement.data('limit');
    const imagesFileMaxSizeData = imagesUploaderElement.data('max-file-size');

    const imagesLimit = imagesDataLimit !== null && imagesDataLimit !== undefined ? imagesDataLimit : 20;
    const imagesFileMaxSize = imagesFileMaxSizeData !== null && imagesFileMaxSizeData !== undefined ? imagesFileMaxSizeData : 10;
    imagesUploaderElement.fileuploader({
        limit: imagesLimit,
        fileMaxSize: imagesFileMaxSize,
        extensions: ['jpg', 'jpeg', 'png', 'bmp'],
        changeInput: ' ',
        theme: 'thumbnails thumbnails-with-caption',
        enableApi: true,
        addMore: true,
        editor: {
            cropper: {
                ratio: '16:9',
                minWidth: 1280,
                minHeight: 720,
                showGrid: true
            }
        },
        thumbnails: {
            item: '<li class="fileuploader-item file-has-popup">' +
                '<div class="fileuploader-item-inner">' +
                '<div class="type-holder">${extension}</div>' +
                '<div class="actions-holder">' +
                '<a class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' +
                '</div>' +
                '<div class="thumbnail-holder">' +
                '${image}' +
                '<span class="fileuploader-action-popup"></span>' +
                '</div>' +
                '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                '<div class="progress-holder">${progressBar}</div>' +
                '</div>' +
                '<input type="text" name="filesTitles[]" class="fileuploader-item-caption form-control" placeholder="Title..." value="">' +
                '</li>',

            box: '<div class="fileuploader-items">' +
                '<ul class="fileuploader-items-list">' +
                '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><i>+</i></div></li>' +
                '</ul>' +
                '</div>',

            item2: '<li class="fileuploader-item file-has-popup">' +
                '<div class="fileuploader-item-inner">' +
                '<div class="type-holder">${data.extension}</div>' +
                '<div class="actions-holder">' +
                '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-sort" title="${captions.sort}"><i></i></a>' +
                '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' +
                '</div>' +
                '<div class="thumbnail-holder">' +
                '${image}' +
                '<span class="fileuploader-action-popup"></span>' +
                '</div>' +
                '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' +
                '<div class="progress-holder">${progressBar}</div>' +
                '</div>' +
                '<input type="text" class="fileuploader-item-caption form-control" placeholder="Title..." value="${name}" disabled>' +
                '</li>',

            startImageRenderer: true,
            canvasImage: false,

            _selectors: {
                list: '.fileuploader-items-list',
                item: '.fileuploader-item',
                start: '.fileuploader-action-start',
                retry: '.fileuploader-action-retry',
                remove: '.fileuploader-action-remove'
            },
            onItemShow: function (item, listEl, parentEl, newInputEl, inputEl) {
                let plusInput = listEl.find('.fileuploader-thumbnails-input'),
                    api = $.fileuploader.getInstance(inputEl.get(0));

                plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();

                if (item.format == 'image') {
                    item.html.find('.fileuploader-item-icon').hide();
                }
            },
        },
        dragDrop: {
            container: '.fileuploader-thumbnails-input'
        },
        afterRender: function (listEl, parentEl, newInputEl, inputEl) {
            let plusInput = listEl.find('.fileuploader-thumbnails-input'),
                api = $.fileuploader.getInstance(inputEl.get(0));

            plusInput.on('click', function () {
                api.open();
            });
        },
        onRemove: function (item, listEl, parentEl, newInputEl, inputEl) {
            let listProps = item.data.listProps;
            if (listProps) {
                let $deletedMedia;
                let inputFileElement = $(parentEl).find('[type="file"]');
                let parentName = inputFileElement.attr('name');
                let inputName = parentName.replaceAll('[]', '') + '-deleted-' + $(inputEl).attr('dropzone');
                $deletedMedia = $(parentEl).find('[name="' + inputName + '"]');
                if (!$deletedMedia.length) {
                    $deletedMedia = $('<input type="hidden" name="' + inputName + '" value="[]">');
                    $(parentEl).prepend($deletedMedia);
                }

                let items = JSON.parse($deletedMedia.val());
                items.push(listProps.id);

                $deletedMedia.val(JSON.stringify(items));
            }
        },
        sorter: {
            selectorExclude: null,
            placeholder: null,
            scrollContainer: window,
        },
    });

    let filesUploaderElement = $('.files-uploader[data-fileuploader-listinput]');
    const filesDataLimit = filesUploaderElement.data('limit');
    const filesFileMaxSizeData = filesUploaderElement.data('max-file-size');

    const filesLimit = filesDataLimit !== null && filesDataLimit !== undefined ? filesDataLimit : 20;
    const filesFileMaxSize = filesFileMaxSizeData !== null && filesFileMaxSizeData !== undefined ? filesFileMaxSizeData : 10;
    filesUploaderElement.fileuploader({
        limit: filesLimit,
        fileMaxSize: filesFileMaxSize,
        enableApi: true,
        addMore: true,
        thumbnails: {
            onItemShow: function (item) {
                // add sorter button to the item html
                item.html.find('.fileuploader-action-remove').before('<a class="fileuploader-action fileuploader-action-sort" title="Sort"><i></i></a>');
            }
        },
        afterRender: function (listEl, parentEl, newInputEl, inputEl) {
            let plusInput = listEl.find('.fileuploader-thumbnails-input'),
                api = $.fileuploader.getInstance(inputEl.get(0));
            if (api.getFileList().length > 0) {
                plusInput.css('display', 'none');
            }

            plusInput.on('click', function () {
                api.open();
            });
        },
        onRemove: function (item, listEl, parentEl, newInputEl, inputEl) {
            let listProps = item.data.listProps;
            if (listProps) {
                let $deletedMedia;
                let inputName = 'deleted-' + $(inputEl).attr('dropzone');

                $deletedMedia = $(parentEl).find('[name="' + inputName + '"]');
                if (!$deletedMedia.length) {
                    $deletedMedia = $('<input type="hidden" name="' + inputName + '" value="[]">');
                    $(parentEl).prepend($deletedMedia);
                }

                let items = JSON.parse($deletedMedia.val());
                items.push(listProps.id);

                $deletedMedia.val(JSON.stringify(items));
            }
        },
        sorter: {
            selectorExclude: null,
            placeholder: null,
            scrollContainer: window,
        },
    });
});
