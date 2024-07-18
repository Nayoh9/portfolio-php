tinymce.init({
    selector: 'textarea',
    menubar: false,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table paste code wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic underline | image | media | forecolor | alignleft aligncenter alignjustify | bullist numlist outdent indent | removeformat | link | code',
    media_live_embeds: true,

    extended_valid_elements: 'iframe[src|frameborder|style|scrolling|class|width|height|name|align],video[controls|preload|autoplay|loop|muted|width|height|poster|playsinline]',
});

