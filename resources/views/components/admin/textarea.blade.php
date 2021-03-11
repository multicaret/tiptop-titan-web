<div class="form-group">
    <label class="form-label">{{$label}}</label>
    <div id="{{$id}}-editor-toolbar">
                    <span class="ql-formats">
                      <select class="ql-font"></select>
                      <select class="ql-size"></select>
                    </span>
        <span class="ql-formats">
                      <button class="ql-bold"></button>
                      <button class="ql-italic"></button>
                      <button class="ql-underline"></button>
                      <button class="ql-strike"></button>
                    </span>
        <span class="ql-formats">
                      <select class="ql-color"></select>
                      <select class="ql-background"></select>
                    </span>
        <span class="ql-formats">
                      <button class="ql-script" value="sub"></button>
                      <button class="ql-script" value="super"></button>
                    </span>
        <span class="ql-formats">
                      <button class="ql-header" value="1"></button>
                      <button class="ql-header" value="2"></button>
                      <button class="ql-blockquote"></button>
                      <button class="ql-code-block"></button>
                    </span>
        <span class="ql-formats">
                      <button class="ql-list" value="ordered"></button>
                      <button class="ql-list" value="bullet"></button>
                      <button class="ql-indent" value="-1"></button>
                      <button class="ql-indent" value="+1"></button>
                    </span>
        <span class="ql-formats">
                      <button class="ql-direction" value="rtl"></button>
                      <select class="ql-align"></select>
                    </span>
        <span class="ql-formats">
                      <button class="ql-link"></button>
                      <button class="ql-image"></button>
                      <button class="ql-video"></button>
                    </span>
        <span class="ql-formats">
                      <button class="ql-clean"></button>
                    </span>
    </div>
    <div id="{{$id}}-editor" style="height: {{ $height ?? '15rem' }}"></div>
    {{--The  real deal--}}
    <textarea class="form-control d-none" id="{{$id}}" name="{{$name}}"
              style="height: {{ $height ?? '15rem' }}">{!! $content !!}</textarea>

</div>

@push('scripts')
    <script>
        if (!window.tempQuill) {
            window.tempQuill = {};
        }
        $(function () {
            if (!window.Quill) {
                $('#{{$id}}-editor,#{{$id}}-editor-toolbar').remove();
                $('#{{$id}}').removeClass('d-none');
            } else {
                $('#{{$id}}').addClass('d-none');
                window.tempQuill[@json($id)] = new Quill('#{{$id}}-editor', {
                    modules: {
                        toolbar: {
                            container: '#{{$id}}-editor-toolbar',
                            handlers: {
                                image: imageHandler.bind(null,@json($id))
                            },
                        },
                    },
                    theme: 'snow'
                });
                window.tempQuill[@json($id)].clipboard.dangerouslyPasteHTML(`{!! $content !!}`);

                window.tempQuill[@json($id)].on('text-change', function (delta, oldDelta, source) {
                    $('#{{$id}}').val(window.tempQuill[@json($id)].container.firstChild.innerHTML);
                });
                window.tempQuill[@json($id)].root.blur();
            }
        });

        async function uploadFile(file) {
            const postUrl = @json(localization()->getLocalizedURL(null,route('ajax.media.store')));
            const form = new FormData();
            form.append('file', file, file.name)
            let headers = {
                'Content-Type': 'multipart/form-data'
            };
            let response = await axios.post(postUrl, form, headers);
            return response.data.location;
        }

        function imageHandler(id) {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            input.onchange = async function () {
                const file = input.files[0];
                const link = await uploadFile(file);
                const range = window.tempQuill[id].getSelection(true);
                window.tempQuill[id].insertEmbed(range.index, 'image', link);
            }
        }

    </script>
@endpush
